<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.invoices.index') }}",
                data: function (d) {
                    d.invoice = $('#filter-invoice').val();
                    d.customer = $('#filter-customer').val();
                    d.invoice_date = $('#filter-invoice-date').val();
                    d.due_date = $('#filter-due-date').val();
                    d.status = $('#filter-status').val();
                    d.total_group = $('#filter-total-group').val();
                    d.type = $('#filter-type').val();
                    d.quotation = $('#filter-quotation').val();
                    d.from_date = $('#filter-from-date').val();
                    d.to_date = $('#filter-to-date').val();
                    d.work_order = $('#filter-work').val();
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
                { data: 'custom_id', name: 'custom_id' },
                { data: 'quotation', name: 'quotation' },
                { data: 'company', name: 'company' },
                { data: 'type', name: 'type' },
                { data: 'invoice_date', name: 'invoice_date' },
                //{ data: 'due_date', name: 'due_date' },
                { data: 'status', name: 'status' },
                //{ data: 'grant_total', name: 'grant_total' },
                //{ data: 'paid_amount', name: 'paid_amount' },
                //{ data: 'balance_amount', name: 'balance_amount' },
                { data: 'payment_status', name: 'payment_status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });

        $('#filter-status, #filter-invoice, #filter-customer, #filter-invoice-date, #filter-due-date, #filter-total-group, #filter-type, #filter-quotation, #filter-from-date, #filter-to-date, #filter-work').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-status').val('').trigger('change');
            $('#filter-total-group').val('').trigger('change');
            $('#filter-type').val('').trigger('change');
            $('#filter-invoice').val('');
            $('#filter-customer').val('');
            $('#filter-invoice-date').val('');
            $('#filter-due-date').val('');
            $('#filter-quotation').val('');
            $('#filter-from-date').val('');
            $('#filter-to-date').val('');
            $('#filter-work').val('');

            table.draw();
        });

        $(document).on('click', '.open-approval-modal', function () {
            let invoiceId = $(this).data('id');

            $('#invoice_id').val(invoiceId);
            $('#approvalLevels').html('<p>Loading...</p>');

            $.get(`/admin/invoices/${invoiceId}/approvals`, function (res) {
                let html = '';

                res.approvals.forEach(function (item, index) {
                    html += `
            <div class="border rounded p-3 mb-3">
                <h6>Level ${item.level} - ${item.role}</h6>

                <select name="approvals[${index}][status]" class="form-select approval-status"
                    ${res.status === 'approved' ? 'disabled' : ''}>
                    <option value="pending" ${item.status == 'pending' ? 'selected' : ''}>Pending</option>
                    <option value="approved" ${item.status == 'approved' ? 'selected' : ''}>Approved</option>
                    <option value="rejected" ${item.status == 'rejected' ? 'selected' : ''}>Rejected</option>
                </select>

                <textarea
                    name="approvals[${index}][rejection_reason]"
                    class="form-control mt-2 rejection-reason"
                    placeholder="Rejection reason"
                    ${item.status != 'rejected' ? 'style="display:none"' : ''}
                >${item.rejection_reason ?? ''}</textarea>

                <input type="hidden" name="approvals[${index}][id]" value="${item.id}">
            </div>
            `;
                });

                $('#approvalLevels').html(html);
                if (res.status === 'approved') {
                    $('#saveApproval').hide();
                } else {
                    $('#saveApproval').show();
                }
                $('#approvalModal').modal('show');
            });
        });

        $(document).on('change', '.approval-status', function () {
            let container = $(this).closest('.border');
            let reason = container.find('.rejection-reason');

            if ($(this).val() === 'rejected') {
                reason.show();
            } else {
                reason.hide().val('');
            }
        });

        $('#saveApproval').click(function (e) {
            e.preventDefault();

            $.post('/admin/invoices/approvals/update', $('#approvalForm').serialize(), function () {
                $('#approvalModal').modal('hide');
                table.draw();
                showToast('success', 'Updated Successfully')
            });
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
                url: '{{ route('admin.invoices.export') }}',
                type: 'POST',
                data: {
                    ids: selectedIds,
                    _token: '{{ csrf_token() }}'
                },
                xhrFields: {
                    responseType: 'arraybuffer'
                },
                success: function (response, status, xhr) {

                    let filename = 'invoices.csv';
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
                url: "{{ route('admin.invoices.status.view') }}",
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
                ? 'Approve this Invoice?'
                : 'Reject this Invoice?';

            let confirmMessage = status === 'approved'
                ? 'This will approve the invoice'
                : 'This will reject the invoice.';

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


    });

    function deleteRow(id) {
        deleteRecord('/admin/invoices/' + id, 'table', 'Do you really want to delete this record?');
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