@extends('admin.layouts.app')
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')

    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Customer Management</h3>
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
                            <div class="col-lg-3 d-none">
                                <div class="o-f-inp">
                                    <label for="">Filter by Date</label>
                                    <input type="date" name="register_date" id="register_date"
                                        class="form-control shadow-none">
                                </div>
                            </div>
                            <div class="col-lg-3 d-none">
                                <div class="o-f-inp">
                                    <label for="">Search by Phone</label>
                                    <input type="text" name="phone" id="phone" class="form-control shadow-none">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="o-f-inp">
                                    <label for="">Filter by Company Type</label>
                                    <select name="company_type_id" id="company_type_id"
                                        class="form-select shadow-none search-select">
                                        <option value="">--- Select ---</option>
                                        @foreach ($companyTypes as $companyType)
                                            <option value="{{ $companyType->id }}">{{ $companyType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 d-none">
                                <div class="o-f-inp">
                                    <label for="">Filter by Corp User</label>
                                    <select name="business_user_id" id="business_user_id"
                                        class="form-select shadow-none search-select">
                                        <option value="">--- Select ---</option>
                                        @foreach ($corpUsers as $corpUser)
                                            <option value="{{ $corpUser->id }}">{{ $corpUser->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="o-f-inp">
                                    <label for="">Filter by Status</label>
                                    <select name="status" id="status" class="form-select shadow-none">
                                        <option value="">--- Select ---</option>
                                        <option value="active">Active</option>
                                        <option value="draft">Draft</option>
                                        <option value="inactive">Inactive</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3 d-none">
                                <div class="o-f-inp">
                                    <label for="">Filter by Category</label>
                                    <select name="category_id" id="category_id" class="form-select shadow-none">
                                        <option value="">--- Select ---</option>
                                        @foreach ($categories as $record)
                                            <option value="{{ $record->id }}">{{ $record->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 d-none">
                                <div class="o-f-inp">
                                    <label for="">Filter by SubCategory</label>
                                    <select name="sub_category_id" id="sub_category_id" class="form-select shadow-none">
                                        <option value="">--- Select ---</option>
                                        @foreach ($subCategories as $record)
                                            <option value="{{ $record->id }}">{{ $record->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 ">
                                <div class="btn-top-filters ">
                                    <button type="button" class="btn-back-cs" id="resetBtn">Reset</button>
                                    <button type="button" id="exportSelected" class="submit-btn">Export</button>
                                </div>

                            </div>
                            <div class="col-lg-3 ms-auto mt-4">
                                <a href="{{ route('admin.company.create') }}" class="add-btn">Add</a>
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
                                            <form method="GET" action="{{ route('admin.manage.company') }}">
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
                                            <label for="search">Search</label>
                                            <input type="text" class="form-control shadow-none" name="search"
                                                id="searchInput" placeholder="Search company...">
                                        </div>
                                    </div>
                                </div>
                                <div class="table-over">
                                    <table id="table" class="align-middle mb-0 table table-striped  tble-cstm mt-3">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    <input type="checkbox" id="checkAll">
                                                </th>
                                                <th class="text-center nowrap">SL NO </th>
                                                <th class="text-center">Company ID</th>
                                                {{-- <th class="text-center nowrap"> Corp User</th> --}}
                                                <th class="text-center">Company Name</th>
                                                <th class="text-center">Type</th>
                                                <th class="text-center ">Email</th>
                                                <th class="text-center ">Phone</th>
                                                <th class="text-center">STATUS</th>
                                                <th class="text-center">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody id="staffTableBody">
                                            @forelse($company as $com)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="row-check" value="{{ $com->id }}">
                                                    </td>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $com->company_code }}</td>
                                                    {{-- <td>{{ $com->businessUser->name ?? '-' }}</td> --}}
                                                    <td>{{ $com->company_name ?? '-' }}</td>
                                                    <td>{{ $com->companyType->name ?? '-' }}</td>
                                                    <td>{{ $com->email_address ?? '-' }}</td>
                                                    <td class="nowrap">{{ $com->mobile_no ?? '-' }}</td>

                                                    <td>
                                                        @if ($com->status == 'active')
                                                            <span class="status-green">Active</span>
                                                        @elseif($com->status == 'draft')
                                                            <span class="status-orange">Draft</span>
                                                        @else
                                                            <span class="status-red">Inactive</span>
                                                        @endif
                                                    </td>


                                                    <td>
                                                        <div class="dropdown action-dropdown">
                                                            <button class="btn btn-link p-0 text-dark dropdown-toggle no-arrow"
                                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                                            </button>

                                                            <ul class="dropdown-menu dropdown-menu-end">

                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.company.view', $com->id) }}"
                                                                        target="_blank">
                                                                        <i class="fa-solid fa-eye me-2"></i> View
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.company.edit', $com->id) }}">
                                                                        <i class="fa-solid fa-pen-to-square me-2"></i> Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <button class="dropdown-item change-status"
                                                                        data-id="{{ $com->id }}">
                                                                        <i
                                                                            class="fa-solid fa-arrow-right me-2 text-primary"></i>
                                                                        Change Status
                                                                    </button>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item text-danger" href="#"
                                                                        onclick="confirmDelete({{ $com->id }})">
                                                                        <i class="fa-solid fa-trash me-2"></i> Delete
                                                                    </a>

                                                                    <form id="delete-company-{{ $com->id }}"
                                                                        action="{{ route('admin.company.destroy', $com->id) }}"
                                                                        method="POST" class="d-none">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                    </form>
                                                                </li>
                                                                <li>
                                                                    <hr class="dropdown-divider">
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.work-orders.index') }}?customer_id={{ $com->id }}">
                                                                        <i class="fa-solid fa-clipboard-list me-2"></i>
                                                                        WO
                                                                    </a>
                                                                </li>

                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.quotations.index') }}?customer_id={{ $com->id }}">
                                                                        <i class="fa-solid fa-file-invoice me-2"></i>
                                                                        QT
                                                                    </a>
                                                                </li>


                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.invoices.index') }}?customer_id={{ $com->id }}">
                                                                        <i class="fa-solid fa-receipt me-2"></i>
                                                                        INV
                                                                    </a>
                                                                </li>

                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.documents.index') }}?company_id={{ $com->id }}">
                                                                        <i class="fa-solid fa-file-lines me-2"></i>
                                                                        Docs
                                                                    </a>
                                                                </li>

                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.payments.index') }}?customer_id={{ $com->id }}">
                                                                        <i class="fa-solid fa-money-bill-wave me-2"></i>
                                                                        OR
                                                                    </a>
                                                                </li>

                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.messages.index') }}?customer_id={{ $com->id }}">
                                                                        <i class="fa-solid fa-comments me-2"></i>
                                                                        MSG
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.company.notes') }}?customer_id={{ $com->id }}">
                                                                        <i class="fa-solid fa-receipt me-2"></i>
                                                                        Notes
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.announcements.index') }}?customer_id={{ $com->id }}">
                                                                        <i class="fa-solid fa-bullhorn me-2"></i>
                                                                        Announcements
                                                                    </a>
                                                                </li>
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
                                    <p>Showing {{ $company->firstItem() }} to {{ $company->lastItem() }} of
                                        {{ $company->total() }} entries
                                    </p>
                                    <div class="table-pagination">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination">
                                                @php
                                                    $current = $company->currentPage();
                                                    $last = $company->lastPage();
                                                    $start = max(1, $current - 2);
                                                    $end = min($last, $current + 2);
                                                @endphp

                                                {{-- Prev --}}
                                                <li class="page-item {{ $company->onFirstPage() ? 'disabled' : '' }}">
                                                    <a class="page-link" href="{{ $company->previousPageUrl() ?? '#' }}">
                                                        Prev
                                                    </a>
                                                </li>

                                                {{-- First page --}}
                                                @if ($start > 1)
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $company->url(1) }}">1</a>
                                                    </li>
                                                    <li class="page-item disabled">
                                                        <span class="page-link">...</span>
                                                    </li>
                                                @endif

                                                {{-- Middle pages --}}
                                                @for ($page = $start; $page <= $end; $page++)
                                                    <li
                                                        class="page-item {{ $company->currentPage() == $page ? 'active' : '' }}">
                                                        <a class="page-link {{ $company->currentPage() == $page ? 'page_active' : '' }}"
                                                            href="{{ $company->url($page) }}">
                                                            {{ $page }}
                                                        </a>
                                                    </li>
                                                @endfor

                                                {{-- Last page --}}
                                                @if ($end < $last)
                                                    <li class="page-item disabled">
                                                        <span class="page-link">...</span>
                                                    </li>
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $company->url($last) }}">
                                                            {{ $last }}
                                                        </a>
                                                    </li>
                                                @endif

                                                {{-- Next --}}
                                                <li class="page-item {{ $company->hasMorePages() ? '' : 'disabled' }}">
                                                    <a class="page-link" href="{{ $company->nextPageUrl() ?? '#' }}">
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
    <div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Dynamic content here -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('.search-select').select2()
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
                    let form = document.getElementById('delete-company-' + id);
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
                let register_date = $('#register_date').val();
                let phone = $('#phone').val();
                let status = $('#status').val();
                let entries = $('#entriesSelect').val();
                let category_id = $('#category_id').val();
                let sub_category_id = $('#sub_category_id').val();
                let business_user_id = $('#business_user_id').val();
                let company_type_id = $('#company_type_id').val();



                $.ajax({
                    url: "{{ route('admin.manage.company') }}",
                    type: "GET",
                    data: {
                        search: query,
                        register_date: register_date,
                        phone: phone,
                        status: status,
                        entries: entries,
                        category_id: category_id,
                        sub_category_id: sub_category_id,
                        business_user_id: business_user_id,
                        company_type_id: company_type_id,

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

            $('#searchInput,#register_date,#status,#phone,#category_id,#sub_category_id,#business_user_id,#company_type_id')
                .on(
                    'keyup change',
                    function () {
                        fetchFilteredData();
                    });

            $('#entriesSelect').on('change', function () {
                fetchFilteredData();
            });

            document.getElementById('resetBtn').addEventListener('click', function () {
                document.getElementById('status').value = '';
                document.getElementById('category_id').value = '';
                document.getElementById('sub_category_id').value = '';
                document.getElementById('register_date').value = '';
                document.getElementById('phone').value = '';
                $('#business_user_id').val('').trigger('change');
                $('#company_type_id').val('').trigger('change');
                fetchFilteredData();
            });

            $(document).on('click', '.change-status', function () {
                let id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.company.status.view') }}",
                    method: 'GET',
                    data: {
                        id: id
                    },
                    success: function (response) {
                        $('#modalBody').html(response.html);
                        $('#modalTitle').text(response.title);
                        $('#formModal').modal('show');
                    },
                    error: function () {
                        alert('Failed to load status change form.');
                    }
                });
            });

            $(document).on('submit', '#commonForm', function (e) {
                e.preventDefault();

                let form = $(this);
                let url = form.attr('action');
                let method = form.find('input[name="_method"]').val() || 'POST';
                let formData = form.serialize();

                // Get status value (approved / cancelled)
                let status = form.find('[name="status"]').val();

                Swal.fire({
                    title: 'Are you sure to change the status?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, continue',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.find('.error-text').text('');

                        $.ajax({
                            url: url,
                            type: method,
                            data: formData,
                            success: function (response) {
                                $('#formModal').modal('hide');
                                fetchFilteredData();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            },
                            error: function (xhr) {
                                if (xhr.status === 422) {
                                    let errors = xhr.responseJSON.errors;
                                    $.each(errors, function (field, messages) {
                                        form.find('.' + field + '_error').text(
                                            messages[0]);
                                    });
                                } else {
                                    Swal.fire('Error', 'Something went wrong', 'error');
                                }
                            }
                        });
                    }
                });
            });


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
                    url: '{{ route('admin.company.export') }}',
                    type: 'POST',
                    data: {
                        ids: selectedIds,
                        _token: '{{ csrf_token() }}'
                    },
                    xhrFields: {
                        responseType: 'arraybuffer'
                    },
                    success: function (response, status, xhr) {

                        let filename = 'companies.csv';
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