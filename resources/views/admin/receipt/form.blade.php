<form action="{{ isset($receipt) ? route('admin.receipts.update', $receipt->id) : route('admin.receipts.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if (isset($receipt))
        @method('PUT')
    @endif
    <input type="hidden" name="invoice_id" class="form-control" value="{{ $invoice->id }}" readonly>

    @if ($workPlanData)
        <input type="hidden" name="work_plan" class="form-control" value="{{ $workPlanData }}">
    @endif
    <h5 class="mb-3">OR (original receipt )</h5>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-6 mb-3 o-f-inp">
                    <label>Receipt Number</label>
                    <input type="text" name="receipt_number" class="form-control" value="{{ $receiptNumber }}"
                        readonly>
                </div>
                <div class="col-lg-6 mb-3 o-f-inp">
                    <label>Receipt Date</label>
                    <input type="date" name="receipt_date" class="form-control" value="{{ now()->format('Y-m-d') }}"
                        readonly>
                </div>
                <div class="col-lg-4 mb-3 o-f-inp">
                    <label>Planner</label>
                    <input type="text" class="form-control"
                        value="{{ $invoice->quotation->workPlan->planner->name ?? 'N/A' }}" readonly>
                </div>
                <div class="col-lg-4 mb-3 o-f-inp">
                    <label>Total Group</label>
                    <input type="text" class="form-control"
                        value="{{ $invoice->quotation->workPlan->totalGroup->customer_name ?? 'N/A' }}" readonly>
                </div>
                <div class="col-lg-4 mb-3 o-f-inp">
                    <label>Customer Name</label>
                    <input type="text" class="form-control"
                        value="{{ $invoice->quotation->workPlan->company->company_name ?? 'N/A' }}" readonly>
                </div>
                <div class="col-lg-4 mb-3 o-f-inp">
                    <label>Invoice Number</label>
                    <input type="text" class="form-control" value="{{ $invoice->invoice_number ?? '-' }}" readonly>
                </div>
                <div class="col-lg-4 mb-3 o-f-inp">
                    <label>Invoice Date</label>
                    <input type="date" class="form-control" value="{{ $invoice->invoice_date->format('Y-m-d') }}"
                        readonly>
                </div>
                <div class="col-lg-4 mb-3 o-f-inp">
                    <label>Invoice Payment Status</label><br>
                    @php
                        $badges = [
                            'paid' => 'success',
                            'partial' => 'warning',
                            'unpaid' => 'danger',
                        ];

                        $status = strtolower($invoice->payment_status);
                        $color = $badges[$status] ?? 'secondary';
                    @endphp

                    <span class="badge bg-{{ $color }}">
                        {{ ucfirst($invoice->payment_status) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="d-flex justify-content-center align-items-center">
                <h4 class="fw-bold text-uppercase">Invoice Items</h4>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="table-responsive mb-0">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>NO</th>
                            <th class="text-start">Item</th>
                            <th>Qty</th>
                            <th>U Price</th>
                            <th>SST %</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->items as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="text-start">{{ $item->item->item_name ?? '-' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ $item->tax_percentage }}%</td>
                                <td class="fw-bold">{{ number_format($item->total_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <table class="table table-sm table-borderless">
                <tr>
                    <th class="text-start">Planner Commission</th>
                    <td class="text-end">RM {{ number_format($invoice->planner_commission, 2) }}</td>
                </tr>
                <tr>
                    <th class="text-start">Bill to P %</th>
                    <td class="text-end">{{ number_format($invoice->p_bill_percentage, 6) }} %</td>
                </tr>
                <tr class="fw-bold table-light">
                </tr>
            </table>
        </div>
        <div class="col-md-4"></div>
        <div class="col-lg-4">
            <table class="table table-sm table-borderless">
                <tr>
                    <th class="text-start">Amount</th>
                    <td class="text-end">RM {{ number_format($invoice->sub_total, 2) }}</td>
                </tr>
                <tr>
                    <th class="text-start">SST</th>
                    <td class="text-end">RM {{ number_format($invoice->tax_total, 2) }}</td>
                </tr>
                <tr class="fw-bold table-light">
                    <th class="text-start">Total</th>
                    <td class="text-end">RM {{ number_format($invoice->grant_total, 2) }}</td>
                </tr>
                <tr class="fw-bold table-light">
                    <th class="text-start">Total Credit Note Amount</th>
                    <td class="text-end">RM {{ number_format($invoice->creditNotes->sum('amount'), 2) }}</td>
                </tr>
                <tr class="fw-bold table-light">
                    <th class="text-start">Paid Amount</th>
                    <td class="text-end">RM {{ number_format($invoice->paid_amount, 2) }}</td>
                </tr>
                <tr class="fw-bold table-light">
                    <th class="text-start">Balance Amount</th>
                    <td class="text-end">
                        RM {{ number_format($invoice->balance_amount - $invoice->creditNotes->sum('amount'), 2) }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <hr>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width:50%">Payment Method</th>
                    <th style="width:50%">Enter the Amount to Pay</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="payment_method" class="form-select">
                            <option value="">Select Payment Method</option>
                            @php
                                $paymentMethod = old('payment_method', $receipt->payment_method ?? '');
                            @endphp
                            <option value="cash" {{ $paymentMethod == 'cash' ? 'selected' : '' }}>
                                Cash
                            </option>
                            <option value="card" {{ $paymentMethod == 'card' ? 'selected' : '' }}>
                                Card
                            </option>
                            <option value="bank_transfer" {{ $paymentMethod == 'bank_transfer' ? 'selected' : '' }}>
                                Bank Transfer
                            </option>
                            <option value="visa_card" {{ $paymentMethod == 'visa_card' ? 'selected' : '' }}>
                                Visa Card
                            </option>
                            <option value="master_card" {{ $paymentMethod == 'master_card' ? 'selected' : '' }}>
                                Master Card
                            </option>
                        </select>
                        @error('payment_method')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </td>
                    <td>
                        <input type="number" name="amount" class="form-control" placeholder="Enter Amount Paid"
                            step="0.01" min="0"
                            value="{{ old(
                                'amount',
                                isset($receipt) ? $receipt->amount : $invoice->balance_amount - $invoice->creditNotes->sum('amount'),
                            ) }}">

                        @error('amount')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <hr>

    <div class="row">
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Receipt Remarks</label>
            <textarea name="remark" class="form-control editor" rows="4" placeholder="Enter remarks">{{ old('remark', isset($receipt) ? $receipt->remark : $invoice->quotation->workPlan->totalGroup->billerProfile->receipt_tc) }}</textarea>
            @error('remark')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Notes</label>
            <textarea name="notes" class="form-control editor" rows="4" placeholder="Enter Notes">{{ old('notes', isset($receipt) ? $receipt->notes : '') }}</textarea>
            @error('notes')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
    <hr>
    <div class="d-flex justify-content-end">
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                {{ isset($receipt) ? 'Update' : 'Save' }}
            </button>
            {{-- @if ($invoice->payments->isNotEmpty())
            <a href="{{ route('admin.receipts.index') }}?inv_id={{ $invoice->id }}" class="btn btn-secondary">
                Back
            </a>
            @else
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
                Back
            </a>
            @endif --}}
        </div>
    </div>
</form>
