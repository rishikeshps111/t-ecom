<div class="dropdown action-dropdown">
    @canany(['document.edit', 'document.delete', 'document.view'])

        <button class="btn btn-link p-0 text-dark dropdown-toggle no-arrow" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>

        <ul class="dropdown-menu dropdown-menu-end">

            {{-- Edit --}}
            @can('document.edit')
                <li>
                    <a class="dropdown-item" href="{{ route('admin.planner-documents.edit', $row->id) }}">
                        <i class="fa-solid fa-pen-to-square me-2"></i>
                        Edit
                    </a>
                </li>
            @endcan

            {{-- Delete --}}
            @can('document.delete')
                <li>
                    <button class="dropdown-item text-danger" onclick="deleteRow('{{ $row->id }}')">
                        <i class="fa-solid fa-trash me-2"></i>
                        Delete
                    </button>
                </li>
            @endcan

            {{-- Change Status --}}
            @can('document.edit')
                <li>
                    <button class="dropdown-item change-status" data-id="{{ $row->id }}">
                        <i class="fa-solid fa-arrow-right me-2 text-primary"></i>
                        Change Status
                    </button>
                </li>
            @endcan

            {{-- View Documents --}}
            @can('document.view')
                <li>
                    <button class="dropdown-item view" data-id="{{ $row->id }}">
                        <i class="fa-solid fa-eye me-2 text-success"></i>
                        View Documents
                    </button>
                </li>
            @endcan

        </ul>

    @else
        <span class="text-muted">No access</span>
    @endcanany
</div>