<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.account-statements.original-receipts') }}",
                dataSrc: function (json) {
                    $('#totalAmount').text(json.total_amount);
                    return json.data;
                },
                data: function (d) {
                    d.total_group = $('#filter-total-group').val();
                    d.from_date = $('#filter-from-date').val();
                    d.to_date = $('#filter-to-date').val();
                    d.status = $('#filter-status').val();
                    d.work_order = $('#filter-wo').val();
                    d.invoice = $('#filter-invoice').val();
                }
            },
            columns: [
                {
                    data: 'checkbox',
                    name: 'checkbox',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `<input type="checkbox" class="row-check" value="${row.id}">`;
                    },
                    className: 'text-center'
                },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'work_order_number', name: 'work_order_number' },
                { data: 'invoice_number', name: 'invoice_number' },
                { data: 'created_at', name: 'created_at' },
                { data: 'invoice_amount', name: 'invoice_amount' },
                { data: 'or_number', name: 'or_number' },
                { data: 'amount', name: 'amount' },
                { data: 'status', name: 'status' },
            ]
        });

        $('#filter-total-group,#filter-status, #filter-from-date, #filter-to-date,#filter-wo,#filter-invoice').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-total-group').val('').trigger('change');
            $('#filter-status').val('').trigger('change');
            $('#filter-from-date').val('');
            $('#filter-to-date').val('');
            $('#filter-wo').val('');
            $('#filter-invoice').val('');
            table.draw();
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
                url: '{{ route('admin.invoices.export') }}',
                type: 'POST',
                data: {
                    ids: selectedIds,
                    _token: '{{ csrf_token() }}'
                },
                xhrFields: {
                    responseType: 'arraybuffer'
                },
                success: function (response, status, xhr) {

                    let filename = 'invoices.csv';
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

</script>