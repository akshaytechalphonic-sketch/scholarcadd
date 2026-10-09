<script>
    $('#CourseCountryName').on('change', function() {
        let countryId = $(this).val();

        if (!countryId) {
            $('#stateName').html('<option value="">Select State</option>');
            return;
        }

        $.ajax({
            url: "{{ route('get.states.by.country') }}",
            type: "GET",
            data: {
                country_id: countryId
            },
            success: function(response) {
                $('#stateName').empty().append('<option value="">Select State</option>');

                $.each(response.states, function(key, state) {
                    $('#stateName').append(
                        `<option value="${state.name}">${state.name}</option>`
                    );
                });
            }
        });
    });
</script>
