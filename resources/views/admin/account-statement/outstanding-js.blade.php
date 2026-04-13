<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.account-statements.outstanding-report') }}",
                dataSrc: function (json) {
                    $('#totalAmount').text(json.total_amount);
                    $('#pendingInvoice').text(json.pending_invoices);
                    $('#totalPlannerCommission').text(json.total_planner_commission);
                    $('#totalPlannerPaid').text(json.total_planner_paid);
                    $('#totalPlannerPending').text(json.total_planner_pending);

                    $('#totalPsCommission').text(json.total_ps_commission);
                    $('#totalPsPaid').text(json.total_ps_paid);
                    $('#totalPsPending').text(json.total_ps_pending);
                    // Dynamic totals section
                    $('#totalAmountDynamic').text(json.total_amount_dynamic);
                    $('#totalPaidAmountDynamic').text(json.total_paid_dynamic);
                    $('#totalBalanceAmountDynamic').text(json.total_balance_dynamic);

                    return json.data;
                },
                data: function (d) {
                    d.from_date = $('#filter-from-date').val();
                    d.to_date = $('#filter-to-date').val();
                    d.company = $('#filter-company').val();
                    d.customer = $('#filter-cus').val();
                    d.planner = $('#filter-planner').val();
                    d.production = $('#filter-production').val();
                    d.total = $('#filter-total').val();

                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'company_name', name: 'company_name' },
                { data: 'planner_id', name: 'planner_id' },
                { data: 'invoice', name: 'invoice' },
                { data: 'or_number', name: 'or_number' },
                { data: 'invoice_amount', name: 'invoice_amount' },
                { data: 'amount', name: 'amount' },
                { data: 'balance_amount', name: 'balance_amount' },
                { data: 'planner_commission', name: 'planner_commission' },
                { data: 'planner_commission_status', name: 'planner_commission_status' },
                { data: 'ps_commission', name: 'ps_commission' },
                { data: 'ps_commission_status', name: 'ps_commission_status' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action' },
            ]
        });

        $('#filter-from-date, #filter-to-date, #filter-company, #filter-cus, #filter-planner, #filter-production, #filter-total').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-from-date').val('');
            $('#filter-to-date').val('');
            $('#filter-company').val('').trigger('change');
            $('#filter-cus').val('').trigger('change');
            $('#filter-planner').val('').trigger('change');
            $('#filter-production').val('').trigger('change');
            $('#filter-total').val('').trigger('change');

            table.draw();
        });

        $('#exportCsv').click(function () {

            let from = $('#filter-from-date').val();
            let to = $('#filter-to-date').val();
            let company = $('#filter-company').val();
            let customer = $('#filter-cus').val();
            let planner = $('#filter-planner').val();
            let production = $('#filter-production').val();

            let url = "{{ route('admin.account-statements.outstanding-export') }}"
                + "?from_date=" + from
                + "&to_date=" + to
                + "&company=" + company
                + "&customer=" + customer
                + "&planner=" + planner
                + "&production=" + production;

            window.open(url, '_blank');
        });

        $('#exportPdf').click(function () {

            let from = $('#filter-from-date').val();
            let to = $('#filter-to-date').val();
            let company = $('#filter-company').val();
            let customer = $('#filter-cus').val();
            let planner = $('#filter-planner').val();
            let production = $('#filter-production').val();
            let total = $('#filter-total').val();


            let url = "{{ route('admin.account-statements.outstanding-pdf') }}"
                + "?from_date=" + from
                + "&to_date=" + to
                + "&company=" + company
                + "&customer=" + customer
                + "&planner=" + planner
                + "&total=" + total
                + "&production=" + production;

            window.open(url, '_blank');
        });

        $(document).on('click', '.settle-planner-btn', function () {
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

        $(document).on('click', '.settle-ps-btn', function () {
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
                                table.draw();
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

</script>