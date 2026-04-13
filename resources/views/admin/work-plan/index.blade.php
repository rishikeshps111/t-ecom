@extends('admin.layouts.app')
@section('title')
    Work Order Management
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Work Order Management</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-number">Search by Work Order Number</label>
                                <input type="text" id="filter-number" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label>From Date</label>
                                <input type="date" id="filter-from-date" class="form-control shadow-none">
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label>To Date</label>
                                <input type="date" id="filter-to-date" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-customer">Filter by Customer</label>
                                <select id="filter-customer" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @unless (auth()->user()->hasRole('Production Staff'))

                            <div class="col-lg-3">
                                <div class="o-f-inp">
                                    <label for="filter-staff">Filter by Production Staff</label>
                                    <select id="filter-staff" class="form-select shadow-none search-select">
                                        <option value="">--- Select ---</option>
                                        @foreach ($staffs as $staff)
                                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endunless

                        @unless (auth()->user()->hasRole('Planner'))
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
                        @endunless
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="status">Filter by Status</label>
                                <select id="filter-status" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    <option value="approved">Approved</option>
                                    <option value="pending">Pending</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="btn-top-filters">
                                <button type="button" class="btn-back-cs" id="reset-filters">Reset</button>
                                <button type="button" id="exportSelected" class="btn-export-cs d-none">Export</button>
                                @can('wo.edit')
                                                            <a href="{{ route_with_query('admin.work-orders.create', [
                                        'customer_id' => $customerID ?? null,
                                    ]) }}" class="add-btn">Add</a>
                                @endcan
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class=" mt-3 table-container">
                                <div class="table-over">
                                    <table id="table" class="align-middle mb-0 table table-striped tble-cstm mt-3">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    <input type="checkbox" id="checkAll">
                                                </th>
                                                <th class="text-center">SL NO </th>
                                                <th class="text-center">WO No</th>
                                                <th class="text-center">WO Date</th>
                                                <th class="text-center">Customer</th>
                                                <th class="text-center">Total Group</th>
                                                <th class="text-center ">Status</th>
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
                </div>
            </div>
        </div>
    </section>
    @include('admin.work-plan.modal.modal')
@endsection

@section('scripts')
    @include('admin.scripts.script')
    @include('admin.work-plan.js')
@endsection