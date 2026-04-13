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
                    <h3>Account Statements - Total Group Report</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-total-group">Filter by Total Group</label>
                                <select id="filter-total-group" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    @foreach ($totalGroups as $totalGroup)
                                        <option value="{{ $totalGroup->id }}">{{ $totalGroup->customer_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="o-f-inp">
                                <label for="filter-start-date">Start Date</label>
                                <input type="date" id="filter-start-date" class="form-control shadow-none"
                                    value="{{ \Carbon\Carbon::today()->subDays(30)->format('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="o-f-inp">
                                <label for="filter-end-date">End Date</label>
                                <input type="date" id="filter-end-date" class="form-control shadow-none"
                                    value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-lg-3 d-flex align-items-start mt-2">
                            <div class="btn-top-filters pt-10-cs ">
                                <button type="button" class="btn-back-cs" id="reset-filters">Reset</button>
                                <button type="button" id="exportSelected" class="add-btn">Export PDF</button>
                            </div>

                        </div>
                        <div class="col-lg-12 d-flex align-items-end mt-2 badge-wo">
                            <strong>
                                Total Work Order Amount:
                                <span class="badge bg-success fs-6 ms-1">
                                    RM <span id="totalAmount">0.00</span>
                                </span>
                            </strong>
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
@endsection

@section('scripts')
    @include('admin.scripts.script')
    @include('admin.account-statement.tg-js')
@endsection