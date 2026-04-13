<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $entries = $request->get('entries', 10);
        $query = Permission::query();

        $permissions = $query->orderBy('id', 'desc')->paginate($entries);
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.permissions.index', compact('permissions'))->render()
            ]);
        }
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        return redirect()->route('admin.manage.permissions')->with('success', 'Permission created successfully');
    }

    public function edit(Request $request, $id)
    {
        $entries = $request->get('entries', 10);
        $query = Permission::query();

        $permissions = $query->orderBy('id', 'desc')->paginate($entries);
        $permission = Permission::findorFail($id);
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.permissions.index', compact('permissions', 'permission'))->render()
            ]);
        }
        return view('admin.permissions.index', compact('permission', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $id,
        ]);
        $permission = Permission::findorFail($id);

        $permission->update(['name' => $request->name]);

        return redirect()->route('admin.manage.permissions')->with('success', 'Permission updated successfully');
    }

    public function destroy($id)
    {
        $permission = Permission::findorFail($id);
        $permission->delete();
        return back()->with('success', 'Permission deleted successfully');
    }
}
