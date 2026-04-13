<script>
    document.getElementById('documentInput').addEventListener('change', function (e) {
        const preview = document.getElementById('documentPreview');
        preview.innerHTML = ''; // Clear previous preview

        const file = this.files[0];
        if (!file) return;

        const fileType = file.type;

        if (fileType.startsWith('image/')) {
            // Show image preview
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.style.maxWidth = '150px';
            img.style.maxHeight = '150px';
            img.classList.add('img-thumbnail');
            preview.appendChild(img);
        } else if (fileType === 'application/pdf') {
            // Show PDF preview
            const link = document.createElement('a');
            link.href = URL.createObjectURL(file);
            link.target = '_blank';
            link.textContent = file.name;
            preview.appendChild(link);
        } else {
            // Other files → just show filename
            const span = document.createElement('span');
            span.textContent = file.name;
            preview.appendChild(span);
        }
    });
</script>

{{--
<script>
    $(document).ready(function () {
        function toggleFields(type) {
            $('#companyField, #projectField').addClass('d-none');

            if (type === 'general') {
                $('#companyField').removeClass('d-none');
            }

            if (type === 'project') {
                $('#projectField').removeClass('d-none');
            }
        }
        toggleFields($('select[name="type"]').val());

        $('select[name="type"]').on('change', function () {
            toggleFields($(this).val());
        });
    });
</script> --}}
<script>
    $(document).ready(function () {

        function loadCompanies() {
            let typeId = $('#company_type_id').val();
            let userId = $('#business_user_id').val();
            let oldCompanyId = '{{ old('company_id', $document->company_id ?? null) }}';
            if (!typeId && !userId) {
                $('#company_id').html('<option value="">-- Select Company --</option>');
                $('#planner_user_id').html('<option value="">-- Select Planner User --</option>');
                return;
            }

            $.get('/companies', {
                company_type_id: typeId,
                business_user_id: userId
            }, function (data) {

                let options = '<option value="">-- Select Company --</option>';

                data.forEach(item => {
                    let selected = oldCompanyId == item.id ? 'selected' : '';
                    options += `<option value="${item.id}" data-total-group-id="${item.total_group_id}" ${selected}>${item.company_name}</option>`;
                });

                $('#company_id').html(options);

                // Auto load planners if company exists
                if (oldCompanyId) {
                    $('#company_id').trigger('change');
                }
            });
        }



        $('#company_type_id, #business_user_id').on('change', loadCompanies);

        $('#company_id').on('change', function () {
            let totalGroupId = $(this).find('option:selected').attr('data-total-group-id') || '';
            $('#total_group_id').val(totalGroupId).trigger('change');
        });

        if ($('#company_type_id').val() || $('#business_user_id').val()) {
            loadCompanies();
        }

    });


</script>