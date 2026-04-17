<div class="dropdown action-dropdown">
    <button class="btn btn-link p-0 text-dark dropdown-toggle no-arrow" type="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item" href="{{ route('admin.document-manger.documents.index', $row) }}">
                <i class="fa-solid fa-file-lines me-2"></i>
                Manage customer documents
            </a>
        </li>
        {{-- <li>
            <a class="dropdown-item" href="{{ route('admin.document-manger.planner-documents.index', $row) }}">
                <i class="fa-solid fa-user-pen me-2"></i>
                Planner Document
            </a>
        </li> --}}
        <li>
            <a class="dropdown-item" href="{{ route('admin.document-manger.work-order-documents.index', $row) }}">
                <i class="fa-solid fa-briefcase me-2"></i>
                WorkOrder Documents
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('admin.document-manger.all-files.index', $row) }}">
                <i class="fa-solid fa-folder-open me-2"></i>
                All Files
            </a>
        </li>
    </ul>
</div>
