@extends('admin.layouts.app')
@section('title', 'Invoice Management')
@section('style')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .card,
            .card * {
                visibility: visible;
            }

            .card {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none !important;
            }

            .btn {
                display: none !important;
            }

            .print {
                display: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="page-title page-title-between">
                <h3>Credit Note Details
                </h3>
            </div>
        </div>
    </div>
    <div class="pt-4" id="printArea">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="col-lg-12">
                    <div class="main-table-container">
                        <div class="wo-preview-top">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-4 mb-3">
                                        <strong>Invoice Number:</strong><br>
                                        {{ $invoice->invoice_number ?? '-' }}
                                    </div>

                                    <div class="col-lg-4 mb-3">
                                        <strong>Credit Note Number:</strong><br>
                                        {{ $creditNote->credit_note_number }}
                                    </div>

                                    <div class="col-lg-4 mb-3">
                                        <strong>Credit Note Date:</strong><br>
                                        {{ $creditNote->date->format('d M Y') }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <strong>Customer Name:</strong><br>
                                        {{ $invoice->quotation->workPlan->company->company_name ?? '-' }}
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                        <strong>Customer Phone:</strong><br>
                                        {{ $invoice->quotation->workPlan->company->mobile_no ?? '-' }}
                                    </div>

                                    <div class="col-lg-4 mb-3">
                                        <strong>Planner:</strong><br>
                                        {{ $invoice->quotation->workPlan->company->planner->name ?? '-' }}
                                    </div>

                                    <div class="col-lg-4 mb-3">
                                        <strong>Production Staff:</strong><br>
                                        {{ $invoice->quotation->workPlan->company->productionStaff->name ?? '-' }}
                                    </div>

                                    <div class="col-lg-4 mb-3">
                                        <strong>Total Group:</strong><br>
                                        {{ $invoice->quotation->workPlan->company->totalGroup->customer_name ?? '-' }}
                                    </div>
                                </div>
                                <h5 class="mt-4">Invoice Items</h5>

                                <table class="table table-bordered mt-2">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Item</th>
                                            <th class="text-end">Unit Price</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Subtotal</th>
                                            <th class="text-end">SST %</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoice->items as $item)
                                            <tr>
                                                <td>{{ $item->item->item_name ?? '-' }}</td>
                                                <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end">{{ number_format($item->sum_amount, 2) }}</td>
                                                <td class="text-end">{{ $item->tax_percentage }}%</td>
                                                <td class="text-end">{{ number_format($item->total_amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="row justify-content-end mt-3">
                                    <div class="col-lg-4">
                                        <p><strong>Planner Commission:</strong> {{ $invoice->planner_commission }}</p>
                                        <p><strong>Bill to P %:</strong> {{ $invoice->p_bill_percentage }}%</p>
                                        <p><strong>Grand Total:</strong> MYR {{ number_format($invoice->grant_total, 2) }}
                                        </p>
                                        <p><strong>Credit Note Amount:</strong> MYR
                                            {{ number_format($creditNote->amount, 2) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <strong>Remarks:</strong>
                                    <div class="border p-2 mt-1">
                                        {!!$creditNote->remark !!}
                                    </div>
                                </div>

                                {{-- Footer / Actions --}}
                                <div class="preview-bottom-btns">
                                    <button onclick="printDiv('printArea')" class="add-btn">
                                        <i class="fa-solid fa-print"></i> Print
                                    </button>
                                    <a href="{{ route('admin.work-orders.show', $creditNote->invoice->quotation->workPlan->id) }}"
                                        class="btn-back-cs">
                                        Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function printDiv(divId) {
            const printContents = document.getElementById(divId).innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endsection