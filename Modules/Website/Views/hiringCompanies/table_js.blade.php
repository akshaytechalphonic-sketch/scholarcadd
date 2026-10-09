<script>
$(document).ready(function() {

    $.append_to_search(
        ['name', 'code', 'status', 'created_by', 'created_at'],
        ['Name', 'Code', 'Status', 'Created By', 'Created At']
    );

    var custom_filter;

    var table = $('#data-table-list').DataTable({
        processing: true,
        serverSide: true,
        ordering: false,
        searching: true,
        ajax: {
            url: "{{ route('hiring-companies-list') }}",
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
                    data: 'courseName',
                    render: function(data) {
                        return data;
                    }
                },

                {
                    data: 'companyName',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'JobRole',
                    render: function(data) {
                        return data;
                    }
                },

                {
                    data: 'jobLink',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'jobDescription',
                    render: function(data) {
                        return data
                    }
                },
                {
                    data: 'countryName',
                    render: function(data) {
                        return data
                    }
                },
                {
                    data: 'created_at',
                    render: function(data) {
                        return data
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


    table.on('init', function() {

        table.page.len(100).draw(false);

        // Replace dropdown manually
        let $select = $('#data-table-list_length select');
        $select.html(`
            <option value="50">50</option>
            <option value="100" selected>100</option>
            <option value="500">500</option>
            <option value="1000">1000</option>
        `);
    });

    // 3️⃣ Status filter
    $(document).on('click', '.status-filter', function() {
        $('#resetfilter').click();
        const selectedStatus = $(this).data('status');
        $('.status-filter').removeClass('border-2 border-blue-500');
        $(this).addClass('border-2 border-blue-500');

        if (selectedStatus)
            custom_filter = ['status', selectedStatus, 'tab', 'tab'];
        else
            custom_filter = [];

        table.draw();
    });

    $(document).on('click', '#applyColumnFilter', function() {
        $('.status-filter').removeClass('border-2 border-blue-500');
    });

});
</script>
