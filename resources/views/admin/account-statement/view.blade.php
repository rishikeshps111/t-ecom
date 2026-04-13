@extends('admin.layouts.app')
@section('title', 'Planner Commission Report')
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
                <h3>Planner Commission Report</h3>
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
                                <table id="table" class="align-middle mb-0 table table-striped tble-cstm">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Item</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Unit Price</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Discount Percentage</th>
                                            <th class="text-center">Total After Discount</th>
                                            <th class="text-center">Commission</th>
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
                                                <td class="text-center">
                                                    {{ number_format($netAmount, 2) }}
                                                </td>

                                                <td class="text-center">
                                                    {{ number_format($netAmount, 2) }}
                                                    x {{ $item->item->planner_iv_percentage }}%
                                                    x {{ $workOrder->planner->planner_c_percentage }}%
                                                    =
                                                    <strong>{{ number_format($commission, 2) }}</strong>
                                                </td>

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
                                    <table class="table table-sm">
                                        <tbody>
                                            <tr class="bg-success bg-opacity-10">
                                                <th class="text-start bg-success bg-opacity-10">Total Commission</th>
                                                <td class="text-end bg-success bg-opacity-10">RM</td>
                                                <td class="text-end bg-success bg-opacity-10">
                                                    {{ $workOrder->quotation->planner_commission ?? '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-start">Total Amount</th>
                                                <td class="text-end">RM</td>
                                                <td class="text-end"> {{ $workOrder->quotation->grant_total ?? '-' }}
                                                </td>
                                            </tr>
                                            <tr class="bg-success bg-opacity-10">
                                                <th class="text-star bg-success bg-opacity-10">Planner Commission %</th>
                                                <td class="text-end bg-success bg-opacity-10"></td>
                                                <td class="text-end bg-success bg-opacity-10">
                                                    {{ isset($workOrder->quotation->p_bill_percentage)
                        ? number_format($workOrder->quotation->p_bill_percentage, 2)
                        : '-' }}%
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
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