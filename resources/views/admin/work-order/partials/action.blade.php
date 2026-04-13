<div class="dropdown action-dropdown">
    @canany(['work-plan.edit', 'work-plan.view', 'work-plan.delete'])
        <button class="btn btn-link p-0 text-dark dropdown-toggle no-arrow" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>

        <ul class="dropdown-menu dropdown-menu-end">

            {{-- Preview --}}
            @can('work-plan.view')
                <li>
                    <a class="dropdown-item" href="{{ route('admin.work-plans.show', $row->id) }}">
                        <i class="fa-solid fa-eye me-2"></i>
                        View
                    </a>
                </li>
            @endcan
            {{-- Edit --}}
            @can('work-plan.edit')
                <li>
                    <a class="dropdown-item" href="{{ route_with_query('admin.work-plans.edit', [
                    'work_plan' => $row->id,
                    'customer_id' => $customerID ?? null
                ]) }}">
                        <i class="fa-solid fa-pen-to-square me-2"></i>
                        Edit
                    </a>
                </li>
            @endcan

            {{-- Delete --}}
            @can('work-plan.delete')
                <li>
                    <button class="dropdown-item text-danger" onclick="deleteRow('{{ $row->id }}')">
                        <i class="fa-solid fa-trash me-2"></i>
                        Delete
                    </button>
                </li>
            @endcan
            @if($row->status != 'approved')
                <li>
                    <button class="dropdown-item change-status" data-id="{{ $row->id }}">
                        <i class="fa-solid fa-arrow-right me-2 text-primary"></i>
                        Change Status
                    </button>
                </li>
            @endif
        </ul>
    @else
        <span class="text-muted">No access</span>
    @endcanany
</div>