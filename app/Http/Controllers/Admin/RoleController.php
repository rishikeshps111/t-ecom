<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:Super Admin', ['index', 'create', 'store', 'edit', 'update', 'destroy', 'assignPermissions']),
        ];
    }
    public function index(Request $request)
    {
        $entries = $request->get('entries', 10);
        $query = Role::whereNotIn(
            'name',
            [
                'Super Admin'
            ]
        );

        $roles = $query->orderBy('id', 'desc')->paginate($entries);
        $permissions = Permission::orderBy('group_name')->get();
        $groupedPermissions = $permissions->groupBy('group_name');
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.roles.index', compact('roles', 'permissions', 'groupedPermissions'))->render()
            ]);
        }
        return view('admin.roles.index', compact('roles', 'permissions', 'groupedPermissions'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        Role::create(['name' => $request->name]);

        return redirect()->route('admin.manage.roles')->with('success', 'Role created successfully');
    }

    public function edit(Request $request, $id)
    {
        $entries = $request->get('entries', 10);
        $query = Role::query();

        $roles = $query->orderBy('id', 'desc')->paginate($entries);

        $role = Role::findOrFail($id);
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.roles.index', compact('roles', 'role'))->render()
            ]);
        }
        return view('admin.roles.index', compact('role', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
        ]);

        $role = Role::findOrFail($id);

        $role->update(['name' => $request->name]);

        return redirect()->route('admin.manage.roles')->with('success', 'Role updated successfully');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return back()->with('success', 'Role deleted successfully');
    }

    public function assignPermissions(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $permissions = $request->permissions ?? [];
        $role->syncPermissions($permissions);

        return redirect()->back()->with('success', 'Permissions assigned successfully');
    }
}
