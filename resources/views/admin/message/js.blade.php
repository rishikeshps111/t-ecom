<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.messages.index') }}",
                data: function (d) {
                    d.name = $('#filter-name').val();
                    d.date = $('#filter-date').val();
                    d.customer_id = '{{ $customerID ?? null }}';
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'user', name: 'user' },
                //{ data: 'subject', name: 'subject' },
                //{ data: 'priority', name: 'priority' },
                { data: 'created_at', name: 'created_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });

        $('#filter-name,#filter-date').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-name').val('');
            $('#filter-date').val('');
            table.draw();
        });

        $(document).on('click', '.send-mail', function () {
            $.ajax({
                url: "{{ route('admin.messages.create') }}",
                method: 'GET',
                success: function (response) {
                    $('#modalBody').html(response.html);
                    $('#modalTitle').text(response.title);
                    initializeMessageUserSelect();
                    //selectCkEditor();
                    $('#formModal').modal('show');
                },
                error: function () {
                    alert('Failed to load status change form.');
                }
            });
        });


        $(document).on('submit', '#commonForm1', function (e) {
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
                    //if (response.url) {
                    //    setTimeout(function () {
                    //        window.location.href = response.url;
                    //    }, 800);
                    //}
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function (field, messages) {
                            const errorField = field.startsWith('user_id.') ? 'user_id' : field;
                            form.find('.' + errorField + '_error').text(messages[0]);
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

        $(document).on('change', '#priority', function () {
            $(this).removeClass('low medium high')
                .addClass($(this).val());
        });

        $(document).on('change', '#message-user-type', function () {
            loadMessageUsers();
        });

    });

    function selectInt(selector = '.multi-select') {
        $(selector).select2({
            width: '100%',
            placeholder: $(selector).data('placeholder') || 'Select users',
            allowClear: true,
            closeOnSelect: false,
            dropdownParent: $('#formModal')
        });
    }

    function initializeMessageUserSelect() {
        const userSelect = $('#message-user-id');
        selectInt('#message-user-id');

        if ($('#message-user-type').val()) {
            loadMessageUsers(userSelect.val() || []);
        }
    }

    function loadMessageUsers(selectedValues = []) {
        const userType = $('#message-user-type').val();
        const userSelect = $('#message-user-id');
        const url = userSelect.data('url');
        const companyId = $('#commonForm1').find('input[name="company_id"]').val() || '';
        const currentValues = selectedValues.length ? selectedValues : (userSelect.val() || []);

        if (userSelect.hasClass('select2-hidden-accessible')) {
            userSelect.select2('destroy');
        }

        userSelect.empty();

        if (!userType) {
            userSelect.prop('disabled', true);
            selectInt('#message-user-id');
            return;
        }

        userSelect.prop('disabled', true);
        userSelect.append(new Option('Loading users...', '', false, false));
        selectInt('#message-user-id');

        $.ajax({
            url: url,
            method: 'GET',
            data: {
                user_type: userType,
                company_id: companyId
            },
            success: function (response) {
                if (userSelect.hasClass('select2-hidden-accessible')) {
                    userSelect.select2('destroy');
                }

                userSelect.empty();

                $.each(response.users || [], function (_, user) {
                    const isSelected = currentValues.includes(String(user.id)) || currentValues.includes(user.id);
                    userSelect.append(new Option(user.text, user.id, false, isSelected));
                });

                userSelect.prop('disabled', (response.users || []).length === 0);
                selectInt('#message-user-id');
                userSelect.trigger('change');
            },
            error: function () {
                if (userSelect.hasClass('select2-hidden-accessible')) {
                    userSelect.select2('destroy');
                }

                userSelect.empty();
                userSelect.prop('disabled', true);
                selectInt('#message-user-id');
                showToast('error', 'Failed to load users');
            }
        });
    }

    function selectCkEditor() {
        ClassicEditor
            .create(document.querySelector('#message'), {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'link',
                    'bulletedList', 'numberedList', '|',
                    'blockQuote', 'insertTable', '|',
                    'undo', 'redo'
                ]
            })
            .catch(error => {
                console.error(error);
            });

    }

    function deleteRow(id) {
        deleteRecord('/admin/messages/' + id, 'table', 'Do you really want to delete this record?');
    }
</script>
