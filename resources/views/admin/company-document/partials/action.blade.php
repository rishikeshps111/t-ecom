<div class="dropdown action-dropdown">
    <button class="btn btn-link p-0 text-dark dropdown-toggle no-arrow" type="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end">
        @can('document.view')
            <li>
                <button type="button" class="dropdown-item preview-document"
                    data-file="{{ asset('storage/' . $row->file) }}"
                    data-name="{{ $row->file_name }}">
                    <i class="fa-solid fa-eye me-2 text-primary"></i>
                    View
                </button>
            </li>
            <li>
                <a class="dropdown-item"
                    href="{{ route('admin.document-manger.documents.download', ['company' => $company->id, 'companyDocument' => $row->id]) }}">
                    <i class="fa-solid fa-download me-2 text-success"></i>
                    Download
                </a>
            </li>
        @endcan

        @can('document.delete')
            <li>
                <button type="button" class="dropdown-item text-danger" onclick="deleteRow('{{ $row->id }}')">
                    <i class="fa-solid fa-trash me-2"></i>
                    Delete
                </button>
            </li>
        @endcan
    </ul>
</div>
