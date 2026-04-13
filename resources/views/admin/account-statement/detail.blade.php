@extends('admin.layouts.app')
@section('title', 'Work Order Report')
@section('style')
    <style>
        .table-borderless tbody tr.bg-success {
            background-color: rgba(25, 135, 84, 0.1) !important;
        }
    </style>
@endsection

@section('content')
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="page-title">
                <h3>Work Order Report</h3>
            </div>
        </div>
    </div>
    <div class="pt-1" id="printArea">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="main-table-container">
                    <div class="row ">
                        <div class="col-lg-3 mb-3">
                            <div class="card-top-wo bg-white-cs">
                                <i class="fa-solid fa-chart-bar"></i>
                                <span class="detail-label ">Work Order Number:</span>
                                <span class="detail-value">{{ $workOrder->workplan_number }}</span>
                            </div>

                        </div>
                        <div class="col-lg-3 mb-3">
                            <div class="card-top-wo bg-white-cs">
                                <i class="fa-solid fa-calendar"></i>
                                <span class="detail-label ">Date:</span>
                                <span class="detail-value">{{ $workOrder->date->format('d M Y') }}</span>
                            </div>

                        </div>
                        <div class="col-lg-3 mb-3">
                            <div class="card-top-wo bg-white-cs">
                                <i class="fa-solid fa-chart-diagram"></i>
                                <span class="detail-label ">Status:</span>
                                <span class="detail-value">{{ ucfirst($workOrder->status) }}</span>
                            </div>

                        </div>
                        <div class="col-lg-3 mb-3">
                            <div class="card-top-wo bg-white-cs">
                                <i class="fa-solid fa-chart-simple"></i>
                                <span class="detail-label ">WP Type:</span>
                                <span class="detail-value">{{ $workOrder->companyType->name ?? '-' }}</span>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-container">
                                {{-- Company / Customer Info --}}
                                <div class="row ">
                                    <div class="col-lg-6 mb-3">
                                        <div class="card-wo-info">
                                            <ul>
                                                <li>Customer Name :
                                                    <span>{{ $workOrder->company->company_name ?? '-' }}</span>
                                                </li>
                                                <li>Email : <span>{{ $workOrder->company->email_address ?? '-' }}</span>
                                                </li>
                                                <li>Phone : <span>{{ $workOrder->company->mobile_no ?? '-' }}</span></li>
                                            </ul>

                                        </div>
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                        <div class="card-wo-info">
                                            <ul>
                                                <li>Total Group:
                                                    <span>{{ $workOrder->totalGroup->customer_name ?? '-' }}</span>
                                                </li>
                                                <li>Planner: <span>{{ $workOrder->planner->name ?? '-' }}</span></li>
                                            </ul>

                                        </div>
                                    </div>
                                    {{-- Description --}}
                                    <div class="col-12">
                                        <div class="card-wo-info">
                                            <span class="detail-label">Description:</span>
                                            {!! $workOrder->description !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($workOrder->quotation)
                            <h3>Items</h3>
                            <table id="table" class="align-middle mb-0 table table-striped tble-cstm">
                                <thead>
                                    <tr>
                                        <th class="text-center">Item</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Unit Price</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Discount Percentage</th>
                                        <th class="text-center">SST</th>
                                        <th class="text-center">SST Amount</th>
                                        <th class="text-center">Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($workOrder->quotation->items as $item)
                                        <tr>
                                            <td class="text-center">{{ $item->item->item_name ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $item->quantity ?? '-' }}
                                            </td>
                                            <td class="text-center">{{ $item->unit_price ?? '-' }}
                                            </td>
                                            <td class="text-center">{{ $item->sum_amount ?? '-' }}
                                            </td>
                                            <td class="text-center">{{ $item->discount_amount ?? '-' }}%
                                            </td>
                                            @php
                                                $netAmount =
                                                    $item->discount_amount > 0
                                                    ? $item->sum_amount -
                                                    $item->sum_amount * ($item->discount_amount / 100)
                                                    : $item->sum_amount;

                                                $commission =
                                                    $netAmount *
                                                    ($item->item->planner_iv_percentage / 100) *
                                                    ($workOrder->planner->planner_c_percentage / 100);
                                            @endphp

                                            <td class="text-center">{{ $item->tax_percentage ?? '-' }}%
                                            </td>

                                            <td class="text-center">{{ $item->tax_amount ?? '-' }}
                                            </td>

                                            <td class="text-center">{{ $item->total_amount ?? '-' }}
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="preview-bottom-right mt-2">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th class="text-start">Amount</th>
                                        <td class="text-end">RM</td>
                                        <td class="text-end"> {{ number_format($workOrder->quotation->sub_total, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-start" style="font-weight:300">SST</th>
                                        <td class="text-end">RM</td>
                                        <td class="text-end"> {{ number_format($workOrder->quotation->tax_total, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-start">Discount</th>
                                        <td class="text-end">RM</td>
                                        <td class="text-end">
                                            {{ number_format($workOrder->quotation->discount_total, 2) }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <th class="text-start pdt">Total</th>
                                        <td class="text-end pdt">
                                            <div class="line-cs"></div>RM <div class="line-cs"></div>
                                        </td>
                                        <td class="text-end pdt">
                                            <div class="line-cs"></div>
                                            {{ number_format($workOrder->quotation->grant_total, 2) }}
                                            <div class="line-cs">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            @if (
                                        optional($workOrder->quotation)->invoice &&
                                        optional($workOrder->quotation->invoice)->payments &&
                                        optional($workOrder->quotation->invoice->payments)->count()
                                    )
                                    <table class="align-middle mb-0 table table-striped tble-cstm mt-3">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Receipt Number</th>
                                                <th class="text-center">Receipt Date</th>
                                                <th class="text-center">Amount</th>
                                                <th class="text-center">Remark</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($workOrder->quotation->invoice->payments as $payment)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $payment->custom_payment_id ?? '-' }}
                                                    </td>

                                                    <td class="text-center">
                                                        {{ optional($payment->created_at)->format('d M Y') ?? '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $payment->amount ?? '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {!! $payment->remark ?? '-' !!}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            @endif

                        @if (
                                optional($workOrder->quotation)->invoice &&
                                optional($workOrder->quotation->invoice)->creditNotes &&
                                optional($workOrder->quotation->invoice->creditNotes)->count()
                            )
                            <div class="col-lg-12" id="creditNoteSection">
                                <div class="dt-box-wo">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3>Credit Note</h3>
                                    </div>
                                    <div class="table-over">
                                        <table id="table" class="align-middle mb-0 table table-striped tble-cstm mt-3">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">SL NO</th>
                                                    <th class="text-center">Credit Note</th>
                                                    <th class="text-center">Date</th>
                                                    <th class="text-center ">Ref No</th>
                                                    <th class="text-center ">CR Amount</th>
                                                    <th class="text-center ">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($workOrder->quotation->invoice->creditNotes as $creditNote)
                                                    <tr>
                                                        <td class="text-center">
                                                            {{ $loop->iteration ?? '-' }}
                                                        </td>
                                                        <td class="text-center">{{ $creditNote->credit_note_number ?? '-' }}
                                                        </td>
                                                        <td class="text-center">{{ $creditNote->date->format('d M Y') }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $creditNote->invoice->invoice_number ?? '-' }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $creditNote->amount ?? '-' }}
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($creditNote->status == 'pending')
                                                                <span class="badge bg-warning">Pending</span>
                                                            @elseif($creditNote->status == 'approved')
                                                                <span class="badge bg-success">Approved</span>
                                                            @elseif($creditNote->status == 'rejected')
                                                                <span class="badge bg-danger">Rejected</span>
                                                            @else
                                                                <span class="badge bg-secondary">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                    <p>Quotation Not Generated</p>
                @endif
                <div class="mt-3">
                    <a href="{{ route('admin.account-statements.index') }}" class="btn-back-cs">Back</a>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection