@extends('admin.layouts.app')
@section('title')
    Total Group Management
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Total Group Management</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-customer-code">Search by Code</label>
                                <input type="text" id="filter-customer-code" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-customer-name">Search by Name</label>
                                <input type="text" id="filter-customer-name" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3 d-none">
                            <div class="o-f-inp">
                                <label for="filter-company">Search by Company</label>
                                <input type="text" id="filter-company" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="status">Filter by Status</label>
                                <select id="filter-status" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 d-flex align-items-end">
                            <div class="btn-top-filters ">
                                <button type="button" class="btn-back-cs w-110" id="reset-filters">Reset</button>
                                <a href="{{ route('admin.customers.create') }}" class="add-btn w-110">Add</a>
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
                                                {{-- <th class="text-center">
                                                    <input type="checkbox">
                                                </th> --}}
                                                <th class="text-center">SL NO </th>
                                                <th class="text-center">TotalGroup Code</th>
                                                {{-- <th class="text-center">Company</th> --}}
                                                <th class="text-center">Name</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center ">Phone</th>
                                                <th class="text-center ">Created At</th>
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
    @include('admin.customer.modal.modal')

@endsection

@section('scripts')
    @include('admin.scripts.script')
    @include('admin.customer.js')
@endsection