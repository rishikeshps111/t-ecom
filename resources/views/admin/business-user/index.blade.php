@extends('admin.layouts.app')
@section('title')
    Cus User Management
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Cus User Management</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        <div class="col-lg-3 d-none">
                            <div class="o-f-inp">
                                <label for="filter-customer-code">Search by Customer Code</label>
                                <input type="text" id="filter-customer-code" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="o-f-inp">
                                <label for="filter-customer-name">Search by Customer Name</label>
                                <input type="text" id="filter-customer-name" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3 d-none">
                            <div class="o-f-inp">
                                <label for="filter-company">Filter by Company</label>
                                <select id="filter-company" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->company_name }}">{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 d-none">
                            <div class="o-f-inp">
                                <label for="status">Filter by Status</label>
                                <select id="filter-status" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 ">
                            <div class="btn-top-filters ">
                                <button type="button" class="btn-back-cs" id="reset-filters">Reset</button>
                                <button type="button" id="exportSelected" class="submit-btn">Export</button>
                            </div>

                        </div>
                        <div class="col-lg-3 ms-auto mt-4">
                            <a href="{{ route('admin.business-users.create') }}" class="add-btn">Add Cus User</a>
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
                                                <th class="text-center">Customer Code</th>
                                                <th class="text-center">Customer Name</th>
                                                {{-- <th class="text-center">Company</th> --}}
                                                <th class="text-center">Email</th>
                                                <th class="text-center ">Phone</th>
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
    @include('admin.business-user.modal.modal')

@endsection

@section('scripts')
    @include('admin.scripts.script')
    @include('admin.business-user.js')
@endsection