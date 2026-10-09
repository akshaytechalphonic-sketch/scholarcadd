<script>

    $(document).ready(function() {

        $.append_to_search(['name','account_no'],['Name','Account No']);

        var type;
        var custom_filter;
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,

            ajax: {
                url: "{{ route('bank-list') }}",
                type: 'GET',
                data: function(d) {
                    d.filter = type;
                    d.custom_filter=custom_filter;
                },
                dataSrc: function(json) {
                    return json.data;
                }
            },

            columns: [{ data: 'name', render: function(data) { return data; } },
                    { data: 'bank_group', render: function(data) { return data; } },
                    { data: 'account_no', render: function(data) { return data; } },
                    { data: 'country', render: function(data) { return data; } },
                    { data: 'city', render: function(data) { return data; } },
                    { data: 'main_account', render: function(data) { return data; } },
                    { data: 'phone_no', render: function(data) { return data; } },
                    { data: 'email', render: function(data) { return data; } },
                    { data: 'contact_name', render: function(data) { return data; } },
                    { data: 'currency', render: function(data) { return data; } },
                     { data: 'enable_reconciliation', render: function(data) { return data; } },
                    { data: 'status', render: function(data) { return data; } },
                    { data: 'actions', render: function(data) { return data; } },
                ],

            @include('layouts.common_yajra')
        });

        @include('layouts.yajra_table_js')
    });

</script>
