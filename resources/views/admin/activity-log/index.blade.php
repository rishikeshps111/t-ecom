@extends('admin.layouts.app')
@section('title')
    Activity Log
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Activity Log</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-from">From Date</label>
                                <input type="date" id="filter-from" class="form-control shadow-none">
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-to">To Date</label>
                                <input type="date" id="filter-to" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-user">Filter by Staff Name</label>
                                <input type="text" id="filter-user" class="form-control shadow-none">
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-module">Filter by Module</label>
                                <input type="text" id="filter-module" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3 d-flex align-items-end mt-2">
                            <button type="button" class="btn btn-secondary" id="reset-filters">Reset</button>
                            <button type="button" class="btn btn-danger ms-2" id="bulk-delete" disabled style="white-space:nowrap">
                                Delete Selected
                            </button>
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
                                                    <input type="checkbox" id="select-all">
                                                </th>
                                                <th class="text-center">SL NO </th>
                                                <th>Log</th>
                                                <th>Module</th>
                                                <th>User</th>
                                                <th>Time</th>
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
    @include('admin.activity-log.js')
@endsection