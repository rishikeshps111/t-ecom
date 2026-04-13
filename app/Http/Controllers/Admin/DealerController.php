<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DealerController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(PermissionMiddleware::using('dealer.view'), ['index']),
            new Middleware(PermissionMiddleware::using('dealer.create'),  ['create', 'store']),
            new Middleware(PermissionMiddleware::using('dealer.edit'),  ['edit', 'update']),
            new Middleware(PermissionMiddleware::using('dealer.delete'), ['destroy']),
        ];
    }
    public function index(Request $request)
    {
        $entries = $request->get('entries', 10);
        $query = User::where('id', '!=', 1)->whereHas('roles', function ($q) {
            $q->where('name', 'Dealer');
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

        $dealer = $query->orderBy('id', 'desc')->paginate($entries);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.user.dealer.index', compact('dealer'))->render()
            ]);
        }
        return view('admin.user.dealer.index', compact('dealer'));
    }

    public function create()
    {
        $userPrefix = get_prefix('dealer') ?? 'DEAL';
        $year = active_financial_year_start();;

        $lastDealer = User::whereHas('roles', function ($q) {
            $q->where('name', 'Dealer');
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

        $userId =  $userPrefix  . $year .  str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        return view('admin.user.dealer.create', compact('userId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'         => 'required|unique:users,user_id',
            'name'            => 'required|string|max:255',
            'contact_person'  => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'phone'           => 'required|digits:10',
            'address'         => 'required|string',
            'status'          => 'nullable|in:0,1',
        ]);

        $user = User::create([
            'user_id'            => $request->user_id,
            'name'               => $request->name,
            'contact_person'     => $request->contact_person,
            'email'              => $request->email,
            'phone' => $request->country_code . $request->phone,
            'address'            => $request->address,
            'role'             => 4,

            'assigned_item'      => $request->assigned_item,
            'pricing_level'      => $request->pricing_level,
            'commission_factor'  => $request->commission_factor,
            'tax_group'          => $request->tax_group,
            'quotation_access'   => $request->quotation_access,
            'invoice_view'       => $request->invoice_view,
            'status'             => $request->status ?? 1,
        ]);

        $role = Role::findById($request->role);
        $user->assignRole($role);
        $user->save();

        return redirect()->route('admin.manage.dealer')->with('success', 'Dealer created successfully');
    }

    public function edit($id)
    {
        $dealer = User::findorFail($id);

        return view('admin.user.dealer.edit', compact('dealer'));
    }

    public function update(Request $request, $id)
    {
        $dealer = User::findOrFail($id);

        $request->validate([
            'name'            => 'required|string|max:255',
            'contact_person'  => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $dealer->id,
            'phone'           => 'required|digits:10',
            'address'         => 'required|string',
            'status'          => 'nullable|in:0,1',
        ]);

        $dealer->update([
            'name'               => $request->name,
            'contact_person'     => $request->contact_person,
            'email'              => $request->email,
            'phone' => $request->country_code . $request->phone,
            'address'            => $request->address,

            'assigned_item'      => $request->assigned_item,
            'pricing_level'      => $request->pricing_level,
            'commission_factor'  => $request->commission_factor,
            'tax_group'          => $request->tax_group,
            'quotation_access'   => $request->quotation_access,
            'invoice_view'       => $request->invoice_view,
            'status'             => $request->status ?? 1,
        ]);

        $dealer->syncRoles([
            Role::findById($request->role)
        ]);
        $dealer->save();

        return redirect()->route('admin.manage.dealer')->with('success', 'Dealer updated successfully');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.manage.dealer')->with('success', 'Dealer deleted successfully.');
    }
}
