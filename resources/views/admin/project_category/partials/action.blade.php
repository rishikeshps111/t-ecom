<div class="action-btns">

    <a href="{{ route('admin.project-categories.edit', $row->id) }}"
        class="btn-edit form-btn">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>

    <button class="btn-delete" onclick="deleteRow('{{ $row->id }}')">
        <i class="fa-solid fa-trash"></i>
    </button>

</div>
