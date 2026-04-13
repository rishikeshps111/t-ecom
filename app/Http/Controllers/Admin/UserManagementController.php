<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\UserTotalGroup;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:Super Admin', ['index', 'create', 'store', 'edit', 'update', 'destroy', 'unlock']),
        ];
    }
    public function index(Request $request)
    {
        $entries = $request->get('entries', 10);
        $query = User::whereNotIn('name', ['Default']);
        User::whereIn('user_type', ['production', 'management'])->whereDate('relived_at', '<', today())
            ->where('status', 1)
            ->update(['status' => 0]);
        if ($request->filled('type')) {
            $query->where('user_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status',  $request->status);
        }
        if ($request->filled('total_group')) {
            $query->whereRelation('totalGroups', 'total_group_id', $request->total_group);
        }
        if ($request->filled('company')) {
            $query->whereRelation('companies', 'company_id', $request->company);
        }
        if ($request->filled('role')) {
            $role = Role::find($request->role);
            if ($role) {
                $query->whereHas('roles', function ($q) use ($role) {
                    $q->where('name', $role->name);
                });
            }
        }

        $users = $query->orderBy('id', 'desc')->paginate($entries);

        $role = Role::whereIn('name', ['Accountant', 'Company Secretary', 'Auditor', 'Tax Consultant'])->get();
        $customers = Customer::get();
        $companies = Company::get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.user.index', compact('users', 'role', 'customers', 'companies'))->render()
            ]);
        }

        return view('admin.user.index', compact('users', 'role', 'customers', 'companies'));
    }

    public function create()
    {
        $roles = Role::whereNotIn('name', [
            'Super Admin',
            'Company User',
            'Staff User',
            'Dealer',
            'Planner',
            'Document Manager',
            'Corp User',
            'Accountant',
            'Company Secretary',
            'Auditor',
            'Tax Consultant'
        ])->get();
        $userPrefix = get_prefix('staff') ?? 'STAFF';
        $year = active_financial_year_start();
        $lastId = User::max('id') ?? 0;
        $userId = $userPrefix . $year . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
        $customers = Customer::get();
        $companies = Company::get();

        return view('admin.user.create', compact('userId', 'roles', 'customers', 'companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'  => 'required|unique:users,user_id',
            'user_code'  => 'nullable|unique:users,user_code',
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|digits_between:10,15|unique:users,phone',
            'password'  => 'required|min:8',
            // 'role'     => 'nullable|exists:roles,id',
            'joining_date' => 'required|date',
            'relived_at'   => 'required|date|after:joining_date',
            'user_type' => 'required|in:production,management',
            // 'company'  => 'required|exists:companies,id',
            // 'status'   => 'required|in:0,1',
            // 'profile_image' => 'nullable|file|max:2048',
            'total_groups' => 'nullable|array', // validate as array
            'total_groups.*' => 'exists:customers,id',
            'companies' => 'nullable|array', // validate as array
            'companies.*' => 'exists:companies,id',
        ]);

        // $profileImagePath = null;

        // if ($request->hasFile('profile_image')) {
        //     $image = $request->file('profile_image');
        //     $imageName = 'user_' . time() . '.' . $image->getClientOriginalExtension();
        //     $image->move(public_path('uploads/users'), $imageName);
        //     $profileImagePath = 'uploads/users/' . $imageName;
        // }


        $user = User::create([
            'user_id' => $request->user_id,
            'user_code' => $request->user_code ?? null,
            'name'    => $request->name,
            'email'   => $request->email,
            'phone' => $request->country_code . $request->phone,
            'password' => $request->password,
            'show_password' => $request->password,
            // 'show_password' => $request->password,
            'is_active'  => 1,
            'role'    => $request->role ?? '',
            'joining_date' => $request->joining_date,
            'relived_at' => $request->relived_at,
            'user_type' => $request->user_type,
            'planner_c_percentage' => $request->planner_c_percentage ?? null,
            'production_c_percentage' => $request->production_c_percentage ?? null,
            // 'company_id' => $request->company,
            // 'profile_image' => $profileImagePath,
        ]);

        if ($request->user_type == 'Production Staff') {
            $user->syncRoles(['Production Staff']);
        } else {
            $user->syncRoles(['Management Staff']);
        }

        if ($request->has('total_groups')) {
            foreach ($request->total_groups as $groupId) {
                UserTotalGroup::create([
                    'user_id' => $user->id,
                    'total_group_id' => $groupId,
                ]);
            }
        }

        if ($request->has('companies')) {
            foreach ($request->companies as $companyId) {
                UserCompany::create([
                    'user_id' => $user->id,
                    'company_id' => $companyId,
                ]);
            }
        }
        activity()
            ->causedBy(Auth::id())
            ->performedOn($user)
            ->log('Staff Created');


        return redirect()->route('admin.manage.user', ['type' => $user->user_type])->with('success', 'Staff created successfully');
    }

    public function edit($id)
    {
        $user = User::findorFail($id);
        $roles = Role::whereNotIn('name', [
            'Super Admin',
            'Company User',
            'Staff User',
            'Dealer',
            'Planner',
            'Document Manager',
            'Corp User',
            'Accountant',
            'Company Secretary',
            'Auditor',
            'Tax Consultant'
        ])->get();
        $customers = Customer::get();
        $selectedGroups = $user->totalGroups->pluck('id')->toArray();
        $companies = Company::get();
        $selectedCompanies = $user->companies->pluck('id')->toArray();

        return view('admin.user.edit', compact('roles', 'user', 'customers', 'selectedGroups', 'companies', 'selectedCompanies'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'user_id' => 'required|unique:users,user_id,' . $user->id,
            'user_code' => 'required|unique:users,user_code,' . $user->id,
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'phone'   => 'required|digits_between:10,15|unique:users,phone,' . $user->id,
            'password' => 'nullable|min:8',
            // 'role'    => 'nullable|exists:roles,id',
            'joining_date' => 'required|date',
            // 'company' => 'required|exists:companies,id',
            // 'status'  => 'required|in:0,1',
            'relived_at'   => 'required|date|after:joining_date',
            'user_type' => 'required|in:production,management',
            'total_groups' => 'nullable|array', // validate as array
            'total_groups.*' => 'exists:customers,id',
            'companies' => 'nullable|array', // validate as array
            'companies.*' => 'exists:companies,id',
        ]);

        $user->user_id    = $request->user_id;
        $user->user_code    = $request->user_code ?? null;
        $user->name       = $request->name;
        $user->email      = $request->email;
        $user->phone      = $request->phone;
        $user->is_active     = 1;
        $user->company_id = $request->company;
        $user->joining_date = $request->joining_date;
        $user->relived_at = $request->relived_at;
        $user->user_type = $request->user_type;
        // $user->role    = $request->role;
        $user->planner_c_percentage = $request->planner_c_percentage ?? null;
        $user->production_c_percentage = $request->production_c_percentage ?? null;

        if ($request->filled('password')) {
            $user->password = $request->password;
            $user->show_password = $request->password;
        }

        // if ($request->hasFile('profile_image')) {
        //     if ($user->profile_image && file_exists(public_path($user->profile_image))) {
        //         unlink(public_path($user->profile_image));
        //     }

        //     $image = $request->file('profile_image');
        //     $imageName = time() . '_' . $image->getClientOriginalName();
        //     $image->move(public_path('uploads/users'), $imageName);
        //     $user->profile_image = 'uploads/users/' . $imageName;
        // }

        $user->totalGroups()->sync($request->total_groups ?? []);
        $user->companies()->sync($request->companies ?? []);


        // $user->syncRoles([
        //     Role::findById($request->role)
        // ]);
        $user->save();

        activity()
            ->causedBy(Auth::id())
            ->performedOn($user)
            ->log('Staff Updated');

        return redirect()->route('admin.manage.user', ['type' => $user->user_type])->with('success', 'Staff updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user_type = $user->user_type;
        $user->delete();

        activity()
            ->causedBy(Auth::id())
            ->performedOn($user)
            ->log('Staff Deleted');

        return redirect()->route('admin.manage.user', ['type' => $user_type])->with('success', 'Staff deleted successfully.');
    }

    public function lock(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $user->update([
            'is_locked'     => 1,
            'locked_reason' => $request->reason,
        ]);

        return response()->json(['success' => true]);
    }

    public function unlock(User $user)
    {
        $user->update([
            'is_locked'     => 0,
            'locked_reason' => null,
        ]);

        return response()->json(['success' => true]);
    }
}
