<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Quotation;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function getSubCategories($categoryId)
    {
        $subCategories = SubCategory::where('category_id', $categoryId)->get();

        return response()->json($subCategories);
    }

    public function getLocations($stateId)
    {
        $records = Location::where('state_id', $stateId)->get();

        return response()->json($records);
    }
    public function companies(Request $request)
    {
        return Company::query()
            ->when($request->company_type_id, function ($q) use ($request) {
                $q->where('company_type_id', $request->company_type_id);
            })
            ->when($request->business_user_id, function ($q) use ($request) {
                $q->where('business_user_id', $request->business_user_id);
            })
            ->select('id', 'company_name', 'total_group_id')
            ->get();
    }


    public function planners(Request $request)
    {
        return User::role('Planner')
            ->where('company_id', $request->company_id)
            ->select('id', 'name')
            ->get();
    }
    public function totalGroups(Request $request)
    {
        return Customer::where('company_id', $request->company_id)
            ->select('id', 'customer_name')
            ->get();
    }

    public function details(Quotation $quotation)
    {
        $quotation->load([
            'items.item',        // quotation items
            'company',
            'currency',
            'companyType',
            'businessUser'
        ]);

        return response()->json([
            'quotation' => $quotation,
            'items' => $quotation->items->map(function ($row) {
                return [
                    'item_id'        => $row->item_id,
                    'description'    => $row->description,
                    'quantity'       => $row->quantity,
                    'umo'            => $row->umo,
                    'unit_price'     => $row->unit_price,
                    'planner_iv'     => $row->planner_iv,
                    'production_iv'     => $row->production_iv,
                    'tax_percentage' => $row->tax_percentage,
                    'is_selected' => $row->is_selected,
                    'discount'       => $row->discount_amount ?? 0,
                ];
            })
        ]);
    }
}
