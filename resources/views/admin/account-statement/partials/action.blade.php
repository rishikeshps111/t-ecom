<div class="dropdown action-dropdown">
    @canany(['account-statement.view'])

        <button class="btn btn-link p-0 text-dark dropdown-toggle no-arrow" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <a class="dropdown-item" href="{{ route('admin.account-statements.details', $row->id) }}">
                    Work Order Report
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('admin.account-statements.show', $row->id) }}">
                    Planner Commission Report
                </a>
            </li>
        </ul>
    @else
        <span class="text-muted">No access</span>
    @endcanany
</div>