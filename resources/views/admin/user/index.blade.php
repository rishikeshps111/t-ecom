@extends('admin.layouts.app')
@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
    <style>
        .no-arrow::after {
            display: none !important;
        }

        .action-dropdown .dropdown-item {
            font-size: 13px;
        }
    </style>
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>
                        @if (request()->get('type') == 'production')
                            Production
                        @else
                            Management
                        @endif Staff
                    </h3>
                </div>
            </div>
        </div>
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <div class="top-btns-section">
                    <!-- <a href="#!">Assign Role</a> -->
                    {{-- <a href="#!">Assign Companies</a> --}}
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
                            <div class="col-lg-4 d-none">
                                <div class="o-f-inp">
                                    <label for="">Filter by Role</label>
                                    <select name="role" id="role" class="form-select shadow-none">
                                        <option value="">--- Select ---</option>
                                        @foreach ($role as $rol)
                                            <option value="{{ $rol->id }}">{{ $rol->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="o-f-inp">
                                    <label for="">Filter by Status</label>
                                    <select name="status" id="status" class="form-select shadow-none">
                                        <option value="">--- Select ---</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="o-f-inp">
                                    <label for="">Filter by Total Group</label>
                                    <select name="total_group" id="total_group"
                                        class="form-select shadow-none search-select">
                                        <option value="">--- Select ---</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 {{ request('type') == 'production' ? 'd-none' : '' }}">
                                <div class="o-f-inp">
                                    <label for="">Filter by Company</label>
                                    <select name="filter-company" id="filter-company"
                                        class="form-select shadow-none search-select">
                                        <option value="">--- Select ---</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 ">
                                <div class="btn-top-filters ">
                                    <button type="button" class="btn-back-cs" id="resetBtn">Reset</button>
                                    <a href="{{ route('admin.user.create') }}?type={{ request()->get('type', 'production') }}"
                                        class="add-btn mt-4">Add</a>
                                </div>

                            </div>

                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class=" mt-3 table-container">
                                <div class="row justify-content-end">
                                    <div class="col-lg-7">
                                        <div class="entry-select">
                                            <p>Showing</p>
                                            <form method="GET" action="{{ route('admin.manage.user') }}">
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
                                                <th class="text-center nowrap">SL NO </th>
                                                <th class="text-center">User Code</th>
                                                <th class="text-center"> Name</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center ">Mobile Number </th>
                                                {{-- <th class="text-center ">Role </th> --}}
                                                <!-- <th class="text-center ">Assigned Companies</th> -->
                                                @if (request('type') == 'production')
                                                    <th class="text-center ">Companies </th>
                                                @endif
                                                <th class="text-center">STATUS</th>
                                                <th class="text-center">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody id="staffTableBody">
                                            @forelse($users as $user)
                                                <tr>
                                                    {{-- <td>
                                                        <input type="checkbox">
                                                    </td> --}}
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $user->user_code ?? '-' }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td class="nowrap">{{ $user->phone }}</td>
                                                    {{-- <td>{{ $user->roles->first()->name ?? '-' }}</td> --}}
                                                    <!-- <td>-</td> -->
                                                    @if (request('type') == 'production')
                                                        <td class="gap-td">
                                                            @forelse($user->customerUsers as $customer)
                                                                <span
                                                                    class="badge bg-primary">{{ $customer->company_name ?? 'N/A' }}</span>
                                                            @empty
                                                                <span class="text-muted">—</span>
                                                            @endforelse
                                                        </td>
                                                    @endif
                                                    <td>
                                                        @if ($user->is_locked)
                                                            <span class="badge bg-danger">Locked</span>
                                                        @elseif ($user->is_active == 1)
                                                            <span class="status-green">Active</span>
                                                        @else
                                                            <span class="status-red">Inactive</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <div class="dropdown action-dropdown">
                                                            <button class="btn btn-sm btn-light dropdown-toggle p-0 no-arrow"
                                                                type="button" data-bs-toggle="dropdown">
                                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                                            </button>

                                                            <ul class="dropdown-menu"
                                                                aria-labelledby="actionDropdown{{ $user->id }}">
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.user.edit', $user->id) }}?type={{ request()->get('type', 'production') }}">
                                                                        <i class="fa-solid fa-pen-to-square me-2"></i> Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item text-danger" href="#!"
                                                                        onclick="confirmDelete({{ $user->id }})">
                                                                        <i class="fa-solid fa-trash me-2"></i> Delete
                                                                    </a>
                                                                    <form id="delete-user-{{ $user->id }}"
                                                                        action="{{ route('admin.user.destroy', $user->id) }}"
                                                                        method="POST" style="display: none;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                    </form>
                                                                </li>
                                                                @if ($user->is_locked)
                                                                    <li>
                                                                        <a class="dropdown-item text-success" href="#!"
                                                                            onclick="unlockUser({{ $user->id }})">
                                                                            <i class="fa-solid fa-lock-open me-2"></i>
                                                                            Unlock User
                                                                        </a>
                                                                    </li>
                                                                @else
                                                                    <li>
                                                                        <a class="dropdown-item text-warning" href="#!"
                                                                            onclick="lockUser({{ $user->id }})">
                                                                            <i class="fa-solid fa-lock me-2"></i> Lock User
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            </ul>
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
                                    <p>Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of
                                        {{ $users->total() }} entries
                                    </p>
                                    <div class="table-pagination">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination">
                                                <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                                                    <a class="page-link" href="{{ $users->previousPageUrl() ?? '#' }}">
                                                        Prev
                                                    </a>
                                                </li>
                                                @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                                    <li class="page-item {{ $users->currentPage() == $page ? 'active' : '' }}">
                                                        <a class="page-link {{ $users->currentPage() == $page ? 'page_active' : '' }}"
                                                            href="{{ $url }}">
                                                            {{ $page }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                                <li class="page-item {{ $users->hasMorePages() ? '' : 'disabled' }}">
                                                    <a class="page-link" href="{{ $users->nextPageUrl() ?? '#' }}">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $('.search-select').select2()

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
                    let form = document.getElementById('delete-user-' + id);
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
                let total_group = $('#total_group').val();
                let company = $('#filter-company').val();
                let entries = $('#entriesSelect').val();

                const urlParams = new URLSearchParams(window.location.search);
                const type = urlParams.get('type') || 'management';

                $.ajax({
                    url: "{{ route('admin.manage.user') }}",
                    type: "GET",
                    data: {
                        search: query,
                        role: role,
                        status: status,
                        entries: entries,
                        type: type
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

            $('#searchInput,#role,#status,#filter-company,#total_group').on('keyup change', function () {
                fetchFilteredData();
            });

            $('#entriesSelect').on('change', function () {
                fetchFilteredData();
            });

            document.getElementById('resetBtn').addEventListener('click', function () {
                document.getElementById('role').value = '';
                document.getElementById('status').value = '';
                $('#total_group').val('').trigger('change');
                $('#filter-company').val('').trigger('change');
                fetchFilteredData();
            });
        });
    </script>
    <script>
        function lockUser(userId) {
            Swal.fire({
                title: 'Lock User',
                text: 'Please provide a reason for locking this user',
                input: 'textarea',
                inputPlaceholder: 'Enter lock reason...',
                inputAttributes: {
                    maxlength: 255
                },
                showCancelButton: true,
                confirmButtonText: 'Lock',
                confirmButtonColor: '#d33',
                preConfirm: (reason) => {
                    if (!reason) {
                        Swal.showValidationMessage('Lock reason is required');
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("{{ url('admin/users') }}/" + userId + "/lock", {
                        _token: "{{ csrf_token() }}",
                        reason: result.value
                    }, function () {
                        Swal.fire('Locked!', 'User has been locked.', 'success')
                            .then(() => location.reload());
                    });
                }
            });
        }

        function unlockUser(userId) {
            Swal.fire({
                title: 'Unlock User?',
                text: 'This user will regain system access',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, unlock',
                confirmButtonColor: '#28a745'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("{{ url('admin/users') }}/" + userId + "/unlock", {
                        _token: "{{ csrf_token() }}"
                    }, function () {
                        Swal.fire('Unlocked!', 'User access restored.', 'success')
                            .then(() => location.reload());
                    });
                }
            });
        }
    </script>
@endsection