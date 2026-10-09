<script src="{{ URL::asset('amybuild/libs/select2/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select-single').select2({
            placeholder: "Select an option...",
            allowClear: true,
            width: '100%'
        });
        $('.select-multi').select2({
            placeholder: "Select multiple options...",
            allowClear: true,
            width: '100%'
        });
        $('.select-tags').select2({
            tags: true,
            tokenSeparators: [','],
            width: '100%',
            placeholder: "Add tags..."
        });
        $('.select-group').select2({
            width: '100%'
        });
    });

    $(document).ready(function() {
        $('.select-single, .select-multi').each(function() {
            let $select = $(this);
            let $drawer = $select.closest('.drawer');

            $select.select2({
                placeholder: $select.data('placeholder') || "Select an option...",
                allowClear: true,
                width: 'resolve',
                dropdownParent: $drawer.length ? $drawer : $(document.body)
            });
            $select.on("select2:select select2:unselect", function() {
                let drawerPosition = $drawer.css("transform");
                $drawer.css("transform", drawerPosition);
            });

        });
    });

    $(document).on('focus', '.select-single, .select-multi', function() {
        if (!$(this).hasClass('select2-hidden-accessible')) {
            let $select = $(this);
            let $drawer = $select.closest('.drawer');

            $select.select2({
                placeholder: $select.data('placeholder') || "Select an option...",
                allowClear: true,
                width: '100%',
                dropdownParent: $drawer.length ? $drawer : $(document.body)
            });
        }
    });
    $(document).on('reset', 'form', function () {
    const form = this;
    setTimeout(() => {
        $(form).find('.select-single, .select-multi').each(function () {
            $(this).val(null).trigger('change.select2');
        });
    }, 0);
});
</script>
