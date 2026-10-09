<script>
    $(document).ready(function() {  
        $(".drawer-overlay").on('click', function(){
            closeAllSidebars('approve-sidebar');
            closeAllSidebars('reject-sidebar'); 
            closeAllSidebars('change-request_modal-sidebar');             
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
                url: "{{ route('workflow-request.list') }}",
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
          
    });

   
 
</script>
 