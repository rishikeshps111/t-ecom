<div class="action-btns">
    @canany(['knowledge-base.edit', 'knowledge-base.delete'])
        @can('knowledge-base.edit')
            <a href="{{ route('admin.knowledge-bases.edit', $row->id) }}" class="btn-edit form-btn">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
        @endcan
        @can('knowledge-base.delete')
            <button class="btn-delete" onclick="deleteRow('{{ $row->id }}')">
                <i class="fa-solid fa-trash"></i>
            </button>
        @endcan
        @can('knowledge-base.edit')
            <button class="btn-cstm change-status" data-id="{{ $row->id }}" style="background-color: #3692d0;font-size: 10px;">
                Change Status
            </button>
        @endcan

    @else
        <span class="text-muted">No access</span>
    @endcanany
</div>