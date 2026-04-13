<div class="dropdown action-dropdown">
    @canany(['document.view'])

        <button class="btn btn-link p-0 text-dark dropdown-toggle no-arrow" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>

        <ul class="dropdown-menu dropdown-menu-end">

            {{-- View --}}
            @can('document.view')
                <li>
                    @php
                        $fileName = basename($row->file);
                        $fileUrl = asset('storage/' . $row->file);
                    @endphp

                <li class="dropdown-item-wrapper">
                    @switch($row->entity)
                        @case('WO')
                            <a class="dropdown-item" target="_blank" href="{{ $fileUrl }}">
                                <i class="fa-solid fa-eye me-2 text-primary"></i>
                                View
                            </a>
                        @break

                        @case('QO')
                            <a class="dropdown-item" target="_blank"
                                href="{{ route_with_query('admin.quotations.show', [
                                    'quotation' => $row->workPlan->quotation->id,
                                    'work_plan' => $row->work_plan_id,
                                ]) }}">
                                <i class="fa-solid fa-eye me-2 text-primary"></i>
                                View
                            </a>
                        @break

                        @case('IN')
                            <a class="dropdown-item" target="_blank"
                                href="{{ route_with_query('admin.invoices.show', [
                                    'invoice' => $row->workPlan->quotation->invoice->id,
                                    'work_plan' => $row->work_plan_id,
                                ]) }}">
                                <i class="fa-solid fa-eye me-2 text-primary"></i>
                                View
                            </a>
                        @break

                        @case('OR')
                            <a class="dropdown-item" target="_blank" href="{{ route('admin.receipts.show', $row->payment_id) }}">
                                <i class="fa-solid fa-eye me-2 text-primary"></i>
                                View
                            </a>
                        @break

                        @default
                            <a class="dropdown-item" target="_blank" href="{{ $fileUrl }}">
                                <i class="fa-solid fa-eye me-2 text-primary"></i>
                                View
                            </a>
                    @endswitch
                </li>
            @endcan
        </ul>
    @else
        <span class="text-muted">No access</span>
    @endcanany
</div>
