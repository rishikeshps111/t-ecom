<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.announcements.index') }}",
                data: function (d) {
                    d.date = $('#filter-date').val();
                    d.customer_id = '{{ $customerID ?? null }}';
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'schedule_date', name: 'schedule_date' },
                { data: 'message', name: 'message' },
                { data: 'priority', name: 'priority' },
                { data: 'type', name: 'type' },
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
            $.ajax({
                url: "{{ route('admin.announcements.create') }}",
                method: 'GET',
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

        $(document).on('change', 'select[name="type"]', function () {
            const type = $(this).val();

            if (type === 'private') {
                $('#customersBlockTwo').slideDown(); // User Type
            } else {
                // Reset & hide all
                $('#customersBlockTwo').slideUp();
                $('#customersBlock').slideUp();
                $('#customersBlockThree').slideUp();

                $('select[name="user_type"]').val('').trigger('change');
                $('select[name="user_id[]"]').val(null).trigger('change');
            }
        });

        // User type change
        $(document).on('change', 'select[name="user_type"]', function () {
            const userType = $(this).val();

            // Hide both sections first
            $('#customersBlock').slideUp();
            $('#customersBlockThree').slideUp();

            // 🔥 Uncheck all user checkboxes
            $('input[name="user_id[]"]').prop('checked', false);

            // Reset "Check All" button text
            $('.check-all').text('Check All');

            if (userType === 'customer') {
                $('#customersBlock').slideDown(); // Customers
            }

            if (userType === 'planner') {
                $('#customersBlockThree').slideDown(); // Planners
            }
        });

        $(document).on('click', '.check-all', function () {
            const target = $(this).data('target');
            const checkboxes = $('.' + target + '-checkbox');

            const allChecked = checkboxes.length === checkboxes.filter(':checked').length;

            checkboxes.prop('checked', !allChecked);

            $(this).text(allChecked ? 'Check All' : 'Uncheck All');
        });

        $(document).on('click', '.view', function () {
            let projectId = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.announcements.show', ':id') }}".replace(':id', projectId), method: 'GET',
                data: { id: projectId },
                success: function (response) {
                    $('#modalBody').html(response.html);
                    $('#modalTitle').text(response.title);
                    $('#formModal').modal('show');
                    table.draw();
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
        deleteRecord('/admin/announcements/' + id, 'table', 'Do you really want to delete this record?');
    }
</script>