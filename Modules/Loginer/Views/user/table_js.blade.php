<script>
    $(document).ready(function() {
        $.append_to_search_with_clear(['name','email','employee_email','type','roles'],['Name','Email','Employee Email','Type','Role']);

        var type;
        var custom_filter;
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,

            ajax: {
                url: "{{ route('users-list') }}",
                type: 'GET',
                data: function(d) {
                    d.filter = type;
                    d.custom_filter= custom_filter;
                },
                dataSrc: function(json) {
                    return json.data;
                }
            },

            columns: [{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', render: function(data) { return data; } },
                    { data: 'email', render: function(data) { return data; } },
                    { data: 'employee_email', render: function(data) { return data; } },
                    { data: 'type', render: function(data) { return data; } },
                    { data: 'roles', render: function(data) { return data; } },
                    { data: 'action', render: function(data) { return data; } },
                ],

             @include('layouts.common_yajra')
        });

       @include('layouts.yajra_table_js')
    });

</script>
