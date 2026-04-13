<script>
    $(function () {
        let table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.business-users.index') }}",
                data: function (d) {
                    d.customer_code = $('#filter-customer-code').val();
                    d.customer_name = $('#filter-customer-name').val();
                    d.company = $('#filter-company').val();
                    d.status = $('#filter-status').val();
                }
            },
            columns: [
                //{
                //    data: 'checkbox',
                //    name: 'checkbox',
                //    orderable: false,
                //    searchable: false,
                //    render: function (data, type, row) {
                //        return `<input type="checkbox" class="row-check" value="${row.id}">`;
                //    },
                //    className: 'text-center'
                //},
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
                { data: 'custom_user_id', name: 'custom_user_id' },
                { data: 'name', name: 'name' },
                //{ data: 'company', name: 'company' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'status', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });

        $('#filter-status, #filter-customer-name, #filter-customer-code, #filter-company').on('keyup change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#filter-status').val('').trigger('change');
            $('#filter-customer-name').val('');
            $('#filter-customer-code').val('');
            $('#filter-company').val('').trigger('change');
            table.draw();
        });

        $(document).on('click', '.toggleStatus', function () {
            let id = $(this).data('id');
            let currentStatus = $(this).data('status');
            let newStatus = currentStatus == 1 ? 0 : 1;

            $.ajax({
                url: "{{ route('admin.business-users.status') }}",
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

        $(document).on('click', '.view', function () {
            var userId = $(this).data('id');

            Swal.fire({
                title: 'Send Credentials?',
                text: "Are you sure you want to send login credentials to this user?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Send',
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ route('admin.business-users.send-credentials') }}",
                        type: "POST",
                        data: {
                            user_id: userId,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (res) {
                            Swal.fire('Sent!', res.message, 'success');
                        },
                        error: function (xhr) {
                            Swal.fire('Error', 'Something went wrong', 'error');
                        }
                    });

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
                url: '{{ route('admin.business-users.export') }}',
                type: 'POST',
                data: {
                    ids: selectedIds,
                    _token: '{{ csrf_token() }}'
                },
                xhrFields: {
                    responseType: 'arraybuffer'
                },
                success: function (response, status, xhr) {

                    let filename = 'business-users.csv';
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

        $(document).on('click', '.view-btn', function () {
            let projectId = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.business-users.show', ':id') }}".replace(':id', projectId), method: 'GET',
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

    function deleteRow(id) {
        deleteRecord('/admin/business-users/' + id, 'table', 'Do you really want to delete this record?');
    }
</script>
<script>
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.copy-credentials');
        if (!btn) return;

        e.preventDefault();

        const email = btn.dataset.user;
        const password = btn.dataset.password;

        if (!email || !password) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Credentials not available'
            });
            return;
        }

        const credentials = `Email: ${email}\nPassword: ${password}`;

        navigator.clipboard.writeText(credentials).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'Credentials copied to clipboard',
                timer: 1500,
                showConfirmButton: false
            });
        });
    });

    function lockUser(userId) {
        Swal.fire({
            title: 'Lock User',
            text: 'Please provide a reason for locking this user',
            input: 'textarea',
            inputPlaceholder: 'Enter lock reason...',
            inputAttributes: {
                maxlength: 255
            },
            showCancelButton: true,
            confirmButtonText: 'Lock',
            confirmButtonColor: '#d33',
            preConfirm: (reason) => {
                if (!reason) {
                    Swal.showValidationMessage('Lock reason is required');
                }
                return reason;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ url('admin/users') }}/" + userId + "/lock", {
                    _token: "{{ csrf_token() }}",
                    reason: result.value
                }, function () {
                    Swal.fire('Locked!', 'User has been locked.', 'success')
                        .then(() => $('#table').DataTable().ajax.reload());
                });
            }
        });
    }

    function unlockUser(userId) {
        Swal.fire({
            title: 'Unlock User?',
            text: 'This user will regain system access',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, unlock',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ url('admin/users') }}/" + userId + "/unlock", {
                    _token: "{{ csrf_token() }}"
                }, function () {
                    Swal.fire('Unlocked!', 'User access restored.', 'success')
                        .then(() => $('#table').DataTable().ajax.reload());
                });
            }
        });
    }
</script>