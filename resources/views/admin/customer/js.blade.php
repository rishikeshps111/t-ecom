<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.customers.index') }}",
                data: function (d) {
                    d.customer_code = $('#filter-customer-code').val();
                    d.customer_name = $('#filter-customer-name').val();
                    d.company = $('#filter-company').val();
                    d.status = $('#filter-status').val();
                }
            },
            columns: [
                //{
                //    data: 'checkbox',
                //    name: 'checkbox',
                //    orderable: false,
                //    searchable: false,
                //    render: function (data, type, row) {
                //        return `<input type="checkbox" class="row-check" value="${row.id}">`;
                //    },
                //    className: 'text-center'
                //},
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'custom_user_id', name: 'custom_user_id' },
                //  { data: 'company', name: 'company' },
                { data: 'customer_name', name: 'customer_name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'created_at', name: 'created_at' },
                { data: 'status', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });

        $('#filter-status, #filter-customer-name, #filter-customer-code, #filter-company').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-status').val('').trigger('change');
            $('#filter-customer-name').val('');
            $('#filter-customer-code').val('');
            $('#filter-company').val('');
            table.draw();
        });

        $(document).on('click', '.toggleStatus', function () {
            let id = $(this).data('id');
            let currentStatus = $(this).data('status');
            let newStatus = currentStatus == 1 ? 0 : 1;

            $.ajax({
                url: "{{ route('admin.customers.status') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    status: newStatus
                },
                success: function (res) {
                    table.ajax.reload();
                    showToast('success', res.message);
                }
            });
        });

    });

    function deleteRow(id) {
        deleteRecord('/admin/customers/' + id, 'table', 'Do you really want to delete this record?');
    }
</script>