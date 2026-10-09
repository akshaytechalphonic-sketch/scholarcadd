<script>
 
    $(document).ready(function() {

        $.append_to_search_with_clear(['name'],['Name']); 
        
        var type;
        var custom_filter; 
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,
 
            ajax: {
                url: "{{ route('roles-list') }}",
                type: 'GET',
                data: function(d) {
                    d.filter = type; 
                    d.custom_filter=custom_filter; 
                },
                dataSrc: function(json) {
                    return json.data;
                }
            },

            columns: [{ data: 'DT_RowIndex', render: function(data) { return data; } },
                    { data: 'name', render: function(data) { return data; } },
                    { data: 'no_of_permission', render: function(data) { return data; } },                     
                    { data: 'actions', render: function(data) { return data; } }, 
                ],

            @include('layouts.common_yajra')  
        });
   
        @include('layouts.yajra_table_js')
    });
 
</script>