<script>
    $(function () {

        let columns = [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },

            {
                data: 'company',
                name: 'company'
            },
            {
                data: 'work_plan',
                name: 'work_plan'
            },
            {
                data: 'entity',
                name: 'entity'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ];


        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            //rowGroup: {
            //    dataSrc: 'work_plan'
            //},
            ajax: {
                url: "{{ route('admin.documents.company') }}",
                data: function (d) {
                    d.work_plan = $('#filter-work').val();
                    d.entity = $('#filter-entity').val();
                    d.type = $('#filter-type').val();
                    d.company_id = $('#filter-customer').val() || '{{ $companyId ?? null }}';
                    d.from_date = $('#filter-from-date').val();
                    d.to_date = $('#filter-to-date').val();
                }
            },
            columns: columns
        });

        $('#filter-work,#filter-entity,#filter-type,#filter-customer,#filter-from-date, #filter-to-date').on('keyup change',
            function () {
                table.draw();
            });

        $('#reset-filters').on('click', function () {
            $('#filter-work').val('').trigger('change');
            $('#filter-entity').val('').trigger('change');
            $('#filter-type').val('').trigger('change');
            $('#filter-customer').val('').trigger('change');
            $('#filter-from-date').val('');
            $('#filter-to-date').val('');
            table.draw();
        });

        $(document).on('click', '.view', function () {
            let projectId = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.documents.show', ':id') }}".replace(':id', projectId), method: 'GET',
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


        $('#exportSelected').on('click', function () {
            let selectedIds = [];

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
                url: '{{ route('admin.documents.export.company') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    work_plan: $('#filter-work').val(),
                    entity: $('#filter-entity').val(),
                    type: $('#filter-type').val(),
                    company_id: $('#filter-customer').val(),
                    from_date: $('#filter-from-date').val(),
                    to_date: $('#filter-to-date').val(),
                },
                xhrFields: {
                    responseType: 'arraybuffer'
                },
                success: function (response, status, xhr) {

                    let filename = 'documents.csv';
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
                    Swal.close();
                },
                error: function () {
                    alert('Something went wrong while exporting.');
                }
            });
        });

    });

    function deleteRow(id) {
        deleteRecord('/admin/documents/' + id, 'table', 'Do you really want to delete this record?');
    }
</script>