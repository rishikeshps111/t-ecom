@php
    $relativePath = ltrim(str_replace('storage/', '', (string) $row->file), '/');
    $fileUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($relativePath);
    $fileName = basename($row->file);
@endphp

<div class="dropdown action-dropdown">
    <button class="btn btn-link p-0 text-dark dropdown-toggle no-arrow" type="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            @switch($row->entity)
                @case('QO')
                    @if (optional($row->workPlan?->quotation)->id)
                        <a class="dropdown-item" target="_blank"
                            href="{{ route_with_query('admin.quotations.show', [
                                'quotation' => $row->workPlan->quotation->id,
                                'work_plan' => $row->work_plan_id,
                            ]) }}">
                            <i class="fa-solid fa-eye me-2 text-primary"></i>
                            View
                        </a>
                    @else
                        <button type="button" class="dropdown-item preview-document" data-file="{{ $fileUrl }}"
                            data-name="{{ $fileName }}">
                            <i class="fa-solid fa-eye me-2 text-primary"></i>
                            View
                        </button>
                    @endif
                @break

                @case('IN')
                    @if (optional($row->workPlan?->quotation?->invoice)->id)
                        <a class="dropdown-item" target="_blank"
                            href="{{ route_with_query('admin.invoices.show', [
                                'invoice' => $row->workPlan->quotation->invoice->id,
                                'work_plan' => $row->work_plan_id,
                            ]) }}">
                            <i class="fa-solid fa-eye me-2 text-primary"></i>
                            View
                        </a>
                    @else
                        <button type="button" class="dropdown-item preview-document" data-file="{{ $fileUrl }}"
                            data-name="{{ $fileName }}">
                            <i class="fa-solid fa-eye me-2 text-primary"></i>
                            View
                        </button>
                    @endif
                @break

                @case('OR')
                    @if ($row->payment_id)
                        <a class="dropdown-item" target="_blank" href="{{ route('admin.receipts.show', $row->payment_id) }}">
                            <i class="fa-solid fa-eye me-2 text-primary"></i>
                            View
                        </a>
                    @else
                        <button type="button" class="dropdown-item preview-document" data-file="{{ $fileUrl }}"
                            data-name="{{ $fileName }}">
                            <i class="fa-solid fa-eye me-2 text-primary"></i>
                            View
                        </button>
                    @endif
                @break

                @default
                    <button type="button" class="dropdown-item preview-document" data-file="{{ $fileUrl }}"
                        data-name="{{ $fileName }}">
                        <i class="fa-solid fa-eye me-2 text-primary"></i>
                        View
                    </button>
            @endswitch
        </li>
        <li>
            @switch($row->entity)
                @case('QO')
                    @if (optional($row->workPlan?->quotation)->id)
                        <a class="dropdown-item" href="{{ route('admin.quotations.pdf', $row->workPlan->quotation->id) }}">
                            <i class="fa-solid fa-download me-2 text-success"></i>
                            Download
                        </a>
                    @else
                        <a class="dropdown-item"
                            href="{{ route('admin.document-manger.work-order-documents.download', ['company' => $company->id, 'workPlanAttachment' => $row->id]) }}">
                            <i class="fa-solid fa-download me-2 text-success"></i>
                            Download
                        </a>
                    @endif
                @break

                @case('IN')
                    @if (optional($row->workPlan?->quotation?->invoice)->id)
                        <a class="dropdown-item"
                            href="{{ route('admin.invoices.pdf', $row->workPlan->quotation->invoice->id) }}">
                            <i class="fa-solid fa-download me-2 text-success"></i>
                            Download
                        </a>
                    @else
                        <a class="dropdown-item"
                            href="{{ route('admin.document-manger.work-order-documents.download', ['company' => $company->id, 'workPlanAttachment' => $row->id]) }}">
                            <i class="fa-solid fa-download me-2 text-success"></i>
                            Download
                        </a>
                    @endif
                @break

                @case('OR')
                    @if ($row->payment_id)
                        <a class="dropdown-item" href="{{ route('admin.receipts.pdf', $row->payment_id) }}">
                            <i class="fa-solid fa-download me-2 text-success"></i>
                            Download
                        </a>
                    @else
                        <a class="dropdown-item"
                            href="{{ route('admin.document-manger.work-order-documents.download', ['company' => $company->id, 'workPlanAttachment' => $row->id]) }}">
                            <i class="fa-solid fa-download me-2 text-success"></i>
                            Download
                        </a>
                    @endif
                @break

                @default
                    <a class="dropdown-item"
                        href="{{ route('admin.document-manger.work-order-documents.download', ['company' => $company->id, 'workPlanAttachment' => $row->id]) }}">
                        <i class="fa-solid fa-download me-2 text-success"></i>
                        Download
                    </a>
            @endswitch
        </li>
    </ul>
</div>
