<script>
    $(function () {
        const HAS_TYPE_FILTER = @json(isset($companyId));

        let columns = [{
            data: 'checkbox',
            name: 'checkbox',
            orderable: false,
            searchable: false,
            render: (data, type, row) =>
                `<input type="checkbox" class="row-check" value="${row.id}">`,
            className: 'text-center'
        },
        {
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            orderable: false,
            searchable: false
        },
        {
            data: 'title',
            name: 'title'
        },
        // {
        //     data: 'type',
        //     name: 'type'
        // },
        {
            data: 'total_group',
            name: 'total_group'
        }, {
            data: 'type',
            name: 'type'
        },

        ];

        if (!HAS_TYPE_FILTER) {
            columns.push({
                data: 'company',
                name: 'company'
            });
        }

        columns.push(
            //{
            // data: 'valid_from',
            //    name: 'valid_from'
            // },
            //  {
            //    data: 'valid_to',
            //name: 'valid_to'
            //   },
            {
                data: 'year',
                name: 'year'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            });

        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.documents.index') }}",
                data: function (d) {
                    const params = new URLSearchParams(window.location.search);
                    d.company_id = params.get('company_id');
                    d.project_id = params.get('project_id');

                    d.status = $('#filter-status').val();
                    d.title = $('#filter-title').val();
                    d.company = $('#filter-company').val();
                    d.project = $('#filter-project').val();
                    d.year = $('#filter-year').val();
                    d.total_group = $('#filter-total-group').val();
                    d.type = $('#filter-type').val();


                }
            },
            columns: columns
        });

        $('#filter-status,#filter-title,#filter-type,#filter-company,#filter-project,#filter-year,#filter-total-group').on('keyup change',
            function () {
                table.draw();
            });

        $('#reset-filters').on('click', function () {
            $('#filter-status').val('').trigger('change');
            $('#filter-type').val('').trigger('change');
            $('#filter-company').val('').trigger('change');
            $('#filter-project').val('').trigger('change');
            $('#filter-year').val('').trigger('change');
            $('#filter-title').val('');
            $('#filter-total-group').val('').trigger('change');
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

        $('#checkAll').on('click', function () {
            $('.row-check').prop('checked', this.checked);
        });


        $('#exportSelected').on('click', function () {
            let selectedIds = [];

            $('#table tbody input.row-check:checked').each(function () {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops!',
                    text: 'Please select at least one row to export.',
                });
                return;
            }

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
                url: '{{ route('admin.documents.export') }}',
                type: 'POST',
                data: {
                    ids: selectedIds,
                    _token: '{{ csrf_token() }}'
                },
                xhrFields: {
                    responseType: 'arraybuffer'
                },
                success: function (response, status, xhr) {

                    let filename = 'general-documents.csv';
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
        deleteRecord('/admin/documents/' + id, 'table', 'Do you really want to delete this record?');
    }
</script>