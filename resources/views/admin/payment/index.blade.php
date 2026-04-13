@extends('admin.layouts.app')
@section('title')
    Original Receipts (OR)
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Original Receipts (OR)</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        <div class="col-lg-3 d-none">
                            <div class="o-f-inp">
                                <label for="filter-customer">Search by Customer</label>
                                <input type="text" id="filter-customer" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3 d-none">
                            <div class="o-f-inp">
                                <label for="filter-company">Filter by Company</label>
                                <select id="filter-company" class="form-select shadow-none search-select">
                                    <option value="">--- Select Company ---</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 d-none">
                            <div class="o-f-inp">
                                <label for="filter-year">Filter by Financial Year</label>
                                <select id="filter-year" class="form-select shadow-none search-select">
                                    <option value="">--- Select Year ---</option>
                                    @for ($y = now()->year; $y >= 2020; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-invoice">Search by Invoice No</label>
                                <input type="text" id="filter-invoice" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-receipt">Search by Receipt No</label>
                                <input type="text" id="filter-receipt" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3 d-none">
                            <div class="o-f-inp">
                                <label for="filter-total-group">Filter by Total Group</label>
                                <select id="filter-total-group" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    @foreach($totalGroups as $group)
                                        <option value="{{ $group->id }}">{{ $group->customer_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 d-none">
                            <div class="o-f-inp">
                                <label for="filter-type">Filter by Type</label>
                                <select id="filter-type" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 d-none">
                            <div class="o-f-inp">
                                <label for="status">Filter by Status</label>
                                <select id="filter-status" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    <option value="closed">Closed</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 d-flex align-items-end mt-3">
                            <button type="button" class="btn btn-secondary" id="reset-filters">Reset</button>
                            <button type="button" id="exportSelected" class="btn btn-success ms-2">Export</button>
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
                                                <th class="text-center">Invoice No</th>
                                                <th class="text-center">Receipt No</th>
                                                <th class="text-center">Receipt Date</th>
                                                <th class="text-center ">Company</th>
                                                <th class="text-center">Amount</th>
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
@endsection

@section('scripts')
    @include('admin.scripts.script')
    @include('admin.payment.js')
@endsection