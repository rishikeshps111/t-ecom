<form action="{{ isset($receipt) ? route('admin.receipts.update', $receipt->id) : route('admin.receipts.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if (isset($receipt))
        @method('PUT')
    @endif
    <input type="hidden" name="invoice_id" class="form-control" value="{{ $invoice->id }}" readonly>
    <h5 class="mb-3">Receipt</h5>
    <div class="row">
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-12 mb-3 o-f-inp">
                    <label>Receipt Number</label>
                    <input type="text" name="receipt_number" class="form-control" value="{{ $receiptNumber }}" readonly>
                </div>
                <div class="col-lg-6 mb-3 o-f-inp">
                    <label>Receipt Date</label>
                    <input type="date" name="receipt_date" class="form-control" value="{{now()->format('Y-m-d') }}"
                        readonly>
                </div>
                <div class="col-lg-6 mb-3 o-f-inp">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select">
                        @php
                            $status = old('status', $receipt->status ?? '');
                        @endphp

                        <option value="closed" {{ $status === 'closed' ? 'selected' : '' }}>
                            Closed
                        </option>

                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                    </select>
                    @error('status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4 mt-4 pt-2">
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li><strong>Company:</strong> {{ $invoice->company->company_name ?? 'N/A' }} </li>
                        <li><strong>Email:</strong> {{ $invoice->company->email_address ?? 'N/A' }} </li>
                        <li><strong>Phone:</strong> +60 {{ $invoice->company->mobile_no }} </li>
                        {{-- <li><strong>Billing Address:</strong> {{ $invoice->customer->billing_address }}</li> --}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <hr>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width:20%"> Invoice No</th>
                    <th style="width:10%">Due Date</th>
                    <th style="width:15%">Payment Status</th>
                    <th style="width:10%">Total Amount</th>
                    <th style="width:10%">Paid Amount</th>
                    <th style="width:15%">Balance Amount</th>
                    <th style="width:20%">Amount to pay</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice->custom_invoice_id }}</td>
                    <td>
                        {{ $invoice->due_date->format('d M Y') }}
                    </td>
                    <td>
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
                    </td>
                    <td>
                        {{ $invoice->grant_total }}
                    </td>
                    <td>
                        {{ $invoice->paid_amount }}
                    </td>
                    <td>
                        {{ $invoice->balance_amount }}
                    </td>
                    <td>
                        <input type="number" name="amount" class="form-control" placeholder="Enter Amount Paid"
                            step="0.01" min="0" value="{{ old('amount', isset($receipt) ? $receipt->amount : '') }}">
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
            <label>Payment Method <span class="text-danger">*</span></label>
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
        </div>
        @if(!isset($receipt))
            <div class="col-lg-6 mb-3 o-f-inp">
                <label>User Stamp</label>
                <input type="text" name="user_stamp" class="form-control" value="{{ auth()->user()->name }}" readonly>
            </div>
        @endif
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Remarks</label>
            <textarea name="remark" class="form-control" rows="4"
                placeholder="Enter remarks">{{ old('remark', isset($receipt) ? $receipt->remark : '') }}</textarea>
            @error('remark')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
    <hr>
    <div class="d-flex justify-content-between">
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                {{ isset($receipt) ? 'Update' : 'Save' }}
            </button>
            {{-- @if($invoice->payments->isNotEmpty())
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