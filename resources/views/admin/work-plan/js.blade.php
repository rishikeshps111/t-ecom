<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.work-orders.index') }}",
                data: function (d) {
                    d.number = $('#filter-number').val();
                    d.customer = $('#filter-customer').val();
                    d.planner = $('#filter-planner').val();
                    d.staff = $('#filter-staff').val();
                    d.status = $('#filter-status').val();
                    d.from_date = $('#filter-from-date').val();
                    d.to_date = $('#filter-to-date').val();
                    d.customer_id = '{{ $customerID ?? null }}';
                }
            },
            columns: [
                {
                    data: 'checkbox',
                    name: 'checkbox',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `<input type="checkbox" class="row-check" value="${row.id}">`;
                    },
                    className: 'text-center'
                },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'workplan_number', name: 'workplan_number' },
                { data: 'date', name: 'date' },
                { data: 'customer', name: 'customer' },
                { data: 'total_group', name: 'total_group' },
                { data: 'status', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });

        $('#filter-status,#filter-customer, #filter-number, #filter-from-date, #filter-to-date,#filter-planner,#filter-staff').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-status').val('').trigger('change');
            $('#filter-customer').val('').trigger('change');
            $('#filter-planner').val('').trigger('change');
            $('#filter-staff').val('').trigger('change');
            $('#filter-number').val('');
            $('#filter-from-date').val('');
            $('#filter-to-date').val('');
            table.draw();
        });



        $('#checkAll').on('click', function () {
            $('.row-check').prop('checked', this.checked);
        });


        $('#exportSelected').on('click', function () {
            let selectedIds = [];

            $('#table tbody input.row-check:checked').each(function () {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops!',
                    text: 'Please select at least one row to export.',
                });
                return;
            }

            Swal.fire({
                title: 'Exporting...',
                text: 'Please wait while we prepare your file.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '{{ route('admin.quotations.export') }}',
                type: 'POST',
                data: {
                    ids: selectedIds,
                    _token: '{{ csrf_token() }}'
                },
                xhrFields: {
                    responseType: 'arraybuffer'
                },
                success: function (response, status, xhr) {

                    let filename = 'workplans.csv';
                    let disposition = xhr.getResponseHeader('Content-Disposition');

                    if (disposition && disposition.indexOf('attachment') !== -1) {
                        let match = disposition.match(/filename="(.+)"/);
                        if (match && match.length === 2) filename = match[1];
                    }

                    let blob = new Blob([response]);
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },
                complete: function () {
                    $('#checkAll').prop('checked', false);
                    $('#table tbody input.row-check').prop('checked', false);
                    Swal.close();
                },
                error: function () {
                    alert('Something went wrong while exporting.');
                }
            });
        });

        $(document).on('click', '.change-status', function () {
            let id = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.work-orders.status.view') }}",
                method: 'GET',
                data: { id: id },
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

        $(document).on('submit', '#commonForm', function (e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');
            let method = form.find('input[name="_method"]').val() || 'POST';
            let formData = form.serialize();

            // Get status value (approved / cancelled)
            let status = form.find('[name="status"]').val();

            let confirmText = status === 'approved'
                ? 'Approve this Work Plan?'
                : 'Reject this Work Plan?';

            let confirmMessage = status === 'approved'
                ? 'This will approve the work plan and generate quotation, work order and invoice.'
                : 'This will reject the work plan.';

            Swal.fire({
                title: confirmText,
                text: confirmMessage,
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
                            table.ajax.reload();
                            $('#formModal').modal('hide');

                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                timer: 3000,
                                showConfirmButton: false
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


        $(document).on('click', '.close-order', function () {
            let workPlanId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This will permanently close the Work Order.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, close it!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    $.ajax({
                        url: "{{ route('admin.work-orders.close', ':id') }}".replace(':id', workPlanId),
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Closed!',
                                text: res.message
                            }).then(() => {
                                table.ajax.reload();
                            });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Cannot Close',
                                text: xhr.responseJSON?.message || 'Something went wrong'
                            });
                        }
                    });
                }
            });
        });

        $(document).on('click', '.reject-order', function () {
            let workPlanId = $(this).data('id');

            Swal.fire({
                title: 'Reject Work Order',
                text: 'Please provide the reason for rejection',
                icon: 'warning',
                input: 'textarea',
                inputPlaceholder: 'Enter rejection reason...',
                inputAttributes: {
                    rows: 3
                },
                showCancelButton: true,
                confirmButtonText: 'Reject',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d33',
                preConfirm: (reason) => {
                    if (!reason || reason.trim() === '') {
                        Swal.showValidationMessage('Rejection reason is required');
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Processing...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    $.ajax({
                        url: "{{ route('admin.work-orders.reject', ':id') }}".replace(':id', workPlanId),
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            rejection_reason: result.value
                        },
                        success: function (res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Rejected!',
                                text: res.message
                            }).then(() => {
                                table.ajax.reload(null, false);
                            });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'Something went wrong'
                            });
                        }
                    });
                }
            });
        });

        $(document).on('click', '.rejection-reason', function () {
            let id = $(this).data('id');

            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: "{{ route('admin.work-orders.rejection-reason', ':id') }}".replace(':id', id),
                type: "GET",
                success: function (res) {
                    Swal.fire({
                        title: 'Rejection Reason',
                        html: `
                    <div class="text-start">
                        <p class="mb-0">${res.reason}</p>
                    </div>
                `,
                        confirmButtonText: 'Close'
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Unable to fetch reason'
                    });
                }
            });
        });
    });

    function deleteRow(id) {
        deleteRecord('/admin/work-orders/' + id, 'table', 'Do you really want to delete this record?');
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const today = new Date();
        const fromDate = new Date();
        fromDate.setDate(today.getDate() - 30);

        const formatDate = (date) => date.toISOString().split('T')[0];

        $('#filter-from-date').val(formatDate(fromDate));
        $('#filter-to-date').val(formatDate(today));
    });
</script>