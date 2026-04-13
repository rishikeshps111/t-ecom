<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreWorkPlanRequest;
use App\Http\Requests\Admin\UpdateWorkPlanRequest;
use App\Models\Company;
use App\Models\CompanyType;
use App\Models\Customer;
use App\Models\DocumentType;
use App\Models\Invoice;
use App\Models\NoteType;
use App\Models\Payment;
use App\Models\PlannerPayout;
use App\Models\ProductionStaffPayout;
use App\Models\Quotation;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkPlan;
use App\Models\WorkPlanAttachment;
use App\Models\WorkPlanNote;
use App\Models\UserCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class WorkPlanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(PermissionMiddleware::using('wo.view'), ['index', 'show', 'details', 'plannerPayoutView', 'productionPayoutView']),
            new Middleware(PermissionMiddleware::using('wo.edit'),  ['create', 'store']),
            new Middleware(PermissionMiddleware::using('wo.edit'),  ['edit', 'update', 'status']),
            new Middleware(PermissionMiddleware::using('wo.delete'), ['destroy']),
            new Middleware(PermissionMiddleware::using('wo.edit'), ['statusView', 'status', 'storePlannerPayout', 'storeProductionPayout', 'close', 'reject']),
            new Middleware(PermissionMiddleware::using('cn.edit'), ['storeNote', 'updateStatus']),
            new Middleware(PermissionMiddleware::using('document.edit'), ['storeAttachment']),
        ];
    }

    protected function getScopedCustomers()
    {
        $user = Auth::user();

        $query = Company::withoutGlobalScopes()
            ->orderBy('company_name', 'asc');

        if ($user->hasRole(['Customer', 'Corp User'])) {
            $companyIds = UserCompany::where('user_id', $user->id)
                ->pluck('company_id')
                ->toArray();

            return empty($companyIds)
                ? collect()
                : $query->whereIn('id', $companyIds)->get();
        }

        if ($user->hasRole('Planner')) {
            return $query->where('planner_id', $user->id)->get();
        }

        if ($user->hasRole('Production Staff')) {
            return $query->where('production_staff_id', $user->id)->get();
        }

        return $query->get();
    }
    protected function getScopedPlanners()
    {
        $user = Auth::user();

        if ($user->hasRole('Customer') || $user->hasRole('Corp User')) {
            $companyIds = $this->getScopedCustomers()->pluck('id');

            if ($companyIds->isEmpty()) {
                return collect();
            }

            return User::role('Planner')
                ->whereHas('plannerCompanies', function ($query) use ($companyIds) {
                    $query->whereIn('id', $companyIds);
                })
                ->orderBy('name', 'asc')
                ->get();
        }

        return User::role('Planner')->orderBy('name', 'asc')->get();
    }

    protected function getScopedProductionStaff()
    {
        $user = Auth::user();

        if ($user->hasRole('Customer') || $user->hasRole('Corp User')) {
            $companyIds = $this->getScopedCustomers()->pluck('id');

            if ($companyIds->isEmpty()) {
                return collect();
            }

            return User::where('user_type', 'production')
                ->whereHas('customerUsers', function ($query) use ($companyIds) {
                    $query->whereIn('id', $companyIds);
                })
                ->orderBy('name', 'asc')
                ->get();
        }

        return User::where('user_type', 'production')->orderBy('name', 'asc')->get();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customerID = $request->customer_id ?? null;
        if ($request->ajax()) {
            $records = WorkPlan::orderBy('id', 'desc');

            if ($request->customer_id) {
                $records->where('company_id', $request->customer_id);
            }
            return DataTables::eloquent($records)
                ->addColumn('customer', function ($row) {
                    return $row->company->company_name ?? 'N/A';
                })
                ->addColumn('job_type', function ($row) {
                    return $row->companyType->name ?? 'N/A';
                })
                ->addColumn('total_group', function ($row) {
                    return $row->totalGroup->customer_name ?? 'N/A';
                })
                ->editColumn('date', function ($row) {
                    return $row->date->format('d M Y') ?? 'N/A';
                })
                ->addColumn('actions', content: function ($row) use ($customerID) {
                    return view('admin.work-plan.partials.action', compact('row', 'customerID'))->render();
                })
                ->addColumn('status', function ($row) {

                    $classes = [
                        'pending' => 'warning',
                        'approved'  => 'success',
                        'cancelled'  => 'danger',
                        'closed'  => 'primary',
                        'rejected'  => 'danger',
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
                    if ($request->filled('planner')) {
                        $query->where('planner_id', $request->planner);
                    }
                    if ($request->filled('staff')) {
                        $query->whereRelation('company', 'production_staff_id', $request->staff);
                    }
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $query->whereBetween('date', [
                            $request->from_date,
                            $request->to_date
                        ]);
                    }
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }

        $customers = $this->getScopedCustomers();
        $planners = $this->getScopedPlanners();
        $staffs = $this->getScopedProductionStaff();
        return view('admin.work-plan.index', compact('customers', 'customerID', 'planners', 'staffs'));
    }

    public function completedList(Request $request)
    {
        $customerID = $request->customer_id ?? null;
        if ($request->ajax()) {
            $records = WorkPlan::where('status', 'closed')->orderBy('id', 'desc');

            if ($request->customer_id) {
                $records->where('company_id', $request->customer_id);
            }
            return DataTables::eloquent($records)
                ->addColumn('customer', function ($row) {
                    return $row->company->company_name ?? 'N/A';
                })
                ->addColumn('job_type', function ($row) {
                    return $row->companyType->name ?? 'N/A';
                })
                ->addColumn('total_group', function ($row) {
                    return $row->totalGroup->customer_name ?? 'N/A';
                })
                ->editColumn('date', function ($row) {
                    return $row->date->format('d M Y') ?? 'N/A';
                })
                ->addColumn('actions', content: function ($row) use ($customerID) {
                    return view('admin.work-plan.partials.action-two', compact('row', 'customerID'))->render();
                })
                ->addColumn('status', function ($row) {

                    $classes = [
                        'pending' => 'warning',
                        'approved'  => 'success',
                        'cancelled'  => 'danger',
                        'closed'  => 'primary',
                        'rejected'  => 'danger',
                    ];

                    $status = $row->status ?? 'pending';

                    return '<span class="badge bg-' . ($classes[$status] ?? 'secondary') . ' text-uppercase">'
                        . ucfirst($status) .
                        '</span>';
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('number')) {
                        $query->where('workplan_number', 'like', '%' . $request->number . '%');
                    }
                    if ($request->filled('customer')) {
                        $query->where('company_id', $request->customer);
                    }
                    if ($request->filled('planner')) {
                        $query->where('planner_id', $request->planner);
                    }
                    if ($request->filled('staff')) {
                        $query->whereRelation('company', 'production_staff_id', $request->staff);
                    }
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $query->whereBetween('date', [
                            $request->from_date,
                            $request->to_date
                        ]);
                    }
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }

        $customers = $this->getScopedCustomers();
        $planners = $this->getScopedPlanners();
        $staffs = $this->getScopedProductionStaff();
        $documentTypes = DocumentType::where('is_active', true)->get();
        $noteTypes = NoteType::get();

        return view('admin.work-plan.completed-list', compact('customers', 'customerID', 'planners', 'staffs', 'documentTypes', 'noteTypes'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // $code = WorkPlan::generateCode();
        $types = CompanyType::orderBy('name', 'asc')->get();
        $customers = $this->getScopedCustomers();
        $planners = $this->getScopedPlanners();
        $totalGroups = Customer::get();
        $customerID = $request->customer_id ?? null;
        $documentTypes = DocumentType::where('is_active', true)->get();
        $staffs = $this->getScopedProductionStaff();


        return view('admin.work-plan.create', compact('types', 'customers', 'planners', 'totalGroups', 'customerID', 'documentTypes', 'staffs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkPlanRequest $request)
    {
        $data = $request->validated();
        // if ($request->hasFile('attachment')) {
        //     $data['attachment'] = $request->file('attachment')->store('workplans', 'public');
        // }
        $workPlan = WorkPlan::create($data);
        if ($request->has('attachments')) {
            foreach ($request->attachments as $attachment) {
                if (!isset($attachment['file'])) {
                    continue;
                }
                $file = $attachment['file'];
                $originalName = $file->getClientOriginalName();
                $path = $attachment['file']->store('work-plans', 'public');

                $workPlan->attachments()->create([
                    'file' => $path,
                    'entity'  => 'WO',
                    'name'   => $originalName,
                    'type' => $attachment['type'] ?? null,
                ]);
            }
        }

        $params = [];
        if ($request->has('custom')) {
            $params = array_filter([
                'customer_id' => $workPlan->company_id
            ]);
        }

        activity()
            ->causedBy(Auth::id())
            ->performedOn($workPlan)
            ->log('Work Order Created');

        return redirect()
            ->route('admin.work-orders.index', $params)
            ->with('success', 'Work Plan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkPlan $workOrder)
    {
        $noteTypes = NoteType::get();
        $latestUser = Company::find($workOrder->company_id)
            ->users()
            ->orderByPivot('created_at', 'desc')
            ->first();
        $documentTypes = DocumentType::where('is_active', true)->get();
        $serviceTypes = CompanyType::get();


        return view('admin.work-plan.view', compact('workOrder', 'noteTypes', 'latestUser', 'documentTypes', 'serviceTypes'));
    }

    public function details(WorkPlan $workOrder)
    {
        return view('admin.work-plan.detail', compact('workOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkPlan $workOrder, Request $request)
    {
        $types = CompanyType::orderBy('name', 'asc')->get();
        $customers = $this->getScopedCustomers();
        $planners = $this->getScopedPlanners();
        $totalGroups = Customer::get();
        $customerID = $request->customer_id ?? null;
        $documentTypes = DocumentType::where('is_active', true)->get();
        $staffs = $this->getScopedProductionStaff();

        return view('admin.work-plan.edit', compact('types', 'customers', 'planners', 'totalGroups', 'workOrder', 'customerID', 'documentTypes', 'staffs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkPlanRequest $request, WorkPlan $workOrder)
    {

        if ($workOrder->status === 'approved') {
            return redirect()
                ->back()
                ->with('warning', 'Approved Work Plans cannot be updated.');
        }
        $data = $request->validated();
        // if ($request->hasFile('attachment')) {
        //     $data['attachment'] = $request->file('attachment')->store('workOrder', 'public');
        // }
        $workOrder->update($data);
        $keptIds = $request->existing_attachment_ids ?? [];

        // Get attachments that were removed in UI
        $attachmentsToDelete = $workOrder->attachments()
            ->whereNotIn('id', $keptIds)
            ->where('entity', 'WO')
            ->get();

        foreach ($attachmentsToDelete as $attachment) {
            $attachment->delete();
        }
        foreach ($request->attachments ?? [] as $data) {

            // Existing attachment update
            if (isset($data['existing']) && isset($data['file'])) {
                $attachment = WorkPlanAttachment::find($data['existing']);

                if ($attachment) {

                    $attachment->update([
                        'file' => $data['file']->store('work-plans', 'public'),
                        'type' => $data['type'] ?? null,
                        'entity'  => 'WO'
                    ]);
                }
            }

            // New attachment
            if (!isset($data['existing']) && isset($data['file'])) {
                $file = $data['file'];
                $originalName = $file->getClientOriginalName();
                $workOrder->attachments()->create([
                    'file' => $data['file']->store('work-plans', 'public'),
                    'type' => $data['type'] ?? null,
                    'entity'  => 'WO',
                    'name'   => $originalName,
                ]);
            }
        }

        $params = [];
        if ($request->has('custom')) {
            $params = array_filter([
                'customer_id' => $workOrder->company_id
            ]);
        }

        activity()
            ->causedBy(Auth::id())
            ->performedOn($workOrder)
            ->log('Work Order Updated');

        return redirect()
            ->route('admin.work-orders.index', $params)
            ->with('success', 'Work Plan updated successfully.'); //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkPlan $workOrder)
    {
        $workOrder->delete();

        activity()
            ->causedBy(Auth::id())
            ->performedOn($workOrder)
            ->log('Work Order Deleted');

        return response()->json(['success' => true]);
    }

    public function statusView(Request $request)
    {
        $record = WorkPlan::find($request->id);

        return response()->json([
            'html' => view('admin.work-plan.status', compact('record'))->render(),
            'title' => 'Change Status',
        ]);
    }

    public function plannerPayoutView(Request $request)
    {
        $record = Payment::with('invoice')->find($request->id);
        $planners = $this->getScopedPlanners();
        return response()->json([
            'html' => view('admin.work-plan.planner-payout', compact('record', 'planners'))->render(),
            'title' => 'Pay Planner Commission',
        ]);
    }

    public function productionPayoutView(Request $request)
    {
        $record = Payment::with('invoice')->find($request->id);
        $staffs = $this->getScopedProductionStaff();

        return response()->json([
            'html' => view('admin.work-plan.production-payout', compact('record', 'staffs'))->render(),
            'title' => 'Pay Production Commission',
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:work_plans,id',
            'status' => 'required|string|in:approved,cancelled',
        ]);

        $record = WorkPlan::findOrFail($request->id);
        $record->status = $request->status;
        $record->approved_by = Auth::user()->id;
        $record->approved_at = now();
        $record->save();

        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Work Order Status Changed');

        // if ($record->status == 'approved') {
        //     $quotation = Quotation::create([
        //         'quotation_number' => Quotation::generateCompanyCode() ?? null,
        //         'quotation_date' => $record->date ?? null,
        //         'work_plan_id' => $record->id ?? null,
        //         'company_id' => $record->company_id ?? null,
        //         'company_type_id' => $record->company_type_id ?? null,
        //         'planner_user_id' => $record->planner_id ?? null,
        //     ]);

        //     foreach (range(1, 4) as $level) {
        //         $quotation->approvals()->create([
        //             'level' => $level
        //         ]);
        //     }

        //     // WorkOrder::create([
        //     //     'workorder_number' => WorkOrder::generateCode() ?? null,
        //     //     'date' => $record->date ?? null,
        //     //     'work_plan_id' => $record->id ?? null,
        //     //     'description' => $record->description ?? null
        //     // ]);
        // }

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'type' => $record->status
        ]);
    }

    public function storePlannerPayout(Request $request)
    {
        $request->validate([
            'id'        => 'required|integer|exists:payments,id',
            'amount'    => 'required|numeric|min:0',
            'is_paid'   => 'required|boolean',
            'paid_date' => 'required|date',
            'payment_method' => 'required',
            'remarks'   => 'nullable|string|max:1000',
            'planner_id' => 'required'
        ]);

        $record = Payment::with('invoice.quotation.workPlan')->findOrFail($request->id);

        $workPlan =  WorkPlan::find($record->invoice?->quotation?->workPlan?->id);
        $workPlan->update([
            'planner_id' => $request->planner_id
        ]);

        PlannerPayout::create([
            'invoice_id' => $record->invoice_id,
            'payment_id' => $record->id,
            'planner_id' => $request->planner_id,
            'amount'     => $request->amount,
            'remarks'    => $request->remarks,
            'payment_method'    => $request->payment_method,
            'status'     => $request->is_paid ? 'paid' : 'unpaid',
        ]);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Planner Payout Created');

        return response()->json([
            'success' => true,
            'message' => 'Planner payout saved successfully.',
        ]);
    }

    public function storeProductionPayout(Request $request)
    {
        $request->validate([
            'id'        => 'required|integer|exists:payments,id',
            'amount'    => 'required|numeric|min:0',
            'is_paid'   => 'required|boolean',
            'paid_date' => 'required|date',
            'payment_method' => 'required',
            'remarks'   => 'nullable|string|max:1000',
            'production_staff_id' => 'required'

        ]);

        $record = Payment::with('invoice.quotation.workPlan')->findOrFail($request->id);
        $workPlan =  WorkPlan::find($record->invoice?->quotation?->workPlan?->id);
        $workPlan->update([
            'production_staff_id' => $request->production_staff_id
        ]);
        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Production Payout Created');

        ProductionStaffPayout::create([
            'invoice_id' => $record->invoice_id,
            'payment_id' => $record->id,
            'production_staff_id' => $request->production_staff_id,
            'amount'     => $request->amount,
            'remarks'    => $request->remarks,
            'payment_method'    => $request->payment_method,
            'status'     => $request->is_paid ? 'paid' : 'unpaid',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Production payout saved successfully.',
        ]);
    }


    public function close($id)
    {
        $workPlan = WorkPlan::with('quotation')->findOrFail($id);

        if ($workPlan->status !== 'approved') {
            return response()->json([
                'message' => 'Only approved work orders can be closed.'
            ], 422);
        }

        $invoices = Invoice::where('quotation_id', optional($workPlan->quotation)->id)->get();

        if ($invoices->isEmpty()) {
            return response()->json([
                'message' => 'No invoices found for this work order.'
            ], 422);
        }

        foreach ($invoices as $invoice) {
            if ((float)$invoice->paid_amount !== (float)$invoice->grant_total) {
                return response()->json([
                    'message' => 'All invoices must be fully paid before closing the Work Order.'
                ], 422);
            }
        }

        DB::transaction(function () use ($workPlan) {
            $workPlan->update([
                'status' => 'closed'
            ]);
        });

        activity()
            ->causedBy(Auth::id())
            ->performedOn($workPlan)
            ->log('work Order Closed');

        return response()->json([
            'message' => 'Work Order closed successfully.'
        ]);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $workPlan = WorkPlan::with('quotation')->findOrFail($id);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($workPlan)
            ->log('work Order Rejected');

        if ($workPlan->status !== 'pending') {
            return response()->json([
                'message' => 'Only pending work orders can be rejected.'
            ], 422);
        }

        DB::transaction(function () use ($workPlan, $request) {
            $workPlan->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason
            ]);
        });

        return response()->json([
            'message' => 'Work Order rejected successfully.'
        ]);
    }
    public function generateCode(Request $request)
    {
        $request->validate([
            'company_type_id' => 'required|integer',
        ]);


        $typeCode = $this->getTypeCode($request->company_type_id);

        return response()->json([
            'code' => WorkPlan::generateCode($typeCode),
        ]);
    }

    private function getTypeCode($companyTypeId): string
    {
        return match ((int) $companyTypeId) {
            1 => 'SEC',   // Secretarial
            2 => 'TAX',   // Taxation
            3 => 'SST',   // Audit
            4 => 'LOAN',  // Loan
            default => 'ALL',
        };
    }

    public function storeAttachment(Request $request, WorkPlan $workOrder)
    {
        $request->validate([
            'entity' => 'required|string',
            'type' => 'required|string|max:255',
            'file' => 'required|file|max:10240', // max 10MB
            'year' => 'required',
            'service_type' => 'required',
            'description' => 'nullable'
        ]);

        // Store file
        $filePath = $request->file('file')->store('work-plans', 'public');

        // Save record
        $record =  WorkPlanAttachment::create([
            'work_plan_id' => $workOrder->id,
            'entity' => $request->entity,
            'type' => $request->type,
            'name' => $request->name,
            'year' =>  $request->year,
            'service_type_id' =>  $request->service_type,
            'description' =>  $request->description ?? null,
            'file' => $filePath,
        ]);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('work Order Attachment Added');

        return redirect()->back()->with('success', 'Document uploaded successfully!');
    }

    public function storeNote(Request $request, WorkPlan $workOrder)
    {
        $request->validate([
            'note_type_id' => 'required',
            'description' => 'required|string',
        ]);

        $record =  WorkPlanNote::create([
            'work_plan_id' => $workOrder->id,
            'note_type_id' => $request->note_type_id,
            'description' => $request->description
        ]);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('work Order Note Added');

        return redirect()->back()->with('success', 'Note Saved successfully!');
    }

    public function storeApiAttachment(Request $request, WorkPlan $workOrder)
    {
        $request->validate([
            'entity' => 'required|string',
            'type' => 'required|string|max:255',
            'file' => 'required|file|max:10240',
            'year' => 'required'
        ]);

        $filePath = $request->file('file')->store('work-plans', 'public');

        $record = WorkPlanAttachment::create([
            'work_plan_id' => $workOrder->id,
            'entity' => $request->entity,
            'type' => $request->type,
            'name' => $request->name,
            'year' => $request->year,
            'file' => $filePath,
        ]);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('work Order Attachment Added');

        return response()->json([
            'status' => true,
            'message' => 'Document uploaded successfully!'
        ]);
    }

    public function storeApiNote(Request $request, WorkPlan $workOrder)
    {
        $request->validate([
            'note_type_id' => 'required',
            'description' => 'required|string',
        ]);

        $record = WorkPlanNote::create([
            'work_plan_id' => $workOrder->id,
            'note_type_id' => $request->note_type_id,
            'description' => $request->description
        ]);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('work Order Note Added');

        return response()->json([
            'status' => true,
            'message' => 'Note saved successfully!'
        ]);
    }

    public function rejectionReason(WorkPlan $workOrder)
    {
        if ($workOrder->status !== 'rejected') {
            return response()->json([
                'message' => 'Work Order is not rejected'
            ], 422);
        }

        return response()->json([
            'reason' => $workOrder->rejection_reason ?? 'No reason provided'
        ]);
    }

    public function updateStatus(Request $request, WorkPlanNote $note)
    {
        $request->validate([
            'status' => 'required|in:pending,active,closed'
        ]);

        $note->update([
            'status' => $request->status
        ]);

        activity()
            ->causedBy(Auth::id())
            ->performedOn($note)
            ->log('work Order Note Status Updated');

        return response()->json([
            'message' => 'Note status updated successfully.'
        ]);
    }
}
