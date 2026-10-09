<script>
 
    $(document).ready(function() {

         $(".drawer-overlay").on('click', function(){
            closeAllSidebars('view-sidebar');
            closeAllSidebars('add-sidebar'); 
        });  
       
        var step_id = "{{ $step_id }}";                
        $.append_to_search(['step_name','condition'],['Step Name','Condition']);        
        var type;
        var custom_filter; 
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,
 
            ajax: {
                url: "{{ route('allworkflowAssignment.list') }}",
                type: 'GET',
                data: function(d) {
                    d.step_id = step_id;
                    d.filter = type;
                    d.custom_filter=custom_filter;                    
                },
                dataSrc: function(json) {
                    return json.data;
                }
            },

            columns: [{ data: 'step_name', render: function(data) { return data; } },
                    { data: 'condition', render: function(data) { return data; } },                    
                    { data: 'assign_to', render: function(data) { return data; } }, 
                    { data: 'created_by', render: function(data) { return data; } }, 
                    { data: 'created_at', render: function(data) { return data; } },
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
                url: "{{ route('add-workflow.assignment') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response[0]==200) {
                        table.draw();
                        toastr.success(response[1]) 
                        location.reload();
                        
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
        });

        $('#edit_modal').on('submit', function(e) {            
            e.preventDefault();
            var formData = new FormData(this);
            showLoader();

            $.ajax({
                url: "{{ route('edit-workflow.assignment') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response[0]==200) {
                        table.draw();
                        toastr.success(response[1]) 
                        location.reload();
                        
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
        });
 

        $.condition_change = function(){
            $("#linemanager_cndn").css("display","none");
            $("#emp_cndn").css("display","none");
            $("#dept_cndn").css("display","none");

            var condition_change = $('#condition_change').val();
            if(condition_change == "Employee"){
                $("#emp_cndn").css("display","block");
            }
            else if(condition_change == "Department"){
                $("#dept_cndn").css("display","block");
            }
            else if(condition_change == "Line Manager"){
                $("#linemanager_cndn").css("display","block");
            }
          }
  
 
    });
 
</script>