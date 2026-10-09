<script>
    $(document).ready(function() {
        $.append_to_search(['name', 'code', 'status', 'created_by', 'created_at'],
            ['Name', 'Code', 'Status', 'Created By', 'Created At']);

        var custom_filter;
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: true,

            ajax: {
                url: "{{ route('course-list') }}",
                type: 'GET',
                data: function(d) {
                    d.custom_filter = custom_filter;
                },
                dataSrc: function(json) {
                    let count_data = json.count_data;
                    if(count_data){
                        $('#total').text(json.count_data?.total || 0);
                        $('#draft_count').text(json.count_data?.draft_count || 0);
                        $('#approved_count').text(json.count_data?.approved_count || 0);
                        $('#rejected_count').text(json.count_data?.rejected_count || 0);
                        $('#inreview_count').text(json.count_data?.inreview_count || 0);
                        $('#created_by_me_count').text(json.count_data?.created_by_me_count || 0);
                    }
                    return json.data;
                }
            },

            columns: [{
                    data: 'course_short_name',
                    render: function(data, type, row) {
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
                    data: 'course_category',
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

                // {
                //     data: 'is_demand_course',
                //     render: function(data) {
                //         return data;
                //     }
                // },
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
            if(selectedStatus)
            custom_filter = ['status', selectedStatus,'tab','tab'];
            else{
                custom_filter= [];
            }
            table.draw();
        });
        $(document).on('click', '#applyColumnFilter', function() {
            const selectedStatus = $(this).data('status');
            $('.status-filter').removeClass('border-2 border-blue-500');
        });
    });
</script>
