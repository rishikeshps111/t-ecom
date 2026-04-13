<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DocumentManagerController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(PermissionMiddleware::using('document-manager.view'), ['index']),
            new Middleware(PermissionMiddleware::using('document-manager.create'),  ['create', 'store']),
            new Middleware(PermissionMiddleware::using('document-manager.edit'),  ['edit', 'update']),
            new Middleware(PermissionMiddleware::using('document-manager.delete'), ['destroy']),
        ];
    }
    public function index(Request $request)
    {
        $entries = $request->get('entries', 10);
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', 'Document Manager');
        });

        if ($request->filled('status')) {
            $query->where('status',  $request->status);
        }
        if ($request->filled('role')) {
            $role = Role::find($request->role);
            if ($role) {
                $query->whereHas('roles', function ($q) use ($role) {
                    $q->where('name', $role->name);
                });
            }
        }

        $manager = $query->orderBy('id', 'desc')->paginate($entries);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.user.manager.index', compact('manager'))->render()
            ]);
        }
        return view('admin.user.manager.index', compact('manager'));
    }

    public function create()
    {
        $userPrefix = get_prefix('document_manager') ?? 'DEAL';
        $year = active_financial_year_start();;

        $lastDealer = User::whereHas('roles', function ($q) {
            $q->where('name', 'Document Manager');
        })
            ->where('user_id', 'like', "{$userPrefix}{$year}D%")
            ->orderBy('id', 'desc')
            ->first();


        if ($lastDealer) {
            $lastNumber = (int) substr($lastDealer->user_id, -2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $userId = $userPrefix . $year .  str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        return view('admin.user.manager.create', compact('userId'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|unique:users,user_id',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'phone'      => 'required|digits:10',
            'department' => 'required|string|max:255',
            'status'     => 'required|in:0,1',
        ]);

        $user = User::create([
            'user_id'             => $request->user_id,
            'name'                => $request->name,
            'email'               => $request->email,
            'phone'               => $request->phone,
            'country_code'        => $request->country_code,
            'department'          => $request->department,

            // Permissions
            'document_category'   => $request->document_category,
            'folder_access'       => $request->folder_access,
            'document_upload'     => $request->document_upload,
            'document_edit'       => $request->document_edit,
            'document_delete'     => $request->document_delete,
            'verson_control'      => $request->verson_control,
            'approval_authority'  => $request->approval_authority,
            'task_scope'          => $request->task_scope,

            'status'              => $request->status,
            'role'                => 6,
            'password'            => bcrypt('12345678'),
        ]);

        // Assign Role
        $user->assignRole('Document Manager');

        return redirect()->route('admin.manage.document_manager')
            ->with('success', 'Document Manager created successfully.');
    }

    public function edit($id)
    {
        $manage = User::findorFail($id);

        return view('admin.user.manager.edit', compact('manage'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'phone'  => 'required|digits:10',
            'status' => 'required|in:0,1',
        ]);

        $user->update([
            'name'                => $request->name,
            'email'               => $request->email,
            'phone'               => $request->phone,
            'department'          => $request->department,

            // Permissions
            'document_category'   => $request->document_category,
            'folder_access'       => $request->folder_access,
            'document_upload'     => $request->document_upload,
            'document_edit'       => $request->document_edit,
            'document_delete'     => $request->document_delete,
            'verson_control'      => $request->verson_control,
            'approval_authority'  => $request->approval_authority,
            'task_scope'          => $request->task_scope,

            'status'              => $request->status,
        ]);

        return redirect()->route('admin.manage.document_manager')
            ->with('success', 'Document Manager updated successfully.');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.manage.document_manager')->with('success', 'Document Manager deleted successfully.');
    }
}
