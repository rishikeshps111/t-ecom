@extends('admin.layouts.app')
@section('title')
    All Files
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

        .selection-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            margin-bottom: 16px;
            border: 1px solid #e6eaf0;
            border-radius: 12px;
            background: #fff;
            flex-wrap: wrap;
        }

        .selection-summary {
            font-weight: 600;
            color: #374151;
        }
    </style>
@endsection
@section('content')
    @php
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
    @endphp

    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h3>Customer File Manager</h3>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.document-manger.index') }}" class="btn btn-danger">Back</a>
                    </div>
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
                                <div class="customer-detail-value">
                                    {{ $company->company_code ?? $company->custom_company_id }}</div>
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

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('admin.document-manger.all-files.export-selected', $company) }}" method="POST"
                        id="selected-files-form">
                        @csrf

                        <div class="selection-toolbar">
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" id="select-all-files">
                                    <label class="form-check-label" for="select-all-files">
                                        Select All
                                    </label>
                                </div>
                                <div class="selection-summary">
                                    Selected Files: <span id="selected-files-count">0</span>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success" id="zip-selected-btn" disabled>
                                    Download Selected ZIP
                                </button>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h5 class="mb-3">Customer Documents</h5>
                            <div class="table-over">
                                <table class="align-middle mb-0 table table-striped tble-cstm">
                                    <thead>
                                        <tr>
                                            <th width="40">
                                                <input type="checkbox" class="section-checkbox"
                                                    data-target="customer-file-checkbox">
                                            </th>
                                            <th>Title</th>
                                            <th>Document Type</th>
                                            <th>File</th>
                                            <th>Uploaded On</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($companyDocuments as $document)
                                            @php
                                                $relativePath = ltrim(
                                                    str_replace('storage/', '', (string) $document->file),
                                                    '/',
                                                );
                                                $fileUrl = \Illuminate\Support\Facades\Storage::disk('public')->url(
                                                    $relativePath,
                                                );
                                                $fileName = $document->file_name;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="file-checkbox customer-file-checkbox"
                                                        name="selected_files[]"
                                                        value="company_document:{{ $document->id }}">
                                                </td>
                                                <td>{{ $document->title ?: '-' }}</td>
                                                <td>{{ $document->type ?: '-' }}</td>
                                                <td>{{ $fileName }}</td>
                                                <td>{{ $document->created_at?->format('d M Y h:i A') ?? '-' }}</td>
                                                <td class="text-center">
                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                        class="btn btn-sm btn-primary">View</a>
                                                    <a href="{{ route('admin.document-manger.documents.download', ['company' => $company->id, 'companyDocument' => $document->id]) }}"
                                                        class="btn btn-sm btn-success">Download</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No customer documents found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-5">
                            <h5 class="mb-3">Work Order Documents</h5>
                            @forelse ($workOrderDocuments as $workOrder => $documents)
                                <div class="mb-4">
                                    <h6 class="mb-2">{{ $workOrder }}</h6>
                                    <div class="table-over">
                                        <table class="align-middle mb-0 table table-striped tble-cstm">
                                            <thead>
                                                <tr>
                                                    <th width="40">
                                                        <input type="checkbox" class="section-checkbox"
                                                            data-target="work-order-checkbox-{{ $loop->index }}">
                                                    </th>
                                                    <th>Entity</th>
                                                    <th>Document Type</th>
                                                    <th>File</th>
                                                    <th>Uploaded On</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($documents as $document)
                                                    @php
                                                        $relativePath = ltrim(
                                                            str_replace('storage/', '', (string) $document->file),
                                                            '/',
                                                        );
                                                        $fileUrl = \Illuminate\Support\Facades\Storage::disk(
                                                            'public',
                                                        )->url($relativePath);
                                                        $fileName = $document->name ?: basename($document->file);
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox"
                                                                class="file-checkbox work-order-checkbox-{{ $loop->parent->index }}"
                                                                name="selected_files[]"
                                                                value="work_order_document:{{ $document->id }}">
                                                        </td>
                                                        <td>{{ $document->entity ?? '-' }}</td>
                                                        <td>{{ $document->type ?: '-' }}</td>
                                                        <td>{{ $fileName }}</td>
                                                        <td>{{ $document->created_at?->format('d M Y h:i A') ?? '-' }}</td>
                                                        <td class="text-center">
                                                            @switch($document->entity)
                                                                @case('QO')
                                                                    @if (optional($document->workPlan?->quotation)->id)
                                                                        <a href="{{ route_with_query('admin.quotations.show', [
                                                                            'quotation' => $document->workPlan->quotation->id,
                                                                            'work_plan' => $document->work_plan_id,
                                                                        ]) }}"
                                                                            target="_blank" class="btn btn-sm btn-primary">View</a>
                                                                        <a href="{{ route('admin.quotations.pdf', $document->workPlan->quotation->id) }}"
                                                                            class="btn btn-sm btn-success">Download</a>
                                                                    @else
                                                                        <a href="{{ $fileUrl }}" target="_blank"
                                                                            class="btn btn-sm btn-primary">View</a>
                                                                        <a href="{{ route('admin.document-manger.work-order-documents.download', ['company' => $company->id, 'workPlanAttachment' => $document->id]) }}"
                                                                            class="btn btn-sm btn-success">Download</a>
                                                                    @endif
                                                                @break

                                                                @case('IN')
                                                                    @if (optional($document->workPlan?->quotation?->invoice)->id)
                                                                        <a href="{{ route_with_query('admin.invoices.show', [
                                                                            'invoice' => $document->workPlan->quotation->invoice->id,
                                                                            'work_plan' => $document->work_plan_id,
                                                                        ]) }}"
                                                                            target="_blank" class="btn btn-sm btn-primary">View</a>
                                                                        <a href="{{ route('admin.invoices.pdf', $document->workPlan->quotation->invoice->id) }}"
                                                                            class="btn btn-sm btn-success">Download</a>
                                                                    @else
                                                                        <a href="{{ $fileUrl }}" target="_blank"
                                                                            class="btn btn-sm btn-primary">View</a>
                                                                        <a href="{{ route('admin.document-manger.work-order-documents.download', ['company' => $company->id, 'workPlanAttachment' => $document->id]) }}"
                                                                            class="btn btn-sm btn-success">Download</a>
                                                                    @endif
                                                                @break

                                                                @case('OR')
                                                                    @if ($document->payment_id)
                                                                        <a href="{{ route('admin.receipts.show', $document->payment_id) }}"
                                                                            target="_blank" class="btn btn-sm btn-primary">View</a>
                                                                        <a href="{{ route('admin.receipts.pdf', $document->payment_id) }}"
                                                                            class="btn btn-sm btn-success">Download</a>
                                                                    @else
                                                                        <a href="{{ $fileUrl }}" target="_blank"
                                                                            class="btn btn-sm btn-primary">View</a>
                                                                        <a href="{{ route('admin.document-manger.work-order-documents.download', ['company' => $company->id, 'workPlanAttachment' => $document->id]) }}"
                                                                            class="btn btn-sm btn-success">Download</a>
                                                                    @endif
                                                                @break

                                                                @default
                                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                                        class="btn btn-sm btn-primary">View</a>
                                                                    <a href="{{ route('admin.document-manger.work-order-documents.download', ['company' => $company->id, 'workPlanAttachment' => $document->id]) }}"
                                                                        class="btn btn-sm btn-success">Download</a>
                                                            @endswitch
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @empty
                                    <div class="table-over">
                                        <table class="align-middle mb-0 table table-striped tble-cstm">
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">No work order documents found.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endforelse
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        @section('scripts')
            @include('admin.scripts.script')
            <script>
                (function() {
                    const allFileCheckboxes = Array.from(document.querySelectorAll('.file-checkbox'));
                    const sectionCheckboxes = Array.from(document.querySelectorAll('.section-checkbox'));
                    const selectAllCheckbox = document.getElementById('select-all-files');
                    const selectedCount = document.getElementById('selected-files-count');
                    const zipButton = document.getElementById('zip-selected-btn');

                    const updateSelectionState = () => {
                        const checkedCount = allFileCheckboxes.filter((checkbox) => checkbox.checked).length;
                        selectedCount.textContent = checkedCount;
                        zipButton.disabled = checkedCount === 0;

                        if (selectAllCheckbox) {
                            selectAllCheckbox.checked = checkedCount > 0 && checkedCount === allFileCheckboxes.length;
                            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < allFileCheckboxes.length;
                        }
                    };

                    allFileCheckboxes.forEach((checkbox) => {
                        checkbox.addEventListener('change', updateSelectionState);
                    });

                    sectionCheckboxes.forEach((checkbox) => {
                        checkbox.addEventListener('change', function() {
                            const targetClass = this.dataset.target;
                            document.querySelectorAll(`.${targetClass}`).forEach((target) => {
                                target.checked = this.checked;
                            });

                            updateSelectionState();
                        });
                    });

                    if (selectAllCheckbox) {
                        selectAllCheckbox.addEventListener('change', function() {
                            allFileCheckboxes.forEach((checkbox) => {
                                checkbox.checked = this.checked;
                            });

                            sectionCheckboxes.forEach((checkbox) => {
                                checkbox.checked = this.checked;
                                checkbox.indeterminate = false;
                            });

                            updateSelectionState();
                        });
                    }

                    updateSelectionState();
                })();
            </script>
        @endsection
    @endsection
