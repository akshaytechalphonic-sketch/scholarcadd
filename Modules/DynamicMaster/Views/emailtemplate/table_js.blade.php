<script>

    $(document).ready(function() {
        $.append_to_search(['email_master','subject'],['Email Master','Subject']);


        var type;
        var custom_filter;
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,

            ajax: {
                url: "{{ route('email-template-list') }}",
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
                    { data: 'email_master', render: function(data) { return data; } },
                    { data: 'subject', render: function(data) { return data; } },
                    { data: 'created_by', render: function(data) { return data; } },
                    { data: 'created_at', render: function(data) { return data; } },
                    { data: 'status', render: function(data) { return data; } },
                    { data: 'action', render: function(data) { return data; } },
                ],

             @include('layouts.common_yajra') 
        });

         @include('layouts.yajra_table_js')
    });

</script>
