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
                    <h3>Account Statements - Planner Commission Report</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        @role(['Super Admin', 'Corp User'])
                        <div class="col-lg-3">
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

                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-customer">Filter by Customer</label>
                                <select id="filter-customer" class="form-select shadow-none search-select">
                                    <option value="">--- Select Customer ---</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-total">Filter by Total Group</label>
                                <select id="filter-total" class="form-select shadow-none search-select">
                                    <option value="">--- Select Total Group ---</option>
                                    @foreach ($totalGroups as $totalGroup)
                                        <option value="{{ $totalGroup->id }}">{{ $totalGroup->customer_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

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

                        <div class="col-lg-5 d-flex align-items-start mt-2">
                            <div class="btn-top-filters pt-10-cs ">
                                <button type="button" class="btn-back-cs" id="reset-filters">Reset</button>
                                <button type="button" id="exportSelected" class="add-btn">Export PDF</button>
                            </div>

                        </div>
                        <div class="col-lg-12 d-flex align-items-end mt-2 badge-wo">
                            <strong>
                                Total Planner Commission Amount:
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
                                                {{-- <th class="text-center">
                                                    <input type="checkbox" id="checkAll">
                                                </th> --}}
                                                <th class="text-center">SL NO </th>
                                                <th class="text-center">WO No</th>
                                                <th class="text-center">Receipt No</th>
                                                <th class="text-center">Customer</th>
                                                <th class="text-center">Planner Commission Amount</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Paid Date</th>
                                                <th class="text-center">Remarks</th>
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
    @include('admin.account-statement.planner-js')
@endsection