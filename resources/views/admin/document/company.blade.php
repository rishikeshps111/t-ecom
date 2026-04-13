@extends('admin.layouts.app')
@section('title')
    Document Management
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Documents By Company</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">

                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-work">Filter by Work Order</label>
                                <select id="filter-work" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    @foreach($workPlans as $workPlan)
                                        <option value="{{ $workPlan->id }}">{{ $workPlan->workplan_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- @unlessrole('Customer') --}}
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-customer">Filter by Customer</label>
                                <select id="filter-customer" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- @endunlessrole --}}
                        <div class="col-lg-3 d-none">
                            <div class="o-f-inp">
                                <label for="filter-entity">Filter by Entity</label>
                                <select id="filter-entity" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    <option value="IN">INVOICE</option>
                                    <option value="OR">Original Receipt</option>
                                    <option value="QO">Quotation</option>
                                    <option value="WO">Work Order</option>
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
                        <div class="col-lg-3 d-none">
                            <div class="o-f-inp">
                                <label for="filter-type">Filter by File Type</label>
                                <select id="filter-type" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    <option value="image">Image</option>
                                    <option value="pdf">PDF </option>
                                    <option value="word">Word</option>
                                    <option value="excel">Excel</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 d-flex align-items-end">
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
                                                <th class="text-center">SL NO </th>
                                                <th class="text-center">Customer </th>
                                                <th class="text-center">Work Order</th>
                                                <th class="text-center">Description</th>
                                                <th class="text-center">Date</th>
                                                <th class="text-center">View Documents</th>
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
    @include('admin.document.company-js')
@endsection