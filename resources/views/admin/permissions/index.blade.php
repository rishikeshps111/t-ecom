@extends('admin.layouts.app')
@section('content')

<section class="section dashboard section-top-padding">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-title">
                <h3>Permissions</h3>
            </div>
        </div>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="main-table-container">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="top-choose-box">
                            <form method="POST" action="{{ isset($permission) ? route('admin.permissions.update', $permission->id) : route('admin.permissions.store') }}">
                                @csrf
                                 @if(isset($permission))
                                    @method('PUT')
                                @endif
                                <div class="row">

                                    <div class="col-lg-3 o-f-inp mb-2">
                                        <label for="search" class="form-label m-0">Permission Name <span class="text-danger">*</span> @error('name') <small class="text-danger">{{ $message }}</small> @enderror</label>
                                        <input type="text" class="form-control shadow-none" name="name" value="{{ old('name', $permission->name ?? '') }}">
                                    </div>
                                    <div class="col-lg-12 ">
                                        <button type="submit" class="submit-btn mx-auto">{{ isset($permission) ? 'Update' : 'Submit' }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class=" mt-3 table-container">
                            <div class="row justify-content-end">
                                <div class="col-lg-7">
                                    <div class="entry-select">
                                        <p>Showing</p>
                                        <form method="GET" action="{{route('admin.manage.permissions')}}">
                                            <select name="entries" id="entries" class="form-select shadow-none" onchange="this.form.submit()">
                                                <option value="10" {{ request('entries') == '10' ? 'selected' : '' }}>10</option>
                                                <option value="20" {{ request('entries') == '20' ? 'selected' : '' }}>20</option>
                                                <option value="50" {{ request('entries') == '50' ? 'selected' : '' }}>50</option>
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
                                        @forelse($permissions as $permission)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{ $permission->name}}</td>
                                            
                                            <td>
                                                <div class="action-btns">
                                                    <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="btn-edit"> <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>

                                                    <a href="#!" class="btn-delete" onclick="confirmDelete({{ $permission->id }})"> <i class="fa-solid fa-trash"
                                                            aria-hidden="true"></i></a>
                                                    <form id="delete-permissions-{{ $permission->id }}" action="{{route('admin.permissions.destroy',$permission->id)}}" method="POST" style="display: none;">
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
                                <p>Showing {{ $permissions->firstItem() }} to {{ $permissions->lastItem() }} of {{ $permissions->total() }} entries</p>
                                <div class="table-pagination">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <li class="page-item {{ $permissions->onFirstPage() ? 'disabled' : '' }}">
                                                <a class="page-link"
                                                    href="{{ $permissions->previousPageUrl() ?? '#' }}">
                                                    Prev
                                                </a>
                                            </li>
                                            @foreach ($permissions->getUrlRange(1, $permissions->lastPage()) as $page => $url)
                                            <li class="page-item {{ $permissions->currentPage() == $page ? 'active' : '' }}">
                                                <a class="page-link {{ $permissions->currentPage() == $page ? 'page_active' : '' }}"
                                                    href="{{ $url }}">
                                                    {{ $page }}
                                                </a>
                                            </li>
                                            @endforeach
                                            <li class="page-item {{ $permissions->hasMorePages() ? '' : 'disabled' }}">
                                                <a class="page-link"
                                                    href="{{ $permissions->nextPageUrl() ?? '#' }}">
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
                let form = document.getElementById('delete-permissions-' + id);
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
                url: "{{ route('admin.manage.permissions') }}",
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