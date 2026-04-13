@extends('admin.layouts.app')
@section('content')

    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Manage Locations</h3>
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
            <div class="col-lg-12">
                <div class="top-choose-box">
                    <form method="POST"
                        action="{{ isset($location) ? route('admin.locations.update', $location->id) : route('admin.locations.store') }}">
                        @csrf
                        <div class="row">

                            <div class="col-lg-3 o-f-inp mb-2">
                                <label for="search" class="form-label m-0">Code <span class="text-danger">*</span>
                                    @error('code')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </label>
                                <input type="text" class="form-control shadow-none" name="code"
                                    value="{{ $code ?? $location->code }}">
                            </div>
                            <div class="col-lg-3 o-f-inp mb-2">
                                <label for="search" class="form-label m-0">Title <span class="text-danger">*</span>
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </label>
                                <input type="text" class="form-control shadow-none" name="name"
                                    value="{{ old('name', $location->name ?? '') }}">
                            </div>
                            <div class="col-lg-3 o-f-inp  mb-2">
                                <label for="search" class="form-label m-0">Choose State <span class="text-danger">*</span>
                                    @error('country')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </label>
                                <select class="form-select shadow-none " name="state" aria-label="Default select example">
                                    <option value="">---Select---</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}" {{ old('country', $location->state_id ?? '') == $state->id ? 'selected' : '' }}>
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 o-f-inp  mb-2">
                                <label for="search" class="form-label m-0">Status <span class="text-danger">*</span>
                                    @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </label>
                                <select class="form-select shadow-none " name="status" aria-label="Default select example">
                                    <option value="1" {{ old('status', $location->status ?? '') == '1' ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="0" {{ old('status', $location->status ?? '') == '0' ? 'selected' : '' }}>
                                        Inactive</option>

                                </select>
                            </div>
                            <div class="col-lg-12 ">
                                <button type="submit"
                                    class="submit-btn mx-auto">{{ isset($location) ? 'Update' : 'Submit' }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-12 my-3">
                <div class="main-table-container">
                    <div class="row">
                        <form method="GET" id="searchForm">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="o-f-inp">
                                        <label for="">Filter by State</label>
                                        <select name="state" id="state" class="form-select shadow-none">
                                            <option value="">--- Select ---</option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 d-flex align-items-end btn-w-cs">
                                    <button type="button" class="reset-filter-btn" id="resetBtn">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class=" mt-3 table-container">
                                <div class="row justify-content-between">
                                    <div class="col-lg-4">
                                        <div class="entry-select">
                                            <p>Showing</p>
                                            <form method="GET" action="{{ route('admin.manage.locations') }}">
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

                                    <div class="col-lg-4">
                                        <form method="GET" id="searchForm">
                                            <div class="table-search">
                                                <label for="search">Search by Title</label>
                                                <input type="text" class="form-control shadow-none" name="search"
                                                    value="{{ request('search') }}" id="searchInput">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-over">
                                    <table
                                        class="align-middle mb-0 table table-striped  tble-cstm table-img-logo mt-3 nowrap-th">
                                        <thead>

                                            <tr>
                                                <th class="text-center">SL NO</th>
                                                <th class="text-center">Code</th>
                                                <th class="text-center">Title</th>
                                                <th class="text-center">State</th>
                                                <th class="text-center">STATUS</th>
                                                <th class="text-center">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody id="staffTableBody">
                                            @forelse($locations as $location)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $location->code }}</td>
                                                    <td>{{ $location->name }}</td>
                                                    <td>{{ $location->state->name ?? '-' }}</td>
                                                    <td>
                                                        @if ($location->status == 1)
                                                            <span class="status-green">Active</span>
                                                        @else
                                                            <span class="status-red">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="action-btns">
                                                            <a href="{{ route('admin.locations.edit', $location->id) }}"
                                                                class="btn-edit"> <i class="fa-solid fa-pen-to-square"
                                                                    aria-hidden="true"></i></a>
                                                            <a href="#!" class="btn-delete"
                                                                onclick="confirmDelete({{ $location->id }})"> <i
                                                                    class="fa-solid fa-trash" aria-hidden="true"></i></a>
                                                            <form id="delete-locations-{{ $location->id }}"
                                                                action="{{ route('admin.locations.delete', $location->id) }}"
                                                                method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8">No Records Found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table_bottom">
                                    <p>Showing {{ $locations->firstItem() }} to {{ $locations->lastItem() }} of
                                        {{ $locations->total() }} entries
                                    </p>
                                    <div class="table-pagination">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination">
                                                <li class="page-item {{ $locations->onFirstPage() ? 'disabled' : '' }}">
                                                    <a class="page-link" href="{{ $locations->previousPageUrl() ?? '#' }}">
                                                        Prev
                                                    </a>
                                                </li>
                                                @foreach ($locations->getUrlRange(1, $locations->lastPage()) as $page => $url)
                                                    <li
                                                        class="page-item {{ $locations->currentPage() == $page ? 'active' : '' }}">
                                                        <a class="page-link {{ $locations->currentPage() == $page ? 'page_active' : '' }}"
                                                            href="{{ $url }}">
                                                            {{ $page }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                                <li class="page-item {{ $locations->hasMorePages() ? '' : 'disabled' }}">
                                                    <a class="page-link" href="{{ $locations->nextPageUrl() ?? '#' }}">
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
                    let form = document.getElementById('delete-locations-' + id);
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
                let entries = $('#entriesSelect').val();
                let state = $('#state').val();


                $.ajax({
                    url: "{{ route('admin.manage.locations') }}",
                    type: "GET",
                    data: {
                        search: query,
                        entries: entries,
                        state: state,
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

            $('#searchInput').on('keyup change', function () {
                fetchFilteredData();
            });

            $('#entriesSelect, #state').on('change', function () {
                fetchFilteredData();
            });

            $('#resetBtn').on('click', function () {
                $('#searchInput').val('');
                $('#state').val('');
                fetchFilteredData();
            });
        });
    </script>
@endsection