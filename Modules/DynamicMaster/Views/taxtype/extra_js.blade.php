<script>
    $(document).ready(function() {
        $(".drawer-overlay").on('click', function() {
            closeAllSidebars('view-sidebar');
            closeAllSidebars('add-sidebar');
        });

        $.append_to_search_with_clear(['name', 'code'], ['Name',
            'Code',,
        ]);

        var type;
        var custom_filter;
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,

            ajax: {
                url: "{{ route('tax-type-list') }}",
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
                    data: 'tax_type_name',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'tax_type_code',
                    render: function(data) {
                        return data;
                    }
                },

                {
                    data: 'description',
                    render: function(data) {
                        return data;
                    }
                },
                {
                    data: 'is_active',
                    render: function(data) {
                        return data ? '<span class="bg-green-400 text-white px-3 rounded-md">Yes</span>' :
                            '<span class="bg-red-400 text-white px-3 rounded-md">No</span>';
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
                url: "{{ route('tax-type-store') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response[0] == 200) {
                        table.draw();
                        toastr.success(response.message)
                        $('#add_modal')[0].reset();
                        closeAllSidebars('add-sidebar');
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
         $('#edit_modal').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            showLoader();
            $.ajax({
                url: "{{ route('tax-type-store') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response[0] == 200) {
                        table.draw();
                        toastr.success(response.message)
                        toggle_model('view-sidebar');
                         $('#edit_modal')[0].reset();
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
     function view_model(modal, id) {
            toggle_model(modal);

            if (id) {
                let url = "{{ route('tax-type-edit') }}";
                showLoader();
                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(data) {
                        hideLoader();

                        // Populate form fields
                        $('#edit_tax_type_name').val(data.tax_type_name);
                        $('#edit_tax_type_code').val(data.tax_type_code);
                        $('#edit_description').val(data.description);
                        $('#edit_is_active').val(data.is_active);
                        $('#old_id').val(id);

                        // Optional: populate status dropdown properly
                        if (data.is_active === 1 || data.is_active === true) {
                            $('#edit_is_active').html(`
                                <option value="">Select Status</option>
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            `);
                        } else if (data.is_active === 0 || data.is_active === false) {
                            $('#edit_is_active').html(`
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0" selected>Inactive</option>
                            `);
                        } else {
                            $('#edit_is_active').html(`
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                                `);
                        }
                    },
                    error: function(xhr) {
                        hideLoader();
                        console.error('Error fetching tax type:', xhr);
                        toastr.error('Failed to load tax type data.');
                    }
                });
            }
        }
</script>
