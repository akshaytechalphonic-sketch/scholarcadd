<script>
    $(document).ready(function() {
        $(".drawer-overlay").on('click', function() {
            closeAllSidebars('view-sidebar');
            closeAllSidebars('add-sidebar');
        });

        $.append_to_search_with_clear(['scope', 'employee_name', 'delegate_to', 'status'], ['Scope',
            'Employee Name', 'Delegate To', 'Status'
        ]);

        var type;
        var custom_filter;
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,

            ajax: {
                url: "{{ route('deligation-workflow.list') }}",
                type: 'GET',
                data: function(d) {
                    d.filter = type;
                    d.custom_filter = custom_filter;
                },
                dataSrc: function(json) {
                    return json.data;
                }
            },

            columns: [{
                    data: 'scope',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'employee_name',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'delegate_to',
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
                    data: 'status',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'actions',
                    render: function(data) {
                        return data;
                    }
                },
            ],

            @include('layouts.common_yajra')
        });

        @include('layouts.yajra_table_js')


        $('#add_modal').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            showLoader();
            $.ajax({
                url: "{{ route('add.delegation') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response[0] == 200) {
                        table.draw();
                        toastr.success(response.message)
                        $('#add_status').val('');
                        $('#end_date').val('');
                        $('#start_date').val('');
                        $('#delegate_to').val('');
                        $('#employee_name').val('');
                        $('#scope').val('');
                        toggle_model('add-sidebar');
                    } else {
                        toastr.error(response.message)
                    }
                    hideLoader();
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = '';
                        for (const [key, messages] of Object.entries(errors)) {
                            errorMessages += messages.join('<br>');
                        }
                        toastr.error('Validation Error!')
                    } else {
                        toastr.error('Oops..,Something went wrong: ' + error)
                    }
                    hideLoader();
                }
            });
        });

    });
</script>
