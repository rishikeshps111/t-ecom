<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use App\Models\UserTotalGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PlannerController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:Super Admin', ['index', 'create', 'store', 'edit', 'update', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        $entries = $request->get('entries', 10);

        $query = User::whereHas('roles', function ($q) {
            $q->where('name', 'Planner');
        })->whereNotIn('name', ['Default']);

        User::role('Planner')
            ->whereDate('relived_at', '<', today())
            ->where('status', 1)
            ->update(['status' => 0]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('planner_code', 'like', "%$search%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Role filter
        if ($request->filled('role')) {
            $role = Role::find($request->role);
            if ($role) {
                $query->whereHas('roles', function ($q) use ($role) {
                    $q->where('name', $role->name);
                });
            }
        }

        $planner = $query->orderBy('id', 'desc')->paginate($entries);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.user.planner.index', compact('planner'))->render()
            ]);
        }

        return view('admin.user.planner.index', compact('planner'));
    }


    public function create()
    {
        $userPrefix = get_prefix('planner') ?? 'PLAN';
        $year = active_financial_year_start();
        $lastDealer = User::role('Planner')
            ->where('user_id', 'like', "{$userPrefix}{$year}%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastDealer) {
            $lastNumber = (int) substr($lastDealer->user_id, -2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $userId = $userPrefix . $year .  str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        $totalGroups = Customer::get();
        $companies = Company::get();

        return view('admin.user.planner.create', compact('userId', 'totalGroups', 'companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // 'planner_code'     => 'required|unique:users,planner_code',
            'user_code'  => 'nullable|unique:users,user_code',
            'name'        => 'required|string|max:255',
            'email'       => 'nullable|email|unique:users,email',
            'phone'       => 'nullable|digits:10',
            'password'  => 'required|min:8',
            'sequence_number'  => 'required|numeric',
            // 'iv'  => 'required|numeric',
            'joining_date' => 'required|date',
            'relived_at'   => 'required|date|after:joining_date',
            'total_groups' => 'required|array', // validate as array
            'total_groups.*' => 'exists:customers,id',
            // 'companies' => 'required|array', // validate as array
            // 'companies.*' => 'exists:companies,id',
            // 'company_id'  => 'required|exists:companies,id',
            // 'total_group_id'  => 'required|exists:customers,id',
            // 'department'  => 'required|string|max:255',
            // 'status'      => 'required|in:0,1',
        ]);

        $user = User::create([
            'user_id'          => $request->user_id,
            'user_code'          => $request->user_code,
            'name'             => $request->name,
            'email'            => $request->email ?? null,
            'phone' => $request->phone ?? null,
            'planner_code' => $request->planner_code ?? null,
            'sequence_number' => $request->sequence_number ?? null,
            'joining_date' => $request->joining_date ?? null,
            'relived_at' => $request->relived_at ?? null,
            // 'iv' => $request->iv ?? null,
            'planner_c_percentage' => $request->planner_c_percentage ?? null,

            // 'company_id'      => $request->company_id,
            // 'total_group_id'  => $request->total_group_id,
            // 'department'       => $request->department,
            // 'designation'      => $request->designation,
            // 'task_access'      => $request->task_access,
            // 'document_upload'  => $request->document_upload,
            // 'document_edit'    => $request->document_edit,
            // 'task_scope'       => $request->task_scope,
            'status'           => 1,
            'role'             => 5,
            'password'         => $request->password,
        ]);

        if ($request->has('total_groups')) {
            foreach ($request->total_groups as $groupId) {
                UserTotalGroup::create([
                    'user_id' => $user->id,
                    'total_group_id' => $groupId,
                ]);
            }
        }

        // if ($request->has('companies')) {
        //     foreach ($request->companies as $companyId) {
        //         UserCompany::create([
        //             'user_id' => $user->id,
        //             'company_id' => $companyId,
        //         ]);
        //     }
        // }

        $user->assignRole('Planner');

        activity()
            ->causedBy(Auth::id())
            ->performedOn($user)
            ->log('Planner Created');

        return redirect()->route('admin.manage.planner')
            ->with('success', 'Planner created successfully.');
    }

    public function edit($id)
    {
        $planner = User::findorFail($id);
        $totalGroups = Customer::get();
        $companies = Company::get();
        $selectedCompanies = $planner->companies->pluck('id')->toArray();
        $selectedGroups = $planner->totalGroups->pluck('id')->toArray();

        $selectedGroups = $planner->totalGroups->pluck('id')->toArray();
        return view('admin.user.planner.edit', compact('planner', 'totalGroups', 'companies', 'selectedCompanies', 'selectedGroups'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            // 'planner_code'     => 'required|unique:users,planner_code,' . $user->id,
            'user_code'  => 'required|unique:users,user_code,' . $user->id,
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|email|unique:users,email,' . $user->id,
            'phone'  => 'nullable|digits:10',
            'password' => 'nullable|min:8',
            // 'status' => 'required|in:0,1',
            // 'company_id'  => 'required|exists:companies,id',
            // 'total_group_id'  => 'required|exists:customers,id',
            'sequence_number'  => 'required|numeric',
            // 'iv'  => 'required|numeric',
            'joining_date' => 'required|date',
            'relived_at'   => 'required|date|after:joining_date',
            'total_groups' => 'required|array', // validate as array
            'total_groups.*' => 'exists:customers,id',
            // 'companies' => 'required|array', // validate as array
            // 'companies.*' => 'exists:companies,id',
        ]);


        $data = [
            'user_id' => $request->user_id,
            'user_code'          => $request->user_code,
            'name'   => $request->name,
            'email'  => $request->email ?? null,
            'phone'  => $request->phone ?? null,
            'status' => 1,
            'planner_code' => $request->planner_code ?? null,
            'sequence_number' => $request->sequence_number ?? null,
            'joining_date' => $request->joining_date ?? null,
            'relived_at' => $request->relived_at ?? null,
            // 'iv' => $request->iv ?? null,
            'planner_c_percentage' => $request->planner_c_percentage ?? null,
            // 'company_id'      => $request->company_id,
            // 'total_group_id'  => $request->total_group_id,
        ];

        $user->totalGroups()->sync($request->total_groups ?? []);
        // $user->companies()->sync($request->companies ?? []);
        // Update password ONLY if provided
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }



        $user->update($data);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($user)
            ->log('Planner Updated');

        return redirect()->route('admin.manage.planner')
            ->with('success', 'Planner updated successfully.');
    }

    public function destroy($id)
    {
        DB::reconnect(); // Reconnect to MySQL
        $user =  User::findOrFail($id);
        $user->delete();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($user)
            ->log('Planner Deleted');

        return redirect()->route('admin.manage.planner')->with('success', 'Planner deleted successfully.');
    }
}
