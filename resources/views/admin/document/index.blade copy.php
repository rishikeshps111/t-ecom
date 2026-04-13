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
                    <h3>Company Document Management</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        @if(!isset($companyId))
                            <div class="col-lg-3 d-none">
                                <div class="o-f-inp">
                                    <label for="filter-company">Filter by Company</label>
                                    <select id="filter-company" class="form-select shadow-none search-select">
                                        <option value="">--- Select ---</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col-lg-3 d-none">
                            <div class="o-f-inp">
                                <label for="filter-title">Filter by Title</label>
                                <input type="text" id="filter-title" class="form-control shadow-none search-input"
                                    placeholder="Enter the title">
                            </div>
                        </div>
                        <div class="col-lg-3">
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
                        <div class="col-lg-3">
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
                                <label for="filter-status">Filter by Status</label>
                                <select id="filter-status" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    {{-- <option value="expired">Expired</option> --}}
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-year">Filter by Financial Year</label>
                                <select id="filter-year" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year->id }}">{{ $year->year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 d-flex align-items-end">
                            <button type="button" class="btn btn-secondary" id="reset-filters">Reset</button>
                            <button type="button" id="exportSelected" class="btn btn-success ms-2">Export</button>
                        </div>
                        @can('general-document.create')
                                            <div class="col-lg-3 ms-auto mt-4">
                                                <a href="{{ route_with_query('admin.documents.create', [
                                'company_id' => $companyId ?? null,
                            ]) }}" class="add-btn">Add Document</a>
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
                                                {{-- <th class="text-center">Document Type</th> --}}
                                                <th class="text-center">Title</th>
                                                <th class="text-center">Total Group</th>
                                                <th class="text-center">Type</th>
                                                @if(!isset($companyId))
                                                    <th class="text-center">Company</th>
                                                @endif
                                                {{-- <th class="text-center">Document Issued</th>
                                                <th class="text-center ">Document Expiry</th> --}}
                                                <th class="text-center ">Financial Year</th>
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

    @include('admin.document.modal.modal')

@endsection

@section('scripts')
    @include('admin.scripts.script')
    @include('admin.document.js')
@endsection