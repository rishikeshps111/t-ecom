<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.account-statements.production-commission') }}",
                dataSrc: function (json) {
                    $('#totalAmount').text(json.total_amount);
                    return json.data;
                },
                data: function (d) {
                    d.production_staff = $('#filter-production').val();
                    d.from_date = $('#filter-from-date').val();
                    d.to_date = $('#filter-to-date').val();
                    d.total = $('#filter-total').val();

                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'work_order', name: 'work_order' },
                { data: 'receipt_number', name: 'receipt_number' },
                { data: 'amount', name: 'amount' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'remarks', name: 'remarks' },
            ]
        });

        $('#filter-production,#filter-from-date, #filter-to-date, #filter-total').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-production').val('').trigger('change');
            $('#filter-total').val('').trigger('change');
            $('#filter-from-date').val('');
            $('#filter-to-date').val('');
            table.draw();
        });

        $('#checkAll').on('click', function () {
            $('.row-check').prop('checked', this.checked);
        });


        $('#exportSelected').on('click', function () {

            let total = $('#filter-total').val();

            if (table.rows({ search: 'applied' }).count() === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No data',
                    text: 'No rows found'
                });
                return;
            }

            let url = "{{ route('admin.account-statements.production-commission.export') }}";

            url += "?total=" + total;
            url += "&production_staff=" + $('#filter-production').val();
            url += "&from_date=" + $('#filter-from-date').val();
            url += "&to_date=" + $('#filter-to-date').val();

            window.open(url, '_blank');
        });
    });

</script>