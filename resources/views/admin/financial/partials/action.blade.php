<div class="action-btns">
    <button class="btn-view form-btn" data-id="{{ $row->id }}">
        <i class="fa-solid fa-pen"></i>
    </button>
    <button class="btn-delete" onclick="deleteRow('{{ $row->id }}')">
        <i class="fa-solid fa-trash"></i>
    </button>
</div>