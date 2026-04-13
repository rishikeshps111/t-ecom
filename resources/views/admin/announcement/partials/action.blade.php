<div class="action-btns">
    @canany(['announcement.view', 'announcement.delete'])
        @can('announcement.view')
            <button class="btn-view view" data-id="{{ $row->id }}">
                <i class="fa-solid fa-eye"></i>
                @if (!auth()->user()->hasRole('Super Admin'))
                    @if($row->isUnreadForUser(auth()->id()))
                        <span class="badge-new">New</span>
                    @endif
                @endif
            </button>
        @endcan
        @can('announcement.delete')
            <button class="btn-delete" onclick="deleteRow('{{ $row->id }}')">
                <i class="fa-solid fa-trash"></i>
            </button>
        @endcan
    @else
        <span class="text-muted">No access</span>
    @endcanany
</div>