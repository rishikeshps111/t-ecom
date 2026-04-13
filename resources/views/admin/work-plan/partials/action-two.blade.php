<div class="dropdown action-dropdown">
    @canany(['wo.edit', 'wo.view'])
        <button class="btn btn-link p-0 text-dark dropdown-toggle no-arrow" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>

        <ul class="dropdown-menu dropdown-menu-end">

            {{-- Preview --}}
            @can('wo.view')
                <li>
                    <a class="dropdown-item" href="{{ route('admin.work-orders.show', $row->id) }}">
                        <i class="fa-solid fa-eye me-2"></i>
                        View WO
                    </a>
                </li>
            @endcan
            {{-- Edit --}}
            @can('wo.edit')
                <li>
                    <a href="#" class="dropdown-item open-note-modal" data-id="{{ $row->id }}">
                        <i class="fa-solid fa-note-sticky me-2"></i>
                        Add Notes
                    </a>
                </li>

                <li>
                    <a href="#" class="dropdown-item open-document-modal" data-id="{{ $row->id }}">
                        <i class="fa-solid fa-file-arrow-up me-2"></i>
                        Add Documents
                    </a>
                </li>
            @endcan
        </ul>
    @else
        <span class="text-muted">No access</span>
    @endcanany
</div>