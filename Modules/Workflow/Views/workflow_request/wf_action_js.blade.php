<script> 
    function view_model(type,doc_id){        
        $('.comment').val('');
        $('.request_id').val(doc_id);
        toggle_model(type); 
    }
    
    $('#approve_modal_form').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        showLoader();
    
        $.ajax({
            url: "{{ route('approve-workflow.submit') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response[0]) {                    
                    toastr.success(response[1]);
                    location.reload();
                } else {
                    toastr.error(response[1]);
                }
                hideLoader();
            },
            error: function(xhr, status, error) {
                if (xhr.status === 422) { 
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = '';
                    for (const [key, messages] of Object.entries(errors)) {
                        errorMessages += messages.join('<br>');
                    }
                    toastr.error('Validation Error!')
                } else {
                    toastr.error('Oops..,Something went wrong: ' + error)
                }
                hideLoader();
            }
        });

    });
    
    $('#reject_modal_form').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        showLoader();
   
        $.ajax({
            url: "{{ route('approve-workflow.submit') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response[0]) {                    
                    toastr.success(response[1]);
                    location.reload();
                } else {
                    toastr.error(response[1]);
                }
                hideLoader();
            },
            error: function(xhr, status, error) {
                if (xhr.status === 422) { 
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = '';
                    for (const [key, messages] of Object.entries(errors)) {
                        errorMessages += messages.join('<br>');
                    }
                    toastr.error('Validation Error!')
                } else {
                    toastr.error('Oops..,Something went wrong: ' + error)
                }
                hideLoader();
            }
        });

    });
    
    $('#change_request_form').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        showLoader();
   
        $.ajax({
            url: "{{ route('approve-workflow.submit') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response[0]) {                    
                    toastr.success(response[1]);
                    location.reload();
                } else {
                    toastr.error(response[1]);
                }
                hideLoader();
            },
            error: function(xhr, status, error) {
                if (xhr.status === 422) { 
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = '';
                    for (const [key, messages] of Object.entries(errors)) {
                        errorMessages += messages.join('<br>');
                    }
                    toastr.error('Validation Error!')
                } else {
                    toastr.error('Oops..,Something went wrong: ' + error)
                }
                hideLoader();
            }
        });

    });

</script>