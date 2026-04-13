@extends('admin.layouts.app')
@section('title')
    Message Management
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Message Management</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-date">Filter by Date</label>
                                <input type="date" id="filter-date" class="form-control shadow-none search-input"
                                    placeholder="Enter the Name">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-name">Filter by Customer Name</label>
                                <input type="text" id="filter-name" class="form-control shadow-none search-input"
                                    placeholder="Enter the Name">
                            </div>
                        </div>
                        <div class="col-lg-3 d-flex align-items-end">
                            <button type="button" class="btn btn-secondary" id="reset-filters">Reset</button>
                        </div>
                        @can('message.edit')
                            <div class="col-lg-3 ms-auto mt-4">
                                <button class="add-btn send-mail">Send Message</button>
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
                                                <th class="text-center">Customer Name</th>
                                                {{-- <th class="text-center">Subject</th> --}}
                                                {{-- <th class="text-center">Priority</th> --}}
                                                <th class="text-center">Date</th>
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
    @include('admin.message.modal.modal')
@endsection

@section('scripts')
    @include('admin.scripts.script')
    @include('admin.message.js')
@endsection