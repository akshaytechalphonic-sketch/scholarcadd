<script>
    function view_model_reassign(w_id){                      
        $('#line_id').val(w_id);         
        if(w_id) { 
            var url = "{{ route('workflow-reassign.details', [':w_id']) }}";
            url = url.replace(':w_id', w_id);
            showLoader();
            $.ajax({
                url: url,
                method: 'get',
                dataType: 'json',
                success: function(data) {
                    hideLoader();
                    $('#reassign').html(' <option value="">Select Employee</option>');
                    data.forEach(function(employee) {
                        $('#reassign').append('<option value="' + employee['id'] + '">' + employee['name']);
                    });
                }
            });
            hideLoader();
            toggle_model('reassign-sidebar');
        }
        else{
            toastr.error("Id not found");
        }
    }
 
</script>
 