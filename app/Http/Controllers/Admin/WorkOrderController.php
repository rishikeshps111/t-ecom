<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Admin\StoreWorkOrderRequest;
use App\Http\Controllers\Admin\UpdateWorkOrderRequest;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $customerID = $request->customer_id ?? null;
        if ($request->ajax()) {
            $records = WorkOrder::orderBy('created_at', 'desc');

            if ($request->customer_id) {
                $records->where('company_id', $request->customer_id);
            }
            return DataTables::eloquent($records)
                ->addColumn('customer', function ($row) {
                    return $row->workPlan->company->company_name ?? 'N/A';
                })
                ->addColumn('wp_date', function ($row) {
                    return $row->workPlan->date->format('d M Y') ?? 'N/A';
                })
                ->addColumn('wp_no', function ($row) {
                    return $row->workPlan->workplan_number ?? 'N/A';
                })
                ->editColumn('date', function ($row) {
                    return $row->date->format('d M Y') ?? 'N/A';
                })
                ->editColumn('description', function ($row) {
                    return $row->description ?? 'N/A';
                })
                ->addColumn('actions', content: function ($row) use ($customerID) {
                    return view('admin.work-order.partials.action', compact('row', 'customerID'))->render();
                })
                ->addColumn('status', function ($row) {

                    $classes = [
                        'pending' => 'warning',
                        'approved'  => 'primary',
                        'closed'  => 'success',
                        'cancelled'  => 'danger',
                    ];

                    $status = $row->status ?? 'pending';

                    return '<span class="badge bg-' . ($classes[$status] ?? 'secondary') . ' text-uppercase">'
                        . ucfirst($status) .
                        '</span>';
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('status')) {
                        $query->where('status', $request->status);
                    }
                    if ($request->filled('number')) {
                        $query->where('workplan_number', 'like', '%' . $request->number . '%');
                    }
                    if ($request->filled('customer')) {
                        $query->where('company_id', $request->customer);
                    }
                    if ($request->filled('date')) {
                        $query->whereDate('date', $request->date);
                    }
                })
                ->rawColumns(['description', 'actions', 'status'])
                ->make(true);
        }

        $customers = Company::get();

        return view('admin.work-order.index', compact('customers', 'customerID'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkOrderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkOrder $workOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkOrder $workOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkOrderRequest $request, WorkOrder $workOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkOrder $workOrder)
    {
        //
    }
}
