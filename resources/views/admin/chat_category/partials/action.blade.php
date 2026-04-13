<div class="action-btns">
    @canany(['knowledge-base-category.edit', 'knowledge-base-category.delete'])
        @can('knowledge-base-category.edit')
            <a href="{{ route('admin.chat-categories.edit', $row->id) }}" class="btn-edit form-btn">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
        @endcan
        @can('knowledge-base-category.delete')
            <button class="btn-delete" onclick="deleteRow('{{ $row->id }}')">
                <i class="fa-solid fa-trash"></i>
            </button>
        @endcan
    @else
        <span class="text-muted">No access</span>
    @endcanany
</div>