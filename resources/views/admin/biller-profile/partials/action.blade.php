<div class="dropdown">
    <button class="btn btn-sm p-0 action-dropdown" type="button" id="actionDropdown{{ $row->id }}"
        data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionDropdown{{ $row->id }}">

        <li>
            <a class="dropdown-item" href="{{ route('admin.biller-profiles.edit', $row->id) }}">
                <i class="fa-solid fa-pen-to-square me-2"></i> Edit
            </a>
        </li>

        <li>
            <a class="dropdown-item text-danger" href="#" onclick="deleteRow('{{ $row->id }}')">
                <i class="fa-solid fa-trash me-2"></i> Delete
            </a>
        </li>

    </ul>
</div>