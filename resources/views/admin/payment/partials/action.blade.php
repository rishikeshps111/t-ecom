<div class="dropdown action-dropdown">
    <button class="btn btn-link p-0 text-dark dropdown-toggle no-arrow" type="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end">

        {{-- View Receipt --}}
        <li>
            <a class="dropdown-item" href="{{ route('admin.receipts.show', $row->id) }}">
                <i class="fa-solid fa-eye me-2 text-primary"></i>
                View OR 
            </a>
        </li>

        {{-- Generate PDF --}}
        <li>
            <a class="dropdown-item" href="{{ route('admin.receipts.pdf', $row->id) }}">
                <i class="fa-solid fa-file-pdf me-2 text-warning"></i>
                Generate PDF
            </a>
        </li>

        {{-- Edit (only if not closed) --}}
        @if($row->status != 'closed')
            <li>
                <a class="dropdown-item" href="{{ route('admin.receipts.edit', $row->id) }}?inv_id={{ $row->invoice_id }}">
                    <i class="fa-solid fa-pen-to-square me-2"></i>
                    Edit
                </a>
            </li>
        @endif

        <li>
            <hr class="dropdown-divider">
        </li>

        {{-- View Invoice --}}
        <li>
            <a class="dropdown-item" target="_blank" href="{{ route('admin.invoices.show', $row->invoice_id) }}">
                <i class="fa-solid fa-file-invoice me-2"></i>
                View Invoice
            </a>
        </li>

    </ul>
</div>