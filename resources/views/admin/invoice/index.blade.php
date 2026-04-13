@extends('admin.layouts.app')
@section('title')
    Invoice Management
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Invoice Management</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-invoice">Search by Invoice No</label>
                                <input type="text" id="filter-invoice" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-work">Search by Work Order</label>
                                <input type="text" id="filter-work" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-quotation">Search by Quotation No</label>
                                <input type="text" id="filter-quotation" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3 d-none">
                            <div class="o-f-inp">
                                <label for="filter-customer">Search by Customer</label>
                                <input type="text" id="filter-customer" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3 d-none">
                            <div class="o-f-inp">
                                <label for="filter-invoice-date">Filter by Invoice Date</label>
                                <input type="date" id="filter-invoice-date" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3 d-none">
                            <div class="o-f-inp">
                                <label for="filter-due-date">Filter by Due Date</label>
                                <input type="date" id="filter-due-date" class="form-control shadow-none">
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
                                <label for="status">Filter by Status</label>
                                <select id="filter-status" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    <option value="draft">Draft</option>
                                    <option value="submitted">Submitted</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 d-flex align-items-end">
                            <button type="button" class="btn btn-secondary" id="reset-filters">Reset</button>
                            <button type="button" id="exportSelected" class="btn btn-success ms-2">Export</button>
                        </div>
                        @can('inv.create')
                            <div class="col-lg-3 ms-auto mt-4 d-none">
                                <a href="{{ route('admin.invoices.create') }}" class="add-btn">Add Invoice</a>
                            </div>
                        @endcan
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
                                                <th class="text-center">Invoice No</th>
                                                <th class="text-center">Quotation No</th>
                                                <th class="text-center">Company</th>
                                                <th class="text-center">Type</th>
                                                <th class="text-center ">Invoice Date</th>
                                                {{-- <th class="text-center ">Due Date</th> --}}
                                                <th class="text-center ">Status</th>
                                                {{-- <th class="text-center ">Total Amount</th> --}}
                                                {{-- <th class="text-center ">Paid Amount</th> --}}
                                                {{-- <th class="text-center ">Balance Amount</th> --}}
                                                <th class="text-center ">Payment Status</th>
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
    @include('admin.invoice.modal.modal')

@endsection

@section('scripts')
    @include('admin.scripts.script')
    @include('admin.invoice.js')
@endsection