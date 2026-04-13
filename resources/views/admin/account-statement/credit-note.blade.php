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
                    <h3>Account Statements - CR Report</h3>
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
                                <button type="button" id="exportSelected" class="add-btn">Export PDF</button>

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
                                        <div class="col-lg-3 mb-2">
                                            <div class="o-f-inp">
                                                <label>Filter by Invoice No</label>
                                                <input type="text" id="filter-invoice" class="form-control shadow-none"
                                                    value="">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mb-2">
                                            <div class="o-f-inp">
                                                <label for="filter-status">Filter by status</label>
                                                <select id="filter-status" class="form-select shadow-none search-select">
                                                    <option value="">--- Select ---</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="approved">Approved</option>
                                                    <option value="cancelled">Cancelled</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mb-2">
                                            <div class="o-f-inp">
                                                <label>Filter by Work Order</label>
                                                <input type="text" id="filter-wo" class="form-control shadow-none" value="">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 d-flex align-items-end mt-2">
                                            <div class="btn-top-filters pt-10-cs ">
                                                <button type="button" class="btn-back-cs" id="reset-filters">Reset</button>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 d-flex align-items-end mt-2 badge-wo">
                            <strong>
                                Total Credit Note Amount:
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
                                                <th class="text-center">INVOICE NO</th>
                                                <th class="text-center">DATE</th>
                                                <th class="text-center">Invoice AMOUNT</th>
                                                <th class="text-center">CR NO</th>
                                                <th class="text-center">CR AMOUNT</th>
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
@endsection

@section('scripts')
    @include('admin.scripts.script')
    @include('admin.account-statement.credit-note-js')
@endsection