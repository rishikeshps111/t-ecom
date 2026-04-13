@extends('admin.layouts.app')
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Business Categories</h3>
                </div>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <!-- <div class="col-lg-4">
                                                                                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                                                                                                            <i class="fa fa-upload"></i> Import CSV
                                                                                                        </button>
                                                                                                    </div> -->
                    <div class="row">
                        <form method="GET" id="searchForm">
                            <div class="row">
                                <div class="col-lg-3 mb-3">
                                    <div class="o-f-inp">
                                        <label for="">Search by Category Name</label>
                                        <input type="text" name="category" id="category" class="form-control shadow-none">
                                    </div>
                                </div>
                                <div class="col-lg-3 mb-3">
                                    <div class="o-f-inp">
                                        <label for="">Filter by Status</label>
                                        <select name="status" id="status" class="form-select shadow-none">
                                            <option value="">--- Select ---</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 mb-3 d-flex align-items-end btn-w-cs">
                                    <button type="button" class="reset-filter-btn" id="resetBtn">Reset</button>
                                </div>
                                <div class="col-lg-3 d-flex align-items-end">
                                    <a href="{{ route('admin.category.create') }}" class="add-btn">Add Category</a>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class=" mt-3 table-container">
                                <div class="row justify-content-end">
                                    <div class="col-lg-7">
                                        <div class="entry-select">
                                            <p>Showing</p>
                                            <form method="GET" action="{{ route('admin.manage.category') }}">
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
                                        <!-- <div class="table-search">
                                                                                                                            <label for="search">Search </label>
                                                                                                                            <input type="text" class="form-control shadow-none">
                                                                                                                        </div> -->
                                    </div>
                                </div>
                                <div class="table-over">
                                    <table class="align-middle mb-0 table table-striped  tble-cstm table-img-logo mt-3">
                                        <thead>

                                            <tr>
                                                <th class="text-center">SL NO</th>
                                                <th class="text-center">Code</th>
                                                <th class="text-center">Category NAME</th>
                                                <th class="text-center">STATUS</th>
                                                <th class="text-center">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody id="staffTableBody">
                                            @forelse($category as $cate)
                                                <tr class="max-width-250">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $cate->code }}</td>
                                                    <td>{{ $cate->name }}</td>
                                                    <td>
                                                        <div class="active-toggle">
                                                            <input type="checkbox" class="toggle-btn" data-id="{{ $cate->id }}"
                                                                {{ $cate->status ? 'checked' : '' }}
                                                                @cannot('business-category.edit') disabled @endcannot>
                                                            <span>{{ $cate->status ? 'Active' : 'Inactive' }}</span>
                                                        </div>

                                                    </td>

                                                    <td>
                                                        <div class="action-btns">
                                                            <a href="{{ route('admin.category.edit', $cate->id) }}"
                                                                class="btn-edit"> <i class="fa-solid fa-pen-to-square"
                                                                    aria-hidden="true"></i></a>
                                                            <a href="#!" class="btn-delete"
                                                                onclick="confirmDelete({{ $cate->id }})"> <i
                                                                    class="fa-solid fa-trash" aria-hidden="true"></i></a>
                                                            <form id="delete-category-{{ $cate->id }}"
                                                                action="{{ route('admin.category.destroy', $cate->id) }}"
                                                                method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
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
                                    <p>Showing {{ $category->firstItem() }} to {{ $category->lastItem() }} of
                                        {{ $category->total() }} entries
                                    </p>
                                    <div class="table-pagination">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination">
                                                <li class="page-item {{ $category->onFirstPage() ? 'disabled' : '' }}">
                                                    <a class="page-link" href="{{ $category->previousPageUrl() ?? '#' }}">
                                                        Prev
                                                    </a>
                                                </li>
                                                @foreach ($category->getUrlRange(1, $category->lastPage()) as $page => $url)
                                                    <li
                                                        class="page-item {{ $category->currentPage() == $page ? 'active' : '' }}">
                                                        <a class="page-link {{ $category->currentPage() == $page ? 'page_active' : '' }}"
                                                            href="{{ $url }}">
                                                            {{ $page }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                                <li class="page-item {{ $category->hasMorePages() ? '' : 'disabled' }}">
                                                    <a class="page-link" href="{{ $category->nextPageUrl() ?? '#' }}">
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
        $(document).on('change', '.toggle-btn', function () {
            let checkbox = $(this);
            let categoryId = checkbox.data('id');
            let status = checkbox.is(':checked') ? 1 : 0;
            let statusText = checkbox.next('span');

            $.ajax({
                url: "{{ route('admin.category.toggle-status') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: categoryId,
                    status: status
                },
                success: function (response) {
                    if (response.success) {
                        statusText.text(status ? 'Active' : 'Inactive');
                    } else {
                        alert('Failed to update status.');
                    }
                },
                error: function () {
                    alert('Something went wrong!');
                }
            });
        });
    </script>
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
                    let form = document.getElementById('delete-category-' + id);
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
                let category = $('#category').val();
                let status = $('#status').val();
                let entries = $('#entriesSelect').val();


                $.ajax({
                    url: "{{ route('admin.manage.category') }}",
                    type: "GET",
                    data: {
                        search: query,
                        category: category,
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

            $('#searchInput,#category,#status').on('keyup change', function () {
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