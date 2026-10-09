<script>
 
    $(document).ready(function() {

        $.append_to_search(['code','registration_no','name','vat_no'],['Code','Registration No','Name','VAT No']);
        
        var type;
        var custom_filter; 
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,
 
            ajax: {
                url: "{{ route('company-list') }}",
                type: 'GET',
                data: function(d) {
                    d.filter = type; 
                    d.custom_filter=custom_filter; 
                },
                dataSrc: function(json) {
                    return json.data;
                }
            },

            columns: [{ data: 'code', render: function(data) { return data; } },
                    { data: 'name', render: function(data) { return data; } },
                    { data: 'registration_no', render: function(data) { return data; } },
                    { data: 'vat_no', render: function(data) { return data; } },                    
                    { data: 'origin_date', render: function(data) { return data; } }, 
                    { data: 'created_by', render: function(data) { return data; } },
                    { data: 'status', render: function(data) { return data; } },
                    { data: 'actions', render: function(data) { return data; } }, 
                ],

            @include('layouts.common_yajra')  
        });
   
        @include('layouts.yajra_table_js')
    });
 
</script>