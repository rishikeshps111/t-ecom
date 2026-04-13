<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.account-statements.workOrders') }}",
                dataSrc: function (json) {
                    $('#totalAmount').text(json.total_amount);
                    return json.data;
                },
                data: function (d) {
                    d.total_group = $('#filter-total-group').val();
                    d.from_date = $('#filter-from-date').val();
                    d.to_date = $('#filter-to-date').val();
                    d.planner = $('#filter-planner').val();
                    d.customer = $('#filter-customer').val();
                    d.status = $('#filter-status').val();
                    d.staff = $('#filter-staff').val();
                }
            },
            columns: [
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

        $('#filter-total-group,#filter-planner,#filter-staff,#filter-customer,#filter-status, #filter-from-date, #filter-to-date').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-total-group').val('').trigger('change');
            $('#filter-planner').val('').trigger('change');
            $('#filter-customer').val('').trigger('change');
            $('#filter-status').val('').trigger('change');
            $('#filter-staff').val('').trigger('change');
            $('#filter-from-date').val('');
            $('#filter-to-date').val('');
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
                url: '{{ route('admin.invoices.export.pdf') }}', // NEW ROUTE
                type: 'POST',
                data: {
                    total_group: totalGroup,
                    from_date: fromDate,
                    to_date: toDate,
                    planner: $('#filter-planner').val(),
                    customer: $('#filter-customer').val(),
                    status: $('#filter-status').val(),
                    staff: $('#filter-staff').val(),
                    _token: '{{ csrf_token() }}'
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (response) {
                    let blob = new Blob([response], { type: 'application/pdf' });
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'work-orders.pdf';
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