<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UserExport;
use App\Models\User;
use App\Models\State;
use App\Models\Company;
use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\SendCredentialsMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\AccountDeactivatedMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class BusinessUserController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:Super Admin', ['index', 'create', 'store', 'edit', 'update', 'destroy', 'customer.send-credential']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $companies = Company::get();
        if ($request->ajax()) {
            $records = User::role('Customer')->orderBy('created_at', 'desc');

            return DataTables::eloquent($records)
                ->addColumn('actions', function ($row) {
                    return view('admin.business-user.partials.action', compact('row'))->render();
                })
                ->addColumn('custom_user_id', function ($row) {
                    return $row->user_code ?? '-';
                })
                ->addColumn('company', function ($row) {

                    if ($row->companies->isEmpty()) {
                        return '-';
                    }

                    return $row->companies->map(function ($company) {
                        return '<span class="badge bg-info me-1">'
                            . e($company->company_name) .
                            '</span>';
                    })->implode(' ');
                })
                ->addColumn('status', function ($row) {

                    if ($row->is_locked) {
                        return '<span class="badge bg-danger">Locked</span>';
                    }
                    if ($row->is_active) {
                        return '<button class="btn btn-sm toggleStatus text-success" data-id="' . $row->id . '" data-status="1">
                            <i class="fa-solid fa-toggle-on fa-lg"></i>
                        </button>';
                    } else {
                        return '<button class="btn btn-sm toggleStatus text-secondary" data-id="' . $row->id . '" data-status="0">
                            <i class="fa-solid fa-toggle-off fa-lg"></i>
                        </button>';
                    }
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('status')) {
                        $query->where('is_active', (bool) $request->status);
                    }
                    if ($request->filled('customer_code')) {
                        $query->searchByCustomId($request->customer_code);
                    }
                    if ($request->filled('customer_name')) {
                        $query->where('name', 'like', '%' . $request->customer_name . '%');
                    }
                    if ($request->filled('company')) {
                        $query->whereRelation('companies', 'company_name', 'like', '%' . $request->company . '%');
                    }
                })
                ->rawColumns(['company', 'actions', 'status'])
                ->make(true);
        }
        return view('admin.business-user.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $states = State::get();
        $cities = Location::get();
        $users = User::get();
        $companies = Company::get();


        return view('admin.business-user.create', compact('states', 'cities', 'users', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['show_password'] = $data['password'];
            $companyIds = $data['company_id'] ?? [];
            unset($data['company_id']);
            $record = User::create($data);
            if (!empty($companyIds)) {
                $record->companies()->sync($companyIds);
            }

            $record->syncRoles(['Customer']);

            activity()
                ->causedBy(Auth::id())
                ->performedOn($record)
                ->log('Create Customer');
        });

        return redirect()
            ->route('admin.business-users.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $business_user)
    {
        $business_user->load('companies');
        return response()->json([
            'html' => view('admin.business-user.companies', compact('business_user'))->render(),
            'title' => 'Company List for ' . $business_user->name,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $business_user)
    {
        $customerCompanies = $business_user
            ->companies()
            ->pluck('companies.id')
            ->toArray();

        $states = State::get();
        $cities = Location::get();
        $users = User::get();
        $companies = Company::get();


        return view('admin.business-user.edit', compact('states', 'cities', 'users', 'companies', 'customerCompanies', 'business_user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $business_user)
    {
        DB::transaction(function () use ($request, $business_user) {

            $data = $request->validated();
            $companyIds = $data['company_id'] ?? [];
            $data['show_password'] = $data['password'];
            unset($data['company_id']);
            $business_user->update($data);
            $business_user->companies()->sync($companyIds);

            activity()
                ->causedBy(Auth::id())
                ->performedOn($business_user)
                ->log('Update Customer');
        });

        return redirect()
            ->route('admin.business-users.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $business_user)
    {
        $business_user->delete();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($business_user)
            ->log('Delete Customer');
        return response()->json(['success' => true]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:users,id',
            'status' => 'required|boolean',
        ]);

        $record = User::findOrFail($request->id);
        $record->is_active = $request->status;
        $record->save();

        $message = $request->status
            ? 'Account activated successfully.'
            : 'Account deactivated successfully.';

        if ($request->status == false) {
            Mail::to($record->email)
                ->queue(new AccountDeactivatedMail($record));
        }

        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log($message);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }


    public function sendCredentials(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        $user = User::findOrFail($request->user_id);
        $password = Str::random(10);
        $user->save();
        Mail::to($user->email)->queue(new SendCredentialsMail($user, $password));

        return response()->json(['success' => true, 'message' => 'Credentials sent successfully!']);
    }

    public function export(Request $request)
    {
        $ids = $request->ids;
        if (empty($ids)) {
            return response()->json(['error' => 'No rows selected.'], 400);
        }
        return response()->streamDownload(function () use ($ids) {
            echo Excel::raw(new UserExport($ids), \Maatwebsite\Excel\Excel::CSV);
        }, 'business-users.csv', [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="business-users.csv"',
            'Cache-Control' => 'no-store, no-cache',
            'Access-Control-Expose-Headers' => 'Content-Disposition',
        ]);
    }
}
