<div class="dropdown action-dropdown">
    @canany(['wo.edit', 'wo.view', 'wo.delete'])
        <button class="btn btn-link p-0 text-dark dropdown-toggle no-arrow" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>

        <ul class="dropdown-menu dropdown-menu-end">

            {{-- Preview --}}
            @can('wo.view')
                <li>
                    <a class="dropdown-item" href="{{ route('admin.work-orders.show', $row->id) }}">
                        <i class="fa-solid fa-eye me-2"></i>
                        View WO
                    </a>
                </li>
            @endcan
            {{-- Edit --}}
            @can('wo.edit')
                @if($row->status == 'pending')
                    <li>
                        <a class="dropdown-item" href="{{ route_with_query('admin.work-orders.edit', [
                            'work_order' => $row->id,
                            'customer_id' => $customerID ?? null
                        ]) }}">
                            <i class="fa-solid fa-pen-to-square me-2"></i>
                            Edit WO
                        </a>
                    </li>
                @endif
            @endcan

            {{-- Delete --}}
            @can('wo.delete')
                @if($row->status != 'approved' && $row->status != 'closed' && $row->status != 'rejected')
                    <li>
                        {{-- <button class="dropdown-item text-danger" onclick="deleteRow('{{ $row->id }}')">
                            <i class="fa-solid fa-trash me-2"></i>
                            Delete WO
                        </button> --}}
                        <button class="dropdown-item text-danger reject-order" data-id="{{ $row->id }}">
                            <i class="fa-solid fa-ban me-2"></i>
                            Reject WO
                        </button>
                    </li>
                @endif
            @endcan
            @can('wo.view')
                @if($row->status == 'closed')
                    <li>
                        <a href="{{ route('admin.work-orders.details', $row->id) }}" class="dropdown-item">
                            <i class="fa-regular fa-file-lines me-2"></i>
                            Preview
                        </a>
                    </li>
                @endif
            @endcan
            @can('wo.edit')
                @if($row->status == 'pending')
                    <li>
                        <button class="dropdown-item change-status" data-id="{{ $row->id }}">
                            <i class="fa-solid fa-arrow-right me-2 text-primary"></i>
                            Change Status
                        </button>
                    </li>
                @endif
            @endcan

            @if ($row->status === 'rejected')
                <li>
                    <button class="dropdown-item rejection-reason" data-id="{{ $row->id }}">
                        <i class="fa-solid fa-circle-info me-2 text-danger"></i>
                        Rejection Reason
                    </button>
                </li>
            @endif

            @if($row->status == 'approved')
                @role('Super Admin')
                <li>
                    <button class="dropdown-item close-order" data-id="{{ $row->id }}">
                        <i class="fa-solid fa-arrow-right me-2 text-primary"></i>
                        Close Work Order
                    </button>
                </li>
                @endrole
            @endif

            @if($row->status == 'approved')

                {{-- <li>
                    <a class="dropdown-item"
                        href="{{ route_with_query('admin.quotations.edit', [
                                                                                                                                                                                                                                                                                                                    'quotation' => $row->quotation->id,
                                                                                                                                                                                                                                                                                                                    'work_plan' => $row->id
                                                                                                                                                                                                                                                                                                                ]) }}">
                        <i class="fa-solid fa-file-invoice me-2 text-primary"></i>
                        View Quotation
                    </a>
                </li> --}}
                {{-- <li>
                    <hr>
                </li> --}}
                {{-- <li>
                    <a class="dropdown-item" href="#">
                        <i class="fa-solid fa-envelope me-2 text-info"></i>
                        Messages
                    </a>
                </li> --}}


                {{-- Remainder --}}
                {{-- <li>
                    <a class="dropdown-item" href="#">
                        <i class="fa-solid fa-bell me-2 text-warning"></i>
                        Remainder
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="fa-solid fa-file-lines me-2 text-success"></i>
                        Document
                    </a>
                </li> --}}
            @endif
        </ul>
    @else
        <span class="text-muted">No access</span>
    @endcanany
</div>