<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.account-statements.original-receipts') }}",
                dataSrc: function (json) {
                    $('#totalAmount').text(json.total_amount);
                    return json.data;
                },
                data: function (d) {
                    d.total_group = $('#filter-total-group').val();
                    d.from_date = $('#filter-from-date').val();
                    d.to_date = $('#filter-to-date').val();
                    d.status = $('#filter-status').val();
                    d.work_order = $('#filter-wo').val();
                    d.invoice = $('#filter-invoice').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'work_order_number', name: 'work_order_number' },
                { data: 'invoice_number', name: 'invoice_number' },
                { data: 'created_at', name: 'created_at' },
                { data: 'invoice_amount', name: 'invoice_amount' },
                { data: 'or_number', name: 'or_number' },
                { data: 'amount', name: 'amount' },
                { data: 'stt', name: 'stt' },
                { data: 'status', name: 'status' },
            ]
        });

        $('#filter-total-group,#filter-status, #filter-from-date, #filter-to-date,#filter-wo,#filter-invoice').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-total-group').val('').trigger('change');
            $('#filter-status').val('').trigger('change');
            $('#filter-from-date').val('');
            $('#filter-to-date').val('');
            $('#filter-wo').val('');
            $('#filter-invoice').val('');
            table.draw();
        });



        $('#exportSelected').on('click', function () {

            let totalGroup = $('#filter-total-group').val();
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
                url: '{{ route('admin.or.export.pdf') }}',
                type: 'POST',
                data: {
                    total_group: totalGroup,
                    from_date: fromDate,
                    to_date: toDate,
                    status: $('#filter-status').val(),
                    work_order: $('#filter-wo').val(),
                    invoice: $('#filter-invoice').val(),
                    _token: '{{ csrf_token() }}'
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (response) {
                    let blob = new Blob([response], { type: 'application/pdf' });
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'or-report.pdf';
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