<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePaymentRequest;
use App\Http\Requests\Admin\UpdatePaymentRequest;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\WorkPlan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Browsershot\Browsershot;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class ReceiptController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(PermissionMiddleware::using('or.view'), ['index', 'show', 'create', 'store', 'edit', 'update']),
            new Middleware(PermissionMiddleware::using('or.edit'), ['create', 'store', 'edit', 'update']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $invoice = Invoice::find($request->inv_id);
        if ($request->ajax()) {
            $records = Payment::where('invoice_id', $request->inv_id)->orderBy('created_at', 'desc');

            return DataTables::eloquent($records)
                ->addColumn('actions', function ($row) {
                    return view('admin.receipt.partials.action', compact('row'))->render();
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y');
                })
                ->editColumn('remark', function ($row) {
                    return $row->remark ?? '-';
                })
                ->editColumn('payment_method', function ($row) {
                    return ucfirst(str_replace('_', ' ', $row->payment_method));
                })
                ->addColumn('payment_id', function ($row) {
                    return $row->custom_payment_id;
                })
                ->addColumn('status', function ($row) {

                    $classes = [
                        'pending' => 'warning',
                        'closed'  => 'success'
                    ];

                    $status = $row->status ?? 'warning';

                    return '<span class="badge bg-' . ($classes[$status] ?? 'secondary') . ' text-uppercase">'
                        . ucfirst($status) .
                        '</span>';
                })
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->filled('status')) {
                        $query->where('status', $request->status);
                    }
                    if ($request->filled('payment')) {
                        $query->where('payment_method', $request->payment);
                    }
                    if ($request->filled('receipt')) {
                        $query->searchByCustomId($request->receipt);
                    }
                    if ($request->filled('date')) {
                        $query->whereDate('created_at', $request->date);
                    }
                })
                ->rawColumns(['actions', 'remark', 'status'])
                ->make(true);
        }
        return view('admin.receipt.index', compact('invoice'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $invoice = Invoice::find($request->inv_id);
        if (!$invoice) {
            return abort(404);
        }
        $lastReceiptId = Payment::max('id') ?? 0;
        $nextReceiptId = $lastReceiptId + 1;
        $typeCode = $this->getTypeCode($invoice->quotation->workPlan->company_type_id);
        $receiptNumber = Payment::generateReceiptNumber($nextReceiptId, $typeCode);
        $workPlanData = $request->work_plan ?? null;
        $inv = $request->inv_id ?? null;

        return view('admin.receipt.create', compact('invoice', 'receiptNumber', 'workPlanData'));
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
    public function store(StorePaymentRequest $request)
    {
        $invoiceId = null;

        DB::transaction(function () use ($request, &$invoiceId) {

            $data = $request->validated();
            $data['created_by'] = Auth::id();

            $record = Payment::create($data);

            $invoice = Invoice::with('creditNotes', 'payments')
                ->lockForUpdate()
                ->findOrFail($data['invoice_id']);

            $invoiceId = $invoice->id;

            $totalPaid        = $invoice->payments->sum('amount');
            $totalCreditNotes = $invoice->creditNotes->sum('amount');

            $balance = max(
                $invoice->grant_total - $totalCreditNotes - $totalPaid,
                0
            );

            $invoicePaymentStatus = $balance == 0
                ? 'paid'
                : ($totalPaid > 0 ? 'partial' : 'unpaid');

            $invoice->update([
                'paid_amount'    => $totalPaid,
                'balance_amount' => $balance,
                'payment_status' => $invoicePaymentStatus,
            ]);

            $workPlan = WorkPlan::findOrFail($invoice->quotation->work_plan_id);

            $workPlan->attachments()->create([
                'type'        => 'pdf',
                'entity'      => 'OR',
                'name'        => 'Original Receipt',
                'payment_id'  => $record->id,
            ]);

            activity()
                ->causedBy(Auth::id())
                ->performedOn($record)
                ->log('Create Receipt');
        });

        if ($request->work_plan) {
            return redirect()
                ->route('admin.work-orders.show', ['work_order' => $request->work_plan])
                ->with('success', 'Receipt created successfully.');
        }

        return redirect()
            ->route('admin.receipts.index', ['inv_id' => $invoiceId])
            ->with('success', 'Receipt created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Payment $receipt)
    {
        $receipt->load([
            'invoice',
            'creator'
        ]);
        $workPlanData = $request->work_plan ?? null;
        $inv = $request->inv_id ?? null;
        return view('admin.receipt.view', compact('receipt', 'inv', 'workPlanData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $receipt, Request $request)
    {
        $invoice = Invoice::find($request->inv_id);
        if (!$invoice) {
            return abort(404);
        }
        $lastReceiptId = Payment::max('id') ?? 0;
        $nextReceiptId = $lastReceiptId + 1;
        $receiptNumber = Payment::generateReceiptNumber($nextReceiptId);

        return view('admin.receipt.edit', compact('invoice', 'receiptNumber', 'receipt'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $receipt)
    {
        $invoiceId = null;

        DB::transaction(function () use ($request, &$invoiceId, $receipt) {

            $data = $request->validated();
            $receipt->update($data);
            $invoice = Invoice::lockForUpdate()->findOrFail($data['invoice_id']);
            $invoiceId = $invoice->id;

            $totalPaid = $invoice->payments()
                ->sum('amount');

            $balance = max($invoice->grant_total - $totalPaid, 0);

            $invoicePaymentStatus = $balance == 0
                ? 'paid'
                : ($totalPaid > 0 ? 'partial' : 'unpaid');

            $invoice->update([
                'paid_amount'    => $totalPaid,
                'balance_amount' => $balance,
                'payment_status' => $invoicePaymentStatus,
            ]);

            // if ($invoice->payment_status == 'paid') {
            //     $workPlan =  WorkPlan::find($invoice->quotation->work_plan_id);
            //     $workPlan->update([
            //         'status' => 'closed'
            //     ]);
            // }

            activity()
                ->causedBy(Auth::id())
                ->performedOn($receipt)
                ->log('Update Receipt');
        });

        return redirect()
            ->route('admin.receipts.index', ['inv_id' => $invoiceId])
            ->with('success', 'Receipt updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $receipt)
    {
        //
    }

    public function downloadPdf(Payment $receipt)
    {
        $receipt->load([
            'invoice',
            'creator'
        ]);

        $safeFileName = str_replace(['#', '/', '\\'], '_', $receipt->custom_payment_id) . '.pdf';

        $nestedFolder = $receipt->type_path ?? '';
        $folder = public_path('storage/receipt' . ($nestedFolder ? '/' . $nestedFolder : ''));

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $filePath = $folder . '/' . $safeFileName;

        // Only generate PDF if preview=1
        // if (request()->input('preview') == 1) {

        $html  = view('admin.receipt.pdf', compact('receipt'))->render();
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
            \Log::error('Browserless Receipt PDF failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            abort(500, 'PDF generation failed. Please try again.');
        }

        file_put_contents($filePath, $response->body());

        return response()->download($filePath);
        // }

        // No preview=1 — just return the blade view
        return view('admin.receipt.pdf', compact('receipt'));
    }
}
