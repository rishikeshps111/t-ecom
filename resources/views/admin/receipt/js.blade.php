<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.receipts.index') }}?inv_id={{ $invoice->id }}",
                data: function (d) {
                    d.payment = $('#filter-payment').val();
                    d.date = $('#filter-date').val();
                    d.status = $('#filter-status').val();
                    d.receipt = $('#filter-number').val();
                }
            },
            columns: [
                { data: 'payment_id', name: 'payment_id' },
                { data: 'created_at', name: 'created_at' },
                //{ data: 'payment_method', name: 'payment_method' },
                { data: 'amount', name: 'amount' },
                { data: 'remark', name: 'remark' },
                //{ data: 'status', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });

        $('#filter-status, #filter-payment, #filter-date, #filter-number').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-status').val('').trigger('change');
            $('#filter-payment').val('').trigger('change');
            $('#filter-date').val('');
            $('#filter-number').val('');
            table.draw();
        });

    });
</script>