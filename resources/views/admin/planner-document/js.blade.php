<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.planner-documents.index') }}",
                data: function (d) {
                    d.status = $('#filter-status').val();
                    d.name = $('#filter-name').val();
                    d.category = $('#filter-category').val();
                    d.company = $('#filter-company').val();
                    d.year = $('#filter-year').val();
                    d.total_group = $('#filter-total-group').val();
                    d.type = $('#filter-type').val();
                    d.planner = $('#filter-planner').val();


                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'planner', name: 'planner' },
                { data: 'total_group', name: 'total_group' },
                { data: 'type', name: 'type' },
                //{ data: 'start_date', name: 'start_date' },
                //{ data: 'end_date', name: 'end_date' },
                { data: 'year', name: 'year' },
                { data: 'status', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });

        $('#filter-status,#filter-name,#filter-company,#filter-year,#filter-total-group, #filter-type,#filter-planner').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-status').val('').trigger('change');
            $('#filter-company').val('').trigger('change');
            $('#filter-year').val('').trigger('change');
            $('#filter-type').val('').trigger('change');
            $('#filter-total-group').val('').trigger('change');
            $('#filter-planner').val('').trigger('change');
            $('#filter-name').val('');
            table.draw();
        });

        $(document).on('click', '.change-status', function () {
            let projectId = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.planner-documents.status.view') }}",
                method: 'GET',
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


        $(document).on('click', '.view', function () {
            let projectId = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.planner-documents.show', ':id') }}".replace(':id', projectId), method: 'GET',
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


        $('#exportSelected').on('click', function () {
            Swal.fire({
                title: 'Exporting...',
                text: 'Please wait while we prepare your file.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '{{ route('admin.planner-documents.export') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                xhrFields: {
                    responseType: 'arraybuffer'
                },
                success: function (response, status, xhr) {

                    let filename = 'planner-documents.csv';
                    let disposition = xhr.getResponseHeader('Content-Disposition');

                    if (disposition && disposition.indexOf('attachment') !== -1) {
                        let match = disposition.match(/filename="(.+)"/);
                        if (match && match.length === 2) filename = match[1];
                    }

                    let blob = new Blob([response]);
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },
                complete: function () {
                    $('#checkAll').prop('checked', false);
                    $('#table tbody input.row-check').prop('checked', false);
                    Swal.close();
                },
                error: function () {
                    alert('Something went wrong while exporting.');
                }
            });
        });

    });

    function deleteRow(id) {
        deleteRecord('/admin/planner-documents/' + id, 'table', 'Do you really want to delete this record?');
    }
</script>