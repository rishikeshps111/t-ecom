<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.work-orders.completed.list') }}",
                data: function (d) {
                    d.number = $('#filter-number').val();
                    d.customer = $('#filter-customer').val();
                    d.planner = $('#filter-planner').val();
                    d.staff = $('#filter-staff').val();
                    d.from_date = $('#filter-from-date').val();
                    d.to_date = $('#filter-to-date').val();
                    d.customer_id = '{{ $customerID ?? null }}';
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'workplan_number', name: 'workplan_number' },
                { data: 'date', name: 'date' },
                { data: 'customer', name: 'customer' },
                { data: 'total_group', name: 'total_group' },
                { data: 'status', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });

        $('#filter-customer, #filter-number, #filter-from-date, #filter-to-date,#filter-planner,#filter-staff').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-customer').val('').trigger('change');
            $('#filter-planner').val('').trigger('change');
            $('#filter-staff').val('').trigger('change');
            $('#filter-number').val('');
            $('#filter-from-date').val('');
            $('#filter-to-date').val('');
            table.draw();
        });


        $(document).on('click', '.open-document-modal', function (e) {
            e.preventDefault();

            let id = $(this).data('id');

            let url = "/admin/work-orders/" + id + "/api/attachments";

            $('#addDocumentForm').attr('action', url);

            $('#addDocumentModal').modal('show');
        });


        $(document).on('click', '.open-note-modal', function (e) {
            e.preventDefault();

            let id = $(this).data('id');

            let url = "/admin/work-orders/" + id + "/api/notes";

            $('#addNoteForm').attr('action', url);

            $('#addNoteModal').modal('show');
        });

        $(document).on("submit", "#addDocumentForm", function (e) {

            e.preventDefault();

            let form = this;
            let url = $(form).attr("action");

            let formData = new FormData(form);

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    $('#addDocumentModal').modal('hide');
                    form.reset();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message
                    });

                },
                error: function (xhr) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message ?? 'Upload failed'
                    });

                }
            });

        });

        $(document).on("submit", "#addNoteForm", function (e) {

            e.preventDefault();

            let form = this;
            let url = $(form).attr("action");

            let formData = new FormData(form);

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    $('#addNoteModal').modal('hide');
                    form.reset();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message
                    });

                },
                error: function (xhr) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message ?? 'Failed'
                    });

                }
            });

        });

    });




</script>