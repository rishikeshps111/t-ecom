<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\User;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\WorkPlan;
use App\Models\Quotation;
use App\Models\CompanyType;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\QuotationExport;
use App\Models\QuotationApproval;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Can;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Requests\Admin\StoreQuotationRequest;
use App\Http\Requests\Admin\UpdateQuotationRequest;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Browsershot\Browsershot;

class QuotationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(PermissionMiddleware::using('qt.view'), ['index', 'show', 'downloadPdf']),
            new Middleware(PermissionMiddleware::using('qt.edit'),  ['create', 'store']),
            new Middleware(PermissionMiddleware::using('qt.edit'),  ['edit', 'update', 'status']),
            new Middleware(PermissionMiddleware::using('qt.delete'), ['destroy']),
            new Middleware(PermissionMiddleware::using('qt.edit'), ['approvals', 'updateApprovals']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customerID = $request->customer_id ?? null;
        if ($request->ajax()) {
            $records = Quotation::orderBy('created_at', 'desc');
            if ($request->customer_id) {
                $records->whereRelation('workPlan', 'company_id', $request->customer_id);
            }
            return DataTables::eloquent($records)
                ->addColumn('company', function ($row) {
                    return $row->company->company_name ?? 'N/A';
                })->addColumn('customer', function ($row) {
                    return $row->customer->name ?? 'N/A';
                })
                ->addColumn('custom_id', function ($row) {
                    return $row->custom_quotation_id ?? 'N/A';
                })
                ->addColumn('custom_id', function ($row) {
                    return $row->custom_quotation_id ?? 'N/A';
                })
                ->addColumn('work_plan', function ($row) {
                    return $row->workPlan->workplan_number ?? 'N/A';
                })
                ->addColumn('planner', function ($row) {
                    return $row->workPlan->company->planner->name ?? 'N/A';
                })
                ->addColumn('actions', function ($row) {
                    return view('admin.quotation.partials.action', compact('row'))->render();
                })
                ->editColumn('quotation_date', function ($row) {
                    return $row->quotation_date->format('d M Y');
                })
                // ->editColumn('approval_date', function ($row) {
                //     return $row->approval_date ? $row->approval_date->format('d M Y') : '-';
                // })
                // ->editColumn('validity_date', function ($row) {
                //     return $row->validity_date->format('d M Y');
                // })
                ->editColumn('type', function ($row) {
                    return $row->workPlan->company->companyType->name ?? 'N/A';
                })
                ->addColumn('actions', function ($row) {
                    return view('admin.quotation.partials.action', compact('row'))->render();
                })
                ->addColumn('status', function ($row) {

                    $classes = [
                        'draft'     => 'secondary',
                        'pending' => 'warning',
                        'approved'  => 'success',
                        'rejected'  => 'danger',
                        'accepted'  => 'success',
                        'cancelled'  => 'success',
                    ];

                    $status = $row->status ?? 'draft';

                    return '<span class="badge bg-' . ($classes[$status] ?? 'secondary') . ' text-uppercase">'
                        . ucfirst($status) .
                        '</span>';
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('status')) {
                        $query->where('status', $request->status);
                    }
                    if ($request->filled('quotation')) {
                        $query->where('quotation_number', 'like', '%' . $request->quotation . '%');
                    }
                    if ($request->filled('customer')) {
                        $query->whereRelation('customer', 'name', 'like', '%' . $request->customer . '%');
                    }
                    if ($request->filled('invoice')) {
                        $query->whereRelation('invoice', 'invoice_number', 'like', '%' . $request->invoice . '%');
                    }
                    if ($request->filled('work_plan')) {
                        $query->whereRelation('workPlan', 'workplan_number', 'like', '%' . $request->work_plan . '%');
                    }
                    if ($request->filled('total_group')) {
                        $query->where('total_group_id', $request->total_group);
                    }
                    if ($request->filled('type')) {
                        $query->where('company_type_id', $request->type);
                    }
                    if ($request->filled('quotation_date')) {
                        $date = Carbon::parse($request->quotation_date)->format('Y-m-d');
                        $query->whereDate('quotation_date',  $date);
                    }
                    if ($request->filled('validity_date')) {
                        $date = Carbon::parse($request->validity_date)->format('Y-m-d');
                        $query->whereDate('validity_date', $date);
                    }
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $query->whereBetween('quotation_date', [
                            $request->from_date,
                            $request->to_date
                        ]);
                    }
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }
        $types = CompanyType::get();
        $totalGroups = Customer::get();

        return view('admin.quotation.index', compact('types', 'totalGroups', 'customerID'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $customers = User::get();
        $companies = Company::get();
        $items = Item::get();
        $types = CompanyType::get();
        $corpUsers = User::role('Customer')->when(!Auth::user()->hasRole('Super Admin'), function ($query) {
            $query->where('id', Auth::id());
        })->get();

        $currencies = Currency::get();
        $totalGroups = Customer::get();
        $workPlans = WorkPlan::get();
        $workPlanData = WorkPlan::find($request->work_plan) ?? null;
        $typeCode = $this->getTypeCode($workPlanData->company_type_id);
        $code = Quotation::generateCompanyCode($typeCode);
        $planners = $this->getScopedPlanners();
        $staffs = $this->getScopedProductionStaff();


        return view('admin.quotation.create', compact('customers', 'companies', 'items', 'types', 'corpUsers', 'code', 'currencies', 'totalGroups', 'workPlans', 'workPlanData', 'planners', 'staffs'));
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuotationRequest $request)
    {

        $quotation = DB::transaction(function () use ($request) {
            $data = $request->only([
                // 'customer_id',
                'company_id',
                // 'contact_person',
                'quotation_date',
                'validity_date',
                // 'validity_in_days',
                'payment_terms',
                // 'notes',
                // 'terms',
                'company_type_id',
                'business_user_id',
                'planner_user_id',
                'invoice_address',
                'delivery_address',
                'remarks',
                // 'status',
                'currency_id',
                'quotation_number',
                'total_group_id',
            ]);
            // $data['status'] = $request->status;
            $data['user_id'] = auth()->id();


            // Calculate subtotal, tax, discount, grand total
            $subTotal = 0;
            $taxTotal = 0;
            $discountTotal = 0;

            foreach ($request->items as $item) {
                $rowTotal = $item['quantity'] * $item['unit_price'];
                $discountPercent = $item['discount_amount'] ?? 0;
                $discountAmount  = $rowTotal * ($discountPercent / 100);
                $taxPercent = $item['tax_percentage'] ?? 0;
                $taxAmount  = ($rowTotal - $discountAmount) * ($taxPercent / 100);
                $subTotal += $rowTotal;
                $discountTotal += $discountAmount;
                $taxTotal += $taxAmount;
            }


            $data['sub_total'] = $subTotal;
            $data['discount_total'] = $discountTotal;
            $data['tax_total'] = $taxTotal;
            $data['grant_total'] = ($subTotal - $discountTotal) + $taxTotal;
            $data['planner_commission'] = $request->planner_commission;
            $data['p_bill_percentage'] = $request->p_bill_percentage;
            $data['work_plan_id'] = $request->work_plan_id;



            $quotation = Quotation::create($data);

            // Generate quotation number
            // $quotation->quotation_number = Quotation::generateQuotationNumber($quotation->id);
            $quotation->save();

            $workPlan = WorkPlan::find($request->work_plan_id);
            $workPlan->update([
                'planner_id' => $request->planner_id,
                'production_staff_id' => $request->production_staff_id
            ]);


            // foreach (range(1, 4) as $level) {
            //     $quotation->approvals()->create([
            //         'level' => $level
            //     ]);
            // }

            // Save quotation items
            foreach ($request->items as $item) {
                $quantity = $item['quantity'];
                $unitPrice = $item['unit_price'];
                $description = $item['description'] ?? null;
                $umo = $item['umo'] ?? null;
                $taxPercentage = $item['tax_percentage'] ?? 0;
                $discountAmount = $item['discount_amount'] ?? 0;

                $rowTotal = $quantity * $unitPrice;
                $discountValue = $rowTotal * ($discountAmount / 100);
                $taxAmount = ($rowTotal - $discountValue) * ($taxPercentage / 100);
                $totalAmount = ($rowTotal - $discountValue) + $taxAmount;


                $quotation->items()->create([
                    'item_id'        => $item['item_id'],
                    'is_selected' => $item['is_selected'],
                    'sum_amount' => $item['sum_amount'],
                    'planner_iv' => $item['planner_iv'],
                    'production_iv' => $item['production_iv'],
                    'quantity'       => $quantity,
                    'unit_price'     => $unitPrice,
                    'description'    => $description,
                    'umo'            => $umo,
                    'tax_percentage' => $taxPercentage,
                    'tax_amount'     => $taxAmount,
                    'discount_amount' => $discountAmount,
                    'total_amount'   => $totalAmount,
                ]);
            }

            // Save attachments
            if ($request->has('attachments')) {
                foreach ($request->attachments as $attachment) {
                    if (!empty($attachment['file'])) {
                        $filePath = $attachment['file']->store('quotations', 'public');
                        $quotation->attachments()->create([
                            'file' => $filePath,
                            'alt'  => $attachment['alt'] ?? null,
                        ]);
                    }
                }
            }

            activity()
                ->causedBy(Auth::id())
                ->performedOn($quotation)
                ->log('Create Quotation');

            return $quotation;
        });

        if ($quotation->work_plan_id) {
            return redirect()
                ->route('admin.work-orders.show', ['work_order' => $quotation->work_plan_id])
                ->with('success', 'Quotation created successfully.');
        }

        return redirect()->route('admin.quotations.index')->with('success', 'Quotation created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Quotation $quotation, Request $request)
    {
        $quotation->load([
            'customer',
            'company',
            'items.item',
            'attachments',
            'approvals.approver'
        ]);
        $workPlan = WorkPlan::find($request->work_plan) ?? null;

        return view('admin.quotation.view', compact('quotation', 'workPlan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quotation $quotation, Request $request)
    {
        $customers = User::get();
        $companies = Company::get();
        $items = Item::get();
        $types = CompanyType::get();
        $corpUsers = User::role('Customer')->when(!Auth::user()->hasRole('Super Admin'), function ($query) {
            $query->where('id', Auth::id());
        })->get();
        $quotation->load('items', 'attachments');
        $currencies = Currency::get();
        $totalGroups = Customer::get();
        $workPlans = WorkPlan::get();
        $workPlanData = WorkPlan::find($request->work_plan) ?? null;
        $planners = $this->getScopedPlanners();
        $staffs = $this->getScopedProductionStaff();
        return view('admin.quotation.edit', compact('quotation', 'customers', 'companies', 'items', 'types', 'corpUsers', 'currencies', 'totalGroups', 'workPlans', 'workPlanData', 'planners', 'staffs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuotationRequest $request, Quotation $quotation)
    {
        DB::transaction(function () use ($request, $quotation) {
            $data = $request->only([
                // 'customer_id',
                'company_id',
                // 'quotation_code',
                // 'contact_person',
                'quotation_date',
                'validity_date',
                // 'validity_in_days',
                'payment_terms',
                // 'notes',
                // 'terms',
                'company_type_id',
                'business_user_id',
                'planner_user_id',
                'invoice_address',
                'delivery_address',
                'remarks',
                // 'status',
                'currency_id',
                'total_group_id',
            ]);
            // $data['status'] = $request->status;
            // Calculate totals
            $subTotal = 0;
            $taxTotal = 0;
            $discountTotal = 0;


            foreach ($request->items as $item) {
                $rowTotal = $item['quantity'] * $item['unit_price'];
                $discountPercent = $item['discount_amount'] ?? 0;
                $discountAmount  = $rowTotal * ($discountPercent / 100);
                $taxPercent = $item['tax_percentage'] ?? 0;
                $taxAmount  = ($rowTotal - $discountAmount) * ($taxPercent / 100);
                $subTotal += $rowTotal;
                $discountTotal += $discountAmount;
                $taxTotal += $taxAmount;
            }

            $data['sub_total'] = $subTotal;
            $data['discount_total'] = $discountTotal;
            $data['tax_total'] = $taxTotal;
            $data['grant_total'] = ($subTotal - $discountTotal) + $taxTotal;
            $data['planner_commission'] = $request->planner_commission;
            $data['p_bill_percentage'] = $request->p_bill_percentage;
            $quotation->update($data);

            // Update items: delete old and create new
            $quotation->items()->delete();
            foreach ($request->items as $item) {
                $quantity = $item['quantity'];
                $unitPrice = $item['unit_price'];
                $description = $item['description'] ?? null;
                $umo = $item['umo'] ?? null;
                $taxPercentage = $item['tax_percentage'] ?? 0;
                $discountAmount = $item['discount_amount'] ?? 0;

                // Calculate tax amount and total amount for each item
                $rowTotal = $quantity * $unitPrice;
                $discountValue = $rowTotal * ($discountAmount / 100);
                $taxAmount = ($rowTotal - $discountValue) * ($taxPercentage / 100);
                $totalAmount = ($rowTotal - $discountValue) + $taxAmount;


                $quotation->items()->create([
                    'item_id'        => $item['item_id'],
                    'is_selected' => $item['is_selected'],
                    'sum_amount' => $item['sum_amount'],
                    'planner_iv' => $item['planner_iv'],
                    'production_iv' => $item['production_iv'],
                    'quantity'       => $quantity,
                    'unit_price'     => $unitPrice,
                    'description'    => $description,
                    'umo'            => $umo,
                    'tax_percentage' => $taxPercentage,
                    'tax_amount'     => $taxAmount,
                    'discount_amount' => $discountAmount,
                    'total_amount'   => $totalAmount,
                ]);
            }

            $submittedAttachmentIds = collect($request->attachments)->pluck('id')->filter()->toArray();
            $quotation->attachments()
                ->whereNotIn('id', $submittedAttachmentIds)
                ->each(function ($attachment) {
                    $attachment->delete();
                });

            // Update attachments: optional
            if ($request->has('attachments')) {
                foreach ($request->attachments as $attachment) {
                    if (!empty($attachment['file'])) {
                        $filePath = $attachment['file']->store('quotations', 'public');
                        if (!empty($attachment['id'])) {
                            $quotation->attachments()->where('id', $attachment['id'])->update([
                                'file' => $filePath,
                                'alt'  => $attachment['alt'] ?? null,
                            ]);
                        } else {
                            $quotation->attachments()->create([
                                'file' => $filePath,
                                'alt'  => $attachment['alt'] ?? null,
                            ]);
                        }
                    }
                }
            }

            $workPlan = WorkPlan::find($quotation->work_plan_id);
            $workPlan->update([
                'planner_id' => $request->planner_id,
                'production_staff_id' => $request->production_staff_id
            ]);

            activity()
                ->causedBy(Auth::id())
                ->performedOn($quotation)
                ->log('Update Quotation');
        });

        if ($request->work_plan) {
            return redirect()
                ->route('admin.work-orders.show', ['work_order' => $quotation->work_plan_id])
                ->with('success', 'Quotation updated successfully.');
        }
        return redirect()->route('admin.quotations.index')->with('success', 'Quotation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quotation $quotation)
    {
        $quotation->delete();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($quotation)
            ->log('Delete Quotation');
        return response()->json(['success' => true]);
    }

    public function approvals(Quotation $quotation)
    {
        return response()->json([
            'status' => $quotation->status,
            'approvals' => $quotation->approvals()->orderBy('level')->get()
        ]);
    }

    public function updateApprovals(Request $request)
    {
        DB::beginTransaction();

        try {
            $quotationId = null;

            foreach ($request->approvals as $approval) {
                $record = QuotationApproval::findOrFail($approval['id']);

                $previousStatus = $record->status;

                $record->update([
                    'status'           => $approval['status'],
                    'rejection_reason' => $approval['status'] === 'rejected'
                        ? $approval['rejection_reason']
                        : null,
                    'approved_by'      => auth()->id(),
                    'approved_at'      => in_array($approval['status'], ['approved', 'rejected']) ? now() : null,
                ]);

                $logDescription = $approval['status'] === 'approved'
                    ? "Approved by {$record->role}"
                    : "Rejected by {$record->role}: {$approval['rejection_reason']}";

                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($record)
                    ->withProperties([
                        'quotation_id'   => $record->quotation_id,
                        'level'          => $record->level,
                        'previous_status' => $previousStatus,
                        'new_status'     => $approval['status']
                    ])
                    ->log($logDescription);

                $quotationId = $record->quotation_id;
            }

            $allApproved = QuotationApproval::where('quotation_id', $quotationId)
                ->where('status', '!=', 'approved')
                ->doesntExist();

            $quotation = Quotation::find($quotationId);

            if ($allApproved) {
                $quotation->update(['status' => 'approved']);

                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($quotation)
                    ->withProperties(['status' => 'approved'])
                    ->log("Quotation fully approved");
            } else {
                $hasRejected = QuotationApproval::where('quotation_id', $quotationId)
                    ->where('status', 'rejected')
                    ->exists();

                if ($hasRejected) {
                    $quotation->update(['status' => 'rejected']);

                    activity()
                        ->causedBy(auth()->user())
                        ->performedOn($quotation)
                        ->withProperties(['status' => 'rejected'])
                        ->log("Quotation rejected due to one or more rejected approvals");
                }
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update approvals',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function downloadPdf(Quotation $quotation)
    {
        $quotation->load([
            'company.address',
            'customer',
            'items.item',
            'attachments'
        ]);

        $safeFileName = str_replace('#', '_', $quotation->custom_quotation_id) . '.pdf';
        $folder = public_path('storage/quotations');

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $filePath = $folder . '/' . $safeFileName;

        // Only generate PDF if preview=1
        // if (request()->input('preview') == 1) {

        $html  = view('admin.quotation.pdf', compact('quotation'))->render();
        $token = '2U5ktUiCF2NEhqG0e4fdd064c15559ac51f539392a464c95c';

        $response = \Illuminate\Support\Facades\Http::timeout(60)
            ->withHeaders([
                'Content-Type'  => 'application/json',
                'Cache-Control' => 'no-cache',
            ])
            ->post("https://chrome.browserless.io/pdf?token={$token}", [
                'html'    => $html,
                'options' => [
                    'format'          => 'A4',
                    'landscape'       => false,
                    'printBackground' => true,
                    'margin'          => [
                        'top'    => '10mm',
                        'right'  => '10mm',
                        'bottom' => '10mm',
                        'left'   => '10mm',
                    ],
                ],
            ]);

        if ($response->failed()) {
            \Log::error('Browserless PDF failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            abort(500, 'PDF generation failed. Please try again.');
        }

        file_put_contents($filePath, $response->body());

        return response()->download($filePath);
        // }

        // No preview=1 — just return the blade view
        return view('admin.quotation.pdf', compact('quotation'));
    }
    public function export(Request $request)
    {
        $ids = $request->ids;
        if (empty($ids)) {
            return response()->json(['error' => 'No rows selected.'], 400);
        }
        return response()->streamDownload(function () use ($ids) {
            echo Excel::raw(new QuotationExport($ids), \Maatwebsite\Excel\Excel::CSV);
        }, 'quotations.csv', [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="quotations.csv"',
            'Cache-Control' => 'no-store, no-cache',
            'Access-Control-Expose-Headers' => 'Content-Disposition',
        ]);
    }

    public function accept(Request $request)
    {
        $request->validate([
            'quotation_id' => 'required|exists:quotations,id',
        ]);
        DB::beginTransaction();

        try {
            $quotation = Quotation::findOrFail($request->quotation_id);

            $quotation->status = 'accepted';
            $quotation->approval_date = Carbon::now();
            $quotation->save();

            activity()
                ->causedBy(auth()->user())
                ->performedOn($quotation)
                ->log("Quotation accepted");

            // $invoice = Invoice::create([
            //     'created_by'      => $quotation->user_id,
            //     'customer_id'     => $quotation->customer_id,
            //     'company_id'      => $quotation->company_id,
            //     'quotation_id'      => $quotation->id,
            //     'invoice_date'    => now(),
            //     'due_date'        => now()->addDays($quotation->payment_terms ?? 30),
            //     'sub_total'       => $quotation->sub_total,
            //     'tax_total'       => $quotation->tax_total,
            //     'discount_total'  => $quotation->discount_total,
            //     'grant_total'     => $quotation->grant_total,
            //     'paid_amount'     => 0,
            //     'balance_amount'  => $quotation->grant_total,
            //     'status'          => 'submitted',
            //     'payment_status'  => 'unpaid',
            // ]);

            // $invoice->invoice_number = Invoice::generateInvoiceNumber($invoice->id);
            // $invoice->save();

            // foreach ($quotation->items as $item) {
            //     InvoiceItem::create([
            //         'invoice_id'      => $invoice->id,
            //         'item_id'         => $item->item_id,
            //         'quantity'        => $item->quantity,
            //         'unit_price'      => $item->unit_price,
            //         'tax_percentage'  => $item->tax_percentage,
            //         'tax_amount'      => $item->tax_amount,
            //         'discount_amount' => $item->discount_amount,
            //         'total_amount'    => $item->total_amount,
            //     ]);
            // }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Quotation accepted and invoice created successfully.',
                // 'invoice_id' => $invoice->id,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to accept quotation.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function statusView(Request $request)
    {
        $record = Quotation::find($request->id);

        return response()->json([
            'html' => view('admin.quotation.status', compact('record'))->render(),
            'title' => 'Change Status',
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:quotations,id',
            'status' => 'required|string|in:approved,cancelled',
        ]);

        $record = Quotation::findOrFail($request->id);
        $record->status = $request->status;
        $record->approved_by = Auth::user()->id;
        $record->approved_at = now();
        $record->save();

        if ($record->status == 'approved') {
            $workPlan = WorkPlan::findOrFail($record->work_plan_id);
            $workPlan->status = $request->status;
            $workPlan->approved_by = Auth::user()->id;
            $workPlan->approved_at = now();
            $workPlan->save();

            $workPlan->attachments()->create([
                'type' => 'pdf',
                'entity'  => 'QO',
                'name'   => 'Quotation'
            ]);
        }

        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Quotation Status Updated');


        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'type' => $record->status
        ]);
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
}
