@extends('admin.layouts.app')
@section('title')
    Announcement Management
@endsection
@section('style')
    @include('admin.scripts.css')
    <style>
        .btn-view {
            position: relative;
        }

        /* New badge */
        .badge-new {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #dc3545;
            /* red */
            color: #fff;
            font-size: 10px;
            font-weight: bold;
            padding: 2px 5px;
            border-radius: 3px;
            animation: blink 1s infinite;
        }

        /* Blink animation */
        @keyframes blink {

            0%,
            50%,
            100% {
                opacity: 1;
            }

            25%,
            75% {
                opacity: 0;
            }
        }
    </style>
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Announcement Management</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-date">Filter by Schedule Date</label>
                                <input type="date" id="filter-date" class="form-control shadow-none search-input"
                                    placeholder="Enter the Name">
                            </div>
                        </div>
                        <div class="col-lg-3 ">
                            <div class="btn-top-filters "> <button type="button" class="btn-back-cs"
                                    id="reset-filters">Reset</button></div>

                        </div>
                        @can('announcement.edit')
                            <div class="col-lg-3 ms-auto mt-4">
                                <button class="add-btn send-mail">Send Announcement</button>
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
                                                <th class="text-center">Schedule Date</th>
                                                <th class="text-center">Message</th>
                                                <th class="text-center">Priority</th>
                                                <th class="text-center">Type</th>
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
    @include('admin.announcement.modal.modal')
@endsection

@section('scripts')
    @include('admin.scripts.script')
    @include('admin.announcement.js')
@endsection