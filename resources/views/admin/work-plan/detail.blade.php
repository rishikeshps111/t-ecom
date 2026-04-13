@extends('admin.layouts.app')
@section('title')
    Work Order Details
@endsection

@section('style')
    @include('admin.scripts.css')
    <style>
        /* Border around each detail row */
        .detail-row {
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #dee2e6;
            /* light gray border */
            border-radius: 5px;
            background-color: #f9f9f9;
            /* optional: subtle bg */
        }

        .detail-label {
            font-weight: 600;
        }

        .detail-value {
            word-wrap: break-word;
        }

        .attachment-preview button {
            display: inline-block;
            margin-right: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="page-title">
                <h3>Work Order Details</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="main-table-container">

                {{-- Top Row: Work Plan Number, Date, Status, WP Type --}}
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
                                            <li>Customer Name : <span>{{ $workOrder->company->company_name ?? '-' }}</span>
                                            </li>
                                            <li>Email : <span>{{ $workOrder->company->email_address ?? '-' }}</span></li>
                                            <li>Phone : <span>{{ $workOrder->company->mobile_no ?? '-' }}</span></li>
                                        </ul>

                                    </div>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <div class="card-wo-info">
                                        <ul>
                                            <li>Total Group: <span>{{ $workOrder->totalGroup->customer_name ?? '-' }}</span>
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
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-container">
                            <h3>Attachments</h3>
                            {{-- Attachments --}}
                            @if ($workOrder->attachments->count())
                                <div class="row g-3">
                                    @foreach ($workOrder->attachments as $attachment)
                                        @php
                                            $fileName = basename($attachment->file);
                                            $fileUrl = asset('storage/' . $attachment->file);
                                            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                                        @endphp

                                        <div class="col-md-4">
                                            <div class="card h-100 text-center">
                                                {{-- File preview --}}
                                                @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']))
                                                    <img src="{{ $fileUrl }}" class="card-img-top"
                                                        style="height:150px; object-fit:cover; border-radius:6px;">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center" style="height:150px;">
                                                        <i class="bi bi-file-earmark-text fs-1"></i>
                                                    </div>
                                                @endif

                                                {{-- File name --}}
                                                <div class="card-body p-2 mb-2">
                                                    <div class="small text-truncate" title="{{ $fileName }}">
                                                        {{ $fileName }}
                                                    </div>
                                                </div>

                                                {{-- Actions --}}
                                                <div class="card-footer d-flex justify-content-center gap-2 p-2">
                                                    <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ $fileUrl }}" download class="btn btn-sm btn-success">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="mb-0"><em>No attachments uploaded.</em></p>
                            @endif

                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-container">
                            <h3>Quotation</h3>
                            {{-- Work Plan Card --}}
                            <div class=" dt-wo-container">
                                <h5>
                                    {{ $workOrder->workplan_number ?? '-' }}
                                </h5>

                                <div class="vw-wo-dt">
                                    <p class="mb-1"><strong>Type:</strong>
                                        {{ $workOrder->company->companyType->name ?? '-' }}</p>
                                    <p class="mb-1"><strong>Date:</strong>
                                        {{ \Carbon\Carbon::parse($workOrder->quotation->quotation_date)->format('d M Y') ?? '-' }}
                                    </p>
                                    <p class="mb-1"><strong>Quotation No:</strong>
                                        {{ $workOrder->quotation->quotation_number ?? '-' }}</p>
                                    <p class="mb-1"><strong>Planner:</strong> {{ $workOrder->planner->name ?? '-' }}</p>

                                    <p class="mb-2">
                                        <strong>Status:</strong>
                                        @if ($workOrder->quotation->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($workOrder->quotation->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($workOrder->quotation->status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </p>
                                </div>
                                <h5>
                                    Quotation Items
                                </h5>
                                <div class="table-responsive mb-5">
                                    <table class="table align-middle ">
                                        <thead class="table-light">
                                            <tr>
                                                <th>NO</th>
                                                <th class="text-start">Description</th>
                                                {{-- <th>UMO</th> --}}
                                                <th>Qty</th>
                                                <th>U Price</th>
                                                <th>Discount %</th>
                                                <th>SST %</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($workOrder->quotation->items as $i => $item)
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td class="text-start">{{ $item->item->item_name ?? '-' }}</td>
                                                    {{-- <td>{{ $item->umo }}</td> --}}
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>{{ number_format($item->unit_price, 2) }}</td>
                                                    <td>{{ number_format($item->discount_amount, 2) }}</td>
                                                    <td>{{ $item->tax_percentage }}%</td>
                                                    <td class="fw-bold">{{ number_format($item->total_amount, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="preview-bottom-right">

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
                            </div>
                            {{-- End Work Plan Card --}}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-container">
                            <h3>Invoice</h3>
                            {{-- Work Plan Card --}}
                            <div class=" dt-wo-container">
                                <h5>
                                    {{ $workOrder->quotation->invoice->invoice_number ?? '-' }}
                                </h5>

                                <div class="vw-wo-dt">
                                    <p class="mb-1"><strong>Type:</strong>
                                        {{ $workOrder->company->companyType->name ?? '-' }}</p>
                                    <p class="mb-1"><strong>Date:</strong>
                                        {{ \Carbon\Carbon::parse($workOrder->quotation->invoice->invoice_date)->format('d M Y') ?? '-' }}
                                    </p>
                                    <p class="mb-2">
                                        <strong>Status:</strong>
                                        @if ($workOrder->quotation->invoice->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($workOrder->quotation->invoice->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($workOrder->quotation->invoice->status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </p>
                                </div>
                                <h5>
                                    Quotation Items
                                </h5>
                                <div class="table-responsive mb-5">
                                    <table class="table align-middle ">
                                        <thead class="table-light">
                                            <tr>
                                                <th>NO</th>
                                                <th class="text-start">Description</th>
                                                {{-- <th>UMO</th> --}}
                                                <th>Qty</th>
                                                <th>U Price</th>
                                                <th>Discount %</th>
                                                <th>SST %</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($workOrder->quotation->invoice->items as $i => $item)
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td class="text-start">{{ $item->item->item_name ?? '-' }}</td>
                                                    {{-- <td>{{ $item->umo }}</td> --}}
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>{{ number_format($item->unit_price, 2) }}</td>
                                                    <td>{{ number_format($item->discount_amount, 2) }}</td>
                                                    <td>{{ $item->tax_percentage }}%</td>
                                                    <td class="fw-bold">{{ number_format($item->total_amount, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="preview-bottom-right">

                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th class="text-start">Amount</th>
                                            <td class="text-end">RM</td>
                                            <td class="text-end">
                                                {{ number_format($workOrder->quotation->invoice->sub_total, 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-start" style="font-weight:300">SST</th>
                                            <td class="text-end">RM</td>
                                            <td class="text-end">
                                                {{ number_format($workOrder->quotation->invoice->tax_total, 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-start">Discount</th>
                                            <td class="text-end">
                                                {{ number_format($workOrder->quotation->invoice->discount_total, 2) }}
                                            </td>
                                        </tr>
                                        <tr class="">
                                            <th class="text-start pdt">Total</th>
                                            <td class="text-end pdt">
                                                <div class="line-cs"></div>RM <div class="line-cs"></div>
                                            </td>
                                            <td class="text-end pdt">
                                                <div class="line-cs"></div>
                                                {{ number_format($workOrder->quotation->invoice->grant_total, 2) }}
                                                <div class="line-cs">
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            {{-- End Work Plan Card --}}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-container">
                            <h3>Payments</h3>
                            {{-- Work Plan Card --}}
                            <div class=" dt-wo-container">

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
                            {{-- End Work Plan Card --}}
                        </div>
                    </div>
                </div>

                {{-- Back Button --}}
                <div class="mt-3">
                    <a href="{{ route('admin.work-orders.index') }}" class="btn-back-cs">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('admin.scripts.script')
@endsection