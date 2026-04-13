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
                    <h3>Account Statements - Production Staff Commission Report</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        @unless (auth()->user()->hasRole('Production Staff'))
                            <div class="col-lg-3">
                                <div class="o-f-inp">
                                    <label for="filter-production">Filter by Production Staff</label>
                                    <select id="filter-production" class="form-select shadow-none search-select">
                                        <option value="">--- Select ---</option>
                                        @foreach ($staffs as $staff)
                                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @else
                            <input type="hidden" id="filter-production" value="{{ auth()->id() }}">
                        @endunless

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

                        <div class="col-lg-3 d-flex align-items-start mt-2">
                            <div class="btn-top-filters pt-2 ">
                                <button type="button" class="btn-back-cs" id="reset-filters">Reset</button>
                                <button type="button" id="exportSelected" class="add-btn">Export PDF</button>
                            </div>

                        </div>
                        <div class="col-lg-12 d-flex align-items-end mt-2 badge-wo">
                            <strong>
                                Total Production Staff Commission Amount:
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
                                                <th class="text-center">Receipt No</th>
                                                <th class="text-center">Production Staff Commission Amount</th>
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
    @include('admin.account-statement.production-js')
@endsection