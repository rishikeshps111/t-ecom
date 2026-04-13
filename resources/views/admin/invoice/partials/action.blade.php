<div class="dropdown action-dropdown">
    @canany(['inv.edit', 'inv.view', 'inv.delete'])

        <button class="btn btn-link p-0 text-dark dropdown-toggle no-arrow" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>

        <ul class="dropdown-menu dropdown-menu-end">

            {{-- View --}}
            @can('inv.view')
                <li>
                    <a class="dropdown-item" href="{{ route('admin.invoices.show', $row->id) }}">
                        <i class="fa-solid fa-eye me-2 text-primary"></i>
                        View
                    </a>
                </li>
            @endcan

            {{-- Generate PDF --}}
            @can('inv.view')
                @if ($row->status == 'approved')
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.invoices.pdf', $row->id) }}">
                            <i class="fa-solid fa-file-pdf me-2 text-warning"></i>
                            Generate PDF
                        </a>
                    </li>
                @endif
            @endcan

            {{-- Edit --}}
            @can('inv.edit')
                @if ($row->status == 'pending')
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.invoices.edit', $row->id) }}">
                            <i class="fa-solid fa-pen-to-square me-2"></i>
                            Edit
                        </a>
                    </li>
                @endif
            @endcan

            {{-- Delete --}}
            @can('inv.delete')
                @if ($row->status != 'approved')
                    <li>
                        <button class="dropdown-item text-danger" onclick="deleteRow('{{ $row->id }}')">
                            <i class="fa-solid fa-trash me-2"></i>
                            Delete
                        </button>
                    </li>
                @endif
            @endcan
            @can('inv.delete')
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
            {{-- @can('invoice.approval')
            @if ($row->status != 'draft')
            <li>
                <button class="dropdown-item open-approval-modal" data-id="{{ $row->id }}">
                    <i class="fa-solid fa-circle-check me-2 text-success"></i>
                    Approvals
                </button>
            </li>
            @endif
            @endcan --}}

            {{-- Receipt --}}
            @can('inv.edit')
                @if ($row->status == 'approved')
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    @if ($row->payments->isNotEmpty())
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.receipts.index') }}?inv_id={{ $row->id }}">
                                <i class="fa-solid fa-receipt me-2 text-purple"></i>
                                View OR
                            </a>
                        </li>
                    @else
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.receipts.create') }}?inv_id={{ $row->id }}">
                                <i class="fa-solid fa-receipt me-2 text-purple"></i>
                                Generate OR
                            </a>
                        </li>
                    @endif
                @endif
            @endcan

        </ul>

    @else
        <span class="text-muted">No access</span>
    @endcanany
</div>