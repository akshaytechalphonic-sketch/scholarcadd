<script>

    $(document).ready(function() {

        $.append_to_search(['code','name','constant_no','range_no','format'],['Number Sequence Code','Name','Constant','Range','Format']);

        var type;
        var custom_filter;
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,

            ajax: {
                url: "{{ route('numbersequence-list') }}",
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
                    { data: 'constant_no', render: function(data) { return data; } },
                    { data: 'range_no', render: function(data) { return data; } },
                    { data: 'format', render: function(data) { return data; } },
                    { data: 'mapped', render: function(data) { return data; } },
                    { data: 'created_by', render: function(data) { return data; } },
                    { data: 'actions', render: function(data) { return data; } },
                ],

           @include('layouts.common_yajra')
        });

        @include('layouts.yajra_table_js')



    });

</script>
