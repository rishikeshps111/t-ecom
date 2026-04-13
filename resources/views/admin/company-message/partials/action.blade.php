<div class="action-btns">
    <button class="btn-view view" data-id="{{ $row->id }}">
        <i class="fa-solid fa-eye"></i>
    </button>

    <button class="btn-delete" onclick="deleteRow('{{ $row->id }}')">
        <i class="fa-solid fa-trash"></i>
    </button>
</div>