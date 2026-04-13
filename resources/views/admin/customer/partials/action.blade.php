<div class="dropdown action-dropdown">
    <button class="btn btn-sm btn-light dropdown-toggle p-0 no-arrow" type="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item" href="{{ route('admin.customers.edit', $row->id) }}">
                <i class="fa-solid fa-pen-to-square me-2"></i> Edit
            </a>
        </li>
        {{-- <li>
            <button class="dropdown-item text-danger" onclick="deleteRow('{{ $row->id }}')">
                <i class="fa-solid fa-trash me-2"></i> Delete
            </button>
        </li> --}}
    </ul>
</div>