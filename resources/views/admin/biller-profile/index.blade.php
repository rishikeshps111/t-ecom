@extends('admin.layouts.app')
@section('title')
    Biller Profile Management
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Biller Profile Management</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        <div class="col-lg-3 d-none">
                            <div class="o-f-inp">
                                <label for="filter-company">Filter by Customer</label>
                                <select id="filter-company" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="o-f-inp">
                                <label for="filter-total-group">Filter by Total Group</label>
                                <select id="filter-total-group" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    @foreach ($totalGroups as $totalGroup)
                                        <option value="{{ $totalGroup->id }}">{{ $totalGroup->customer_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 d-flex align-items-end">
                            <button type="button" class="reset-filter-btn" id="reset-filters">Reset</button>
                        </div>
                        {{-- <div class="col-lg-3 ms-auto mt-4">
                            <a href="{{ route('admin.biller-profiles.create') }}" class="add-btn">Add</a>
                        </div> --}}
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class=" mt-3 table-container">
                                <div class="table-over btn-icon-table">
                                    <table id="table" class="align-middle mb-0 table table-striped tble-cstm mt-3">
                                        <thead>
                                            <tr>
                                                <th class="text-center">SL NO </th>
                                                <th class="text-center">Total Group</th>
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
@endsection

@section('scripts')
    @include('admin.scripts.script')
    @include('admin.biller-profile.js')
@endsection