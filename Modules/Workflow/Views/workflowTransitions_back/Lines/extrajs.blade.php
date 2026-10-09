<script>
  
    function togglview_modelSidebar(w_id = null) {
        const sidebar = document.getElementById('view-sidebar');
        sidebar.classList.toggle('translate-x-full');         
        if (w_id) {
            $('#line_id').val(w_id);
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
        }                
    }
 
</script>  