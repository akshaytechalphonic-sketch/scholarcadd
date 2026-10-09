<script>
    $(document).ready(function() {
        $.append_to_search(['name', 'code', 'status', 'created_by', 'created_at'],
            ['Title', 'Code', 'Status', 'Created By', 'Created At']);

        var custom_filter;
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: true,

            ajax: {
                url: "{{ route('course-category-list') }}",
                type: 'GET',
                data: function(d) {
                    d.custom_filter = custom_filter;
                },
                dataSrc: function(json) {
                    return json.data;
                }
            },

            columns: [{
                    data: null, // because it's not from DB
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: 'category_name',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'page_link',
                    render: function(data) {
                        return data;
                    }
                },
                  {
                    data: 'sub_category',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'short_description',
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
