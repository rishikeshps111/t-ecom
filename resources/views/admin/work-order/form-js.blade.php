<script>

    $(document).ready(function () {
        $('#company_id').on('change', function () {
            syncCustomerData();
        });

        if ($('#company_id').val()) {
            syncCustomerData();
        }

        function syncCustomerData() {
            const selectedOption = $('#company_id option:selected');

            const companyTypeId = selectedOption.data('type');
            const totalGroupId = selectedOption.data('total-group');

            // WP Type
            $('#company_type_id')
                .val(companyTypeId ?? '')
                .trigger('change');

            // Total Group
            $('#total_group_id')
                .val(totalGroupId ?? '')
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

    document.getElementById('attachment').addEventListener('change', function (e) {
        const preview = document.getElementById('attachment_preview');
        preview.innerHTML = ''; // Clear previous preview

        const file = this.files[0];
        if (!file) return;

        const fileExt = file.name.split('.').pop().toLowerCase();

        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
            const img = document.createElement('img');
            img.style.maxWidth = '150px';
            img.style.maxHeight = '150px';
            img.src = URL.createObjectURL(file);
            preview.appendChild(img);
        } else if (fileExt === 'pdf') {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(file);
            link.target = '_blank';
            link.textContent = 'View PDF';
            preview.appendChild(link);
        } else {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(file);
            link.target = '_blank';
            link.textContent = 'View File';
            preview.appendChild(link);
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