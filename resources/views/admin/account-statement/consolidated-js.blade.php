<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.account-statements.consolidated') }}",
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
                    d.total_group = $('#filter-total-group').val();
                    d.from_date = $('#filter-from-date').val();
                    d.to_date = $('#filter-to-date').val();
                    d.planner = $('#filter-planner').val();
                    d.customer = $('#filter-customer').val();
                    d.month = $('#filter-month').val();
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
                { data: 'total_group', name: 'total_group' },
                { data: 'planner', name: 'planner' },
                { data: 'customer_name', name: 'customer_name' },
                { data: 'invoice_amount', name: 'invoice_amount' },
                { data: 'status', name: 'status' },
                { data: 'quotation_number', name: 'quotation_number' },
                { data: 'invoice_number', name: 'invoice_number' },
                { data: 'invoice_date', name: 'invoice_date' },
                { data: 'payment_status', name: 'payment_status' },
            ]
        });

        $('#filter-total-group,#filter-planner,#filter-customer, #filter-from-date, #filter-to-date,#filter-month').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-total-group').val('').trigger('change');
            $('#filter-planner').val('').trigger('change');
            $('#filter-customer').val('').trigger('change');
            $('#filter-from-date').val('');
            $('#filter-to-date').val('');
            $('#filter-month').val('');
            table.draw();
        });

        $('#exportPdf').on('click', function () {

            let totalGroup = $('#filter-total-group').val();

            Swal.fire({
                title: 'Generating Report...',
                text: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: '{{ route('admin.consolidated.export.pdf') }}',
                type: 'POST',
                data: {
                    total_group: totalGroup,
                    from_date: $('#filter-from-date').val(),
                    to_date: $('#filter-to-date').val(),
                    planner: $('#filter-planner').val(),
                    customer: $('#filter-customer').val(),
                    month: $('#filter-month').val(),
                    _token: '{{ csrf_token() }}'
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (response) {
                    let blob = new Blob([response], { type: 'application/pdf' });
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'consolidated-report.pdf';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },
                complete: function () {
                    Swal.close();
                }
            });
        });
    });

</script>