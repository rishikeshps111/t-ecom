<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.account-statements.total-group') }}",
                dataSrc: function (json) {
                    $('#totalAmount').text(json.total_amount);
                    return json.data;
                },
                data: function (d) {
                    d.total_group = $('#filter-total-group').val();
                    d.start_date = $('#filter-start-date').val();
                    d.end_date = $('#filter-end-date').val();
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
                { data: 'payment_status', name: 'payment_status' }
            ]
        });

        $('#filter-start-date, #filter-end-date, #filter-total-group').on('change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-total-group').val('').trigger('change');
            $('#filter-start-date').val('');
            $('#filter-end-date').val('');
            table.draw();
        });


        $('#exportSelected').on('click', function () {

            let total_group = $('#filter-total-group').val();

            let start_date = $('#filter-start-date').val();
            let end_date = $('#filter-end-date').val();

            let url = "{{ route('admin.account-statements.total-group.export') }}";

            url += "?total_group=" + total_group
                + "&start_date=" + start_date
                + "&end_date=" + end_date;

            window.open(url, '_blank');

        });

    });

</script>