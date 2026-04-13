@extends('admin.layouts.app')
@section('title', 'Payment Receipt')
@section('style')
    <style>
        .preview-bottom-btns a,
        .preview-bottom-btns button {
            white-space: nowrap;
            font-size: 14px;
            min-width: 110px;

        }

        .p-tag p {
            text-align: center;
        }

        .center-logo {
            display: flex;
            justify-content: center;
            align-items: center !Important;
        }

        @media print {
            .main-table-container {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .preview-container-center h2 {
                font-size: 11px !imortant;
            }

            .card,
            .card * {
                visibility: visible;
            }

            .card {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none !important;
            }

            .btn {
                display: none !important;
            }

            .preview-bottom-btns {
                display: none !important;
            }

            .print {
                display: none !important;
            }

            .main-table-container {
                padding: 0 !important;
                border: 0 !imortant;
                padding-top: 15px !Important;
                box-shadow: none !important;
            }

        }
    </style>
@endsection

@section('content')
    <div class="pt-4" id="printArea">
        <div class="row">
            <div class="col-lg-10 mb-4">
                <div class="main-table-container">
                    <div class="wo-preview-top wo-preview-top-2 invoice-prv text-center center-logo">
                        @if (
                                $receipt->invoice &&
                                $receipt->invoice->quotation &&
                                $receipt->invoice->quotation->workPlan &&
                                $receipt->invoice->quotation->workPlan->totalGroup &&
                                $receipt->invoice->quotation->workPlan->totalGroup->billerProfile
                            )
                            <img
                                src="{{ asset('storage/' . $receipt->invoice->quotation->workPlan->totalGroup->billerProfile->receipt_header) }}">
                        @else
                            <img src="{{ asset('assets/images/default-logo.png') }}" alt="Company Logo">
                        @endif

                    </div>
                    <div class="preview-container-center">
                        <div class="row justify-content-center">
                            <div class="col-lg-9">
                                <div class="preview-container-center-left">
                                    {{-- Company Logo --}}
                                    <div class="p-tag">
                                        {!!  $receipt->invoice->quotation->workPlan->totalGroup->billerProfile->address ?? '' !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wo-preview-top-2 pt-3">
                            <div class="line-cs-2"></div>
                        </div>
                        <div class="row">
                            {{-- Company Logo --}}
                            <h2>
                                Payment Receipt:
                                <span style="font-weight: normal;"></span>{{ $receipt->custom_payment_id ?? '' }}</span>
                            </h2>
                            <div class="col-6 mb-3">
                                <div class="preview-container-center-two">

                                    <ul>
                                        <li> Payment Date: <span>{{  $receipt->created_at->format('d M Y') }}</span></li>
                                        <li>Customer:
                                            <span>{{  $receipt->invoice->quotation->workPlan->company->company_name ?? '' }}</span>
                                        </li>
                                        <li> Payment Amount: <span>RM {{  $receipt->amount ?? '-' }}</span></li>
                                        @php
                                            $amountToPay = $receipt->amount * ($receipt->invoice->p_bill_percentage / 100);
                                        @endphp
                                        {{-- <li>
                                            Planner Commission Amount:
                                            <span>RM {{ isset($amountToPay) ? number_format($amountToPay, 2) : '-' }}</span>
                                        </li> --}}
                                    </ul>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="preview-container-center-two">

                                    <ul>
                                        @php
                                            $amountToPayTwo = $receipt->amount * ($receipt->invoice->quotation->workPlan->company->productionStaff->production_c_percentage / 100);
                                        @endphp
                                        {{-- <li>
                                            Production Staff Commission Amount:
                                            <span>RM
                                                {{ isset($amountToPayTwo) ? number_format($amountToPayTwo, 2) : '-'
                                                }}</span>
                                        </li> --}}
                                        <li>Payment Method: <span> {{ $receipt->payment_method ?? '-' }}</span></li>
                                        <li> INV Number: <span>{{ $receipt->invoice->invoice_number ?? '-' }}</span></li>
                                        {{-- <li>
                                            Status:
                                            @php
                                            $status = $receipt->status ?? '-';
                                            $badgeClass = match ($status) {
                                            'pending' => 'badge bg-warning', // yellow for pending
                                            'closed' => 'badge bg-success', // green for closed
                                            default => 'badge bg-secondary', // gray for null or unknown
                                            };
                                            @endphp
                                            <span class="{{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                        </li> --}}
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="preview-container-center-bottom">
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle text-center tble-pay">
                                            <thead>
                                                <tr>
                                                    <th>Invoice Date</th>
                                                    <th class="text-start">Invoice Number</th>
                                                    <th>Reference</th>
                                                    <th>Original Amount</th>
                                                    <th>Amount Paid</th>
                                                    <th>Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $receipt->invoice->invoice_date->format('d M Y') }}</td>
                                                    <td class="text-start">{{ $receipt->invoice->invoice_number ?? '-' }}
                                                    </td>
                                                    <td></td>
                                                    <td>{{ $receipt->invoice->grant_total }}</td>
                                                    <td>{{ $receipt->amount }}</td>
                                                    <td>{{ $receipt->invoice->balance_amount }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="preview-bottom-btns">
                        <button onclick="printDiv('printArea')" class="add-btn">
                            <i class="fa-solid fa-print me-2"></i> Print
                        </button>
                        <a href="{{ route('admin.receipts.pdf', $receipt->id) }}" class="submit-btn">
                            <i class="fa-solid fa-file-pdf me-2"></i> Download PDF
                        </a>
                        @if(isset($inv) && $inv)
                            <a href="{{ route('admin.receipts.index') }}?inv_id={{ $receipt->invoice_id }}" class="btn-back-cs">
                                Back
                            </a>
                        @elseif(isset($workPlanData) && $workPlanData)
                            <a href="{{ route('admin.work-plans.show', $workPlanData) }}" class="btn-back-cs">
                                Back
                            </a>
                        @else
                            <a href="{{ route('admin.payments.index') }}" class="btn-back-cs">
                                Back
                            </a>
                        @endif
                        @if (!$receipt->plannerPayout)
                            @can('or.edit')
                                <button class="add-btn planner-payout" data-id="{{ $receipt->id }}"
                                    style="background-color:#adaa17; border-color:#adaa17">

                                    Planner Commission Not Paid
                                </button>
                            @endcan
                        @else
                            <button type="button" class="add-btn" data-bs-toggle="modal" data-bs-target="#payoutModal"
                                style="background-color:#078d80; border-color:#078d80">

                                Planner Commission Paid
                            </button>
                        @endif
                        @if (!$receipt->productionStaffPayout)
                            @can('or.edit')
                                <button class="add-btn production-payout" data-id="{{ $receipt->id }}"
                                    style="background-color:#a6417b; border-color:#a6417b">

                                    Production Commission Not Paid
                                </button>
                            @endcan
                        @else
                            <button type="button" class="add-btn" data-bs-toggle="modal" data-bs-target="#productionModal "
                                style="background-color:#4b7506; border-color:#4b7506">

                                Production Commission Paid
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>


    </div>
    </div>
    </div>

    <div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Dynamic content here -->
                </div>
            </div>
        </div>
    </div>
    @if ($receipt->plannerPayout)
        <div class="modal fade" id="payoutModal" tabindex="-1" aria-labelledby="payoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="payoutModalLabel">Planner Payout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="wo-preview-top company-top-logo">
                            @if (
                                    $receipt->invoice &&
                                    $receipt->invoice->quotation &&
                                    $receipt->invoice->quotation->workPlan &&
                                    $receipt->invoice->quotation->workPlan &&
                                    $receipt->invoice->quotation->workPlan->totalGroup &&
                                    $receipt->invoice->quotation->workPlan->totalGroup->billerProfile
                                )
                                <img src="{{ asset('storage/' . $receipt->invoice->quotation->workPlan->totalGroup->billerProfile->receipt_header) }}"
                                    style="width:150px">
                            @else
                                <img src="{{ asset('assets/images/default-logo.png') }}" alt="Company Logo" style="width:150px">
                            @endif
                            @if (
                                    $receipt->invoice &&
                                    $receipt->invoice->quotation &&
                                    $receipt->invoice->quotation->workPlan &&
                                    $receipt->invoice->quotation->workPlan->totalGroup &&
                                    $receipt->invoice->quotation->workPlan->totalGroup->billerProfile
                                )
                                <p>{!! $receipt->invoice->quotation->workPlan->totalGroup->billerProfile->address !!}</p>
                            @else
                                <p>Not Available</p>
                            @endif
                        </div>
                        <div class="preview-container-center mt-4">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <div class="preview-container-center-two bg-prev ">
                                        <ul>
                                            <li>Planner: <span>{{  $receipt->plannerPayout->planner->name ?? ''  }}</span></li>
                                            <li>Payout Amount: <span>{{  $receipt->plannerPayout->amount }}</span></li>
                                            <li>Payout Date:
                                                <span>{{  $receipt->plannerPayout->created_at->format('d M Y') ?? '' }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <div class="preview-container-center-two bg-prev">

                                        <ul>
                                            <li></li>
                                            <li>Payment Method:
                                                <span>{{  $receipt->plannerPayout->payment_method ?? ''}}</span>
                                            </li>
                                            <li>Payout Status: <span> {{ $receipt->plannerPayout->status ?? '-' }}</span></li>
                                            <li>Payout Remarks: <span>{{ $receipt->plannerPayout->remarks ?? '-' }}</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-">
                        <button type="button" class="btn-back-cs" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($receipt->productionStaffPayout)
        <div class="modal fade" id="productionModal" tabindex="-1" aria-labelledby="productionModalModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productionModalModalLabel">Production Payout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="wo-preview-top company-top-logo">
                            @if (
                                    $receipt->invoice &&
                                    $receipt->invoice->quotation &&
                                    $receipt->invoice->quotation->workPlan &&
                                    $receipt->invoice->quotation->workPlan &&
                                    $receipt->invoice->quotation->workPlan->totalGroup &&
                                    $receipt->invoice->quotation->workPlan->totalGroup->billerProfile
                                )
                                <img src="{{ asset('storage/' . $receipt->invoice->quotation->workPlan->totalGroup->billerProfile->receipt_header) }}"
                                    style="width:150px">
                            @else
                                <img src="{{ asset('assets/images/default-logo.png') }}" alt="Company Logo" style="width:150px">
                            @endif
                            @if (
                                    $receipt->invoice &&
                                    $receipt->invoice->quotation &&
                                    $receipt->invoice->quotation->workPlan &&
                                    $receipt->invoice->quotation->workPlan->totalGroup &&
                                    $receipt->invoice->quotation->workPlan->totalGroup->billerProfile
                                )
                                <p>{!!  $receipt->invoice->quotation->workPlan->totalGroup->billerProfile->address !!}</p>
                            @else
                                <p>Not Available</p>
                            @endif
                        </div>
                        <div class="preview-container-center mt-4">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <div class="preview-container-center-two  bg-prev">
                                        <ul>
                                            <li>Production Staff:
                                                <span>{{  $receipt->productionStaffPayout->productionStaff->name ?? '' }}</span>
                                            </li>
                                            <li>Payout Amount: <span>{{  $receipt->productionStaffPayout->amount }}</span></li>
                                            <li>Payout Date:
                                                <span>{{  $receipt->productionStaffPayout->created_at->format('d M Y') ?? '' }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <div class="preview-container-center-two bg-prev">

                                        <ul>
                                            <li></li>
                                            <li>Payment Method:
                                                <span>{{  $receipt->productionStaffPayout->payment_method ?? '' }}</span>
                                            </li>
                                            <li>Payout Status: <span>
                                                    {{ $receipt->productionStaffPayout->status ?? '-' }}</span></li>
                                            <li>Payout Remarks:
                                                <span>{{ $receipt->productionStaffPayout->remarks ?? '-' }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-">
                        <button type="button" class="btn-back-cs" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    @include('admin.scripts.script')
    <script>
        function printDiv(divId) {
            const printContents = document.getElementById(divId).innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        $(document).on('click', '.planner-payout', function () {
            let id = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.work-orders.planner.payout-view') }}",
                method: 'GET',
                data: {
                    id: id
                },
                success: function (response) {
                    $('#modalBody').html(response.html);
                    $('#modalTitle').text(response.title);
                    $('#formModal').modal('show');
                },
                error: function () {
                    alert('Failed to load status change form.');
                }
            });
        });

        $(document).on('click', '.production-payout', function () {
            let id = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.work-orders.production.payout-view') }}",
                method: 'GET',
                data: {
                    id: id
                },
                success: function (response) {
                    $('#modalBody').html(response.html);
                    $('#modalTitle').text(response.title);
                    $('#formModal').modal('show');
                },
                error: function () {
                    alert('Failed to load status change form.');
                }
            });
        });

        $(document).on('submit', '#commonFormTwo', function (e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');
            let method = form.find('input[name="_method"]').val() || 'POST';
            let formData = form.serialize();

            Swal.fire({
                title: 'Are you sure ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, continue',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.find('.error-text').text('');

                    $.ajax({
                        url: url,
                        type: method,
                        data: formData,
                        success: function (response) {
                            $('#formModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                confirmButtonText: 'OK',
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function (xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                $.each(errors, function (field, messages) {
                                    form.find('.' + field + '_error').text(messages[0]);
                                });
                            } else {
                                Swal.fire('Error', 'Something went wrong', 'error');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection