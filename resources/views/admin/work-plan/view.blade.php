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

        .dropdown-toggle::after {
            display: none !important;
        }

        button.btn.btn-sm.btn-light.dropdown-toggle {
            background: transparent;
            border: 0;
        }

        .table-over {
            overflow: unset !important;
        }

        .btns-preview-attachments {
            justify-content: flex-end !important;
        }
    </style>
@endsection

@section('content')
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="page-title page-title-between">
                <h3>Work Order Details
                    <a href="{{ route('admin.work-orders.index') }}" class="btn-back-cs">Back</a>
                </h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="main-table-container">
                <div class="accordion accordion-wo-cs" id="workPlanAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingWorkPlan">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseWorkPlan" aria-expanded="false" aria-controls="collapseWorkPlan">
                                Work Order Details
                            </button>
                        </h2>

                        <div id="collapseWorkPlan" class="accordion-collapse collapse" aria-labelledby="headingWorkPlan"
                            data-bs-parent="#workPlanAccordion">
                            <div class="accordion-body">
                                {{-- Top Row: Work Plan Number, Date, Status --}}
                                <div class="row">
                                    <div class="col-lg-3 mb-3">
                                        <div class="card-top-wo">
                                            <i class="fa-solid fa-chart-bar"></i>
                                            <span class="detail-label">Work Order Number:</span>
                                            <span class="detail-value">{{ $workOrder->workplan_number }}</span>
                                        </div>

                                    </div>
                                    <div class="col-lg-3 mb-3">
                                        <div class="card-top-wo">
                                            <i class="fa-solid fa-calendar"></i>
                                            <span class="detail-label">Date:</span>
                                            <span class="detail-value">{{ $workOrder->date->format('d M Y') }}</span>
                                        </div>

                                    </div>
                                    <div class="col-lg-3 mb-3">
                                        <div class="card-top-wo">
                                            <i class="fa-solid fa-chart-diagram"></i>
                                            <span class="detail-label">Status:</span>
                                            <span class="detail-value">{{ ucfirst($workOrder->status) }}</span>
                                        </div>

                                    </div>
                                    <div class="col-lg-3 mb-3">
                                        <div class="card-top-wo">
                                            <i class="fa-solid fa-chart-simple"></i>
                                            <span class="detail-label">WP Type:</span>
                                            <span class="detail-value">{{ $workOrder->companyType->name ?? '-' }}</span>
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
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
                                                <li>Total Group :
                                                    <span>{{ $workOrder->totalGroup->customer_name ?? '-' }}</span>
                                                </li>
                                                <li>Planner : <span>{{ $workOrder->planner->name ?? '-' }}</span></li>
                                                <li>Production Staff :
                                                    <span>{{ $workOrder->productionStaff->name ?? '-' }}</span>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="row ">
                                    <div class="col-lg-12">
                                        <div class="card-wo-info">
                                            <span class="detail-label">Description:</span>
                                            {!! $workOrder->description !!}

                                        </div>

                                    </div>
                                </div>

                                {{-- Attachment --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-lg-12">
                        <div class="attachment-preview btns-preview-attachments">
                            <button type="button" class="submit-btn d-none" data-bs-toggle="modal"
                                data-bs-target="#attachmentsModal">
                                View Attachments
                            </button>
                            @if ($workOrder->quotation)
                            @else
                                @can('qt.edit')
                                                    <a href="{{ route_with_query('admin.quotations.create', [
                                        'work_plan' => $workOrder->id,
                                    ]) }}" class="add-btn">
                                                        Create Quotation
                                                    </a>
                                @endcan
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-lg-12">
                        <div class="work-plan-btns">
                            @php
                                $docCount = $workOrder->attachments->count() ?? 0;
                                $hasDocs = $docCount > 0;
                            @endphp
                            @can('document.view')
                                <a href="#documentSection" class="btn btn-warning me-2 {{ $hasDocs ? '' : 'disabled' }}" {{ $hasDocs ? '' : 'aria-disabled=true' }}>DOC <span class="badge ms-1">
                                        {{ $docCount }}
                                    </span></a>
                            @endcan
                            @php
                                $mesCount = $workOrder->company->messages()->count() ?? 0;
                                $hasMes = $mesCount > 0;
                            @endphp
                            @can('message.view')
                                <a href="#messageSection" class="btn btn-success me-2">Messages <span class="badge ms-1">
                                        {{ $mesCount }}
                                    </span></a>
                            @endcan
                            @can('notes.view')
                                <a href="#noteSection" class="btn btn-danger me-2">Notes</a>
                            @endcan
                            @if ($workOrder->quotation)
                                @if ($workOrder->quotation->status == 'approved')
                                    <!-- Approved status -->
                                    @php
                                        $paymentCount = $workOrder->quotation?->invoice?->payments?->count() ?? 0;
                                        $hasPayments = $paymentCount > 0;
                                    @endphp

                                    @can('or.view')
                                        <a href="#receiptSection" class="btn btn-success me-2 {{ $hasPayments ? '' : 'disabled' }}" {{ $hasPayments ? '' : 'aria-disabled=true' }}>
                                            OR <span class="badge ms-1">
                                                {{ $paymentCount }}
                                            </span>
                                        </a>
                                    @endcan

                                    @php
                                        $crCount = $workOrder->quotation?->invoice?->creditNotes?->count() ?? 0;
                                        $hasCR = $crCount > 0;
                                    @endphp
                                    @can('cn.view')
                                        <a href="#creditNoteSection" class="btn btn-danger me-2 {{ $hasCR ? '' : 'disabled' }}" {{ $hasCR ? '' : 'aria-disabled=true' }}>CR <span class="badge ms-1">
                                                {{-- {{ $crCount }} --}}
                                            </span></a>
                                    @endcan
                                @endif
                            @endif
                        </div>
                        @if ($workOrder->quotation)
                            @role('Super Admin')
                            <div class="mt-4 plan-commision-2-cs plan-commision-3-cs">
                                <ul>
                                    <li class="bg1">Planner Commission : <span>RM
                                            {{ $workOrder->quotation->planner_commission ?? '-' }}</span></li>
                                    <li class="bg2">Bill to P% : <span>
                                            {{ $workOrder->quotation->p_bill_percentage ?? '-' }}%</span></li>
                                    @if ($workOrder->quotation)
                                        <li class="bg1">
                                            Production Commission :
                                            <span>
                                                RM
                                                {{ ($workOrder->quotation->grant_total * $workOrder->productionStaff->production_c_percentage) / 100 }}
                                            </span>
                                        </li>
                                    @endif
                                    <li class="bg2">Production Commission % : <span>
                                            {{ $workOrder->productionStaff->production_c_percentage ?? '-' }}%</span>
                                    </li>
                                </ul>
                            </div>
                            @if ($workOrder->quotation)
                                        <div class="mt-4 btn-center">
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#plannerModal">Planner
                                                Com%</button>
                                            <button class="btn btn-primary" {{ !($workOrder?->quotation?->invoice && $workOrder->quotation->invoice->plannerPayouts()->exists())
                                ? 'disabled'
                                : '' }}
                                                data-bs-toggle="modal" data-bs-target="#plannerPayoutModal">
                                                Planner Paid Out
                                            </button>
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productionModal" {{ !($workOrder?->quotation?->invoice && $workOrder->quotation->invoice->productionStaffPayouts()->exists())
                                ? 'disabled'
                                : '' }}>Production
                                                Com%</button>
                                            <button class="btn btn-primary" {{ !($workOrder?->quotation?->invoice && $workOrder->quotation->invoice->productionStaffPayouts()->exists())
                                ? 'disabled'
                                : '' }}
                                                data-bs-toggle="modal" data-bs-target="#productionPayoutModal">
                                                Production Paid Out
                                            </button>
                                        </div>
                            @endif
                            @endrole
                        @endif
                    </div>
                    @if ($workOrder->quotation)
                        <div class="col-lg-12">
                            <div class="figures-list figures-list-cs">
                                <ul>
                                    <li class="bg1">Quotation Amount : <span>MYR
                                            {{ $workOrder->quotation->grant_total ?? '-' }}</span></li>
                                    <li class="bg2">Invoice Amount : <span>MYR
                                            {{ $workOrder->quotation->invoice->grant_total ?? '-' }}</span></li>
                                    <li class="bg3">Receipt Amount : <span>MYR
                                            {{ $workOrder->quotation->invoice->paid_amount ?? '-' }}</span></li>
                                    <li class="bg5">Credit Note Amount : <span>MYR
                                            {{ $workOrder?->quotation?->invoice?->creditNotes->sum('amount') }}</span>
                                    </li>
                                    @php
                                        $invoice = $workOrder->quotation->invoice ?? null;
                                        $creditTotal = $invoice?->creditNotes?->sum('amount') ?? 0;
                                        $balance = $invoice?->balance_amount ?? 0;
                                    @endphp

                                    <li class="bg4">
                                        Balance :
                                        <span>MYR
                                            {{ $balance != 0 ? $balance - $creditTotal : 0 }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
                @if ($workOrder->quotation)
                    @can('qt.view')
                        <div class="col-lg-12">
                            <div class="dt-box-wo">
                                <h3>Quotation</h3>
                                <div class="table-over">
                                    <table id="table" class="align-middle mb-0 table table-striped tble-cstm mt-3">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Date</th>
                                                <th class="text-center">Quotation No</th>
                                                <th class="text-center">Work Order</th>
                                                <th class="text-center">Planner</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">{{ $workOrder->company->companyType->name ?? '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($workOrder->quotation->quotation_date)->format('d M Y') ?? '-' }}
                                                </td>
                                                <td class="text-center">{{ $workOrder->quotation->quotation_number ?? '-' }}
                                                </td>
                                                <td class="text-center">{{ $workOrder->workplan_number ?? '-' }}</td>
                                                <td class="text-center">{{ $workOrder->planner->name ?? '-' }}</td>
                                                <td class="text-center">
                                                    @if ($workOrder->quotation->status == 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($workOrder->quotation->status == 'approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @elseif($workOrder->quotation->status == 'rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @else
                                                        <span class="badge bg-secondary">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="dropdown action-dropdown">
                                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                                        </button>

                                                        <ul class="dropdown-menu dropdown-menu-end">

                                                            {{-- Preview --}}
                                                            @can('qt.view')
                                                                                                    <li>
                                                                                                        <a class="dropdown-item" href="{{ route_with_query('admin.quotations.show', [
                                                                    'quotation' => $workOrder->quotation->id,
                                                                    'work_plan' => $workOrder->id,
                                                                ]) }}">
                                                                                                            <i class="fa-solid fa-eye me-2"></i>
                                                                                                            Preview
                                                                                                        </a>
                                                                                                    </li>
                                                            @endcan

                                                            {{-- Edit --}}
                                                            @can('qt.edit')
                                                                @if ($workOrder->quotation->status == 'pending')
                                                                                                    <li>
                                                                                                        <a class="dropdown-item" href="{{ route_with_query('admin.quotations.edit', [
                                                                        'quotation' => $workOrder->quotation->id,
                                                                        'work_plan' => $workOrder->id,
                                                                    ]) }}">
                                                                                                            <i class="fa-solid fa-pen-to-square me-2"></i>
                                                                                                            Edit
                                                                                                        </a>
                                                                                                    </li>
                                                                @endif
                                                            @endcan



                                                            {{-- Change Status --}}
                                                            @if ($workOrder->quotation->status == 'pending')
                                                                @can('qt.edit')
                                                                    <li>
                                                                        <button class="dropdown-item change-status"
                                                                            data-id="{{ $workOrder->quotation->id }}">
                                                                            <i class="fa-solid fa-arrow-right me-2 text-primary"></i>
                                                                            Change Status
                                                                        </button>
                                                                    </li>
                                                                @endcan
                                                            @endif
                                                            @can('inv.edit')
                                                                @if ($workOrder->quotation->status == 'approved' && !$workOrder->quotation->invoice)
                                                                                                    <li>
                                                                                                        <a class="dropdown-item" href="{{ route_with_query('admin.invoices.create', [
                                                                        'work_plan' => $workOrder->id,
                                                                    ]) }}">
                                                                                                            <i class="fa-solid fa-file-invoice me-2 text-secondary"></i>
                                                                                                            Generate Invoice
                                                                                                        </a>
                                                                                                    </li>
                                                                @endif
                                                            @endcan
                                                        </ul>
                                                    </div>

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endcan
                @endif

                @if (optional($workOrder->quotation)->invoice)
                    @can('inv.view')
                        <div class="col-lg-12">
                            <div class="dt-box-wo">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3>Invoice</h3>
                                    @can('cn.edit')
                                        @if ($workOrder->quotation->invoice->payment_status != 'paid')
                                            <a href="{{ route('admin.credit-notes.create') }}?invoice={{ $workOrder->quotation->invoice->id }}"
                                                class="btn btn-success">Credit Note</a>
                                        @endif
                                    @endcan
                                </div>
                                <div class="table-over">
                                    <table id="table" class="align-middle mb-0 table table-striped tble-cstm mt-3">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Invoice No</th>
                                                <th class="text-center">Company</th>
                                                <th class="text-center">Type</th>
                                                <th class="text-center ">Invoice Date</th>
                                                <th class="text-center ">Status</th>
                                                <th class="text-center ">Payment Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">
                                                    {{ $workOrder->quotation->invoice->invoice_number ?? '-' }}
                                                </td>
                                                <td class="text-center">{{ $workOrder->company->company_name ?? '-' }}
                                                </td>
                                                <td class="text-center">{{ $workOrder->company->companyType->name ?? '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($workOrder->quotation->quotation_date)->format('d M Y') ?? '-' }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($workOrder->quotation->invoice->status == 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($workOrder->quotation->invoice->status == 'approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @elseif($workOrder->quotation->invoice->status == 'rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @else
                                                        <span class="badge bg-secondary">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($workOrder->quotation->invoice->payment_status == 'unpaid')
                                                        <span class="badge bg-danger">Unpaid</span>
                                                    @elseif($workOrder->quotation->invoice->payment_status == 'paid')
                                                        <span class="badge bg-success">Paid</span>
                                                    @elseif($workOrder->quotation->invoice->payment_status == 'partial')
                                                        <span class="badge bg-warning">Partial</span>
                                                    @else
                                                        <span class="badge bg-secondary">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="dropdown action-dropdown">
                                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                                        </button>

                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            @can('inv.view')
                                                                                                    <li>
                                                                                                        <a class="dropdown-item" href="{{ route_with_query('admin.invoices.show', [
                                                                    'invoice' => $workOrder->quotation->invoice->id,
                                                                    'work_plan' => $workOrder->id,
                                                                ]) }}">
                                                                                                            <i class="fa-solid fa-eye me-2"></i>
                                                                                                            Preview
                                                                                                        </a>
                                                                                                    </li>
                                                            @endcan
                                                            @can('inv.edit')
                                                                @if ($workOrder->quotation->invoice->status == 'pending')
                                                                                                    <li>
                                                                                                        <a class="dropdown-item" href="{{ route_with_query('admin.invoices.edit', [
                                                                        'invoice' => $workOrder->quotation->invoice->id,
                                                                        'work_plan' => $workOrder->id,
                                                                    ]) }}">
                                                                                                            <i class="fa-solid fa-pen-to-square me-2"></i>
                                                                                                            Edit
                                                                                                        </a>
                                                                                                    </li>
                                                                @endif
                                                            @endcan
                                                            @can('inv.edit')
                                                                @if ($workOrder->quotation->invoice->status == 'pending')
                                                                    <li>
                                                                        <button class="dropdown-item change-status-invoice"
                                                                            data-id="{{ $workOrder->quotation->invoice->id }}">
                                                                            <i class="fa-solid fa-arrow-right me-2 text-primary"></i>
                                                                            Change Status
                                                                        </button>
                                                                    </li>
                                                                @endif
                                                            @endcan
                                                            @can('or.edit')
                                                                @if ($workOrder->quotation->invoice->status == 'approved')
                                                                    @if ($workOrder->quotation->invoice->payment_status != 'paid')
                                                                                                    <li>
                                                                                                        <a class="dropdown-item" href="{{ route_with_query('admin.receipts.create', [
                                                                            'inv_id' => $workOrder->quotation->invoice->id,
                                                                            'work_plan' => $workOrder->id,
                                                                        ]) }}">
                                                                                                            <i class="fa-solid fa-receipt me-2 text-purple"></i>
                                                                                                            Generate Receipt
                                                                                                        </a>
                                                                                                    </li>
                                                                    @endif
                                                                @endif
                                                            @endcan
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endcan
                @endif

                @if (
                        optional($workOrder->quotation)->invoice &&
                        optional($workOrder->quotation->invoice)->creditNotes &&
                        optional($workOrder->quotation->invoice->creditNotes)->count()
                    )
                    @can('cn.view')
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
                                                <th class="text-center">Action</th>
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
                                                    <td class="text-center">
                                                        <div class="dropdown action-dropdown">
                                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li>
                                                                    @can('cn.view')
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('admin.credit-notes.show', $creditNote->id) }}">
                                                                            <i class="fa-solid fa-eye me-2"></i>
                                                                            View
                                                                        </a>
                                                                    @endcan
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endcan
                @endif

                @if (
                        optional($workOrder->quotation)->invoice &&
                        optional($workOrder->quotation->invoice)->payments &&
                        optional($workOrder->quotation->invoice->payments)->count()
                    )
                    @can('or.view')
                        <div class="col-lg-12" id="receiptSection">
                            <div class="dt-box-wo">
                                <h3>Receipts</h3>
                                <div class="table-over">
                                    <table class="align-middle mb-0 table table-striped tble-cstm mt-3">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Receipt Number</th>
                                                <th class="text-center">Receipt Date</th>
                                                <th class="text-center">Amount</th>
                                                <th class="text-center">Remark</th>
                                                <th class="text-center">Action</th>
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

                                                    <td class="text-center">
                                                        <div class="dropdown action-dropdown">
                                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                                            </button>

                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                @can('or.view')
                                                                    <li>
                                                                        <a class="dropdown-item" target="_blank"
                                                                            href="{{ route('admin.receipts.show', $payment->id) }}">
                                                                            <i class="fa-solid fa-eye me-2 text-primary"></i>
                                                                            View
                                                                        </a>
                                                                    </li>
                                                                @endcan
                                                                @can('or.view')
                                                                    <li>
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('admin.receipts.pdf', $payment->id) }}">
                                                                            <i class="fa-solid fa-file-pdf me-2 text-warning"></i>
                                                                            Generate PDF
                                                                        </a>
                                                                    </li>
                                                                @endcan
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    @endcan
                @endif
                @can('document.view')
                    <div class="col-lg-12" id="documentSection">
                        <div class="dt-box-wo">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3>Documents</h3>
                                @can('document.edit')
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#addDocumentModal">
                                        Add Documents
                                    </button>
                                @endcan
                            </div>
                            <div class="table-over">
                                <table class="align-middle mb-0 table table-striped tble-cstm mt-3">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Sl No</th>
                                            <th class="text-center">Entity</th>
                                            <th class="text-center">Document Type</th>
                                            <th class="text-center">Year</th>
                                            <th class="text-center">Document</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($workOrder->attachments as $attachment)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $loop->iteration }}
                                                </td>

                                                <td class="text-center">
                                                    {{ $attachment->entity ?? '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ ucfirst($attachment->type) }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $attachment->year ?? '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ ucfirst(str_replace(['_', '-'], ' ', pathinfo($attachment->name, PATHINFO_FILENAME))) }}
                                                </td>

                                                <td class="text-center">
                                                    <div class="dropdown action-dropdown">
                                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                @php
                                                                    $fileName = basename($attachment->file);
                                                                    $fileUrl = asset('storage/' . $attachment->file);
                                                                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                                                                @endphp
                                                                @if ($attachment->entity == 'WO')
                                                                    <a class="dropdown-item" target="_blank" href="{{ $fileUrl }}">
                                                                        <i class="fa-solid fa-eye me-2 text-primary"></i>
                                                                        View
                                                                    </a>
                                                                @endif
                                                                @if ($attachment->entity == 'QO')
                                                                                                            <a class="dropdown-item" target="_blank" href="{{ route_with_query('admin.quotations.show', [
                                                                        'quotation' => $workOrder->quotation->id,
                                                                        'work_plan' => $workOrder->id,
                                                                    ]) }}">
                                                                                                                <i class="fa-solid fa-eye me-2 text-primary"></i>
                                                                                                                View
                                                                                                            </a>
                                                                @endif
                                                                @if ($attachment->entity == 'IN')
                                                                                                            <a class="dropdown-item" target="_blank" href="{{ route_with_query('admin.invoices.show', [
                                                                        'invoice' => $workOrder->quotation->invoice->id,
                                                                        'work_plan' => $workOrder->id,
                                                                    ]) }}">
                                                                                                                <i class="fa-solid fa-eye me-2 text-primary"></i>
                                                                                                                View
                                                                                                            </a>
                                                                @endif
                                                                @if ($attachment->entity == 'OR')
                                                                    <a class="dropdown-item" target="_blank"
                                                                        href="{{ route('admin.receipts.show', $attachment->payment_id) }}">
                                                                        <i class="fa-solid fa-eye me-2 text-primary"></i>
                                                                        View
                                                                    </a>
                                                                @endif
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-3">
                                                    <i class="fa-regular fa-note-sticky me-1"></i>
                                                    No notes available
                                                </td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                @endcan
                @can('notes.view')
                    <div class="col-lg-12" id="noteSection">
                        <div class="dt-box-wo">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3>Notes</h3>
                                @can('notes.edit')
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#addNoteModal">
                                        Add Note
                                    </button>
                                @endcan
                            </div>
                            <div class="table-over">
                                <table class="align-middle mb-0 table table-striped tble-cstm mt-3">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Sl No</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Note Type</th>
                                            <th class="text-center">Description</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($workOrder->notes as $note)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $loop->iteration }}
                                                </td>

                                                <td class="text-center">
                                                    {{ $note->created_at->format('d M Y') ?? '-' }}
                                                </td>

                                                <td class="text-center">
                                                    {{ $note->noteType->note ?? '-' }}
                                                </td>

                                                <td class="text-center">
                                                    {{ $note->description ?? '-' }}
                                                </td>

                                                <td class="text-center">
                                                    @if ($note->status == 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($note->status == 'active')
                                                        <span class="badge bg-primary">Active</span>
                                                    @elseif($note->status == 'closed')
                                                        <span class="badge bg-success">Closed</span>
                                                    @else
                                                        <span class="badge bg-secondary">-</span>
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    <div class="dropdown action-dropdown">
                                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                                        </button>

                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                <button class="dropdown-item text-primary change-note-status"
                                                                    data-id="{{ $note->id }}" data-status="{{ $note->status }}">
                                                                    <i class="fa-solid fa-arrow-right me-2"></i>
                                                                    Change Status
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-3">
                                                    <i class="fa-regular fa-note-sticky me-1"></i>
                                                    No notes available
                                                </td>
                                            </tr>
                                        @endforelse


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endcan
                @can('message.view')
                    <div class="col-lg-12" id="messageSection">
                        <div class="dt-box-wo">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 class="mb-0">Messages</h3>
                                @can('message.edit')
                                    <button class="btn btn-success send-mail" data-id="{{ $workOrder->company->id }}">Send
                                        Message</button>
                                @endcan
                            </div>
                            @if (optional($workOrder->company)->messages && optional($workOrder->company->messages)->count())
                                <div class="table-over">
                                    <table class="align-middle mb-0 table table-striped tble-cstm mt-3">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Sl No</th>
                                                <th class="text-center">Customer</th>
                                                <th class="text-center">Date</th>
                                                <th class="text-center">Subject</th>
                                                <th class="text-center">Message</th>
                                                <th class="text-center">Priority</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($workOrder->company->messages as $message)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $loop->iteration }}
                                                    </td>

                                                    <td class="text-center">
                                                        {{ $message->company->company_name ?? '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $message->created_at->format('d M Y') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $message->subject ?? '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {!! optional($message->conversations->first())->message ?? '-' !!}
                                                    </td>
                                                    <td class="text-center">
                                                        @php
                                                            $priority = strtolower($message->priority ?? '');
                                                            $color = match ($priority) {
                                                                'high' => 'danger', // Red
                                                                'medium' => 'warning', // Orange
                                                                'low' => 'success', // Green
                                                                default => 'secondary', // Grey
                                                            };
                                                        @endphp
                                                        <span class="badge bg-{{ $color }}">{{ ucfirst($priority ?: '-') }}</span>
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="dropdown action-dropdown">
                                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                                            </button>

                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                @php $unread = unreadConversationCount($message->id); @endphp

                                                                {{-- Corp User Reply --}}
                                                                @role('Corp User')
                                                                <li>
                                                                    <a href="{{ route('admin.messages.conversation', $message->id) }}"
                                                                        class="dropdown-item d-flex justify-content-between align-items-center">
                                                                        Reply
                                                                        @if ($unread > 0)
                                                                            <span class="badge bg-danger ms-2">{{ $unread }}</span>
                                                                        @endif
                                                                    </a>
                                                                </li>
                                                                @endrole

                                                                {{-- Super Admin Replies --}}
                                                                @role('Super Admin')
                                                                <li>
                                                                    <a href="{{ route('admin.messages.conversation', $message->id) }}"
                                                                        target="_blank"
                                                                        class="dropdown-item d-flex justify-content-between align-items-center">
                                                                        <div>
                                                                            <i class="fa-solid fa-message"></i> Replies
                                                                        </div>
                                                                        @if ($unread > 0)
                                                                            <span class="badge bg-danger ms-2">{{ $unread }}</span>
                                                                        @endif
                                                                    </a>
                                                                </li>
                                                                @endrole

                                                                {{-- Delete --}}
                                                                <li>
                                                                    <button class="dropdown-item text-danger"
                                                                        onclick="deleteRow('{{ $message->id }}')">
                                                                        <i class="fa-solid fa-trash me-2"></i> Delete
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                @endcan


                {{-- Back Button --}}
                <!--<div class="mt-3">-->
                <!--    <a href="{{ route('admin.work-orders.index') }}" class="btn-back-cs">Back</a>-->
                <!--</div>-->
            </div>
        </div>
    </div>
    <div class="modal fade" id="attachmentsModal" tabindex="-1" aria-labelledby="attachmentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attachmentsModalLabel">Attachments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        @if ($workOrder->attachments->count())
                            <div class="row g-3">
                                @foreach ($workOrder->attachments()->where('entity', 'WO')->get() as $attachment)
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="plannerModal" tabindex="-1" aria-labelledby="plannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="plannerModalLabel">Planner Commission Break Down</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="planner-modal-content">
                        @if ($workOrder->quotation)
                                        <div class="overscroll-cs">
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
                                        </div>
                                        <div class="preview-bottom-right preview-bottom-right-planner mt-4">
                                            <div class="row justify-content-end">
                                                <div class="col-lg-4">
                                                    <table class="table table-sm table-borderless">
                                                        <tbody>
                                                            <tr>
                                                                <th class="text-start">Total Commission</th>
                                                                <td class="text-end">RM</td>
                                                                <td class="text-end">
                                                                    {{ $workOrder->quotation->planner_commission ?? '-' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-start">Total Amount</th>
                                                                <td class="text-end">RM</td>
                                                                <td class="text-end"> {{ $workOrder->quotation->grant_total ?? '-' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-start">Planner Commission %</th>
                                                                <td class="text-end"></td>
                                                                <td class="text-end">
                                                                    {{ isset($workOrder->quotation->p_bill_percentage)
                            ? number_format($workOrder->quotation->p_bill_percentage, 2)
                            : '-' }}%
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer p-0">
                    <button type="button" class="btn-back-cs" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="productionModal" tabindex="-1" aria-labelledby="productionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productionModalLabel">Production Commission Break Down</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="planner-modal-content">
                        @if ($workOrder->quotation)
                            <div class="overscroll-cs">
                                <table id="table" class="align-middle mb-0 table table-striped tble-cstm">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Item</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Unit Price</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Discount Percentage</th>
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
                                                    $netAmount = $item->total_amount;

                                                    $commission =
                                                        $netAmount *
                                                        ($workOrder->productionStaff->production_c_percentage /
                                                            100);
                                                @endphp

                                                <td class="text-center">
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
                            </div>
                            <div class="preview-bottom-right preview-bottom-right-planner mt-4">
                                <div class="row justify-content-end">
                                    <div class="col-lg-4">
                                        <table class="table table-sm table-borderless">
                                            <tbody>
                                                <tr>
                                                    <th class="text-start">Total Commission</th>
                                                    <td class="text-end">RM</td>
                                                    <td class="text-end">
                                                        {{ ($workOrder->quotation->grant_total * $workOrder->productionStaff->production_c_percentage) / 100 }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-start">Total Amount</th>
                                                    <td class="text-end">RM</td>
                                                    <td class="text-end"> {{ $workOrder->quotation->grant_total ?? '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-start">Planner Commission %</th>
                                                    <td class="text-end"></td>
                                                    <td class="text-end">
                                                        {{ $workOrder->productionStaff->production_c_percentage ?? '-' }}%
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer p-0">
                    <button type="button" class="btn-back-cs" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="plannerPayoutModal" tabindex="-1" aria-labelledby="plannerPayoutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="plannerPayoutModalLabel">Planner Commission Payouts</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="planner-modal-content">
                        @php
                            $invoice = $workOrder->quotation?->invoice;
                        @endphp
                        @if ($invoice && $invoice->plannerPayouts()->exists())
                                        <div class="overscroll-cs">
                                            <table id="table" class="align-middle mb-0 table table-striped tble-cstm">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Receipt Number</th>
                                                        <th class="text-center">Receipt Date</th>
                                                        <th class="text-center">Invoice Amount</th>
                                                        <th class="text-center">Receipt Amount</th>
                                                        <th class="text-center">Planner Commission Percentage</th>
                                                        <th class="text-center">Planner Commission Amount</th>
                                                        <th class="text-center">PC Paid Amount</th>
                                                        <th class="text-center">PC Paid Date</th>
                                                        <th class="text-center">Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($workOrder->quotation->invoice->plannerPayouts as $item)
                                                        <tr>
                                                            <td class="text-center">{{ $item->payment->custom_payment_id ?? '-' }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $item->payment->created_at->format('d M Y') ?? '-' }}
                                                            </td>
                                                            <td class="text-center">{{ $item->payment->invoice->grant_total ?? '-' }}
                                                            </td>
                                                            <td class="text-center">{{ $item->payment->amount ?? '-' }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $item->payment->invoice->p_bill_percentage ?? '-' }}%
                                                            </td>
                                                            <td class="text-center">{{ $item->amount ?? '-' }}
                                                            </td>
                                                            <td class="text-center">{{ $item->amount ?? '-' }}
                                                            </td>
                                                            <td class="text-center">{{ $item->created_at->format('d M Y') ?? '-' }}
                                                            </td>
                                                            <td class="text-center">{{ $item->remarks ?? '-' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="preview-bottom-right preview-bottom-right-planner mt-4">
                                            <div class="row justify-content-end">
                                                <div class="col-lg-4">
                                                    <table class="table table-sm table-borderless">
                                                        <tbody>
                                                            <tr>
                                                                <th class="text-start">Total Commission</th>
                                                                <td class="text-end">MYR</td>
                                                                <td class="text-end">
                                                                    {{ $workOrder->quotation->planner_commission ?? '-' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-start">Planner Commission %</th>
                                                                <td class="text-end"></td>
                                                                <td class="text-end">
                                                                    {{ isset($workOrder->quotation->p_bill_percentage)
                            ? number_format($workOrder->quotation->p_bill_percentage, 2)
                            : '-' }}%
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-start">Total Commission Paid</th>
                                                                <td class="text-end">MYR</td>
                                                                <td class="text-end">
                                                                    {{ $workOrder->quotation->invoice->plannerPayouts->sum('amount') ?? '-' }}
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer p-0">
                    <button type="button" class="btn-back-cs" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="productionPayoutModal" tabindex="-1" aria-labelledby="productionPayoutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productionPayoutModalLabel">Production Commission Payouts</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="planner-modal-content">
                        @php
                            $invoice = $workOrder->quotation?->invoice;
                        @endphp
                        @if ($invoice && $invoice->productionStaffPayouts()->exists())
                                        <div class="overscroll-cs">
                                            <table id="table" class="align-middle mb-0 table table-striped tble-cstm">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Receipt Number</th>
                                                        <th class="text-center">Receipt Date</th>
                                                        <th class="text-center">Invoice Amount</th>
                                                        <th class="text-center">Receipt Amount</th>
                                                        <th class="text-center">Production Commission Percentage</th>
                                                        <th class="text-center">Production Commission Amount</th>
                                                        <th class="text-center">PC Paid Amount</th>
                                                        <th class="text-center">PC Paid Date</th>
                                                        <th class="text-center">Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($workOrder->quotation->invoice->productionStaffPayouts as $item)
                                                        <tr>
                                                            <td class="text-center">{{ $item->payment->custom_payment_id ?? '-' }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $item->payment->created_at->format('d M Y') ?? '-' }}
                                                            </td>
                                                            <td class="text-center">{{ $item->payment->invoice->grant_total ?? '-' }}
                                                            </td>
                                                            <td class="text-center">{{ $item->payment->amount ?? '-' }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $item->payment->invoice->quotation->workPlan->productionStaff->production_c_percentage ?? '-' }}%
                                                            </td>
                                                            <td class="text-center">{{ $item->amount ?? '-' }}
                                                            </td>
                                                            <td class="text-center">{{ $item->amount ?? '-' }}
                                                            </td>
                                                            <td class="text-center">{{ $item->created_at->format('d M Y') ?? '-' }}
                                                            </td>
                                                            <td class="text-center">{{ $item->remarks ?? '-' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="preview-bottom-right preview-bottom-right-planner mt-4">
                                            <div class="row justify-content-end">
                                                <div class="col-lg-4">
                                                    <table class="table table-sm table-borderless">
                                                        <tbody>
                                                            <tr>
                                                                <th class="text-start">Total Commission</th>
                                                                <td class="text-end">MYR</td>
                                                                <td class="text-end">
                                                                    {{ $workOrder->quotation->invoice->grant_total * ($workOrder->productionStaff->production_c_percentage / 100) ?? '-' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-start">Production Commission %</th>
                                                                <td class="text-end"></td>
                                                                <td class="text-end">
                                                                    {{ isset($workOrder->productionStaff->production_c_percentage)
                            ? number_format($workOrder->productionStaff->production_c_percentage, 2)
                            : '-' }}%
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-start">Total Commission Paid</th>
                                                                <td class="text-end">MYR</td>
                                                                <td class="text-end">
                                                                    {{ $workOrder->quotation->invoice->productionStaffPayouts->sum('amount') ?? '-' }}
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer p-0">
                    <button type="button" class="btn-back-cs" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Dynamic content here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Add Document Modal -->
    <div class="modal fade" id="addDocumentModal" tabindex="-1" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="post" action="{{ route('admin.work-orders.attachments.store', $workOrder->id) }}"
                id="addDocumentForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDocumentModalLabel">Add Document</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <label for="name" class="form-label">Document Title</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="col-lg-6">
                                <label for="entity" class="form-label">Type</label>
                                <select name="entity" id="entity" class="form-select" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="General">General</option>
                                    <option value="Business">Business</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>

                            <div class="col-lg-6">
                                <label for="type" class="form-label">Document Type</label>
                                <select name="type" id="type" class="form-select" required>
                                    <option value="">-- Select Document Type --</option>
                                    @foreach ($documentTypes as $type)
                                        <option value="{{ $type->type }}">
                                            {{ $type->type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6">
                                <label for="year" class="form-label">Year</label>
                                <input type="text" name="year" id="year" value="{{ active_financial_year_start() }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-lg-6">
                                <label for="file" class="form-label">Upload File</label>
                                <input type="file" name="file" id="file" class="form-control" required>
                            </div>

                            <div class="col-lg-6">
                                <label for="service_type" class="form-label">Service Type</label>
                                <select name="service_type" id="service_type" class="form-select" required>
                                    <option value="">-- Select Service Type --</option>
                                    @foreach ($serviceTypes as $type)
                                        <option value="{{ $type->id }}">
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" rows="3" class="form-control"
                                    placeholder="Enter description (optional)"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Upload</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Note Modal -->
    <div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="post" action="{{ route('admin.work-orders.notes.store', $workOrder->id) }}" id="addNoteForm"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNoteModalLabel">Add Note</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="note_type_id" class="form-label">Note Type</label>
                                <select name="note_type_id" id="note_type_id" class="form-select" required>
                                    <option value="">-- Select Note Type --</option>
                                    @foreach ($noteTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->note }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="4"
                                    placeholder="Enter note description..." required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @include('admin.scripts.script')
    <script>
        $(document).on('click', '.change-status', function () {
            let id = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.quotations.status.view') }}",
                method: 'GET',
                data: {
                    id: id
                },
                success: function (response) {
                    $('#modalBody').html(response.html);
                    $('#modalTitle').text(response.title);
                    $('#formModal').modal('show');
                },
                error: function () {
                    alert('Failed to load status change form.');
                }
            });
        });

        $(document).on('click', '.change-status-invoice', function () {
            let id = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.invoices.status.view') }}",
                method: 'GET',
                data: {
                    id: id
                },
                success: function (response) {
                    $('#modalBody').html(response.html);
                    $('#modalTitle').text(response.title);
                    $('#formModal').modal('show');
                },
                error: function () {
                    alert('Failed to load status change form.');
                }
            });
        });

        $(document).on('click', '.planner-payout', function () {
            let id = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.work-orders.planner.payout-view') }}",
                method: 'GET',
                data: {
                    id: id
                },
                success: function (response) {
                    $('#modalBody').html(response.html);
                    $('#modalTitle').text(response.title);
                    $('#formModal').modal('show');
                },
                error: function () {
                    alert('Failed to load status change form.');
                }
            });
        });

        $(document).on('submit', '#commonForm', function (e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');
            let method = form.find('input[name="_method"]').val() || 'POST';
            let formData = form.serialize();

            // Get status value (approved / cancelled)
            let status = form.find('[name="status"]').val();
            let type = form.find('[name="type"]').val();
            let typeLabel = type.charAt(0).toUpperCase() + type.slice(1);

            let isApprove = status === 'approved';

            let confirmText = isApprove ?
                `Approve this ${typeLabel}?` :
                `Cancel this ${typeLabel}?`;

            let confirmMessage = isApprove ?
                `This will approve the ${type}.` :
                `This will cancel the ${type}.`;

            Swal.fire({
                title: confirmText,
                text: confirmMessage,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, continue',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.find('.error-text').text('');

                    $.ajax({
                        url: url,
                        type: method,
                        data: formData,
                        success: function (response) {
                            $('#formModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                confirmButtonText: 'OK',
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function (xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                $.each(errors, function (field, messages) {
                                    form.find('.' + field + '_error').text(messages[0]);
                                });
                            } else {
                                Swal.fire('Error', 'Something went wrong', 'error');
                            }
                        }
                    });
                }
            });
        });

        $(document).on('submit', '#commonFormTwo', function (e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');
            let method = form.find('input[name="_method"]').val() || 'POST';
            let formData = form.serialize();

            Swal.fire({
                title: 'Are you sure ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, continue',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.find('.error-text').text('');

                    $.ajax({
                        url: url,
                        type: method,
                        data: formData,
                        success: function (response) {
                            $('#formModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                confirmButtonText: 'OK',
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function (xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                $.each(errors, function (field, messages) {
                                    form.find('.' + field + '_error').text(messages[0]);
                                });
                            } else {
                                Swal.fire('Error', 'Something went wrong', 'error');
                            }
                        }
                    });
                }
            });
        });



        $(document).on('click', '.send-mail', function () {
            let companyId = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.messages.create') }}",
                method: 'GET',
                data: {
                    company_id: companyId,
                    user_id: '{{ $latestUser->id ?? null }}'
                },
                success: function (response) {
                    $('#modalBody').html(response.html);
                    $('#modalTitle').text(response.title);
                    selectInt();
                    selectCkEditor();
                    $('#formModal').modal('show');
                },
                error: function () {
                    alert('Failed to load status change form.');
                }
            });
        });

        function selectInt() {
            $('.multi-select').select2({
                width: '100%',
                placeholder: 'Select an option',
                allowClear: true,
                dropdownParent: '#commonForm1'
            });
        }

        function selectCkEditor() {
            ClassicEditor
                .create(document.querySelector('#message'), {
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'link',
                        'bulletedList', 'numberedList', '|',
                        'blockQuote', 'insertTable', '|',
                        'undo', 'redo'
                    ]
                })
                .catch(error => {
                    console.error(error);
                });

        }

        $(document).on('submit', '#commonForm1', function (e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var method = form.find('input[name="_method"]').val() || 'POST';
            var formData = form.serialize();
            form.find('.error-text').text('');
            $.ajax({
                url: url,
                type: method,
                data: formData,
                success: function (response) {
                    $('#formModal').modal('hide');
                    if (response.url) {
                        setTimeout(function () {
                            window.location.href = response.url;
                        }, 800);
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            confirmButtonText: 'OK',
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function (field, messages) {
                            form.find('.' + field + '_error').text(messages[0]);
                        });
                    } else {
                        showToast('error', 'Something went wrong');
                    }
                }
            });
        });

        function deleteRow(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This message will be deleted permanently!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Call delete API
                    $.ajax({
                        url: `/admin/messages/${id}`, // DELETE route
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}' // CSRF token
                        },
                        success: function (response) {
                            Swal.fire(
                                'Deleted!',
                                response.message || 'Message has been deleted.',
                                'success'
                            ).then(() => {
                                // Reload page or remove the row from table
                                location.reload();
                            });
                        },
                        error: function (xhr) {
                            Swal.fire(
                                'Error!',
                                xhr.responseJSON?.message || 'Something went wrong.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        $(document).on('click', '.change-note-status', function () {
            let noteId = $(this).data('id');
            let currentStatus = $(this).data('status');

            Swal.fire({
                title: 'Change Note Status',
                html: `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <select id="noteStatus" class="form-select">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <option value="active">Active</option>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <option value="closed">Closed</option>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </select>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        `,
                confirmButtonText: 'Update',
                showCancelButton: true,
                didOpen: () => {
                    $('#noteStatus').val(currentStatus); // ✅ auto select
                },
                preConfirm: () => {
                    return $('#noteStatus').val();
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    updateNoteStatus(noteId, result.value);
                }
            });
        });

        function updateNoteStatus(noteId, status) {
            $.ajax({
                url: "{{ route('admin.notes.update-status', ':id') }}".replace(':id', noteId),
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    status: status
                },
                success: function (res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated',
                        text: res.message
                    }).then(() => {
                        location.reload(); // or ajax reload if table
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Something went wrong'
                    });
                }
            });
        }
    </script>
@endsection