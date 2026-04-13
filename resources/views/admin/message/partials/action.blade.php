<div class="action-btns">
    @canany(['message.view', 'message.delete'])
        @php $unread = unreadConversationCount($row->id); @endphp
        @role(['Customer', 'Planner','Production Staff'])
            <a href="{{ route('admin.messages.conversation', $row->id) }}" class="btn-cstm" data-id="{{ $row->id }}"
                style="background-color: green; font-size: 10px;">
                Reply
                @if ($unread > 0)
                    <span class="badge bg-danger position-absolute ms-4 mb-4"
                        style="font-size: 8px;
                                                                            padding: 3px 6px;">
                        {{ $unread }}
                    </span>
                @endif
            </a>
        @endrole

        @role('Super Admin')
            <a href="{{ route('admin.messages.conversation', $row->id) }}" class="btn-cstm" data-id="{{ $row->id }}"
                style="background-color: green; font-size: 10px;">
                Replies
                @if ($unread > 0)
                    <span class="badge bg-danger position-absolute ms-4 mb-4"
                        style="font-size: 8px;
                                                                                    padding: 3px 6px;">
                        {{ $unread }}
                    </span>
                @endif
            </a>
        @endrole

        @can('message.delete')
            <button class="btn-delete" onclick="deleteRow('{{ $row->id }}')">
                <i class="fa-solid fa-trash"></i>
            </button>
        @endcan
    @else
        <span class="text-muted">No access</span>
    @endcanany
</div>
