@extends('admin.layouts.app')
@section('title')
    Work Order Management
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Work Order Completed List</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-number">Search by Work Order Number</label>
                                <input type="text" id="filter-number" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label>From Date</label>
                                <input type="date" id="filter-from-date" class="form-control shadow-none">
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label>To Date</label>
                                <input type="date" id="filter-to-date" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-customer">Filter by Customer</label>
                                <select id="filter-customer" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-staff">Filter by Production Staff</label>
                                <select id="filter-staff" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    @foreach ($staffs as $staff)
                                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="o-f-inp">
                                <label for="filter-planner">Filter by Planner</label>
                                <select id="filter-planner" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    @foreach ($planners as $planner)
                                        <option value="{{ $planner->id }}">{{ $planner->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="btn-top-filters">
                                <button type="button" class="btn-back-cs" id="reset-filters">Reset</button>
                                <button type="button" id="exportSelected" class="btn-export-cs d-none">Export</button>
                                @can('wo.edit')
                                                            <a href="{{ route_with_query('admin.work-orders.create', [
                                        'customer_id' => $customerID ?? null,
                                    ]) }}" class="add-btn">Add</a>
                                @endcan
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class=" mt-3 table-container">
                                <div class="table-over">
                                    <table id="table" class="align-middle mb-0 table table-striped tble-cstm mt-3">
                                        <thead>
                                            <tr>
                                                <th class="text-center">SL NO </th>
                                                <th class="text-center">WO No</th>
                                                <th class="text-center">WO Date</th>
                                                <th class="text-center">Customer</th>
                                                <th class="text-center">Total Group</th>
                                                <th class="text-center ">Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add Document Modal -->
    <div class="modal fade" id="addDocumentModal" tabindex="-1" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="post" action="" id="addDocumentForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDocumentModalLabel">Add Document</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="name" class="form-label">Document Title</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="col-lg-6">
                                <label for="entity" class="form-label">Type</label>
                                <select name="entity" id="entity" class="form-select" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="General">General</option>
                                    <option value="Business">Business</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>

                            <div class="col-lg-6">
                                <label for="type" class="form-label">Document Type</label>
                                <select name="type" id="type" class="form-select" required>
                                    <option value="">-- Select Document Type --</option>
                                    @foreach ($documentTypes as $type)
                                        <option value="{{ $type->type }}">
                                            {{ $type->type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6">
                                <label for="year" class="form-label">Year</label>
                                <input type="text" name="year" id="year" class="form-control" required>
                            </div>

                            <div class="col-lg-6">
                                <label for="file" class="form-label">Upload File</label>
                                <input type="file" name="file" id="file" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Upload</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Note Modal -->
    <div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="post" action="" id="addNoteForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNoteModalLabel">Add Note</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="note_type_id" class="form-label">Note Type</label>
                                <select name="note_type_id" id="note_type_id" class="form-select" required>
                                    <option value="">-- Select Note Type --</option>
                                    @foreach ($noteTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->note }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="4"
                                    placeholder="Enter note description..." required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @include('admin.work-plan.modal.modal')
@endsection

@section('scripts')
    @include('admin.scripts.script')
    @include('admin.work-plan.js-two')
@endsection