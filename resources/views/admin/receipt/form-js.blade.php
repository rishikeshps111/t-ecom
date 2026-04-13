<script>
    $(document).ready(function () {
        selectTwo();
    });

    function selectTwo() {
        $('.form-select').select2()
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.editor').forEach((el) => {
            ClassicEditor
                .create(el, {
                    toolbar: [
                        'heading',
                        '|',
                        'bold', 'italic', 'underline',
                        '|',
                        'bulletedList', 'numberedList',
                        '|',
                        'link',
                        '|',
                        'undo', 'redo'
                    ]
                })
                .catch(error => {
                    console.error(error);
                });
        });
    });
</script>
@error('amount')
    <script>
        showToast('warning', '{{ $message }}');
    </script>
@enderror