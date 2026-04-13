<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.biller-profiles.index') }}",
                data: function (d) {
                    d.total_group = $('#filter-total-group').val();
                    d.company = $('#filter-company').val();
                }
            },
            columns: [

                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'total_group', name: 'total_group' },
                { data: 'status', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });

        $('#filter-total-group, #filter-company').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-total-group').val('').trigger('change');
            $('#filter-company').val('').trigger('change');
            table.draw();
        });


    });

    function deleteRow(id) {
        deleteRecord('/admin/biller-profiles/' + id, 'table', 'Do you really want to delete this record?');
    }
</script>