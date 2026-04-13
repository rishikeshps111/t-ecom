<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.knowledge-bases.index') }}",
                data: function (d) {
                    d.status = $('#filter-status').val();
                    d.title = $('#filter-title').val();
                    d.category = $('#filter-category').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'category', name: 'category' },
                { data: 'keywords', name: 'keywords' },
                { data: 'status', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });

        $('#filter-status,#filter-title,#filter-category,#filter-company').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-status').val('').trigger('change');
            $('#filter-category').val('').trigger('change');
            $('#filter-title').val('');
            table.draw();
        });

        $(document).on('click', '.change-status', function () {
            let id = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.knowledge-bases.status.view') }}",
                method: 'GET',
                data: { id: id },
                success: function (response) {
                    $('#modalBody').html(response.html);
                    $('#modalTitle').text(response.title);
                    $('#formModal').modal('show');
                },
                error: function () {
                    alert('Failed to load status change form.');
                }
            });
        });


        $(document).on('submit', '#commonForm', function (e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var method = form.find('input[name="_method"]').val() || 'POST';
            var formData = form.serialize();
            form.find('.error-text').text('');
            $.ajax({
                url: url,
                type: method,
                data: formData,
                success: function (response) {
                    table.ajax.reload();
                    $('#formModal').modal('hide');
                    showToast('success', response.message);
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function (field, messages) {
                            form.find('.' + field + '_error').text(messages[0]);
                        });
                    } else {
                        showToast('error', 'Something went wrong');
                    }
                }
            });
        });

    });

    function deleteRow(id) {
        deleteRecord('/admin/knowledge-bases/' + id, 'table', 'Do you really want to delete this record?');
    }
</script>