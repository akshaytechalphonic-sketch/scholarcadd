<script src="{{ URL::asset('amybuild/libs/summernote/summernote-lite.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.summernote').summernote({

            height: 400,
            minHeight: 400,
            maxHeight: 500,
            codeviewFilter: false,
            codeviewIframeFilter: false,
            styleTags: ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
