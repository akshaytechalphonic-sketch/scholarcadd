<script>
    $(document).ready(function() {  
        $(".drawer-overlay").on('click', function(){
            closeAllSidebars('view-sidebar');
            closeAllSidebars('add-sidebar'); 
        });     

        $.append_to_search(['workflow_code','workflow_name','apply_on'],['Code','Workflow Name','Apply On']);
        
        var type;
        var custom_filter; 
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,
 
            ajax: {
                url: "{{ route('workflow-all-transition-list') }}",
                type: 'GET',
                data: function(d) {
                    d.filter = type; 
                    d.custom_filter=custom_filter; 
                },
                dataSrc: function(json) {
                    return json.data;
                }
            }, 

            columns: [{ data: 'ref_code', render: function(data) { return data; } },
                    { data: 'type', render: function(data) { return data; } },
                    { data: 'document_code', render: function(data) { return data; } },
                    { data: 'workflow', render: function(data) { return data; } },
                    { data: 'requester', render: function(data) { return data; } }, 
                    { data: 'date', render: function(data) { return data; } }, 
                    { data: 'status', render: function(data) { return data; } },
                    { data: 'actions', render: function(data) { return data; } }, 
                ],

             @include('layouts.common_yajra') 
        });
   
        @include('layouts.yajra_table_js')


        $('#add_modal').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        showLoader();

        $.ajax({
            url: "{{ route('add-workflow.details') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response[0]==200) {
                    table.draw();
                    toastr.success(response.message) 
                    $('#add_status').val('');
                    $('#add_name').val('');
                    $('#add_apply_on_id').val('');
                    $('#add_code').val(''); 
                    toggle_model('add-sidebar');  
                } else {
                    toastr.error(response.message)
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

        $('#edit_modal').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            showLoader();

            $.ajax({
                url: "{{ route('edit-workflow.details') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response[0]==200) {
                        table.draw();
                        toastr.success(response.message) 
                        toggle_model('view-sidebar');                             
                        $('#w_code').val('');
                        $('#w_apply_on').val('');
                        $('#w_name').val('');
                        $('#w_status').val('');                            
                    } else {
                        toastr.error(response.message)
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
                     
    });

    function view_model(modal,w_id) {
        toggle_model(modal);
        if(w_id){                 
            var url = "{{ route('workflow-setup.show',[':w_id']) }}";
            url = url.replace(':w_id',w_id);
            showLoader();
            $.ajax({
                url: url,
                method: 'get',
                dataType: 'json',
                success: function(data) {  
                hideLoader(); 
                var workflow_code = data.workflow_code;
                var wName = data.workflow_name;
                var status=data.status;
                    
                $('#wf_id').val(w_id);
                $('#w_code').val(workflow_code);
                $('#w_name').val(wName);
                $('#w_apply_on').val(data.apply_on.workflow_name);

                if(status == "Active")
                    $('#w_status').html('<option value="">Select Status</option> <option value="Active" selected>Active</option> <option value="Inactive">In Active</option>');
                else if(status == "Inactive")
                    $('#w_status').html('<option value="">Select Status</option> <option value="Active">Active</option> <option value="Inactive" selected>In Active</option>');
                else
                    $('#w_status').html('<option value="">Select Status</option> <option value="Active">Active</option> <option value="Inactive">In Active</option>');
                }
            });  
        } 
    }  
    
</script>
 