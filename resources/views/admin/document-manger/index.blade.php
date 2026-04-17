@extends('admin.layouts.app')
@section('title')
    Document Manager
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Customer Document Manager</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="o-f-inp">
                                <label for="filter-search">Search by Customer ID, Name</label>
                                <input type="text" id="filter-search" class="form-control shadow-none"
                                    placeholder="Enter customer ID, name">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-status">Filter by Status</label>
                                <select id="filter-status" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    <option value="active">Active</option>
                                    <option value="draft">Draft</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="btn-top-filters">
                                <button type="button" class="btn-back-cs" id="reset-filters">Reset</button>
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
                                                <th class="text-center">SL NO </th>
                                                <th class="text-center">Customer ID</th>
                                                <th class="text-center">Customer Name</th>
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
    <script>
        $(function () {
            let table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.document-manger.index') }}",
                    data: function (d) {
                        d.search_term = $('#filter-search').val();
                        d.status = $('#filter-status').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'company_code', name: 'company_code' },
                    { data: 'company_name', name: 'company_name' },
                    { data: 'totalGroup', name: 'totalGroup' },
                    { data: 'status', name: 'status' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });

            $('#filter-search, #filter-status').on('keyup change', function () {
                table.draw();
            });

            $('#reset-filters').on('click', function () {
                $('#filter-search').val('');
                $('#filter-status').val('').trigger('change');
                table.draw();
            });
        });
    </script>
@endsection