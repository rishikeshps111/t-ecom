@extends('admin.layouts.app')
@section('title', 'Invoice Management')
@section('content')
    <div class="container my-5">
        <div class="card shadow-sm">
            <div class="card-body p-5">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h3 class="fw-bold">{{ $invoice->company->company_name }}</h3>
                        <p class="mb-1">{{ $invoice->company->address->address1 ?? '' }}</p>
                        <p class="mb-0">{{ $invoice->company->contact_number ?? '' }}</p>
                    </div>
                    <div class="text-end">
                        <h4 class="fw-bold text-uppercase">Invoice</h4>
                        <p class="mb-1">{{ $invoice->invoice_number ?? '-' }}</p>
                        <p class="mb-0">Date: {{ $invoice->invoice_date->format('d M Y') }}</p>
                    </div>
                </div>

                {{-- Customer & Meta Info --}}
                <div class="row mb-5">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-uppercase">Bill To</h6>
                        <p class="mb-1">{{ $invoice->customer->name }}</p>
                        <p class="mb-1">{{ $invoice->contact_person }}</p>
                        <p class="mb-0">{{ $invoice->customer->email ?? '' }}</p>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <p><strong>Due Date:</strong> {{ $invoice->due_date }}</p>
                        <p><strong>Status:</strong>
                            <span
                                class="badge 
                                                                                    {{ $invoice->status === 'approved' ? 'bg-success' : ($invoice->status === 'rejected' ? 'bg-danger' : 'bg-secondary') }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </p>
                    </div>
                </div>

                {{-- Items Table --}}
                <div class="table-responsive mb-5">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>SL</th>
                                <th class="text-start">Item</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Tax %</th>
                                <th>Discount</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice->items as $i => $item)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="text-start">{{ $item->item->item_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->unit_price, 2) }}</td>
                                    <td>{{ $item->tax_percentage }}%</td>
                                    <td>{{ number_format($item->discount_amount, 2) }}</td>
                                    <td class="fw-bold">{{ number_format($item->total_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Totals Summary --}}
                <div class="row justify-content-end mb-5">
                    <div class="col-md-4">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th class="text-start">Subtotal</th>
                                <td class="text-end">{{ number_format($invoice->sub_total, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="text-start">Tax</th>
                                <td class="text-end">{{ number_format($invoice->tax_total, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="text-start">Discount</th>
                                <td class="text-end">{{ number_format($invoice->discount_total, 2) }}</td>
                            </tr>
                            <tr class="fw-bold table-light">
                                <th class="text-start">Grand Total</th>
                                <td class="text-end">{{ number_format($invoice->grant_total, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Footer / Actions --}}
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary me-2">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
                </div>

            </div>
        </div>
    </div>

@endsection