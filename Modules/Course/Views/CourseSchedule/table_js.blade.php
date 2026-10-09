<script>
    let selectedRows = [];
    $(document).ready(function() {
        $.append_to_search(['name', 'code', 'status', 'created_by', 'created_at'],
            ['Name', 'Code', 'Status', 'Created By', 'Created At']);

        var custom_filter;
        let selectedRows = [];
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: true,

            ajax: {
                url: "{{ route('course-schedule-list') }}",
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
                data: 'id',
                orderable: false,
                searchable: false,
                render: function (data) {
                    const checked = selectedRows.includes(data) ? 'checked' : '';
                    return `<input type="checkbox" class="row-checkbox" value="${data}" ${checked}>`;
                }
            },

            {
                    data: 'course_schedule_id',
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'course_includes',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'ISO2',
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
                    data: 'discount_amt',
                    render: function(data) {
                        return data;
                    }
                },
                 {
                    data: 'course_fees',
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
                    data: 'start_date',
                    render: function(data) {
                        return data;
                    }
                },

                 {
                    data: 'end_date',
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

            table.on('init', function() {

        table.page.len(100).draw(false);

        // Replace dropdown manually
        let $select = $('#data-table-list_length select');
        $select.html(`
            <option value="50">50</option>
            <option value="100" selected>100</option>
            <option value="500">500</option>
            <option value="1000">1000</option>
            <option value="2000">2000</option>
            <option value="2000">4000</option>
        `);
    });

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

            $(document).on('change', '.row-checkbox', function () {
            const id = parseInt($(this).val());

            if (this.checked) {
                if (!selectedRows.includes(id)) {
                    selectedRows.push(id);
                }
            } else {
                selectedRows = selectedRows.filter(item => item !== id);
            }

            updateSelectAllCheckbox();
        });

                $('#select-all').on('change', function () {
            const isChecked = this.checked;

            $('#data-table-list tbody .row-checkbox').each(function () {
                const id = parseInt($(this).val());

                $(this).prop('checked', isChecked);

                if (isChecked) {
                    if (!selectedRows.includes(id)) {
                        selectedRows.push(id);
                    }
                } else {
                    selectedRows = selectedRows.filter(item => item !== id);
                }
            });
        });

        function updateSelectAllCheckbox() {
            const total = $('#data-table-list tbody .row-checkbox').length;
            const checked = $('#data-table-list tbody .row-checkbox:checked').length;

            $('#select-all').prop('checked', total > 0 && total === checked);
        }
$('#delete-selected').on('click', function () {

    if (selectedRows.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Records Selected',
            text: 'Please select at least one record'
        });
        return;
    }

    Swal.fire({
        title: 'Are you sure?',
        text: "You want to delete selected records!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete them!'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "{{ route('course-schedule-bulk-delete') }}",
                type: "POST",
                data: {
                    ids: selectedRows,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {

                    selectedRows = [];
                    $('#select-all').prop('checked', false);

                    // Reload DataTable without resetting pagination
                    $('#data-table-list').DataTable().draw(false);

                    Swal.fire(
                        'Deleted!',
                        response.message ?? 'Records deleted successfully',
                        'success'
                    );
                },
                error: function () {
                    Swal.fire(
                        'Error!',
                        'Something went wrong while deleting records',
                        'error'
                    );
                }
            });

        }
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


// });

</script>

