<script>
 
    $(document).ready(function() {

        $.append_to_search(['workflow_code','workflow_name','apply_on'],['Code','Workflow Name','Apply On']);
        
        var type;
        var custom_filter; 
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,
 
            ajax: {
                url: "{{ route('all-transition-yajra') }}",
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
                    { data: 'map_name', render: function(data) { return data; } },
                    { data: 'document_code', render: function(data) { return data; } },
                    { data: 'workflow_name', render: function(data) { return data; } },
                    { data: 'requester_name', render: function(data) { return data; } },
                    { data: 'date', render: function(data) { return data; } },                     
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

        // $('#add_modal').on('submit', function(e) {
        //     e.preventDefault();
        //     var formData = new FormData(this);
        //     showLoader();

        //     $.ajax({
        //         url: "{{ route('add-workflow.details') }}",
        //         type: 'POST',
        //         data: formData,
        //         contentType: false,
        //         processData: false,
        //         success: function(response) {
        //             if (response[0]==200) {
        //                 table.draw();
        //                 toastr.success(response.message) 
        //                 $('#add_status').val('');
        //                 $('#add_name').val('');
        //                 $('#add_apply_on_id').val('');
        //                 $('#add_code').val('');   
        //             } else {
        //                 toastr.error(response.message)
        //             }
        //             hideLoader();
        //         },
        //         error: function(xhr, status, error) {
        //             if (xhr.status === 422) { 
        //                 let errors = xhr.responseJSON.errors;
        //                 let errorMessages = '';
        //                 for (const [key, messages] of Object.entries(errors)) {
        //                     errorMessages += messages.join('<br>');
        //                 }
        //                 toastr.error('Validation Error!')
        //             } else {
        //                 toastr.error('Oops..,Something went wrong: ' + error)
        //             }
        //             hideLoader();
        //         }
        //     });
        // });

        // $('#edit_modal').on('submit', function(e) {
        //     e.preventDefault();
        //     var formData = new FormData(this);
        //     showLoader();

        //     $.ajax({
        //         url: "{{ route('edit-workflow.details') }}",
        //         type: 'POST',
        //         data: formData,
        //         contentType: false,
        //         processData: false,
        //         success: function(response) {
        //             if (response[0]==200) {
        //                 table.draw();
        //                 toastr.success(response.message)                            
        //                 $('#w_code').val('');
        //                 $('#w_apply_on').val('');
        //                 $('#w_name').val('');
        //                 $('#w_status').val('');                            
        //             } else {
        //                 toastr.error(response.message)
        //             }
        //             hideLoader();
        //         },
        //         error: function(xhr, status, error) {
        //             if (xhr.status === 422) { 
        //                 let errors = xhr.responseJSON.errors;
        //                 let errorMessages = '';
        //                 for (const [key, messages] of Object.entries(errors)) {
        //                     errorMessages += messages.join('<br>');
        //                 }
        //                 toastr.error('Validation Error!')
        //             } else {
        //                 toastr.error('Oops..,Something went wrong: ' + error)
        //             }
        //             hideLoader();
        //         }
        //     });
        // });
 
        
    });
 
</script>