<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AllDocumentManagerController extends Controller
{
    public function index(Request $request)
    {
        $totalGroups = Customer::orderBy('customer_name', 'asc')
            ->get();

        if ($request->ajax()) {
            $records = Company::with([
                'totalGroup' => function ($query) {
                    $query->withoutGlobalScope('exclude_default');
                }
            ])->orderBy('id', 'desc');

            if ($request->filled('search_term')) {
                $search = trim($request->search_term);

                $records->where(function ($query) use ($search) {
                    $query->where('company_code', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%")
                        ->orWhere('email_address', 'like', "%{$search}%")
                        ->orWhere('mobile_no', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $records->where('status', $request->status);
            }

            if ($request->filled('total_group')) {
                $records->where('total_group_id', $request->total_group);
            }

            return DataTables::eloquent($records)
                ->addColumn('actions', function ($row) {
                    return view('admin.document-manger.action', compact('row'))->render();
                })
                ->addColumn('status', function ($row) {
                    $classes = [
                        'active' => 'status-green',
                        'draft'  => 'status-orange',
                        'inactive'  => 'status-red'
                    ];
                    $status = $row->status ?? 'draft';
                    return '<span class="' . ($classes[$status] ?? 'secondary') . '">'
                        . ucfirst($status) .
                        '</span>';
                })
                ->addColumn('totalGroup', function ($row) {
                    return $row->totalGroup->customer_name ?? '-';
                })
                ->addIndexColumn()
                ->filter(function ($query) {
                    // Custom filters are handled from request params above.
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }
        return view('admin.document-manger.index', compact('totalGroups'));
    }
}
