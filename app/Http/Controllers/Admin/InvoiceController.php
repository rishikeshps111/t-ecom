<?php

namespace App\Http\Controllers\Admin;

use App\Exports\InvoiceExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInvoiceRequest;
use App\Http\Requests\Admin\UpdateInvoiceRequest;
use App\Models\Company;
use App\Models\CompanyType;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceApproval;
use App\Models\Item;
use App\Models\Quotation;
use App\Models\User;
use App\Models\WorkPlan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Browsershot\Browsershot;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(PermissionMiddleware::using('inv.view'), ['index', 'show', 'downloadPdf']),
            new Middleware(PermissionMiddleware::using('inv.edit'),  ['create', 'store']),
            new Middleware(PermissionMiddleware::using('inv.edit'),  ['edit', 'update', 'status']),
            new Middleware(PermissionMiddleware::using('inv.delete'), ['destroy']),
            new Middleware(PermissionMiddleware::using('inv.edit'), ['approvals', 'updateApprovals']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customerID = $request->customer_id ?? null;

        if ($request->ajax()) {
            $records = Invoice::orderBy('created_at', 'desc');
            if ($request->customer_id) {
                $records->whereRelation('quotation.workPlan', 'company_id', $request->customer_id);
            }
            return DataTables::eloquent($records)
                ->addColumn('company', function ($row) {
                    return $row->quotation->workPlan->company->company_name ?? 'N/A';
                })->addColumn('customer', function ($row) {
                    return $row->customer->customer_name ?? 'N/A';
                })
                ->addColumn('quotation', function ($row) {
                    return $row->quotation->quotation_number ?? 'N/A';
                })
                ->addColumn('custom_id', function ($row) {
                    return $row->custom_invoice_id ?? 'N/A';
                })
                ->addColumn('quotation_id', function ($row) {
                    return $row->quotation->custom_quotation_id ?? '-';
                })
                ->addColumn('type', function ($row) {
                    return $row->quotation->workPlan->company->companyType->name ?? '-';
                })
                ->addColumn('actions', function ($row) {
                    return view('admin.invoice.partials.action', compact('row'))->render();
                })
                ->editColumn('invoice_date', function ($row) {
                    return $row->invoice_date->format('d M Y');
                })
                ->editColumn('due_date', function ($row) {
                    return $row->due_date->format('d M Y');
                })
                ->addColumn('status', function ($row) {

                    $classes = [
                        'draft'     => 'secondary',
                        'submitted' => 'warning',
                        'approved'  => 'success',
                        'cancelled'  => 'danger',
                        'pending'  => 'warning',
                    ];

                    $status = $row->status ?? 'draft';

                    return '<span class="badge bg-' . ($classes[$status] ?? 'secondary') . ' text-uppercase">'
                        . ucfirst($status) .
                        '</span>';
                })
                ->editColumn('payment_status', function ($row) {

                    $classes = [
                        'partial' => 'warning',
                        'paid'  => 'success',
                        'unpaid'  => 'danger',
                    ];

                    $status = $row->payment_status ?? 'draft';

                    return '<span class="badge bg-' . ($classes[$status] ?? 'secondary') . ' text-uppercase">'
                        . ucfirst($status) .
                        '</span>';
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('status')) {
                        $query->where('status', $request->status);
                    }
                    if ($request->filled('invoice')) {
                        $query->where('invoice_number', 'like', '%' . $request->invoice . '%');
                    }
                    if ($request->filled('customer')) {
                        $query->whereRelation('customer', 'customer_name', 'like', '%' . $request->customer . '%');
                    }
                    if ($request->filled('invoice_date')) {
                        $date = Carbon::parse($request->invoice_date)->format('Y-m-d');
                        $query->whereDate('invoice_date',  $date);
                    }
                    if ($request->filled('due_date')) {
                        $date = Carbon::parse($request->due_date)->format('Y-m-d');
                        $query->whereDate('due_date', $date);
                    }
                    if ($request->filled('total_group')) {
                        $query->where('total_group_id', $request->total_group);
                    }
                    if ($request->filled('type')) {
                        $query->where('company_type_id', $request->type);
                    }
                    if ($request->filled('quotation')) {
                        $query->whereRelation('quotation', 'quotation_number', $request->quotation);
                    }
                    if ($request->filled('work_order')) {
                        $query->whereRelation('quotation.workPlan', 'workplan_number', $request->work_order);
                    }
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $query->whereBetween('invoice_date', [
                            $request->from_date,
                            $request->to_date
                        ]);
                    }
                })
                ->rawColumns(['actions', 'status', 'payment_status'])
                ->make(true);
        }
        $totalGroups = Customer::get();
        $types = CompanyType::get();
        return view('admin.invoice.index', compact('totalGroups', 'types', 'customerID'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $customers = Customer::get();
        $companies = Company::get();
        $items = Item::get();
        $quotations = Quotation::get();
        $types = CompanyType::get();
        $corpUsers = User::role('Customer')->when(!Auth::user()->hasRole('Super Admin'), function ($query) {
            $query->where('id', Auth::id());
        })->get();
        $currencies = Currency::get();
        $totalGroups = Customer::get();
        $workPlanData = WorkPlan::with('quotation')->find($request->work_plan) ?? null;
        $typeCode = $this->getTypeCode($workPlanData->company_type_id);
        $code = Invoice::generateCode($typeCode);

        return view('admin.invoice.create', compact('customers', 'companies', 'items', 'quotations', 'code', 'types', 'corpUsers', 'currencies', 'totalGroups', 'workPlanData'));
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
    public function store(StoreInvoiceRequest $request)
    {
        $invoice = DB::transaction(function () use ($request) {
            $data = $request->only([
                // 'customer_id',
                // 'company_id',
                // 'company_type_id',
                // 'business_user_id',
                // 'total_group_id',
                // 'currency_id',
                'quotation_id',
                'invoice_date',
                'due_date',
                'terms',
                'remarks',
                'invoice_number'
                // 'payment_terms',
                // 'currency'
            ]);
            $data['status'] = 'approved';
            $data['created_by'] = auth()->id();

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
            $data['balance_amount'] = ($subTotal - $discountTotal) + $taxTotal;
            $data['planner_commission'] = $request->planner_commission;
            $data['p_bill_percentage'] = $request->p_bill_percentage;
            $data['quotation_id'] = $request->quotation_id;

            $invoice = Invoice::create($data);

            // Generate quotation number
            // $invoice->invoice_number = Invoice::generateInvoiceNumber($invoice->id);
            $invoice->save();

            // foreach (range(1, 4) as $level) {
            //     $invoice->approvals()->create([
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

                // Calculate tax amount and total amount for each item
                $rowTotal = $quantity * $unitPrice;
                $discountValue = $rowTotal * ($discountAmount / 100);
                $taxAmount = ($rowTotal - $discountValue) * ($taxPercentage / 100);
                $totalAmount = ($rowTotal - $discountValue) + $taxAmount;

                $invoice->items()->create([
                    'item_id'        => $item['item_id'],
                    'is_selected' => $item['is_selected'],
                    'sum_amount' => $item['sum_amount'],
                    'planner_iv' => $item['planner_iv'],
                    'quantity'       => $quantity,
                    'unit_price'     => $unitPrice,
                    'description'   => $description,
                    'umo'   => $umo,
                    'tax_percentage' => $taxPercentage,
                    'tax_amount'     => $taxAmount,
                    'discount_amount' => $discountAmount,
                    'total_amount'   => $totalAmount,
                ]);
            }

            activity()
                ->causedBy(Auth::id())
                ->performedOn($invoice)
                ->log('Create Invoice');

            $workPlan = WorkPlan::findOrFail($invoice->quotation->work_plan_id);

            if ($workPlan) {
                $workPlan->attachments()->create([
                    'type' => 'pdf',
                    'entity'  => 'IN',
                    'name'   => 'Invoice'
                ]);
            }

            return $invoice;
        });

        if ($invoice->quotation->work_plan_id) {
            return redirect()
                ->route('admin.work-orders.show', ['work_order' => $invoice->quotation->work_plan_id])
                ->with('success', 'Invoice created successfully.');
        }

        return redirect()->route('admin.invoices.index')->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice, Request $request)
    {
        $invoice->load([
            'customer',
            'company',
            'items.item'
        ]);
        $workPlan = WorkPlan::find($request->work_plan) ?? null;

        return view('admin.invoice.view', compact('invoice', 'workPlan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice, Request $request)
    {
        $customers = Customer::get();
        $companies = Company::get();
        $items = Item::get();
        $quotations = Quotation::get();
        $types = CompanyType::get();
        $corpUsers = User::role('Corp User')->when(!Auth::user()->hasRole('Super Admin'), function ($query) {
            $query->where('id', Auth::id());
        })->get();
        $currencies = Currency::get();
        $totalGroups = Customer::get();

        $invoice->load('items');
        $workPlanData = WorkPlan::with('quotation')->find($request->work_plan) ?? null;

        return view('admin.invoice.edit', compact('invoice', 'customers', 'companies', 'items', 'quotations', 'types', 'corpUsers', 'currencies', 'totalGroups', 'workPlanData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        DB::transaction(function () use ($request, $invoice) {
            $data = $request->only([
                // 'customer_id',
                // 'company_id',
                'quotation_id',
                // 'company_type_id',
                // 'business_user_id',
                // 'total_group_id',
                // 'currency_id',
                'invoice_date',
                'due_date',
                'terms',
                'remarks',
                // 'payment_terms',
                // 'currency'
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
            $data['balance_amount'] = ($subTotal - $discountTotal) + $taxTotal;
            $data['planner_commission'] = $request->planner_commission;
            $data['p_bill_percentage'] = $request->p_bill_percentage;
            $data['quotation_id'] = $request->quotation_id;

            $invoice->update($data);

            // Update items: delete old and create new
            $invoice->items()->delete();
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

                $invoice->items()->create([
                    'item_id'        => $item['item_id'],
                    'is_selected' => $item['is_selected'],
                    'sum_amount' => $item['sum_amount'],
                    'planner_iv' => $item['planner_iv'],
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

            activity()
                ->causedBy(Auth::id())
                ->performedOn($invoice)
                ->log('Update Invoice');
        });

        if ($request->work_plan) {
            return redirect()
                ->route('admin.work-orders.show', ['work_order' => $invoice->quotation->work_plan_id])
                ->with('success', 'Invoice updated successfully.');
        }

        return redirect()->route('admin.invoices.index')->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        activity()
            ->causedBy(Auth::id())
            ->performedOn($invoice)
            ->log('Delete Invoice');
        return response()->json(['success' => true]);
    }

    public function approvals(Invoice $invoice)
    {
        return response()->json([
            'status' => $invoice->status,
            // 'status' => 'pending',
            'approvals' => $invoice->approvals()->orderBy('level')->get()
        ]);
    }

    public function updateApprovals(Request $request)
    {
        DB::beginTransaction();

        try {
            $invoiceId = null;

            foreach ($request->approvals as $approval) {
                $record = InvoiceApproval::findOrFail($approval['id']);

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
                        'invoice_id' => $record->invoice_id,
                        'level' => $record->level,
                        'previous_status' => $previousStatus,
                        'new_status' => $approval['status']
                    ])
                    ->log($logDescription);

                $invoiceId = $record->invoice_id;
            }

            $allApproved = InvoiceApproval::where('invoice_id', $invoiceId)
                ->where('status', '!=', 'approved')
                ->doesntExist();

            $invoice = Invoice::find($invoiceId);

            if ($allApproved) {
                $invoice->update(['status' => 'approved']);

                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($invoice)
                    ->withProperties(['status' => 'approved'])
                    ->log("Invoice fully approved");
            } else {
                $hasRejected = InvoiceApproval::where('invoice_id', $invoiceId)
                    ->where('status', 'rejected')
                    ->exists();

                if ($hasRejected) {
                    $invoice->update(['status' => 'rejected']);

                    activity()
                        ->causedBy(auth()->user())
                        ->performedOn($invoice)
                        ->withProperties(['status' => 'rejected'])
                        ->log("Invoice rejected due to one or more rejected approvals");
                }
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e); // Optional for debugging
            return response()->json([
                'success' => false,
                'message' => 'Failed to update approvals'
            ], 500);
        }
    }

    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load([
            'customer',
            'company',
            'items.item'
        ]);

        $safeFileName = str_replace('#', '_', $invoice->custom_invoice_id) . '.pdf';
        $folder = public_path('storage/invoice');

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $filePath = $folder . '/' . $safeFileName;

        // Only generate PDF if preview=1
        // if (request()->input('preview') == 1) {

        $html  = view('admin.invoice.pdf', compact('invoice'))->render();
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
            \Log::error('Browserless Invoice PDF failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            abort(500, 'PDF generation failed. Please try again.');
        }

        file_put_contents($filePath, $response->body());

        return response()->download($filePath);
        // }

        // No preview=1 — just return the blade view
        return view('admin.invoice.pdf', compact('invoice'));
    }

    public function export(Request $request)
    {
        $ids = $request->ids;
        if (empty($ids)) {
            return response()->json(['error' => 'No rows selected.'], 400);
        }
        return response()->streamDownload(function () use ($ids) {
            echo Excel::raw(new InvoiceExport($ids), \Maatwebsite\Excel\Excel::CSV);
        }, 'invoices.csv', [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="invoices.csv"',
            'Cache-Control' => 'no-store, no-cache',
            'Access-Control-Expose-Headers' => 'Content-Disposition',
        ]);
    }

    public function statusView(Request $request)
    {
        $record = Invoice::find($request->id);

        return response()->json([
            'html' => view('admin.invoice.status', compact('record'))->render(),
            'title' => 'Change Status',
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:invoices,id',
            'status' => 'required|string|in:approved,cancelled',
        ]);

        $record = Invoice::findOrFail($request->id);
        $record->status = $request->status;
        $record->save();

        activity()
            ->causedBy(Auth::id())
            ->performedOn($record)
            ->log('Invoice Status Changes');

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'type' => $record->status
        ]);
    }
}
