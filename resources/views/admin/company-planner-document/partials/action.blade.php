<div class="dropdown action-dropdown">
    <button class="btn btn-link p-0 text-dark dropdown-toggle no-arrow" type="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <button type="button" class="dropdown-item preview-document" data-file="{{ asset($row->document) }}"
                data-name="{{ basename($row->document) }}">
                <i class="fa-solid fa-eye me-2 text-primary"></i>
                View
            </button>
        </li>
        <li>
            <a class="dropdown-item"
                href="{{ route('admin.document-manger.planner-documents.download', ['company' => $company->id, 'plannerDocumentFile' => $row->id]) }}">
                <i class="fa-solid fa-download me-2 text-success"></i>
                Download
            </a>
        </li>
    </ul>
</div>
