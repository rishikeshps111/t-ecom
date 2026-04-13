@extends('admin.layouts.app')
@section('content')
    <style>
        .group-name {
            display: inline-block;
            font-size: 11px;
            padding: 2px 6px;
            margin: 2px;
            background: #50779e;
            border-radius: 4px;
            color: #ffffff;
        }

        table tr th {
            white-space: nowrap;
        }
    </style>
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Planner</h3>
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
                            <div class="col-lg-3 ms-auto">
                                <a href="{{ route('admin.planner.create') }}" class="add-btn">Add</a>
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
                                            <form method="GET" action="{{ route('admin.manage.planner') }}">
                                                <select name="entries" id="entriesSelect" class="form-select shadow-none"
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
                                            <input type="text" class="form-control shadow-none" id="searchInput"
                                                name="search" placeholder="Search planner...">
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
                                                <th class="text-center">Planner Code</th>
                                                <th class="text-center">Total Group</th>
                                                <th class="text-center">Planner Name</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center ">Phone </th>
                                                <!-- <th class="text-center ">Role </th>
                                                                                                                                                                                <th class="text-center ">Assigned Companies</th> -->
                                                <th class="text-center">STATUS</th>
                                                <th class="text-center">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody id="staffTableBody">
                                            @forelse($planner as $plan)
                                                <tr>
                                                    {{-- <td>
                                                        <input type="checkbox">
                                                    </td> --}}
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $plan->user_code ?? '-' }}</td>
                                                    <td>
                                                        @foreach ($plan->totalGroups as $totalGroup)
                                                            <span class="group-name">{{ $totalGroup->customer_name }}</span>
                                                        @endforeach
                                                    </td>

                                                    <td>{{ $plan->name }}</td>
                                                    <td>{{ $plan->email ?? '-' }}</td>
                                                    <td class="nowrap">{{ $plan->phone ?? '-' }}</td>
                                                    {{-- <td>{{ $plan->roles->first()->name ?? '-' }}</td>
                                                    <td>-</td> --}}
                                                    <td>
                                                        @if ($plan->is_locked)
                                                            <span class="badge bg-danger">Locked</span>
                                                        @elseif ($plan->status == 1)
                                                            <span class="status-green">Active</span>
                                                        @else
                                                            <span class="status-red">Inactive</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm p-0" type="button"
                                                                id="actionDropdown{{ $plan->id }}" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="fa-solid fa-ellipsis-vertical"
                                                                    style="color: black;"></i>
                                                            </button>

                                                            <ul class="dropdown-menu dropdown-menu-end"
                                                                aria-labelledby="actionDropdown{{ $plan->id }}">
                                                                <!-- Edit -->
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.planner.edit', $plan->id) }}">
                                                                        <i class="fa-solid fa-pen-to-square me-2"></i> Edit
                                                                    </a>
                                                                </li>

                                                                <!-- Delete -->
                                                                <li>
                                                                    <a class="dropdown-item text-danger" href="#"
                                                                        onclick="event.preventDefault(); confirmDelete({{ $plan->id }});">
                                                                        <i class="fa-solid fa-trash me-2"></i> Delete
                                                                    </a>
                                                                    <form id="delete-planner-{{ $plan->id }}"
                                                                        action="{{ route('admin.planner.destroy', $plan->id) }}"
                                                                        method="POST" style="display: none;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                    </form>
                                                                </li>

                                                                @if ($plan->is_locked)
                                                                    <li>
                                                                        <a class="dropdown-item text-success" href="#!"
                                                                            onclick="unlockUser({{ $plan->id }})">
                                                                            <i class="fa-solid fa-lock-open me-2"></i>
                                                                            Unlock User
                                                                        </a>
                                                                    </li>
                                                                @else
                                                                    <li>
                                                                        <a class="dropdown-item text-warning" href="#!"
                                                                            onclick="lockUser({{ $plan->id }})">
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
                                    <p>Showing {{ $planner->firstItem() }} to {{ $planner->lastItem() }} of
                                        {{ $planner->total() }} entries
                                    </p>
                                    <div class="table-pagination">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination">
                                                <li class="page-item {{ $planner->onFirstPage() ? 'disabled' : '' }}">
                                                    <a class="page-link" href="{{ $planner->previousPageUrl() ?? '#' }}">
                                                        Prev
                                                    </a>
                                                </li>
                                                @foreach ($planner->getUrlRange(1, $planner->lastPage()) as $page => $url)
                                                    <li
                                                        class="page-item {{ $planner->currentPage() == $page ? 'active' : '' }}">
                                                        <a class="page-link {{ $planner->currentPage() == $page ? 'page_active' : '' }}"
                                                            href="{{ $url }}">
                                                            {{ $page }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                                <li class="page-item {{ $planner->hasMorePages() ? '' : 'disabled' }}">
                                                    <a class="page-link" href="{{ $planner->nextPageUrl() ?? '#' }}">
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
                    let form = document.getElementById('delete-planner-' + id);
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
                    url: "{{ route('admin.manage.planner') }}",
                    type: "GET",
                    data: {
                        search: query,
                        role: role,
                        status: status,
                        entries: entries
                    },
                    success: function (data) {
                        let newContent = $(data.html).find("#staffTableBody").html();
                        $("#staffTableBody").html(newContent);
                    }
                });
            }

            $('#searchInput, #role, #status').on('keyup change', function () {
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