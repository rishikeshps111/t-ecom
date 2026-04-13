@extends('admin.layouts.app')
@section('title')
    Account Statements
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <style>
        .dataTables_length {
            float: left;
        }

        div.dt-buttons {
            float: inline-end;
        }

        div.dt-buttons button {
            margin-left: 10px !important;
            background-color: green !important;
            color: #fff !important;
            background-image: unset !important;
            border-radius: 5px !important;
        }

        div.dataTables_wrapper div.dataTables_length select {
            box-shadow: none !important;
        }
    </style>
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Account Statements - Monthly Summary</h3>
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
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="collapse" id="filterCollapse">
                                <div class="filter-body">
                                    <div class="row">
                                        <div class="col-lg-3 mb-2">
                                            <div class="o-f-inp">
                                                <label>Filter by Month</label>
                                                <input type="month" id="filter-month" class="form-control shadow-none"
                                                    value="{{ now()->format('Y-m') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mb-2">
                                            <div class="o-f-inp">
                                                <label for="filter-total-group">Filter by Total Group</label>
                                                <select id="filter-total-group"
                                                    class="form-select shadow-none search-select">
                                                    <option value="">--- Select ---</option>
                                                    @foreach ($totalGroups as $totalGroup)
                                                        <option value="{{ $totalGroup->id }}">
                                                            {{ $totalGroup->customer_name }}
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
                        <div class="row mt-3 mx-0  px-0">
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                <div class="acd-card bg1">
                                    <div>
                                        <p> Total Work Order Amount</p>
                                        <h2> RM <span id="totalAmount">0.00</span></h2>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                <div class="acd-card bg2">
                                    <div>
                                        <p> Total Quotation Amount</p>
                                        <h2>RM <span id="totalQuotationAmount">0.00</span></h2>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                <div class="acd-card bg3">
                                    <div>
                                        <p>Total Invoice Amount</p>
                                        <h2>RM <span id="totalInvoiceAmount">0.00</span></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                <div class="acd-card bg4">
                                    <div>
                                        <p> Total Paid Amount</p>
                                        <h2>RM <span id="totalPaidAmount">0.00</span></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                <div class="acd-card bg5">
                                    <div>
                                        <p>Total Balance Amount</p>
                                        <h2> RM <span id="totalBalanceAmount">0.00</span></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                <div class="acd-card bg6">
                                    <div>
                                        <p>Total Receipt Amount</p>
                                        <h2>RM <span id="totalReceiptAmount">0.00</span></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                <div class="acd-card bg7">
                                    <div>
                                        <p>Total Credit Note Amount</p>
                                        <h2>RM <span id="totalCreditAmount">0.00</span></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                <div class="acd-card bg8">
                                    <div>
                                        <p>Total Planner Commission Amount</p>
                                        <h2>RM <span id="totalPlannerAmount">0.00</span></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                <div class="acd-card bg1">
                                    <div>
                                        <p>Total Production Commission Amount</p>
                                        <h2>RM <span id="totalProductionAmount">0.00</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class=" mt-3 table-container">
                                <div class="table-over">
                                    <table id="table"
                                        class="align-middle mb-0 table table-striped tble-cstm mt-3 min-width-table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">SL NO </th>
                                                <th class="text-center">WO</th>
                                                <th class="text-center">CUSTOMER NAME</th>
                                                <th class="text-center">TOTAL GROUP</th>
                                                <th class="text-center">AMOUNT</th>
                                                <th class="text-center">INVOICE AMOUNT</th>
                                                <th class="text-center">AMOUNT RECEIVED</th>
                                                <th class="text-center">CREDIT NOTE</th>
                                                <th class="text-center">DEBIT NOTE</th>
                                                <th class="text-center">BALANCE</th>
                                                <th class="text-center">STATUS</th>
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
    @include('admin.account-statement.monthly-summary-js')
@endsection