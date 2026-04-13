<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.account-statements.invoice') }}",
                dataSrc: function (json) {
                    $('#totalAmount').text(json.total_amount);
                    $('#totalBalanceAmount').text(json.total_balance_amount);
                    $('#totalPaidAmount').text(json.total_paid_amount);

                    return json.data;
                },
                data: function (d) {
                    d.total_group = $('#filter-total-group').val();
                    d.from_date = $('#filter-from-date').val();
                    d.to_date = $('#filter-to-date').val();
                    d.planner = $('#filter-planner').val();
                    d.customer = $('#filter-customer').val();
                    d.status = $('#filter-status').val();
                    d.work_order = $('#filter-wo').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'work_order_number', name: 'work_order_number' },
                { data: 'invoice_number', name: 'invoice_number' },
                { data: 'invoice_date', name: 'invoice_date' },
                { data: 'grant_total', name: 'grant_total' },
                { data: 'or', name: 'or' },
                { data: 'or_amount', name: 'or_amount' },
                { data: 'payment_status', name: 'payment_status' },
                { data: 'cr', name: 'cr' },
                { data: 'cr_amount', name: 'cr_amount' },
            ]
        });

        $('#filter-total-group,#filter-planner,#filter-customer,#filter-status, #filter-from-date, #filter-to-date,#filter-wo').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-total-group').val('').trigger('change');
            $('#filter-planner').val('').trigger('change');
            $('#filter-customer').val('').trigger('change');
            $('#filter-status').val('').trigger('change');
            $('#filter-from-date').val('');
            $('#filter-to-date').val('');
            $('#filter-wo').val('');
            table.draw();
        });


        $('#exportSelected').on('click', function () {

            let totalGroup = $('#filter-total-group').val();
            let planner = $('#filter-planner').val();

            let fromDate = $('#filter-from-date').val();
            let toDate = $('#filter-to-date').val();

            Swal.fire({
                title: 'Exporting PDF...',
                text: 'Please wait while we prepare your file.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '{{ route('admin.invoice.export.pdf') }}',
                type: 'POST',
                data: {
                    total_group: totalGroup,
                    planner: planner,
                    from_date: fromDate,
                    to_date: toDate,
                    customer: $('#filter-customer').val(),
                    status: $('#filter-status').val(),
                    work_order: $('#filter-wo').val(),
                    _token: '{{ csrf_token() }}'
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (response) {
                    let blob = new Blob([response], { type: 'application/pdf' });
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'invoice-report.pdf';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },
                complete: function () {
                    Swal.close();
                },
                error: function () {
                    alert('Something went wrong while exporting.');
                }
            });
        });

    });

</script>