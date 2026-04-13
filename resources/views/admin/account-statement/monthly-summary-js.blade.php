<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            dom: 'Blfrtip', // ✅ enable buttons
            buttons: [
                {
                    text: 'Export PDF',
                    className: 'btn btn-danger btn-sm',
                    action: function () {
                        let month = $('#filter-month').val();
                        let total_group = $('#filter-total-group').val();

                        let url = "{{ route('admin.account-statements.monthly-summary.pdf') }}?" +
                            "month=" + (month || '') +
                            "&total_group=" + total_group;

                        window.open(url, '_blank');
                    }
                }
            ],
            ajax: {
                url: "{{ route('admin.account-statements.monthly-summary') }}",
                dataSrc: function (json) {
                    $('#totalAmount').text(json.total_amount);
                    $('#totalQuotationAmount').text(json.total_quotation_amount);
                    $('#totalInvoiceAmount').text(json.total_amount);
                    $('#totalPaidAmount').text(json.total_paid_amount);
                    $('#totalBalanceAmount').text(json.total_balance_amount);
                    $('#totalReceiptAmount').text(json.total_receipt_amount);
                    $('#totalCreditAmount').text(json.total_credit_amount);
                    $('#totalPlannerAmount').text(json.total_planner_amount);
                    $('#totalProductionAmount').text(json.total_production_amount);


                    return json.data;
                },
                data: function (d) {
                    d.month = $('#filter-month').val();
                    d.total_group = $('#filter-total-group').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'workplan_number', name: 'workplan_number' },
                { data: 'customer_name', name: 'customer_name' },
                { data: 'total_group', name: 'total_group' },
                { data: 'amount', name: 'amount' },
                { data: 'invoice_amount', name: 'invoice_amount' },
                { data: 'paid_amount', name: 'paid_amount' },
                { data: 'credit_note_amount', name: 'credit_note_amount' },
                { data: 'debit_note_amount', name: 'debit_note_amount' },
                { data: 'balance_amount', name: 'balance_amount' },
                { data: 'payment_status', name: 'payment_status' },
            ]
        });
        $('#filter-month, #filter-total-group').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-month').val('');
            $('#filter-total-group').val('').trigger('change');
            table.draw();
        });

    });

</script>