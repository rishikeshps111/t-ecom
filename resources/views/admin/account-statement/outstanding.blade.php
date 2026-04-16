@extends('admin.layouts.app')
@section('title')
    Account Statements
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <style>
        .btn-top-filters button,
        .btn-top-filters a {
            margin: 0 !important;
            width: fit-content;
            min-width: unset;
            padding: 8px 15px;
        }
    </style>
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Account Statements - OutStanding Report</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-3 mb-2">
                                    <div class="o-f-inp">
                                        <label>From Date</label>
                                        <input type="date" id="filter-from-date" class="form-control shadow-none"
                                            value="{{ now()->subDays(30)->toDateString() }}">
                                    </div>
                                </div>

                                <div class="col-lg-3 mb-2">
                                    <div class="o-f-inp">
                                        <label>To Date</label>
                                        <input type="date" id="filter-to-date" class="form-control shadow-none"
                                            value="{{ now()->toDateString() }}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="o-f-inp">
                                        <label for="filter-company">Filter by Customer</label>
                                        <select name="filter-company" id="filter-company"
                                            class="form-select shadow-none search-select">
                                            <option value="">--- Select ---</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="o-f-inp">
                                        <label for="filter-cus">Filter by Cus User</label>
                                        <select name="filter-cus" id="filter-cus"
                                            class="form-select shadow-none search-select">
                                            <option value="">--- Select ---</option>
                                            @foreach ($cusUsers as $cusUser)
                                                <option value="{{ $cusUser->id }}">{{ $cusUser->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="o-f-inp">
                                        <label for="filter-planner">Filter by Planner</label>
                                        <select name="filter-planner" id="filter-planner"
                                            class="form-select shadow-none search-select">
                                            <option value="">--- Select ---</option>
                                            @foreach ($planners as $planner)
                                                <option value="{{ $planner->id }}">{{ $planner->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="o-f-inp">
                                        <label for="filter-production">Filter by Production Staff</label>
                                        <select name="filter-production" id="filter-production"
                                            class="form-select shadow-none search-select">
                                            <option value="">--- Select ---</option>
                                            @foreach ($productions as $production)
                                                <option value="{{ $production->id }}">{{ $production->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="o-f-inp">
                                        <label for="filter-total">Filter by Total Group</label>
                                        <select name="filter-total" id="filter-total"
                                            class="form-select shadow-none search-select">
                                            <option value="">--- Select ---</option>
                                            @foreach ($totalGroups as $totalGroup)
                                                <option value="{{ $totalGroup->id }}">{{ $totalGroup->customer_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 d-flex align-items-start mt-2">
                                    <div class="btn-top-filters pt-10-cs ">
                                        <button type="button" class="btn-back-cs" id="reset-filters">Reset</button>
                                        {{-- <button type="button" id="exportCsv" class="add-btn">
                                            Export CSV
                                        </button> --}}
                                        <button type="button" id="exportPdf" class="add-btn"
                                            style="background-color:#9a8b1c;border-color:#9a8b1c;">
                                            Export PDF
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 d-flex align-items-end mt-2 badge-wo">
                            <strong>
                                Total OutStanding:
                                <span class="badge bg-success fs-6 ms-1">
                                    RM <span id="totalAmount">0.00</span>
                                </span>
                            </strong>
                            <strong class="ms-2">
                                Total Pending Invoice:
                                <span class="badge bg-success fs-6 ms-1">
                                    <span id="pendingInvoice">0.00</span>
                                </span>
                            </strong>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class=" mt-3 table-container">
                                <div class="table-over">
                                    <table id="table"
                                        class="align-middle mb-0 table table-striped tble-cstm mt-3 min-width-table last-nowrap">
                                        <thead>
                                            <tr>
                                                <th class="text-center">SL NO </th>
                                                <th class="text-center">Customer Name</th>
                                                <th class="text-center">Planner ID</th>
                                                <th class="text-center">Invoice no</th>
                                                <th class="text-center">OR</th>
                                                <th class="text-center">OR AMOUNT</th>
                                                <th class="text-center">OR paid</th>
                                                <th class="text-center">DUE</th>
                                                <th class="text-center">planner COMMISSION</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">PS COMMISSION</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Paid / Not Paid</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="items-summary" class="mt-3 ">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="plan-commision-cs report-1-cs">
                                    <ul>
                                        <li class="detail-label fw-bold">TOTAL AMOUNT : <span class="detail-value"
                                                id="totalAmountDynamic">00.0</span>
                                        </li>
                                        <li class="detail-label fw-bold">TOTAL PAID AMOUNT : <span class="detail-value"
                                                id="totalPaidAmountDynamic">0.00</span></li>
                                        <li class="detail-label fw-bold">TOTAL BALANCE AMOUNT : <span class="detail-value"
                                                id="totalBalanceAmountDynamic"></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                        @role(['Super Admin', 'Admin', 'Management Staff', 'Production Staff', 'Planner'])
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <div class="plan-commision-2-cs report-2-cs">
                                    <ul>
                                        <li class="detail-label fw-bold">Total Planner Commission: <span
                                                class="detail-value" id="totalPlannerCommission">00.0</span>
                                        </li>
                                        <li class="detail-label fw-bold">Total Paid Planner Commission: <span
                                                class="detail-value" id="totalPlannerPaid">0.00</span></li>
                                        <li class="detail-label fw-bold">Total Pending Planner Commission:: <span
                                                class="detail-value" id="totalPlannerPending">0.00</span></li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <div class="plan-commision-2-cs report-3-cs">
                                    <ul>
                                        <li class="detail-label fw-bold">Total PS Commission: <span class="detail-value"
                                                id="totalPsCommission">00.0</span>
                                        </li>
                                        <li class="detail-label fw-bold">Total Paid PS Commission: <span
                                                class="detail-value" id="totalPsPaid">0.00</span></li>
                                        <li class="detail-label fw-bold">Total Pending PS Commission:: <span
                                                class="detail-value" id="totalPsPending">0.00</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endrole
                    </div>
                </div>
            </div>
        </div>
    </section>

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
@endsection

@section('scripts')
    @include('admin.scripts.script')
    @include('admin.account-statement.outstanding-js')
@endsection