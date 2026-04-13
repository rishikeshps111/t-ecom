<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.account-statements.planner-commission') }}",
                dataSrc: function (json) {
                    $('#totalAmount').text(json.total_amount);
                    return json.data;
                },
                data: function (d) {
                    d.planner = $('#filter-planner').val();
                    d.from_date = $('#filter-from-date').val();
                    d.to_date = $('#filter-to-date').val();
                    d.customer = $('#filter-customer').val();
                    d.total = $('#filter-total').val();

                }
            },
            columns: [
                // {
                //     data: 'checkbox',
                //     name: 'checkbox',
                //     orderable: false,
                //     searchable: false,
                //     render: function (data, type, row) {
                //         return `<input type="checkbox" class="row-check" value="${row.id}">`;
                //     },
                //     className: 'text-center'
                // },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'work_order', name: 'work_order' },
                { data: 'receipt_number', name: 'receipt_number' },
                { data: 'customer', name: 'customer' },
                { data: 'amount', name: 'amount' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'remarks', name: 'remarks' },
            ]
        });

        $('#filter-planner, #filter-customer, #filter-from-date, #filter-to-date,#filter-total').on('change', function () {
            table.draw();
        });

        // Update Reset button
        $('#reset-filters').on('click', function () {
            $('#filter-planner, #filter-customer, #filter-total').val('').trigger('change');
            $('#filter-from-date').val('{{ now()->subDays(30)->toDateString() }}');
            $('#filter-to-date').val('{{ now()->toDateString() }}');
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

            let url = "{{ route('admin.account-statements.planner-commission.export') }}";

            url += "?total=" + total;
            url += "&planner=" + $('#filter-planner').val();
            url += "&customer=" + $('#filter-customer').val();
            url += "&from_date=" + $('#filter-from-date').val();
            url += "&to_date=" + $('#filter-to-date').val();

            window.open(url, '_blank');
        });
    });

</script>