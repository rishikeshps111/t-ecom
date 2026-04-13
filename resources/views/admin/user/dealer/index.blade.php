@extends('admin.layouts.app')
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Dealer</h3>
                </div>
            </div>
        </div>
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <div class="top-btns-section">
                    <!-- <a href="#!">Assign Role</a>
                                        <a href="#!">Assign Companies</a> -->

                </div>
            </div>

        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">

                    <form method="GET" id="searchForm">
                        <div class="row">
                            <div class="col-lg-3">
                                {{-- <div class="o-f-inp">
                                    <label for="">Filter by Role</label>
                                    <select name="role" id="role" class="form-select shadow-none">
                                        <option value="">--- Select ---</option>
                                        @foreach ($role as $rol)
                                        <option value="{{$rol->id}}">{{$rol->name}}</option>
                                        @endforeach

                                    </select>
                                </div> --}}
                            </div>
                            <div class="col-lg-3">
                                <!-- <div class="o-f-inp">
                                                        <label for="">Filter by Status</label>
                                                        <select name="status" id="status" class="form-select shadow-none">
                                                            <option value="">--- Select ---</option>
                                                            <option value="1">Active</option>
                                                            <option value="0">Inactive</option>

                                                        </select>
                                                    </div> -->
                            </div>
                            @can('dealer.create')
                                <div class="col-lg-3 ms-auto">
                                    <a href="{{ route('admin.dealer.create') }}" class="add-btn">Add</a>
                                </div>
                            @endcan
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class=" mt-3 table-container">
                                <div class="row justify-content-end">
                                    <div class="col-lg-7">
                                        <div class="entry-select">
                                            <p>Showing</p>
                                            <form method="GET" action="{{ route('admin.manage.dealer') }}">
                                                <select name="entries" id="entries" class="form-select shadow-none"
                                                    onchange="this.form.submit()">
                                                    <option value="10" {{ request('entries') == '10' ? 'selected' : '' }}>10
                                                    </option>
                                                    <option value="20" {{ request('entries') == '20' ? 'selected' : '' }}>20
                                                    </option>
                                                    <option value="50" {{ request('entries') == '50' ? 'selected' : '' }}>50
                                                    </option>
                                                </select>
                                            </form>
                                            <p>Entries</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="table-search">
                                            <label for="search">Search </label>
                                            <input type="text" class="form-control shadow-none">
                                        </div>
                                    </div>
                                </div>
                                <div class="table-over">
                                    <table class="align-middle mb-0 table table-striped  tble-cstm mt-3">
                                        <thead>

                                            <tr>
                                                {{-- <th class="text-center">
                                                    <input type="checkbox">
                                                </th> --}}
                                                <th class="text-center">SL NO </th>
                                                <th class="text-center">User Id</th>
                                                <th class="text-center"> Name</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center ">Mobile Number </th>
                                                <!--<th class="text-center ">Role </th>-->
                                                <!--<th class="text-center ">Assigned Companies</th>-->
                                                <th class="text-center">STATUS</th>
                                                <th class="text-center">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody id="staffTableBody">
                                            @forelse($dealer as $deal)
                                                <tr>
                                                    {{-- <td>
                                                        <input type="checkbox">
                                                    </td> --}}
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $deal->user_id }}</td>
                                                    <td>{{ $deal->name }}</td>
                                                    <td>{{ $deal->email }}</td>
                                                    <td class="nowrap">{{ $deal->phone }}</td>

                                                    <td>
                                                        @if ($deal->status == 1)
                                                            <span class="status-green">Active</span>
                                                        @else
                                                            <span class="status-yellow">InActive</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <div class="action-btns">
                                                            @canany(['dealer.edit', 'dealer.delete'])
                                                                @can('dealer.edit')
                                                                    <a href="{{ route('admin.dealer.edit', $deal->id) }}"
                                                                        class="btn-edit">
                                                                        <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                                                                @endcan
                                                                @can('dealer.delete')
                                                                    <a href="#!" class="btn-delete"
                                                                        onclick="confirmDelete({{ $deal->id }})"> <i
                                                                            class="fa-solid fa-trash" aria-hidden="true"></i></a>
                                                                    <form id="delete-dealer-{{ $deal->id }}"
                                                                        action="{{ route('admin.dealer.destroy', $deal->id) }}"
                                                                        method="POST" style="display: none;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                    </form>
                                                                @endcan
                                                            @else
                                                                <span class="text-muted">No access</span>
                                                            @endcanany
                                                        </div>
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
                                <div class="table_bottom">
                                    <p>Showing {{ $dealer->firstItem() }} to {{ $dealer->lastItem() }} of
                                        {{ $dealer->total() }} entries
                                    </p>
                                    <div class="table-pagination">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination">
                                                <li class="page-item {{ $dealer->onFirstPage() ? 'disabled' : '' }}">
                                                    <a class="page-link" href="{{ $dealer->previousPageUrl() ?? '#' }}">
                                                        Prev
                                                    </a>
                                                </li>
                                                @foreach ($dealer->getUrlRange(1, $dealer->lastPage()) as $page => $url)
                                                    <li class="page-item {{ $dealer->currentPage() == $page ? 'active' : '' }}">
                                                        <a class="page-link {{ $dealer->currentPage() == $page ? 'page_active' : '' }}"
                                                            href="{{ $url }}">
                                                            {{ $page }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                                <li class="page-item {{ $dealer->hasMorePages() ? '' : 'disabled' }}">
                                                    <a class="page-link" href="{{ $dealer->nextPageUrl() ?? '#' }}">
                                                        Next
                                                    </a>
                                                </li>

                                            </ul>
                                        </nav>
                                    </div>
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

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.getElementById('delete-dealer-' + id);
                    if (form) {
                        form.submit();
                    } else {
                        Swal.fire('Error', 'Delete form not found!', 'error');
                    }
                }
            });
        }
    </script>

    <script>
        $(document).ready(function () {
            function fetchFilteredData() {
                let query = $('#searchInput').val();
                let role = $('#role').val();
                let status = $('#status').val();
                let entries = $('#entriesSelect').val();


                $.ajax({
                    url: "{{ route('admin.manage.dealer') }}",
                    type: "GET",
                    data: {
                        search: query,
                        role: role,
                        status: status,
                        entries: entries
                    },
                    dataType: "json",
                    success: function (data) {
                        let newContent = $(data.html).find("#staffTableBody").html();
                        $("#staffTableBody").html(newContent);
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                    }
                });
            }

            $('#searchInput,#role,#status').on('keyup change', function () {
                fetchFilteredData();
            });

            $('#entriesSelect').on('change', function () {
                fetchFilteredData();
            });
        });
    </script>
    <script>
        document.getElementById('resetBtn').addEventListener('click', function () {
            document.getElementById('category').value = '';
            document.getElementById('status').value = '';

            document.getElementById('searchForm').submit(); // reload with empty filters
        });
    </script>
@endsection