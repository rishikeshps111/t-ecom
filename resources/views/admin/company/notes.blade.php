@extends('admin.layouts.app')
@section('style')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection
@section('content')

    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Customer Notes</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class=" mt-3 table-container">
                                <div class="table-over">
                                    <table id="table" class="align-middle mb-0 table table-striped  tble-cstm mt-3">
                                        <thead>
                                            <tr>
                                                <th class="text-center nowrap">SL NO </th>
                                                <th class="text-center">Note Type</th>
                                                <th class="text-center">Description</th>
                                                <th class="text-center">STATUS</th>
                                            </tr>
                                        </thead>
                                        <tbody id="staffTableBody">
                                            @forelse($records as $com)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $com->noteType->note ?? '' }}</td>
                                                    <td>{{ $com->description ?? '-' }}</td>
                                                    <td>
                                                        @if ($com->status == 'closed')
                                                            <span class="status-red">Closed</span>
                                                        @elseif($com->status == 'pending')
                                                            <span class="status-orange">Pending</span>
                                                        @else
                                                            <span class="status-green">Active</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10">No Records Found</td>
                                                </tr>
                                            @endforelse
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#table').DataTable({
                pageLength: 10,
                lengthChange: false,
                ordering: true,
                searching: true,
                info: true,
                autoWidth: false,
                columnDefs: [
                    { orderable: false, targets: [0] } // Disable sort on SL NO
                ]
            });
        });
    </script>
@endsection