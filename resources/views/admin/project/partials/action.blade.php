<div class="action-btns">

    <a href="{{ route('admin.projects.edit', $row->id) }}" class="btn-edit form-btn">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>

    <button class="btn-delete" onclick="deleteRow('{{ $row->id }}')">
        <i class="fa-solid fa-trash"></i>
    </button>

    <button class="btn-cstm change-status" data-id="{{ $row->id }}"  style="background-color: #3692d0;font-size: 10px;">
        Change Status
    </button>

      <a href="{{ route('admin.documents.index') }}?type=planner&project_id={{ $row->id }}" class="btn-nowrap btn-cstm bg-4" style="font-size: 10px;">Documents</a>
</div>
