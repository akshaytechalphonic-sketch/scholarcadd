<script>
    $(document).ready(function() {
        $.append_to_search(['name', 'code', 'status', 'created_by', 'created_at'],
            ['Name', 'Code', 'Status', 'Created By', 'Created At']);

        var custom_filter;
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,

            ajax: {
                url: "{{ route('enrolled-request-individual.index') }}",
                type: 'GET',
                data: function(d) {
                    d.custom_filter = custom_filter;
                },
                dataSrc: function(json) {
                    return json.data;
                }
            },

            columns: [{
                    data: 'request_type',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'name',
                    render: function(data) {
                        return data;
                    }
                },

                {
                    data: 'email',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'phone',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'courseName',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'countryName',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'trainingMode',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'created_at',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'actions',
                    render: function(data) {
                        return data
                    }
                }
            ],

            @include('layouts.common_yajra')
        });

        @include('layouts.yajra_table_js')

        $(document).on('click', '.status-filter', function() {
            $('#resetfilter').click();
            const selectedStatus = $(this).data('status');
            $('.status-filter').removeClass('border-2 border-blue-500');
            $(this).addClass('border-2 border-blue-500');
            if (selectedStatus)
                custom_filter = ['status', selectedStatus, 'tab', 'tab'];
            else {
                custom_filter = [];
            }
            table.draw();
        });
        $(document).on('click', '#applyColumnFilter', function() {
            const selectedStatus = $(this).data('status');
            $('.status-filter').removeClass('border-2 border-blue-500');
        });
    });
</script>
