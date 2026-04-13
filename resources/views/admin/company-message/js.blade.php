<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.company-messages.index') }}",
                data: function (d) {
                    const params = new URLSearchParams(window.location.search);

                    d.company_id = params.get('company_id');
                    d.date = $('#filter-date').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'subject', name: 'subject' },
                { data: 'created_at', name: 'created_at' },
                { data: 'priority', name: 'priority' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });

        $('#filter-date').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-date').val('');
            table.draw();
        });

        $(document).on('click', '.send-mail', function () {
            const params = new URLSearchParams(window.location.search);
            $.ajax({
                url: "{{ route('admin.company-messages.create') }}",
                method: 'GET',
                data: {
                    company_id: params.get('company_id')
                },
                success: function (response) {
                    $('#modalBody').html(response.html);
                    $('#modalTitle').text(response.title);
                    selectInt();
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

        $(document).on('click', '.view', function () {
            let projectId = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.company-messages.show', ':id') }}".replace(':id', projectId), method: 'GET',
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
        deleteRecord('/admin/company-messages/' + id, 'table', 'Do you really want to delete this record?');
    }
</script>