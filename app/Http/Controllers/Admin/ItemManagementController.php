<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ItemExport;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanyType;
use App\Models\Customer;
use App\Models\Item;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ItemManagementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:Super Admin', ['index', 'create', 'store', 'edit', 'update', 'destroy', 'export']),
        ];
    }

    public function index(Request $request)
    {
        $entries = $request->get('entries', 10);
        $query = Item::query();
        $companies = Company::get();
        $users = User::get();
        $types = CompanyType::get();
        $customers = Customer::get();

        if ($request->filled('search')) {
            $query->where('item_name',  $request->search);
        }
        if ($request->filled('status')) {
            $query->where('status',  $request->status);
        }
        if ($request->filled('category')) {
            $query->where('category_id',  $request->category);
        }
        if ($request->filled('sub_category')) {
            $query->where('sub_category_id',  $request->sub_category);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id',  $request->user_id);
        }
        if ($request->filled('company_id')) {
            $query->where('company_id',  $request->company_id);
        }

        if ($request->filled('company_type_id')) {
            $query->where('company_type_id',  $request->company_type_id);
        }
        if ($request->filled('total_group_id')) {
            $query->where('total_group_id',  $request->total_group_id);
        }

        $items = $query->orderBy('id', 'desc')->paginate($entries);
        $category = Category::where('status', 1)->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.item.index', compact('items', 'category', 'companies', 'users', 'types', 'customers'))->render()
            ]);
        }
        return view('admin.item.index', compact('items', 'category', 'companies', 'users', 'types', 'customers'));
    }

    public function create()
    {
        $code = 'TECOM#' . date('Y') . 'I' . str_pad(Item::count() + 1, 2, '0', STR_PAD_LEFT);
        $category = Category::where('status', 1)->get();

        $companies = Company::get();
        $users = User::get();
        $types = CompanyType::get();
        $customers = Customer::get();

        return view('admin.item.create', compact('code', 'category', 'companies', 'users', 'types', 'customers'));
    }

    public function getSubCategories(Request $request)
    {
        $subCategories = SubCategory::where('category_id', $request->category_id)
            ->select('id', 'name')
            ->get();

        return response()->json($subCategories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_code' => 'required|unique:items,item_code',
            'item_name' => 'required|string|max:255',
            'company_type_id' => 'required|exists:company_types,id',
            'total_group_id' => 'nullable|exists:customers,id',
            'suggested_price' => 'required|numeric',
            'status' => 'required|boolean',

            'planner_iv_percentage' => 'required_if:planner_commission,1|nullable|numeric|min:0',
            // 'planner_c_percentage'  => 'required_if:planner_commission,1|nullable|numeric|min:0',

            'production_iv_percentage' => 'required_if:production_commission,1|nullable|numeric|min:0',
            // 'production_c_percentage'  => 'required_if:production_commission,1|nullable|numeric|min:0',

            'stt'  => 'nullable|numeric|min:0',
            'account_code'  => 'nullable|string|max:25',
            'description'  => 'nullable|string',



            // 'category' => 'required|exists:categories,id',
            // 'sub_category' => 'required|exists:sub_categories,id',
            // 'user_id' => 'required|exists:users,id',
            // 'company_id' => 'required|exists:companies,id',
            // 'selling_price' => 'required|numeric',
            // 'cost_price' => 'required|numeric',
            // 'commission_factor' => 'required|numeric',
            // 'tax_group' => 'required',
            // 'uom' => 'required',
            // 'opening_stock' => 'required|integer',
            // 'reorder_level' => 'required|integer',
            // 'safety_stock' => 'required|integer',
            // 'default_supplier' => 'required',
            // 'supplier_item' => 'required',
            // 'purchase_price' => 'required|numeric',
            // 'warehouse' => 'required',
            // 'bin_location' => 'required',
            // 'weight' => 'required|numeric',
            // 'short_description' => 'required',
            // 'detail_description' => 'required',
        ]);

        $item = Item::create([
            'item_code' => $request->item_code,
            'item_name' => $request->item_name,
            'company_type_id' => $request->company_type_id,
            'total_group_id' => $request->total_group_id ?? null,
            'suggested_price' => $request->suggested_price,
            'status' => $request->status ?? 1,
            'planner_commission'    => $request->has('planner_commission'),
            'planner_iv_percentage' => $request->has('planner_commission')
                ? $request->planner_iv_percentage
                : null,
            'planner_c_percentage'  => $request->has('planner_commission')
                ? $request->planner_c_percentage
                : null,

            // Production Commission
            'production_commission'    => $request->has('production_commission'),
            'production_iv_percentage' => $request->has('production_commission')
                ? $request->production_iv_percentage
                : null,
            'production_c_percentage'  => $request->has('production_commission')
                ? $request->production_c_percentage
                : null,

            'stt'    => $request->stt ?? null,
            'account_code'    => $request->account_code ?? null,
            'detail_description'    => $request->description ?? null,

            // 'category_id' => $request->category,
            // 'sub_category_id' => $request->sub_category,
            // 'user_id' => $request->user_id,
            // 'company_id' => $request->company_id,
            // 'item_type' => $request->item_type,
            // 'status' => $request->status ?? 1,

            // 'selling_price' => $request->selling_price,
            // 'cost_price' => $request->cost_price,
            // 'commission_factor' => $request->commission_factor,
            // 'tax_group' => $request->tax_group,

            // 'uom' => $request->uom,
            // 'opening_stock' => $request->opening_stock,
            // 'reorder_level' => $request->reorder_level,
            // 'safety_stock' => $request->safety_stock,

            // 'default_supplier' => $request->default_supplier,
            // 'supplier_item' => $request->supplier_item,
            // 'purchase_price' => $request->purchase_price,

            // 'warehouse' => $request->warehouse,
            // 'bin_location' => $request->bin_location,
            // 'weight' => $request->weight,

            // 'short_description' => $request->short_description,
            // 'detail_description' => $request->detail_description,
        ]);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($item)
            ->log('Item Created');

        return redirect()
            ->route('admin.manage.item')
            ->with('success', 'Item created successfully');
    }

    public function edit($id)
    {
        $item = Item::findorFail($id);
        $category = Category::where('status', 1)->get();
        $companies = Company::get();
        $users = User::get();
        $types = CompanyType::get();
        $customers = Customer::get();
        return view('admin.item.edit', compact('item', 'category', 'companies', 'users', 'types', 'customers'));
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'item_code' => 'required|unique:items,item_code,' . $item->id,
            'item_name' => 'required|string|max:255',
            'company_type_id' => 'required|exists:company_types,id',
            'total_group_id' => 'nullable|exists:customers,id',
            'suggested_price' => 'required|numeric',
            'status' => 'required|boolean',

            'planner_iv_percentage' => 'required_if:planner_commission,1|nullable|numeric|min:0',
            // 'planner_c_percentage'  => 'required_if:planner_commission,1|nullable|numeric|min:0',

            'production_iv_percentage' => 'required_if:production_commission,1|nullable|numeric|min:0',
            // 'production_c_percentage'  => 'required_if:production_commission,1|nullable|numeric|min:0',

            'stt'  => 'nullable|numeric|min:0',
            'account_code'  => 'nullable|string|max:25',
            'description'  => 'nullable|string',

            // 'category' => 'required|exists:categories,id',
            // 'sub_category' => 'required',
            // 'user_id' => 'required|exists:users,id',
            // 'company_id' => 'required|exists:companies,id',
            // 'selling_price' => 'required|numeric',
            // 'cost_price' => 'required|numeric',
            // 'commission_factor' => 'required|numeric',
            // 'tax_group' => 'required',
            // 'uom' => 'required',
            // 'opening_stock' => 'required|integer',
            // 'reorder_level' => 'required|integer',
            // 'safety_stock' => 'required|integer',
            // 'default_supplier' => 'required',
            // 'supplier_item' => 'required',
            // 'purchase_price' => 'required|numeric',
            // 'warehouse' => 'required',
            // 'bin_location' => 'required',
            // 'weight' => 'required|numeric',
            // 'short_description' => 'required',
            // 'detail_description' => 'required',
        ]);

        $item->update([
            'item_code' => $request->item_code,
            'item_name' => $request->item_name,
            'company_type_id' => $request->company_type_id,
            'total_group_id' => $request->total_group_id ?? null,
            'suggested_price' => $request->suggested_price,
            'status' => $request->status ?? 1,

            'planner_commission'    => $request->has('planner_commission'),
            'planner_iv_percentage' => $request->has('planner_commission')
                ? $request->planner_iv_percentage
                : null,
            'planner_c_percentage'  => $request->has('planner_commission')
                ? $request->planner_c_percentage
                : null,

            // Production Commission
            'production_commission'    => $request->has('production_commission'),
            'production_iv_percentage' => $request->has('production_commission')
                ? $request->production_iv_percentage
                : null,
            'production_c_percentage'  => $request->has('production_commission')
                ? $request->production_c_percentage
                : null,

            'stt'    => $request->stt ?? null,
            'account_code'    => $request->account_code ?? null,
            'detail_description'    => $request->description ?? null,

            // 'category_id' => $request->category,
            // 'sub_category_id' => $request->sub_category,
            // 'user_id' => $request->user_id,
            // 'company_id' => $request->company_id,
            // 'item_type' => $request->item_type,
            // 'status' => $request->status ?? 1,

            // 'selling_price' => $request->selling_price,
            // 'cost_price' => $request->cost_price,
            // 'commission_factor' => $request->commission_factor,
            // 'tax_group' => $request->tax_group,

            // 'uom' => $request->uom,
            // 'opening_stock' => $request->opening_stock,
            // 'reorder_level' => $request->reorder_level,
            // 'safety_stock' => $request->safety_stock,

            // 'default_supplier' => $request->default_supplier,
            // 'supplier_item' => $request->supplier_item,
            // 'purchase_price' => $request->purchase_price,

            // 'warehouse' => $request->warehouse,
            // 'bin_location' => $request->bin_location,
            // 'weight' => $request->weight,

            // 'short_description' => $request->short_description,
            // 'detail_description' => $request->detail_description,
        ]);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($item)
            ->log('Item Updated');

        return redirect()
            ->route('admin.manage.item')
            ->with('success', 'Item updated successfully');
    }

    public function destroy($id)
    {
        $item =   Item::findOrFail($id);
        $item->delete();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($item)
            ->log('Item Deleted');
        return redirect()->route('admin.manage.item')->with('success', 'Item deleted successfully.');
    }

    public function export(Request $request)
    {
        $ids = $request->ids;
        if (empty($ids)) {
            return response()->json(['error' => 'No rows selected.'], 400);
        }
        return response()->streamDownload(function () use ($ids) {
            echo Excel::raw(new ItemExport($ids), \Maatwebsite\Excel\Excel::CSV);
        }, 'membership.csv', [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="items.csv"',
            'Cache-Control' => 'no-store, no-cache',
            'Access-Control-Expose-Headers' => 'Content-Disposition',
        ]);
    }
}
