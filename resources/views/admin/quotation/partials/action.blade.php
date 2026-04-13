<div class="dropdown action-dropdown">
    @canany(['qt.edit', 'qt.view', 'qt.delete'])
        <button class="btn btn-link p-0 text-dark dropdown-toggle no-arrow" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>

        <ul class="dropdown-menu dropdown-menu-end">

            {{-- Accept Quotation --}}
            {{-- @can('quotation.edit')
            @if ($row->status == 'approved')
            <li>
                <button class="dropdown-item text-success accept" data-id="{{ $row->id }}">
                    <i class="fa-solid fa-check me-2"></i>
                    Accept Quotation
                </button>
            </li>
            @endif
            @endcan --}}

            {{-- Preview --}}
            @can('qt.view')
                @if($row->status == 'approved')
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.quotations.show', $row->id) }}">
                            <i class="fa-solid fa-eye me-2"></i>
                            Preview
                        </a>
                    </li>
                @endif
            @endcan

            {{-- Edit --}}
            @can('qt.edit')
                @if($row->status == 'pending')
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.quotations.edit', $row->id) }}">
                            <i class="fa-solid fa-pen-to-square me-2"></i>
                            Edit
                        </a>
                    </li>
                @endif
            @endcan

            {{-- Delete --}}
            @can('qt.delete')
                @if($row->status != 'approved')
                    <li>
                        <button class="dropdown-item text-danger" onclick="deleteRow('{{ $row->id }}')">
                            <i class="fa-solid fa-trash me-2"></i>
                            Delete
                        </button>
                    </li>
                @endif
            @endcan
            @can('qt.edit')
                @if($row->status == 'pending')
                    <li>
                        <button class="dropdown-item change-status" data-id="{{ $row->id }}">
                            <i class="fa-solid fa-arrow-right me-2 text-primary"></i>
                            Change Status
                        </button>
                    </li>
                @endif
            @endcan

            {{-- Approvals --}}
            {{-- @can('quotation.approval')
            @if ($row->status != 'draft')
            <li>
                <button class="dropdown-item open-approval-modal" data-id="{{ $row->id }}">
                    <i class="fa-solid fa-circle-check me-2"></i>
                    Approvals
                </button>
            </li>
            @endif
            @endcan --}}

        </ul>
    @else
        <span class="text-muted">No access</span>
    @endcanany
</div>