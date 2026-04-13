@extends('admin.layouts.app')
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Items Management</h3>
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
                            <div class="col-lg-3 mb-3">
                                <div class="o-f-inp">
                                    <label for="">Filter by Type</label>
                                    <select name="company_type_id" id="company_type_id" class="form-select shadow-none">
                                        <option value="">--- Select ---</option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="o-f-inp">
                                    <label for="">Filter by Total Group</label>
                                    <select name="total_group_id" id="total_group_id" class="form-select shadow-none">
                                        <option value="">--- Select ---</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3 d-none">
                                <div class="o-f-inp">
                                    <label for="">Filter by Category</label>
                                    <select name="category" id="category" class="form-select shadow-none">
                                        <option value="">--- Select ---</option>
                                        @foreach ($category as $cate)
                                            <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3 d-none">
                                <div class="o-f-inp">
                                    <label for="">Filter by SubCategory</label>
                                    <select name="sub_category" id="sub_category" class="form-select shadow-none">
                                        <option value="">--- Select ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3 d-none">
                                <div class="o-f-inp">
                                    <label for="">Filter by Company</label>
                                    <select name="company_id" id="company_id" class="form-select shadow-none">
                                        <option value="">--- Select ---</option>
                                        @foreach ($companies as $cate)
                                            <option value="{{ $cate->id }}">{{ $cate->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3 d-none">
                                <div class="o-f-inp">
                                    <label for="">Filter by User</label>
                                    <select name="user_id" id="user_id" class="form-select shadow-none">
                                        <option value="">--- Select ---</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3 d-none">
                                <div class="o-f-inp">
                                    <label for="">Filter by Status</label>
                                    <select name="status" id="status" class="form-select shadow-none">
                                        <option value="">--- Select ---</option>
                                        <option value="1">Active</option>
                                        <option value="0">InActive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3 d-flex align-items-end btn-w-cs">
                                <button type="button" class="reset-filter-btn" id="resetBtn">Reset</button>
                                <button type="button" id="exportSelected" class="add-btn">Export</button>
                            </div>
                            <div class="col-lg-3 ms-auto mt-4">
                                <a href="{{ route('admin.item.create') }}" class="add-btn">Add Items</a>
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
                                            <form method="GET" action="{{ route('admin.manage.item') }}">
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
                                        <form method="GET" id="searchForm">
                                            <div class="table-search">
                                                <label for="search">Search by Item Name</label>
                                                <input type="text" class="form-control shadow-none" name="search"
                                                    value="{{ request('search') }}" id="searchInput">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-over">
                                    <table class="align-middle mb-0 table table-striped  tble-cstm mt-3" id="table">
                                        <thead>

                                            <tr>
                                                <th class="text-center">
                                                    <input type="checkbox" id="checkAll">
                                                </th>
                                                <th class="text-center nowrap">SL NO </th>
                                                <th class="text-center">Item Code</th>
                                                <th class="text-center nowrap">Item Name</th>
                                                <th class="text-center nowrap">Type</th>
                                                <th class="text-center nowrap">Total Group</th>
                                                <th class="text-center ">Status</th>
                                                <th class="text-center">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody id="staffTableBody">
                                            @forelse($items as $item)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="row-check" value="{{ $item->id }}">
                                                    </td>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->custom_item_id }}</td>
                                                    <td>{{ $item->item_name }}</td>
                                                    <td>{{ $item->companyType ? $item->companyType->name : 'N/A' }}</td>
                                                    <td>{{ $item->totalGroup ? $item->totalGroup->customer_name : 'N/A' }}</td>
                                                    <td>
                                                        @if ($item->status == 1)
                                                            <span class="status-green">Active</span>
                                                        @else
                                                            <span class="status-red">InActive</span>
                                                        @endif
                                                    </td>


                                                    <td>
                                                        <div class="dropdown text-center">
                                                            <a href="#" class="text-dark" id="actionDropdown{{ $item->id }}"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                                                <!-- 3-dot icon -->
                                                            </a>
                                                            <ul class="dropdown-menu"
                                                                aria-labelledby="actionDropdown{{ $item->id }}">
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.item.edit', $item->id) }}">
                                                                        <i class="fa-solid fa-pen-to-square me-2"></i> Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item text-danger" href="#!"
                                                                        onclick="confirmDelete({{ $item->id }})">
                                                                        <i class="fa-solid fa-trash me-2"></i> Delete
                                                                    </a>
                                                                    <form id="delete-item-{{ $item->id }}"
                                                                        action="{{ route('admin.item.destroy', $item->id) }}"
                                                                        method="POST" style="display: none;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="12">No Records Found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table_bottom">
                                    <p>Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of
                                        {{ $items->total() }} entries
                                    </p>
                                    <div class="table-pagination">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination">
                                                <li class="page-item {{ $items->onFirstPage() ? 'disabled' : '' }}">
                                                    <a class="page-link" href="{{ $items->previousPageUrl() ?? '#' }}">
                                                        Prev
                                                    </a>
                                                </li>
                                                @foreach ($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                                                    <li class="page-item {{ $items->currentPage() == $page ? 'active' : '' }}">
                                                        <a class="page-link {{ $items->currentPage() == $page ? 'page_active' : '' }}"
                                                            href="{{ $url }}">
                                                            {{ $page }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                                <li class="page-item {{ $items->hasMorePages() ? '' : 'disabled' }}">
                                                    <a class="page-link" href="{{ $items->nextPageUrl() ?? '#' }}">
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
                    let form = document.getElementById('delete-item-' + id);
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
                let sub_category = $('#sub_category').val();
                let status = $('#status').val();
                let entries = $('#entriesSelect').val();
                let user_id = $('#user_id').val();
                let company_id = $('#company_id').val();
                let company_type_id = $('#company_type_id').val();
                let total_group_id = $('#total_group_id').val();



                $.ajax({
                    url: "{{ route('admin.manage.item') }}",
                    type: "GET",
                    data: {
                        search: query,
                        category: category,
                        sub_category: sub_category,
                        status: status,
                        entries: entries,
                        user_id: user_id,
                        company_id: company_id,
                        company_type_id: company_type_id,
                        total_group_id: total_group_id,
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

            $('#searchInput,#category,#status,#sub_category,#user_id,#company_id, #company_type_id, #total_group_id').on('keyup change', function () {
                fetchFilteredData();
            });

            $('#entriesSelect').on('change', function () {
                fetchFilteredData();
            });

            document.getElementById('resetBtn').addEventListener('click', function () {
                document.getElementById('category').value = '';
                document.getElementById('status').value = '';
                document.getElementById('user_id').value = '';
                document.getElementById('company_id').value = '';
                document.getElementById('company_type_id').value = '';
                document.getElementById('total_group_id').value = '';

                document.getElementById('searchForm').submit(); // reload with empty filters
            });
        });
    </script>
    <script></script>

    <script>
        $('#category').on('change', function () {
            let categoryId = $(this).val();

            $('#sub_category').html('<option value="">Loading...</option>');

            if (categoryId) {
                $.ajax({
                    url: "{{ route('admin.get.subcategories') }}",
                    type: "GET",
                    data: {
                        category_id: categoryId
                    },
                    success: function (res) {
                        $('#sub_category').empty().append('<option value="">--- Select ---</option>');

                        $.each(res, function (key, value) {
                            $('#sub_category').append(
                                `<option value="${value.id}">${value.name}</option>`
                            );
                        });
                    }
                });
            } else {
                $('#sub_category').html('<option value="">--- Select ---</option>');
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#checkAll').on('click', function () {
                $('.row-check').prop('checked', this.checked);
            });


            $('#exportSelected').on('click', function () {
                let selectedIds = [];

                $('#table tbody input.row-check:checked').each(function () {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops!',
                        text: 'Please select at least one row to export.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Exporting...',
                    text: 'Please wait while we prepare your file.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '{{ route('admin.item.export') }}',
                    type: 'POST',
                    data: {
                        ids: selectedIds,
                        _token: '{{ csrf_token() }}'
                    },
                    xhrFields: {
                        responseType: 'arraybuffer'
                    },
                    success: function (response, status, xhr) {

                        let filename = 'items.csv';
                        let disposition = xhr.getResponseHeader('Content-Disposition');

                        if (disposition && disposition.indexOf('attachment') !== -1) {
                            let match = disposition.match(/filename="(.+)"/);
                            if (match && match.length === 2) filename = match[1];
                        }

                        let blob = new Blob([response]);
                        let link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    },
                    complete: function () {
                        $('#checkAll').prop('checked', false);
                        $('#table tbody input.row-check').prop('checked', false);
                        Swal.close();
                    },
                    error: function () {
                        alert('Something went wrong while exporting.');
                    }
                });
            });


        });
    </script>
@endsection