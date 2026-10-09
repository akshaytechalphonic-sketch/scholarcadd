<script>
    $(document).ready(function() {
        $.append_to_search(['name', 'created_by', 'created_at'],
            ['Name', 'Created By', 'Created At']);

        var custom_filter;
        var table = $('#data-table-list-menu').DataTable({

            ordering: false,
            searching: false,

            ajax: {
                url: "{{ route('enroll-course-corporate-list') }}",
                type: 'GET',
                data: function(d) {
                    d.custom_filter = custom_filter;
                },
            },

            columns: [{
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
                    data: 'contact_number',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'course',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'country',
                    render: function(data) {
                        return data;
                    }
                },
                  {
                    data: 'company_name',
                    render: function(data) {
                        return data;
                    }
                },
                  {
                    data: 'job_title',
                    render: function(data) {
                        return data;
                    }
                },
                  {
                    data: 'group_size',
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

    });
</script>
