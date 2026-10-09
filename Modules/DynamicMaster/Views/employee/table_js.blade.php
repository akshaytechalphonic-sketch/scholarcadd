<script>
 
    $(document).ready(function() {

        $.append_to_search(['empCode','email','name','department'],['Code','Email','Name','Department']);
        
        var type;
        var custom_filter; 
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,
 
            ajax: {
                url: "{{ route('employee-list') }}",
                type: 'GET',
                data: function(d) {
                    d.filter = type; 
                    d.custom_filter=custom_filter; 
                },
                dataSrc: function(json) {
                    return json.data;
                }
            },

            columns: [{ data: 'empCode', render: function(data) { return data; } },
                    { data: 'name', render: function(data) { return data; } }, 
                    { data: 'email', render: function(data) { return data; } },                    
                    { data: 'department', render: function(data) { return data; } }, 
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
    });
 
</script>