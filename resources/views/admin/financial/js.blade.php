<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.financial-years.index') }}",
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'year', name: 'year' },
                { data: 'status', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });


        $(document).on('click', '.form-btn', function () {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.financial-years.create') }}",
                type: 'GET',
                data: { id: id },
                success: function (response) {
                    $('#modalBody').html(response.html);
                    $('#modalTitle').text(response.title);
                    $('#formModal').modal('show');
                },
                error: function (xhr) {
                    console.log('Error:', xhr.responseText);
                    alert('Failed to load form.');
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

        $(document).on('click', '.view', function () {
            let projectId = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.messages.show', ':id') }}".replace(':id', projectId), method: 'GET',
                data: { id: projectId },
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

        $(document).on('click', '.toggleStatus', function () {
            let id = $(this).data('id');
            let currentStatus = $(this).data('status');
            let newStatus = currentStatus == 1 ? 0 : 1;

            $.ajax({
                url: "{{ route('admin.financial-years.status') }}",
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

        $(document).on('input', 'input[name="year"]', function () {
            let value = $(this).val();
            value = value.replace(/\D/g, '');
            value = value.slice(0, 4);
            $(this).val(value);
        });


    });

    function selectInt() {
        $('.multi-select').select2({
            width: '100%',
            placeholder: 'Select an option',
            allowClear: true,
            dropdownParent: '#commonForm'
        });
    }

    function deleteRow(id) {
        deleteRecord('/admin/financial-years/' + id, 'table', 'Do you really want to delete this record?');
    }
</script>