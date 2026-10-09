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
                url: "{{ route('appointment-list') }}",
                type: 'GET',
                data: function(d) {
                    d.custom_filter = custom_filter;
                },
                dataSrc: function(json) {
                    return json.data;
                }
            },
            columns: [
                 {
                    data: 'CourseName',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'fullName',
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
                    data: 'CountryName',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'agreed',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'Created',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'isContacted',
                    render: function(data) {
                        if (data === "Contacted") {
                            return `<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">${data}</span>`;
                        } else if(data === "Not Contacted") {
                            return `<span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-medium">${data}</span>`;
                        }
                    }
                },
                {
                    data: 'actions',
                    render: function(data) {
                        return data;
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
