@extends('admin.layouts.app')
@section('title')
    Account Statements
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Account Statements - Consolidated WO Report</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="btn-top-filters pt-0 justify-content-end">
                                <button class="btn-filter-cs" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#filterCollapse" aria-expanded="false"
                                    aria-controls="filterCollapse">Filters </button>
                                <button type="button" id="exportPdf" class="add-btn">Print PDF</button>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="collapse" id="filterCollapse">
                                <div class="filter-body">
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
                                        <div class="col-lg-3 mb-2">
                                            <div class="o-f-inp">
                                                <label>Filter by Month</label>
                                                <input type="month" id="filter-month" class="form-control shadow-none">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mb-2">
                                            <div class="o-f-inp">
                                                <label for="filter-total-group">Filter by Total Group</label>
                                                <select id="filter-total-group"
                                                    class="form-select shadow-none search-select">
                                                    <option value="">--- Select ---</option>
                                                    @foreach ($totalGroups as $totalGroup)
                                                        <option value="{{ $totalGroup->id }}">{{ $totalGroup->customer_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @role(['Super Admin', 'Corp User'])
                                        <div class="col-lg-3 mb-2">
                                            <div class="o-f-inp">
                                                <label for="filter-planner">Filter by Planner</label>
                                                <select id="filter-planner" class="form-select shadow-none search-select">
                                                    <option value="">--- Select ---</option>
                                                    @foreach ($planners as $planner)
                                                        <option value="{{ $planner->id }}">{{ $planner->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @endrole
                                        <div class="col-lg-3 mb-2">
                                            <div class="o-f-inp">
                                                <label for="filter-customer">Filter by Customer</label>
                                                <select id="filter-customer" class="form-select shadow-none search-select">
                                                    <option value="">--- Select ---</option>
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}">{{ $customer->company_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 d-flex align-items-start mt-2">
                                            <div class="btn-top-filters pt-10-cs ">
                                                <button type="button" class="btn-back-cs" id="reset-filters">Reset</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 mx-0 px-0">
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="acd-card bg1">
                                    <div>
                                        <p>Total Work Order Amount</p>

                                        <h2> RM <span id="totalAmount">0.00</span></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="acd-card bg2">
                                    <div>
                                        <p>Total Quotation Amount</p>

                                        <h2> RM <span id="totalQuotationAmount">0.00</span></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="acd-card bg3">
                                    <div>
                                        <p>Total Invoice Amount</p>
                                        <h2> RM <span id="totalInvoiceAmount">0.00</span></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="acd-card bg4">
                                    <div>
                                        <p> Total Paid Amount</p>

                                        <h2>RM <span id="totalPaidAmount">0.00</span></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="acd-card bg5">
                                    <div>
                                        <p> Total Balance Amount</p>

                                        <h2>RM <span id="totalBalanceAmount">0.00</span></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="acd-card bg6">
                                    <div>
                                        <p> Total Receipt Amount</p>

                                        <h2>RM <span id="totalReceiptAmount">0.00</span></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="acd-card ">
                                    <div>
                                        <p style="color:#222;"> Total Credit Note Amount</p>

                                        <h2 style="color:#222;"> RM <span id="totalCreditAmount">0.00</span></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="acd-card ">
                                    <div>
                                        <p style="color:#222;">Total Planner Commission Amount</p>

                                        <h2 style="color:#222;">RM <span id="totalPlannerAmount">0.00</span></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="acd-card ">
                                    <div>
                                        <p style="color:#222;">Total Production Commission Amount</p>

                                        <h2 style="color:#222;">RM <span id="totalProductionAmount">0.00</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row d-none">
                        <div class="col-lg-12">
                            <div class=" mt-3 table-container">
                                <div class="table-over">
                                    <table id="table"
                                        class="align-middle mb-0 table table-striped tble-cstm mt-3 min-width-table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    <input type="checkbox" id="checkAll">
                                                </th>
                                                <th class="text-center">SL NO </th>
                                                <th class="text-center">WO No</th>
                                                <th class="text-center">WO DATE</th>
                                                <th class="text-center">TG</th>
                                                <th class="text-center">PLANNER</th>
                                                <th class="text-center">CUSTOMER NAME</th>
                                                <th class="text-center">Amount</th>
                                                <th class="text-center ">STATUS</th>
                                                <th class="text-center">QO No</th>
                                                <th class="text-center ">Invoice No</th>
                                                <th class="text-center ">Invoice Date</th>
                                                <th class="text-center ">Payment status</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('admin.account-statement.modal.modal')
@endsection

@section('scripts')
    @include('admin.scripts.script')
    @include('admin.account-statement.consolidated-js')
@endsection