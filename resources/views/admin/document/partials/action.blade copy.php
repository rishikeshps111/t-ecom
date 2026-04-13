<div class="dropdown action-dropdown">
    @canany(['general-document.edit', 'general-document.delete', 'general-document.view'])

        <button class="btn btn-link p-0 text-dark dropdown-toggle no-arrow" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>

        <ul class="dropdown-menu dropdown-menu-end">

            {{-- View --}}
            @can('general-document.view')
                <li>
                    <button class="dropdown-item view" data-id="{{ $row->id }}">
                        <i class="fa-solid fa-eye me-2 text-primary"></i>
                        View
                    </button>
                </li>
            @endcan

            {{-- Edit --}}
            @can('general-document.edit')
                <li>
                    <a class="dropdown-item" href="{{ route_with_query('admin.documents.edit', [
                    'document' => $row->id,
                    'company_id' => $companyId ?? null
                ]) }}">
                        <i class="fa-solid fa-pen-to-square me-2"></i>
                        Edit
                    </a>
                </li>
            @endcan

            {{-- Delete --}}
            @can('general-document.delete')
                <li>
                    <button class="dropdown-item text-danger" onclick="deleteRow('{{ $row->id }}')">
                        <i class="fa-solid fa-trash me-2"></i>
                        Delete
                    </button>
                </li>
            @endcan

        </ul>

    @else
        <span class="text-muted">No access</span>
    @endcanany
</div>