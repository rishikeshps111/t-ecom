<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\State;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\StoreCustomerRequest;
use App\Http\Requests\Admin\UpdateCustomerRequest;
use App\Models\CompanyType;
use App\Models\Currency;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CustomerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:Super Admin', ['index', 'create', 'store', 'edit', 'update', 'destroy', 'status']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = Customer::orderBy('created_at', 'desc');

            return DataTables::eloquent($records)
                ->addColumn('actions', function ($row) {
                    return view('admin.customer.partials.action', compact('row'))->render();
                })
                ->addColumn('custom_user_id', function ($row) {
                    return $row->custom_user_id ?? '-';
                })
                ->addColumn('company', function ($row) {
                    return $row->company->company_name ?? '-';
                })
                ->addColumn('phone', function ($row) {
                    return $row->phone ?? '-';
                })
                ->addColumn('email', function ($row) {
                    return $row->email ?? '-';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : '-';
                })
                ->addColumn('status', function ($row) {
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
                        $query->where('customer_name', 'like', '%' . $request->customer_name . '%');
                    }
                    if ($request->filled('company')) {
                        $query->whereRelation('company', 'company_name', 'like', '%' . $request->company . '%');
                    }
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }
        return view('admin.customer.index');
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
        $currencies = Currency::get();
        $companyTypes = CompanyType::get();


        return view('admin.customer.create', compact('states', 'cities', 'users', 'companies', 'currencies', 'companyTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();

            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('customers', 'public');
                $data['logo'] = $logoPath;
            }
            $data['company_type_id'] = json_encode($request->input('company_type_id', []));
            $record = Customer::create($data);
            $record->customer_code = Customer::generateCustomerCode($record->id);
            $record->save();

            activity()
                ->causedBy(Auth::id())
                ->performedOn($record)
                ->log('Create Client');
        });

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $states = State::get();
        $cities = Location::get();
        $users = User::get();
        $companies = Company::get();
        $currencies = Currency::get();
        $companyTypes = CompanyType::get();
        return view('admin.customer.edit', compact('customer', 'states', 'cities', 'users', 'companies', 'currencies', 'companyTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        DB::transaction(function () use ($request, $customer) {
            $data = $request->validated();
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('customers', 'public');
                $data['logo'] = $logoPath ?? $customer->logo;
            }
            $data['company_type_id'] = json_encode($request->input('company_type_id', []));
            $customer->update($data);
            activity()
                ->causedBy(Auth::id())
                ->performedOn($customer)
                ->log('Update Client');
        });

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        if ($customer->invoices()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete customer with invoices.'
            ], 400);
        }

        $customer->delete();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($customer)
            ->log('Delete Client');
        return response()->json(['success' => true]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:customers,id',
            'status' => 'required|boolean',
        ]);

        $record = Customer::findOrFail($request->id);
        $record->is_active = $request->status;
        $record->save();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Status Change Customer');

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
