<script>

    $(document).ready(function () {

        const isEditMode = {{ isset($workOrder) ? 'true' : 'false' }};

        $('#company_id').on('change', function () {
            syncCustomerData();
        });

        if ($('#company_id').val() && !isEditMode) {
            syncCustomerData();
        }

        function syncCustomerData() {
            const selectedOption = $('#company_id option:selected');

            const companyTypeId = selectedOption.data('type');
            const totalGroupId = selectedOption.data('total-group');
            const plannerId = selectedOption.data('planner');
            const staffId = selectedOption.data('staff');


            // WP Type
            $('#company_type_id')
                .val(companyTypeId ?? '')
                .trigger('change');

            $('#planner_id')
                .val(plannerId ?? '')
                .trigger('change');

            $('#planner_id_hidden')
                .val(plannerId ?? '');

            // Total Group
            $('#total_group_id')
                .val(totalGroupId ?? '')
                .trigger('change');

            $('#production_staff_id')
                .val(staffId ?? '')
                .trigger('change');

            updateCustomerInfo();
        }

        updateCustomerInfo();

        function updateCustomerInfo() {
            const selected = $('#company_id option:selected');

            const name = selected.data('name') || '';
            const email = selected.data('email') || '';
            const phone = selected.data('phone') || '';


            $('#customer_name').val(name);
            $('#customer_email').val(email);
            $('#customer_phone').val(phone);

        }
    });


    ClassicEditor
        .create(document.querySelector('#description'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', 'link', '|',
                'bulletedList', 'numberedList', '|',
                'blockQuote', 'undo', 'redo'
            ]
        })
        .catch(error => {
            console.error(error);
        });
</script>

<script>
    let attachmentIndex = {{ isset($workPlan) ? $workPlan->attachments->count() : 1 }};

    // Add new row
    $(document).on('click', '.add-row', function () {
        let row = `
        <div class="row g-2 attachment-row align-items-end mt-2">
            <div class="col-lg-4">
                <select name="attachments[${attachmentIndex}][type]" class="form-select shadow-none">
                    <option value="">-- Select Type --</option>
                    <option value="image">Image</option>
                    <option value="pdf">PDF</option>
                    <option value="word">Word</option>
                    <option value="excel">Excel</option>
                </select>
            </div>

            <div class="col-lg-4">
                <input type="file"
                       name="attachments[${attachmentIndex}][file]"
                       class="form-control attachment-file shadow-none">
            </div>

            <div class="col-lg-3">
                <div class="attachment-preview"></div>
            </div>

            <div class="col-lg-1 btn-field-rows">
                <button type="button" class="btn btn-danger remove-row">×</button>
            </div>
        </div>`;

        $('#attachment-wrapper').append(row);
        attachmentIndex++;
    });

    // Remove row
    $(document).on('click', '.remove-row', function () {
        $(this).closest('.attachment-row').remove();
    });

    // Preview + open in new tab
    $(document).on('change', '.attachment-file', function () {
        let file = this.files[0];
        let preview = $(this).closest('.attachment-row').find('.attachment-preview');
        preview.html('');

        if (!file) return;

        let url = URL.createObjectURL(file);

        if (file.type.startsWith('image/')) {
            preview.html(`
                <a href="${url}" target="_blank">
                    <img src="${url}"
                         style="width:80px;height:80px;object-fit:cover;border-radius:6px;">
                </a>
            `);
        } else {
            preview.html(`
                <a href="${url}" target="_blank"
                   class="d-block border rounded p-2 small text-decoration-none">
                    📎 ${file.name}
                </a>
            `);
        }
    });

    $('#total_group_id').on('change', function () {
        let id = $(this).val();
        if (!id) return;

        $.get("{{ route('admin.work-orders.generate-code') }}", {
            company_type_id: id
        }, function (res) {
            $('input[name="workplan_number"]').val(res.code);
        });
    });

</script>