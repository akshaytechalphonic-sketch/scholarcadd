<script>
    $(document).ready(function() {
        $.append_to_search(['title',  'status',  'created_by', 'created_at'],
            ['Title',  'Status', 'Created By', 'Created At']);

        var custom_filter;
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: true,

            ajax: {
                url: "{{ route('knowledge-center-list') }}",
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
                        $('#Article').text(json.count_data?.Article || 0);
                        $('#inreview_count').text(json.count_data?.Blog || 0);
                        $('#Info').text(json.count_data?.Info || 0);
                        $('#Webinar').text(json.count_data?.Webinar || 0);
                        $('#created_by_me_count').text(json.count_data?.created_by_me_count || 0);
                    }
                    return json.data;
                }
            },

            columns: [{
                    data: null, // because it's not from DB
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: 'resource_title',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'meta_description',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'categoryname',
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
                    data: 'status',
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
