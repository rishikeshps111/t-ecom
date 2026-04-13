@extends('admin.layouts.app')
@section('title')
    Planner Documents
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Planner Documents</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        <div class="col-lg-3 d-none">
                            <div class="o-f-inp">
                                <label for="filter-company">Filter by Company</label>
                                <select id="filter-company" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if(!auth()->user()->hasRole('Planner'))
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
                        @endif
                        <div class="col-lg-3 d-none d-none">
                            <div class="o-f-inp">
                                <label for="filter-name">Filter by Title</label>
                                <input type="text" id="filter-name" class="form-control shadow-none search-input"
                                    placeholder="Enter the Name">
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
                                <label for="filter-year">Filter by Financial Year</label>
                                <select id="filter-year" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year->id }}">{{ $year->year }}</option>
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
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 d-flex align-items-end">
                            <button type="button" class="btn btn-secondary" id="reset-filters">Reset</button>
                            <button type="button" id="exportSelected" class="btn btn-success ms-2">Export</button>
                        </div>
                        @can('document.edit')
                            <div class="col-lg-3 ms-auto mt-4">
                                <a href="{{ route('admin.planner-documents.create') }}" class="add-btn">Add Planner Document</a>
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
                                                <th class="text-center">SL NO </th>
                                                <th class="text-center">Title</th>
                                                <th class="text-center">Planner</th>
                                                <th class="text-center">Total Group</th>
                                                <th class="text-center">Type</th>
                                                {{-- <th class="text-center">Start Date</th>
                                                <th class="text-center">End Date</th> --}}
                                                <th class="text-center">Financial Year</th>
                                                <th class="text-center">Status</th>
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
    @include('admin.planner-document.modal.modal')
@endsection

@section('scripts')
    @include('admin.scripts.script')
    @include('admin.planner-document.js')
@endsection