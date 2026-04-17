@extends('admin.layouts.app')
@section('title')
    Work Order Documents
@endsection
@section('style')
    @include('admin.scripts.css')
    <style>
        .customer-detail-card {
            border: 1px solid #e6eaf0;
            border-radius: 14px;
            padding: 20px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
            margin-bottom: 16px;
        }

        .customer-detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }

        .customer-detail-label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .customer-detail-value {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            word-break: break-word;
        }
    </style>
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h3>Customer Work Order Documents</h3>
                    <a href="{{ route('admin.document-manger.index') }}" class="btn btn-danger">Back</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="customer-detail-card">
                        <div class="customer-detail-grid">
                            <div>
                                <div class="customer-detail-label">Customer ID</div>
                                <div class="customer-detail-value">{{ $company->company_code ?? $company->custom_company_id }}</div>
                            </div>
                            <div>
                                <div class="customer-detail-label">Customer Name</div>
                                <div class="customer-detail-value">{{ $company->company_name }}</div>
                            </div>
                            <div>
                                <div class="customer-detail-label">Email</div>
                                <div class="customer-detail-value">{{ $company->email_address ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="customer-detail-label">Phone</div>
                                <div class="customer-detail-value">{{ $company->mobile_no ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-lg-4">
                            <div class="o-f-inp">
                                <label for="filter-work-order">Filter by Work Order</label>
                                <select id="filter-work-order" class="form-select shadow-none search-select">
                                    <option value="">--- Select ---</option>
                                    @foreach ($workPlans as $workPlan)
                                        <option value="{{ $workPlan->id }}">{{ $workPlan->workplan_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="btn-top-filters justify-content-end">
                                <button type="button" class="btn-back-cs" id="reset-filters">Reset</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mt-3 table-container">
                                <div class="table-over">
                                    <table id="table" class="align-middle mb-0 table table-striped tble-cstm mt-3">
                                        <thead>
                                            <tr>
                                                <th class="text-center">SL NO</th>
                                                <th class="text-center">Work Order</th>
                                                <th class="text-center">Entity</th>
                                                <th class="text-center">Document Type</th>
                                                <th class="text-center">Uploaded On</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="previewDocumentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewDocumentTitle">View Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="previewDocumentBody"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('admin.scripts.script')
    <script>
        $(function () {
            let table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.document-manger.work-order-documents.index', $company) }}",
                    data: function (d) {
                        d.work_plan = $('#filter-work-order').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'work_order', name: 'work_order' },
                    { data: 'entity', name: 'entity' },
                    { data: 'type', name: 'type' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });

            $('#filter-work-order').on('keyup change', function () {
                table.draw();
            });

            $('#reset-filters').on('click', function () {
                $('#filter-work-order').val('').trigger('change');
                table.draw();
            });

            $(document).on('click', '.preview-document', function () {
                let fileUrl = $(this).data('file');
                let fileName = $(this).data('name') || 'Document';
                let extension = (fileName.split('.').pop() || '').toLowerCase();
                let previewHtml = '';

                if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'].includes(extension)) {
                    previewHtml =
                        `<img src="${fileUrl}" alt="${fileName}" class="img-fluid rounded w-100">`;
                } else if (extension === 'pdf') {
                    previewHtml =
                        `<iframe src="${fileUrl}" style="width:100%; height:75vh;" frameborder="0"></iframe>`;
                } else {
                    previewHtml = `
                                    <div class="text-center py-5">
                                        <p class="mb-3">Preview is not available for this file type.</p>
                                        <a href="${fileUrl}" target="_blank" class="btn btn-primary">Open Document</a>
                                    </div>
                                `;
                }

                //$('#previewDocumentTitle').text(fileName);
                $('#previewDocumentBody').html(previewHtml);
                $('#previewDocumentModal').modal('show');
            });

            $('#previewDocumentModal').on('hidden.bs.modal', function () {
                $('#previewDocumentTitle').text('View Document');
                $('#previewDocumentBody').html('');
            });
        });
    </script>
@endsection
