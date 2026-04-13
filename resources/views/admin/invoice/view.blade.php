@extends('admin.layouts.app')
@section('title', 'Invoice Management')
@section('style')
    <style>
        @media print {
            .main-table-container {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
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

            .preview-bottom-btns {
                display: none !important;
            }

            .print {
                display: none !important;
            }

            .w-60-print {
                width: 60% !important;
            }

            .w-40-print {
                width: 40% !important;
            }

            .print-width-100 .col-lg-7 {
                width: 60%;
            }

            .print-width-100 .col-lg-4 {
                width: 30%;
            }

            .preview-container-center-bottom table.mb-5 {
                margin-bottom: 20px !important;
            }

            .line-hr {
                height: 1px !important;
            }
        }
    </style>



@endsection

@section('content')
    <div class="pt-4" id="printArea">
        <div class="row">
            <div class="col-lg-10 mb-4">
                <div class="main-table-container">
                    @php
                        $biller = $invoice->quotation->workPlan?->totalGroup?->billerProfile;
                    @endphp

                    <div class="wo-preview-top text-center company-top-logo">
                        @if($biller && $biller->invoice_header)
                            <img src="{{ asset('storage/' . $biller->invoice_header) }}" alt="Company Logo"
                                style="max-height: 80px; width: auto;">
                        @else
                            <img src="{{ asset('assets/images/default-logo.png') }}" alt="Company Logo"
                                style="max-height: 80px; width: auto;">
                        @endif

                        {!! $biller->address ?? 'Not Available' !!}
                    </div>
                    <div class="line-hr"></div>
                    <div class="preview-container-center">
                        <h3>Invoice</h3>
                        <div class="row">
                            <div class="col-8 w-60-print">

                                <div class="preview-container-center-left">

                                    {{-- Header --}}
                                    {{-- Company Logo --}}
                                    <div>
                                        <h4>
                                            {{ $invoice->quotation->workPlan->company->company_name ?? '' }}
                                        </h4>
                                        <p class="mb-1">
                                            {!! $invoice->quotation->workPlan->company->address ?? '' !!}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 w-40-print">
                                <div class="preview-container-center-right">
                                    <table class="table">
                                        <tr>
                                            <th>Invoice NO</th>
                                            <td>: {{ $invoice->invoice_number ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date</th>
                                            <td>: {{ $invoice->quotation->quotation_date->format('d M Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Planner</th>
                                            <td>:
                                                {{ $invoice->quotation->workPlan->company->planner->name ?? 'Not Available' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Production</th>
                                            <td>:
                                                {{ $invoice->quotation->workPlan->company->productionStaff->name ?? 'Not Available' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>QT NO</th>
                                            <td>: {{ $invoice->quotation->quotation_number ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="preview-container-center-bottom">
                                    {{-- Customer & Meta Info --}}
                                    <div class="row ">
                                    </div>
                                    {{-- Items Table --}}
                                    <div class="table-responsive mb-5">
                                        <table class="table align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>NO</th>
                                                    <th class="min-width-200">Description</th>
                                                    {{-- <th>UMO</th> --}}
                                                    <th>Qty</th>
                                                    <th>U Price</th>
                                                    {{-- <th>Discount %</th> --}}
                                                    <th>SST %</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($invoice->items as $i => $item)
                                                    <tr>
                                                        <td>{{ $i + 1 }}</td>
                                                        <td class="text-center">{{ $item->description ?? '-' }}</td>
                                                        {{-- <td>{{ $item->umo }}</td> --}}
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ number_format($item->unit_price, 2) }}</td>
                                                        {{-- <td>{{ number_format($item->discount_amount, 2) }}</td> --}}
                                                        <td>{{ $item->tax_percentage }}%</td>
                                                        <td class="fw-bold">{{ number_format($item->total_amount, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <div class="line-hr" style="margin-bottom:15px"></div>
                            </div>
                            <div class="col-lg-12">
                                <div class="preview-bottom">
                                    <div class="row justify-content-between print-width-100">
                                        {{-- Totals Summary --}}
                                        <div class="col-lg-7">
                                            <div class="preview-bottom-left">
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        @php
                                                            $formatter = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
                                                            $subTotalInWords = $formatter->format((float) $invoice->grant_total);
                                                        @endphp
                                                        <td class="text-start">
                                                            RINGGIT MALAYSIA {{ ucfirst($subTotalInWords) }} Only
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">
                                                            @if($biller && $biller->invoice_payment_terms)
                                                                {!! $biller->invoice_payment_terms !!}
                                                            @else
                                                                Not Available
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start" style=" font-style:italic;">This is computer
                                                            generated printout, No signature required</td>
                                                    </tr>
                                                </table>

                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="preview-bottom-right">

                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <th class="text-start">Amount</th>
                                                        <td class="text-end">RM</td>
                                                        <td class="text-end"> {{ number_format($invoice->sub_total, 2) }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-start" style="font-weight:300">SST</th>
                                                        <td class="text-end">RM</td>
                                                        <td class="text-end"> {{ number_format($invoice->tax_total, 2) }}
                                                        </td>
                                                    </tr>
                                                    <!--<tr>-->
                                                    <!--    <th class="text-start">Discount</th>-->
                                                    <!--    <td class="text-end">-->
                                                    <!--        {{ number_format($invoice->discount_total, 2) }}-->
                                                    <!--    </td>-->
                                                    <!--</tr>-->
                                                    <tr class="">
                                                        <th class="text-start pdt">Total</th>
                                                        <td class="text-end pdt">
                                                            <div class="line-cs"></div>RM <div class="line-cs"></div>
                                                        </td>
                                                        <td class="text-end pdt">
                                                            <div class="line-cs"></div>
                                                            {{ number_format($invoice->grant_total, 2) }}
                                                            <div class="line-cs"></div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>




                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-12">
                    {{-- Footer / Actions --}}
                    <div class="preview-bottom-btns">
                        <button onclick="printDiv('printArea')" class="add-btn">
                            <i class="fa-solid fa-print"></i> Print
                        </button>
                        <a href="{{ route('admin.invoices.pdf', $invoice->id) }}" class="submit-btn">
                            <i class="fa-solid fa-file-pdf"></i> Download PDF
                        </a>
                        @if ($workPlan)
                            <a href="{{ route('admin.work-orders.show', $workPlan->id) }}" class="btn-back-cs">
                                Back
                            </a>
                        @else
                            <a href="{{ route('admin.invoices.index') }}" class="btn-back-cs">
                                Back
                            </a>
                        @endif
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