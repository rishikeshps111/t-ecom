<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.payments.index') }}",
                data: function (d) {
                    d.company = $('#filter-company').val();
                    d.year = $('#filter-year').val();
                    d.invoice_no = $('#filter-invoice').val();
                    d.status = $('#filter-status').val();
                    d.customer = $('#filter-customer').val();
                    d.total_group = $('#filter-total-group').val();
                    d.type = $('#filter-type').val();
                    d.receipt = $('#filter-receipt').val();
                    d.customer_id = '{{ $customerID ?? null }}';
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
                { data: 'invoice_no', name: 'invoice_no' },
                { data: 'custom_payment_id', name: 'custom_payment_id' },
                { data: 'created_at', name: 'created_at' },
                { data: 'company', name: 'company' },
                //{ data: 'type', name: 'type' },
                //{ data: 'invoice_date', name: 'invoice_date' },
                //{ data: 'payment_method', name: 'payment_method' },
                //{ data: 'remark', name: 'remark' },
                { data: 'amount', name: 'amount' },
                //{ data: 'status', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });

        $('#filter-status, #filter-invoice, #filter-year, #filter-company, #filter-customer, #filter-total-group, #filter-type, #filter-receipt').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-status').val('').trigger('change');
            $('#filter-year').val('').trigger('change');
            $('#filter-total-group').val('').trigger('change');
            $('#filter-type').val('').trigger('change');
            $('#filter-invoice').val('');
            $('#filter-company').val('').trigger('change');
            $('#filter-customer').val('');
            $('#filter-receipt').val('');
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
                url: '{{ route('admin.payments.export') }}',
                type: 'POST',
                data: {
                    ids: selectedIds,
                    _token: '{{ csrf_token() }}'
                },
                xhrFields: {
                    responseType: 'arraybuffer'
                },
                success: function (response, status, xhr) {

                    let filename = 'payments.csv';
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