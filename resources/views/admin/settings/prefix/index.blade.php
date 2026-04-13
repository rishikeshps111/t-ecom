@extends('admin.layouts.app')
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Manage Prefixes</h3>
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
                        action="{{ isset($prefix) ? route('admin.prefixes.update', $prefix->id) : route('admin.prefixes.store') }}">
                        @if (isset($prefix))
                            @method('PUT')
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 o-f-inp  mb-2">
                                <label for="search" class="form-label m-0">Module <span class="text-danger">*</span>
                                    @error('module')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </label>
                                <select class="form-select shadow-none search-select" name="module"
                                    aria-label="Default select example">
                                    <option value="">
                                        Select a module</option>
                                    <option value="user"
                                        {{ old('module', $prefix->module ?? '') == 'user' ? 'selected' : '' }}>
                                        User</option>
                                    <option value="document_manager"
                                        {{ old('module', $prefix->module ?? '') == 'document_manager' ? 'selected' : '' }}>
                                        Document Manager</option>
                                    <option value="dealer"
                                        {{ old('module', $prefix->module ?? '') == 'dealer' ? 'selected' : '' }}>
                                        Dealer</option>
                                    <option value="planner"
                                        {{ old('module', $prefix->module ?? '') == 'planner' ? 'selected' : '' }}>
                                        Planner</option>
                                    <option value="business_owner"
                                        {{ old('module', $prefix->module ?? '') == 'business_owner' ? 'selected' : '' }}>
                                        Business Owner</option>
                                    <option value="customer"
                                        {{ old('module', $prefix->module ?? '') == 'customer' ? 'selected' : '' }}>
                                        Customer
                                    </option>

                                    <option value="company"
                                        {{ old('module', $prefix->module ?? '') == 'company' ? 'selected' : '' }}>
                                        Company
                                    </option>

                                    <option value="item"
                                        {{ old('module', $prefix->module ?? '') == 'item' ? 'selected' : '' }}>
                                        Item
                                    </option>

                                    <option value="invoice"
                                        {{ old('module', $prefix->module ?? '') == 'invoice' ? 'selected' : '' }}>
                                        Invoice
                                    </option>

                                    <option value="quotation"
                                        {{ old('module', $prefix->module ?? '') == 'quotation' ? 'selected' : '' }}>
                                        Quotation
                                    </option>
                                    <option value="payment"
                                        {{ old('module', $prefix->module ?? '') == 'payment' ? 'selected' : '' }}>
                                        Payment
                                    </option>

                                </select>
                            </div>
                            <div class="col-lg-6 o-f-inp mb-2">
                                <label for="search" class="form-label m-0">Prefix <span class="text-danger">*</span>
                                    @error('prefix')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </label>
                                <input type="text" class="form-control shadow-none" name="prefix"
                                    value="{{ old('prefix', $prefix->prefix ?? '') }}">
                            </div>
                            <div class="col-lg-12 ">
                                <button type="submit"
                                    class="submit-btn mx-auto">{{ isset($prefix) ? 'Update' : 'Submit' }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-12 my-3">
                <div class="main-table-container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class=" mt-3 table-container">
                                <div class="row justify-content-between">
                                    <div class="col-lg-4">
                                        <div class="entry-select">
                                            <p>Showing</p>
                                            <form method="GET" action="{{ route('admin.prefixes.index') }}">
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
                                                <th class="text-center">Module</th>
                                                <th class="text-center">Prefix</th>
                                                <th class="text-center">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody id="staffTableBody">
                                            @forelse($records as $record)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ ucfirst(str_replace('_', ' ', $record->module)) }}</td>
                                                    <td>{{ $record->prefix }}</td>
                                                    <td>
                                                        <div class="action-btns">
                                                            <a href="{{ route('admin.prefixes.edit', $record->id) }}"
                                                                class="btn-edit"> <i class="fa-solid fa-pen-to-square"
                                                                    aria-hidden="true"></i></a>
                                                            <a href="#!" class="btn-delete"
                                                                onclick="confirmDelete({{ $record->id }})"> <i
                                                                    class="fa-solid fa-trash" aria-hidden="true"></i></a>
                                                            <form id="delete-locations-{{ $record->id }}"
                                                                action="{{ route('admin.prefixes.destroy', $record->id) }}"
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
                                    <p>Showing {{ $records->firstItem() }} to {{ $records->lastItem() }} of
                                        {{ $records->total() }} entries
                                    </p>
                                    <div class="table-pagination">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination">
                                                <li class="page-item {{ $records->onFirstPage() ? 'disabled' : '' }}">
                                                    <a class="page-link" href="{{ $records->previousPageUrl() ?? '#' }}">
                                                        Prev
                                                    </a>
                                                </li>
                                                @foreach ($records->getUrlRange(1, $records->lastPage()) as $page => $url)
                                                    <li
                                                        class="page-item {{ $records->currentPage() == $page ? 'active' : '' }}">
                                                        <a class="page-link {{ $records->currentPage() == $page ? 'page_active' : '' }}"
                                                            href="{{ $url }}">
                                                            {{ $page }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                                <li class="page-item {{ $records->hasMorePages() ? '' : 'disabled' }}">
                                                    <a class="page-link" href="{{ $records->nextPageUrl() ?? '#' }}">
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
        $(document).ready(function() {
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
                    success: function(data) {
                        let newContent = $(data.html).find("#staffTableBody").html();
                        $("#staffTableBody").html(newContent);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                    }
                });
            }

            $('#searchInput').on('keyup change', function() {
                fetchFilteredData();
            });

            $('#entriesSelect, #state').on('change', function() {
                fetchFilteredData();
            });

            $('#resetBtn').on('click', function() {
                $('#searchInput').val('');
                $('#state').val('');
                fetchFilteredData();
            });


        });

        $('.search-select').select2();
    </script>
@endsection
