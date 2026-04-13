<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.activity.index') }}",
                data: function (d) {
                    d.user = $('#filter-user').val();
                    d.module = $('#filter-module').val();
                    d.from = $('#filter-from').val();
                    d.to = $('#filter-to').val();
                }
            },
            columns: [
                {
                    data: 'checkbox',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'event', name: 'description' },
                { data: 'subject', name: 'subject_type' },
                { data: 'user', name: 'causer.name' },
                { data: 'time', name: 'created_at' }
            ]
        });

        $('#filter-user, #filter-module, #filter-from, #filter-to').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-module').val('');
            $('#filter-user').val('');
            $('#filter-from').val('');
            $('#filter-to').val('');
            table.draw();
        });

        $('#select-all').on('click', function () {
            $('.row-checkbox').prop('checked', this.checked);
            toggleBulkDelete();
        });

        // Individual checkbox
        $(document).on('change', '.row-checkbox', function () {
            toggleBulkDelete();
        });

        function toggleBulkDelete() {
            let anyChecked = $('.row-checkbox:checked').length > 0;
            $('#bulk-delete').prop('disabled', !anyChecked);
        }

        $('#bulk-delete').on('click', function () {
            let ids = [];

            $('.row-checkbox:checked').each(function () {
                ids.push($(this).val());
            });

            if (ids.length === 0) return;

            Swal.fire({
                title: 'Are you sure?',
                text: 'Selected activity logs will be permanently deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.activity.bulk-delete') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            ids: ids
                        },
                        success: function () {
                            Swal.fire('Deleted!', 'Activity logs removed.', 'success');
                            table.draw(false);
                            $('#select-all').prop('checked', false);
                            $('#bulk-delete').prop('disabled', true);
                        }
                    });
                }
            });
        });

    });

</script>