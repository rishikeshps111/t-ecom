<div class="dropdown">
    <button class="btn btn-sm p-0 action-dropdown" type="button" id="actionDropdown{{ $row->id }}"
        data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionDropdown{{ $row->id }}">

        <li>
            <a class="dropdown-item" href="{{ route('admin.business-users.edit', $row->id) }}">
                <i class="fa-solid fa-pen-to-square me-2"></i> Edit
            </a>
        </li>

        <li>
            <a class="dropdown-item copy-credentials" href="#" data-id="{{ $row->id }}"
                data-password="{{ $row->show_password }}" data-user="{{ $row->email }}">
                <i class="fa-solid fa-copy me-2"></i> Copy Credentials
            </a>
        </li>

        <li class="d-none">
            <a class="dropdown-item view" href="#" data-id="{{ $row->id }}">
                <i class="fa-solid fa-paper-plane me-2"></i> Send Credentials
            </a>
        </li>

        <li>
            <button class="dropdown-item view-btn" data-id="{{ $row->id }}">
                <i class="fa-solid fa-eye me-2"></i> View Companies
            </button>
        </li>

        <li>
            <a class="dropdown-item text-danger" href="#" onclick="deleteRow('{{ $row->id }}')">
                <i class="fa-solid fa-trash me-2"></i> Delete
            </a>
        </li>

        @if ($row->is_locked)
            <li>
                <a class="dropdown-item text-success" href="#!" onclick="unlockUser({{ $row->id }})">
                    <i class="fa-solid fa-lock-open me-2"></i>
                    Unlock User
                </a>
            </li>
        @else
            <li>
                <a class="dropdown-item text-warning" href="#!" onclick="lockUser({{ $row->id }})">
                    <i class="fa-solid fa-lock me-2"></i> Lock User
                </a>
            </li>
        @endif
    </ul>
</div>