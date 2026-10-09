<script>
    $(document).ready(function() {
        $('#courseDate, #CourseCountryName, #stateName').on('change', function() {
            table.draw();
        });

       $.append_to_search(['coursename', 'CourseCountryName', 'stateName', 'created_by', 'created_at'],
            ['coursename', 'CourseCountryName', 'stateName', 'created_by', 'created_at']);


        var custom_filter;
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: true,

            ajax: {
                url: "{{ route('dynamic-course-pages-list') }}",
                type: 'GET',
                data: function(d) {
                    d.custom_filter = custom_filter;
                    d.courseDate = $('#courseDate').val();
                    d.CourseCountryName = $('#CourseCountryName').val();
                    d.stateName = $('#stateName').val();
                },
                dataSrc: function(json) {
                    return json.data;
                }
            },

            columns: [{
                    data: 'Coursedate',
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'CourseCountryName',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'CourseCountryISO2',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'state',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'created_by',
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
                //     data: 'status',
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

        function deleteRecord(deleteUrl) {

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this record!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: deleteUrl,
                    type: "GET",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {

                        $('#data-table-list').DataTable().draw(false);

                        Swal.fire(
                            'Deleted!',
                            response.message ?? 'Record deleted successfully',
                            'success'
                        );
                    },
                    error: function () {
                        Swal.fire(
                            'Error!',
                            'Something went wrong while deleting record',
                            'error'
                        );
                    }
                });

            }
        });
    }
</script>
