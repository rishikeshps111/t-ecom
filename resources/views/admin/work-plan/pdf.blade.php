<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quotation PDF</title>
    <style>
        /* Base */
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
            line-height: 1.4;
        }

        .container {
            width: 100%;
            padding: 10px;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
        }

        h3,
        h4,
        h6 {
            margin: 0;
        }

        .text-end {
            text-align: right;
        }

        .text-start {
            text-align: left;
        }

        .fw-bold {
            font-weight: bold;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .mb-1 {
            margin-bottom: 5px;
        }

        .mb-3 {
            margin-bottom: 10px;
        }

        .mb-5 {
            margin-bottom: 20px;
        }

        /* Table */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 5px;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .table-sm th,
        .table-sm td {
            padding: 3px;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            color: #fff;
            font-size: 11px;
        }

        .bg-success {
            background-color: #198754;
        }

        .bg-danger {
            background-color: #dc3545;
        }

        .bg-secondary {
            background-color: #6c757d;
        }

        /* Sections */
        .row {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .col-md-4,
        .col-md-6 {
            padding: 0 10px;
        }

        .col-md-4 {
            width: 33.333%;
        }

        .col-md-6 {
            width: 50%;
        }

        .justify-content-end {
            justify-content: flex-end;
            display: flex;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">

            {{-- Header --}}
            <div class="row mb-5">
                <div class="col-md-6">
                    <div>
                        @if($quotation->totalGroup && $quotation->totalGroup->logo)
                            <img src="{{ asset('storage/' . $quotation->totalGroup->logo) }}" alt="Company Logo"
                                style="max-height: 70px; max-width: 120px; object-fit: contain;">
                        @else
                            <img src="{{ asset('assets/images/default-logo.png') }}" alt="Company Logo"
                                style="max-height: 70px; max-width: 120px; object-fit: contain;">
                        @endif
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">
                            {{ $quotation->totalGroup->customer_name ?? '' }}
                        </h3>
                        <p class="mb-1">
                            {{ $quotation->totalGroup->email ?? '' }}
                        </p>
                        <p class="mb-1">
                            +61 {{ $quotation->totalGroup->phone ?? '' }}
                        </p>
                        <p class="mb-0">
                            {{ $quotation->invoice_address ?? '' }}
                        </p>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <h4 class="fw-bold text-uppercase">Quotation</h4>
                    <p class="mb-1">QT NO: {{ $quotation->quotation_number ?? '-' }}</p>
                    <p class="mb-0">Date: {{ $quotation->quotation_date->format('d M Y') }}</p>
                    <p class="mb-0">Planner: {{ $quotation->plannerUser->name ?? 'Not Available' }}</p>
                </div>
            </div>

            {{-- Customer Info --}}
            {{-- <div class="row mb-5">
                <div class="col-md-6">
                    <h6 class="fw-bold text-uppercase">Bill To</h6>
                    <p class="mb-1">{{ $quotation->customer->name }}</p>
                    <p class="mb-1">{{ $quotation->contact_person }}</p>
                    <p class="mb-0">{{ $quotation->customer->email ?? '' }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <p><strong>Validity:</strong> {{ $quotation->validity_in_days }} Days</p>
                    <p><strong>Status:</strong>
                        <span
                            class="badge
                            {{ $quotation->status === 'approved' ? 'bg-success' : ($quotation->status === 'rejected' ? 'bg-danger' : 'bg-secondary') }}">
                            {{ ucfirst($quotation->status) }}
                        </span>
                    </p>
                </div>
            </div> --}}

            {{-- Items Table --}}
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th class="text-start">Item</th>
                        {{-- <th>UMO</th> --}}
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Tax %</th>
                        {{-- <th>Discount</th> --}}
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quotation->items as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="text-start">{{ $item->item->item_name ?? '-' }}</td>
                            {{-- <td>{{ $item->umo }}</td> --}}
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ $item->tax_percentage }}%</td>
                            {{-- <td>{{ number_format($item->discount_amount, 2) }}</td> --}}
                            <td class="fw-bold">{{ number_format($item->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Totals --}}
            <div class="row justify-content-end">
                <div class="col-md-4">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th class="text-start">Subtotal</th>
                            <td class="text-end">₹{{ number_format($quotation->sub_total, 2) }}</td>
                        </tr>
                        <tr>
                            <th class="text-start">Tax</th>
                            <td class="text-end">₹{{ number_format($quotation->tax_total, 2) }}</td>
                        </tr>
                        <tr>
                            <th class="text-start">Discount</th>
                            <td class="text-end">₹{{ number_format($quotation->discount_total, 2) }}</td>
                        </tr>
                        <tr class="fw-bold table-light">
                            <th class="text-start">Grand Total</th>
                            <td class="text-end">₹{{ number_format($quotation->grant_total, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Terms & Notes --}}
            {{-- <div class="row mb-5">
                <div class="col-md-4">
                    <h6 class="fw-bold text-uppercase">Payment Terms</h6>
                    <p>{{ $quotation->payment_terms }}</p>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold text-uppercase">Notes</h6>
                    <p>{{ $quotation->notes }}</p>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold text-uppercase">Terms & Conditions</h6>
                    <p>{{ $quotation->terms }}</p>
                </div>
            </div> --}}

            {{-- Attachments --}}
            @if ($quotation->attachments->count())
                <hr>
                <h6 class="fw-bold text-uppercase">Attachments</h6>
                <ul>
                    @foreach ($quotation->attachments as $file)
                        <li>
                            {{ basename($file->file) }}
                        </li>
                    @endforeach
                </ul>
            @endif

        </div>
    </div>
</body>

</html>