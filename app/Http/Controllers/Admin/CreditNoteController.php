<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreditNote;
use App\Models\Invoice;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CreditNoteController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(PermissionMiddleware::using('cn.view'), ['show']),
            new Middleware(PermissionMiddleware::using('cn.edit'),  ['create', 'store'])
        ];
    }
    public function create(Request $request)
    {
        $invoice = Invoice::find($request->invoice);

        $typeCode = $this->getTypeCode($invoice->quotation->workPlan->company_type_id);
        $userPrefix = get_prefix('cr') ?? 'CR';
        $year = active_financial_year_start();
        $lastCreditNote = CreditNote::where('credit_note_number', 'like', "{$userPrefix}/{$typeCode}/{$year}-%")
            ->orderBy('id', 'desc')
            ->first();
        if ($lastCreditNote) {
            $parts = explode('-', $lastCreditNote->credit_note_number);
            $lastNumber = (int) end($parts);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $code = $userPrefix . '/' . $typeCode . '/' . $year . '-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        $items = Item::get();
        return view('admin.credit-note.create', compact('invoice', 'items', 'code'));
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

    public function store(Request $request)
    {
        $request->validate([
            'invoice_id'         => 'required|exists:invoices,id',
            'credit_note_number' => 'required|string|max:255|unique:credit_notes,credit_note_number',
            'amount'             => 'required|numeric|min:0.01',
            'remark'             => 'required|string',
            'date'               => 'required|date',
            'type'               => 'required|in:credit,debit',
        ]);

        $creditNote = DB::transaction(function () use ($request) {

            $invoice = Invoice::with('creditNotes', 'payments')
                ->lockForUpdate()
                ->findOrFail($request->invoice_id);

            // Calculate totals
            $totalCreditNotes = $invoice->creditNotes->sum('amount');
            $totalPaid        = $invoice->payments->sum('amount');

            // Remaining balance before creating this credit note
            $remainingBalance = max(
                $invoice->grant_total - $totalPaid - $totalCreditNotes,
                0
            );

            if ($request->amount > $remainingBalance) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'amount' => 'Credit note amount cannot exceed the remaining invoice balance.',
                ]);
            }

            $data = $request->only([
                'invoice_id',
                'credit_note_number',
                'amount',
                'remark',
                'date',
                'type'
            ]);

            $data['status'] = 'approved';
            $creditNote = CreditNote::create($data);
            $creditNote->invoice->refreshPaymentStatus();

            activity()
                ->causedBy(Auth::id())
                ->performedOn($creditNote)
                ->log('Credit Note Created');

            return $creditNote;
        });

        return redirect()
            ->route('admin.work-orders.show', $creditNote->invoice->quotation->workPlan->id)
            ->with('success', 'Credit Note Created Successfully.');
    }



    public function show(CreditNote $creditNote)
    {
        $creditNote->load([
            'invoice.items.item',
            'invoice.quotation.workPlan.company.planner',
            'invoice.quotation.workPlan.company.productionStaff',
            'invoice.quotation.workPlan.company.totalGroup',
        ]);

        $invoice = $creditNote->invoice;

        return view('admin.credit-note.view', compact('creditNote', 'invoice'));
    }
}
