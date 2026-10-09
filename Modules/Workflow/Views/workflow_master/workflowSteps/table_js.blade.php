<script>
 
    $(document).ready(function() {

        $(".drawer-overlay").on('click', function(){
            closeAllSidebars('view-sidebar');
            closeAllSidebars('add-sidebar'); 
        });


       
        var setup_id = "{{ $setup_id }}";                
        $.append_to_search(['step_name','condition'],['Step Name','Condition']);        
        var type;
        var custom_filter; 
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,
 
            ajax: {
                url: "{{ route('allworkflowSteps.list') }}",
                type: 'GET',
                data: function(d) {
                    d.setup_id = setup_id;
                    d.filter = type;
                    d.custom_filter=custom_filter;                    
                },
                dataSrc: function(json) {
                    return json.data;
                }
            },

            columns: [{ data: 'workflowCode_Name', render: function(data) { return data; } },
                    { data: 'step_name', render: function(data) { return data; } },
                    { data: 'condition', render: function(data) { return data; } },
                    { data: 'next_step', render: function(data) { return data; } },
                    { data: 'created_by', render: function(data) { return data; } },
                    { data: 'created_at', render: function(data) { return data; } }, 
                    { data: 'status', render: function(data) { return data; } },
                    { data: 'actions', render: function(data) { return data; } }, 
                ],

            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    text: 'Export Excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'csvHtml5',
                    text: 'Export CSV',
                    className: 'btn btn-info btn-sm'
                }
            ],
            lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ],
            pageLength: 10    
        });
   
        @include('layouts.yajra_table_js')



        $('#add_modal').on('submit', function(e) {            
            e.preventDefault();
            var formData = new FormData(this);
            showLoader();

            $.ajax({
                url: "{{ route('add-workflow.step') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response[0]==200) {
                        table.draw();
                        closeAllSidebars('add-sidebar');
                        toastr.success(response.message) 
                        $('#w_condition').val('');
                        $('#w_name').val('');
                        $('#show_approvalType').html('');
                        $('#email_template_html').html('');
                        $('#status').val('');       
                        $('#email_check').prop('checked', false);
                        
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
                url: "{{ route('edit-workflow.steps') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response[0]==200) {
                        table.draw();
                        closeAllSidebars('view-sidebar');
                        toastr.success(response.message)  
                        $('#step_id').val('');
                        $('#step_name').val('');
                        $('#step_condition').val('');
                        $('#edit_approvalType').html('');
                        $('#edit_email_template_html').html('');
                        $('#step_status').val('');       
                        $('#edit_system_check').prop('checked', false);
                        $('#edit_email_check').prop('checked', false);
                                                               
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

        $('#condition_model').on('submit', function(e) {                        
            e.preventDefault();
            var formData = new FormData(this);
            showLoader();
 
            var already_have = 0;
            var fields = new Array();
            $("select[name='field[]']").each(function() {
                var exists = check_accurance(fields,this.value);
                if(exists){     
                    toastr.error(this.value + " already exists");
                    already_have++;
                }
                fields.push(this.value);
            });
 
            if(fields.length <= 0){
                toastr.error("Please add atleast one condition");                
            }
            if(already_have <= 0){                     
                $.ajax({ 
                    url: "{{ route('save-conditional-workflow.steps') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response); 
                        if (response[0]==200) {                            
                            toastr.success(response[1]);
                            closeNewModal('view-conditional');
                            togglview_conditional_model();                            
                        } else {
                            toastr.error(response[1])
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
            }
    
            
        });

        $('#next_define_modal').on('submit', function(e) {                        
            e.preventDefault();
            var formData = new FormData(this);
            showLoader();
            var next_id = $('#next_step_id').val();
            if(next_id){                     
                $.ajax({ 
                    url: "{{ route('workflow-steps-store-next') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);
                        if (response[0]==200) {  
                            table.draw();                          
                            toastr.success(response[1]) 
                            togglview_nextmodel();                            
                        } else {
                            toastr.error(response[1])
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
            }
            else{
                toastr.error('Please select next step');
            }
    
            
        });
 
       
        $.validate_hierarchy = function(){            
            showLoader();
            $.ajax({
            url:"{{ route('wf-setup-validation') }}",
            method: 'post',
            data: {
                w_id:setup_id,
                _token: '{{ csrf_token() }}'
            },
            cache: false, 
                success: function(response) {
                    hideLoader();
                    if(response[0]){
                        toastr.success(response[1]);
                    }
                    else{
                        toastr.error(response[1]);
                    }
                }
            });
        }

        $.view_hierarchy = function(){            
            openNewModal('view-hier');            
            // showLoader();
            // $.ajax({
            // url:"{{ route('wf-setup-show-hierarchy') }}",
            // method: 'get',
            // data: {
            //     w_id:setup_id, 
            // },
            // cache: false, 
            //     success: function(response) {
            //         hideLoader();
            //     }
            // });
        }
        
        $.add_approval_type = function(){            
            if($('#w_condition').val()=='Employee' || $('#w_condition').val()=='Conditional'){
                $('#show_approvalType').html('<div class="w-full"><label class="text-sm">No of approver <span class="text-danger">*</span></label><div class="filled w-100 d-flex flex-column"><input type="text" name="no_of_approver" value="1" id="no_of_approver" onkeypress="return $.numeric();" maxlength="2" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5"></div>');
            }
            else{
                $('#show_approvalType').html('');
            }
        }

        $.email_notification = function(email_temp_id,checkbox_id){
            $('#edit_email_template_html').html('');
            $('#email_template_html').html('');
            var email_value = document.getElementById(checkbox_id);
            if(email_value.checked){
                showLoader();
                $.ajax({
                    url:"{{ route('get-all-email-template') }}",
                    method: 'get',
                    data: {
                        email_temp_id:email_temp_id, 
                    },
                    cache: false, 
                    success: function(response) {
                        hideLoader();
                        if(checkbox_id == 'edit_email_check')
                            $('#edit_email_template_html').html('<label for=""> Select Email Template <span class="text-danger">*</span></label><div class=" filled w-100 d-flex flex-column"><select name="email_template_id" id="email_template_id"  class="form-input mt-2" required><option value="">Select Template</option>'+response+'</select></div>');
                        else
                            $('#email_template_html').html('<label for=""> Select Email Template <span class="text-danger">*</span></label><div class=" filled w-100 d-flex flex-column"><select name="email_template_id" id="email_template_id"  class="form-input mt-2" required><option value="">Select Template</option>'+response+'</select></div>');
                    }
                });
            }
            else{
                $('#email_template_html').html('');
            }

        }

        var condition = "<select name='condition[]' required style='width:100%' class='form-input mt-2'><option value='Equals to'>Equals to</option><option value='Less Than'>Less Than</option><option value='Less Than Equals to'>Less Than Equals to</option><option value='Greater Than'>Greater Than</option><option value='Greater Than Equals to'>Greater Than Equals to</option><option value='In Between'>In Between</option></select>";        
         
        $("#add-line").on("click", function() {
            $('#add_new_line').append('<tr class="text-center"><td>'+field+'</td><td>'+condition+'</td><td><input type="text" name="condition_value[]" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5" required></td>  <td class="text-center"><button type="button" class="delete-line" ><i class="ri-delete-bin-6-line text-xl text-red-500"></i></button></td></tr>');
        });

        $(document).on("click", ".delete-line", function(e) {
            e.preventDefault(); // prevent any default action
            e.stopPropagation(); // prevent event bubbling
            $(this).closest('tr').remove();
        });
 
    });
 
</script>