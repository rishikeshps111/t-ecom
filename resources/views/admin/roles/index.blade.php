@extends('admin.layouts.app')
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Roles</h3>
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

                    <div class="row">
                        {{-- <div class="col-lg-12">
                            <div class="top-choose-box">
                                <form method="POST"
                                    action="{{ isset($role) ? route('admin.roles.update', $role->id) : route('admin.roles.store') }}">
                                    @csrf
                                    @if (isset($role))
                                        @method('PUT')
                                    @endif
                                    <div class="row">

                                        <div class="col-lg-12 o-f-inp mb-2">
                                            <label for="search" class="form-label m-0">Role Name <span
                                                    class="text-danger">*</span> @error('name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </label>
                                            <input type="text" class="form-control shadow-none" name="name"
                                                value="{{ old('name', $role->name ?? '') }}">
                                        </div>
                                        <div class="col-lg-12 ">
                                            <button type="submit"
                                                class="submit-btn mx-auto">{{ isset($role) ? 'Update' : 'Submit' }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div> --}}
                        <div class="col-lg-12">
                            <div class=" mt-3 table-container">
                                <div class="row justify-content-end">
                                    <div class="col-lg-7">
                                        <div class="entry-select">
                                            <p>Showing</p>
                                            <form method="GET" action="{{ route('admin.manage.roles') }}">
                                                <select name="entries" id="entries" class="form-select shadow-none"
                                                    onchange="this.form.submit()">
                                                    <option value="10"
                                                        {{ request('entries') == '10' ? 'selected' : '' }}>10
                                                    </option>
                                                    <option value="20"
                                                        {{ request('entries') == '20' ? 'selected' : '' }}>20
                                                    </option>
                                                    <option value="50"
                                                        {{ request('entries') == '50' ? 'selected' : '' }}>50
                                                    </option>
                                                </select>
                                            </form>
                                            <p>Entries</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        <!-- <div class="table-search">
                                                                            <label for="search">Search </label>
                                                                            <input type="text" class="form-control shadow-none">
                                                                        </div> -->
                                    </div>
                                </div>
                                <div class="table-over">
                                    <table class="align-middle mb-0 table table-striped  tble-cstm mt-3">
                                        <thead>

                                            <tr>
                                                <th class="text-center"># </th>
                                                <th class="text-center"> Name</th>
                                                <th class="text-center">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody id="staffTableBody">
                                            @forelse($roles as $role)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $role->name }}</td>

                                                    <td>
                                                        <div class="action-btns">
                                                            {{-- <a href="{{ route('admin.roles.edit', $role->id) }}"
                                                                class="btn-edit"> <i class="fa-solid fa-pen-to-square"
                                                                    aria-hidden="true"></i></a> --}}

                                                            {{-- <a href="#!" class="btn-delete"
                                                                onclick="confirmDelete({{ $role->id }})"> <i
                                                                    class="fa-solid fa-trash" aria-hidden="true"></i></a>
                                                            <form id="delete-roles-{{ $role->id }}"
                                                                action="{{ route('admin.roles.destroy', $role->id) }}"
                                                                method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form> --}}
                                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                                data-bs-target="#permissionModal{{ $role->id }}">
                                                                Assign Permissions
                                                            </button>
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
                                    <p>Showing {{ $roles->firstItem() }} to {{ $roles->lastItem() }} of
                                        {{ $roles->total() }} entries
                                    </p>
                                    <div class="table-pagination">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination">
                                                <li class="page-item {{ $roles->onFirstPage() ? 'disabled' : '' }}">
                                                    <a class="page-link" href="{{ $roles->previousPageUrl() ?? '#' }}">
                                                        Prev
                                                    </a>
                                                </li>
                                                @foreach ($roles->getUrlRange(1, $roles->lastPage()) as $page => $url)
                                                    <li
                                                        class="page-item {{ $roles->currentPage() == $page ? 'active' : '' }}">
                                                        <a class="page-link {{ $roles->currentPage() == $page ? 'page_active' : '' }}"
                                                            href="{{ $url }}">
                                                            {{ $page }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                                <li class="page-item {{ $roles->hasMorePages() ? '' : 'disabled' }}">
                                                    <a class="page-link" href="{{ $roles->nextPageUrl() ?? '#' }}">
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

    @foreach ($roles as $role)
        <div class="modal fade" id="permissionModal{{ $role->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <form action="{{ route('admin.roles.assign.permissions', $role->id) }}" method="POST">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title">
                                Assign Permissions to <strong>{{ $role->name }}</strong>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                @foreach ($groupedPermissions as $groupName => $groupPermissions)
                                    <div class="card mb-3">
                                        <div class="card-header text-danger">
                                            {{ $groupName ? ucfirst(str_replace('_', ' ', $groupName)) : 'General' }}
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach ($groupPermissions as $permission)
                                                    <div class="col-md-4 mb-2">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="permissions[]" value="{{ $permission->name }}"
                                                                {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                            <label class="form-check-label">
                                                                {{ strtoupper(str_replace(['_', '.'], ' ', $permission->name)) }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Save Permissions</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Cancel
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    @endforeach
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
                    let form = document.getElementById('delete-roles-' + id);
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
        $(document).ready(function() {
            function fetchFilteredData() {
                let query = $('#searchInput').val();
                let role = $('#role').val();
                let status = $('#status').val();
                let entries = $('#entriesSelect').val();


                $.ajax({
                    url: "{{ route('admin.manage.user') }}",
                    type: "GET",
                    data: {
                        search: query,
                        role: role,
                        status: status,
                        entries: entries
                    },
                    dataType: "json",
                    success: function(data) {
                        let newContent = $(data.html).find("#staffTableBody").html();
                        $("#staffTableBody").html(newContent);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                    }
                });
            }

            $('#searchInput,#role,#status').on('keyup change', function() {
                fetchFilteredData();
            });

            $('#entriesSelect').on('change', function() {
                fetchFilteredData();
            });
        });
    </script>
    <script>
        document.getElementById('resetBtn').addEventListener('click', function() {
            document.getElementById('category').value = '';
            document.getElementById('status').value = '';

            document.getElementById('searchForm').submit(); // reload with empty filters
        });
    </script>
@endsection
