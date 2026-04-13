{{-- Include CKEditor --}}
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content-editor');
</script>
<script>
    $(document).ready(function () {
        $('#keywords').select2({
            tags: true,             // Allow new items
            tokenSeparators: [','], // Split by comma
            placeholder: 'Type a keyword and press enter',
            width: '100%'           // Make it full width
        });
    });
</script>