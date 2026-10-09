<script>
    var type= "{{$type}}"



    function assign_employee(employee_id,master_parent_id) {
        toggle_model('employee-sidebar');
        if (master_parent_id) {
            $('#master_id_for_emp').val(master_parent_id);
            $('#employee_id').val(employee_id);
        }
    }

      function view_model(modal,master_parent_id) {
        toggle_model(modal);
        if (master_parent_id) {
            var url = "{{route('master-parent-edit')}}";
            $('#master_id_edit').val('');
            $('#master_code_edit').val('');
            $('#master_name_edit').val('');
            $('#master_description_edit').val('');
            showLoader();
            $.ajax({
                url: url,
                method: 'get',
                data: {
                    master_parent_id: master_parent_id
                },
                success: function(data) {
                    hideLoader();
                    if(data.status){
                        if(type=="Period")
                        {
                            $('#master_name_edit').val(data.data['name']).trigger('change');
                        }

                        $('#master_id_edit').val(master_parent_id);
                        $('#master_name_edit').val(data.data['name']);
                        $('#master_code_edit').val(data.data['code']);
                        $('#master_description_edit').val(data.data['description']);
                        $('#master_status_edit').val(data.data['status']);


                    }
                    else{
                        toastr.error("Data not found");
                    }
                }
            });
        }
    }






    $(document).ready(function() {

        $(".drawer-overlay").on('click', function(){
            closeAllSidebars('view-sidebar');
            closeAllSidebars('add-sidebar');
            closeAllSidebars('employee-sidebar');
        });

        var name_array = {!! json_encode($name_array) !!};
        var show_name_array = {!! json_encode($show_name_array) !!};

        $.append_to_search(name_array, show_name_array);


        var master_type = $('#type').val();
        var url = "{{route('master-parent-list')}}";

        var type;
        var custom_filter;
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,

            ajax: {
                url: url,
                type: 'GET',
                data: function(d) {
                    d.ty = master_type;
                    d.filter = type;
                    d.custom_filter = custom_filter;
                },
                dataSrc: function(json) {
                    return json.data;
                }
            },
            columns: [{ data: 'code', name: 'code', },
                { data: 'description', name: 'description' },
                { data: 'creator_name_or_code', name: 'creator_name_or_code' },
                { data: 'status', name: 'status' },
                { data: 'create_on', name: 'create_on' },
                { data: 'actions', name: 'actions', searchable: false }
            ],

            @include('layouts.common_yajra')


        });

        @include('layouts.yajra_table_js')


        $(document).on('click', '.master_add', function() {
            var masters_name = $('#master_name').val();
            var masters_description = $('#master_description').val();
            var masters_code = $('#master_code').val();
            var masters_type = $('#type').val();


            if (masters_name && masters_description) {
                var count = 0;
                if (masters_type == 'Currency' || masters_type == 'Site' || masters_type == 'RatingCriteria') {
                    if (masters_code);
                    else
                        count++;
                }
                if (count == 0) {
                    var url = "{{route('master-parent-store')}}";
                    showLoader();
                    $.ajax({
                        url: url,
                        method: 'post',
                        data: {
                            _token: '{{ csrf_token() }}',
                            masters_name: masters_name,
                            masters_code: masters_code,
                            masters_description: masters_description,
                            masters_type: masters_type
                        },
                        cache: false,
                        //   async: false,
                        success: function(data) {
                            hideLoader();
                            if (data.status) {
                                toastr.success(data.success);
                                table.draw();

                                $('#master_name').val('');
                                $('#master_description').val('');
                                $('#master_code').val('');

                                toggle_model('add-sidebar');
                            } else
                                toastr.error(data.error);
                        },
                       error: function(xhr) {
                            hideLoader();
                            if (xhr.status === 400) {
                                let response = JSON.parse(xhr.responseText);
                                toastr.error(response.error);
                            }
                            else if (xhr.status === 422) {
                                // Validation error
                                let response = JSON.parse(xhr.responseText);

                                if (response.type === 'validation' && response.error) {
                                    // Display each validation error
                                    $.each(response.error, function(key, messages) {
                                        if (Array.isArray(messages)) {
                                            messages.forEach(function(msg) {
                                                toastr.error(msg);
                                            });
                                        } else {
                                            toastr.error(messages);
                                        }
                                    });
                                } else {
                                    toastr.error("Validation failed.");
                                }
                            }
                            else {
                                toastr.error("An unexpected error occurred.");
                            }
                        }

                    });
                } else {
                    toastr.warning(model + " Code is mandatory");
                }

            } else {
                toastr.warning("Please fill all fields");
            }
        });

        $(document).on('click', '.employee_update', function() {
            var master_parent_id = $('#master_id_for_emp').val();
            var employee_id = $('#employee_id').val();
            if (master_parent_id && employee_id) {
                var url = "{{route('employee-assign')}}";
                showLoader();
                $.ajax({
                    url: url,
                    method: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        master_parent_id: master_parent_id,
                        employee_id: employee_id
                    },
                    cache: false,
                    success: function(data) {
                        hideLoader();
                        if (data.status) {
                            toastr.success(data.success);
                            table.draw();

                            $('#master_id_for_emp').val('');
                            $('#employee_id').val('');

                            toggle_model('employee-sidebar');
                        } else
                            toastr.error(data.error);
                    },
                    error: function(xhr) {
                        hideLoader();
                        if (xhr.status === 400) {
                            let response = JSON.parse(xhr.responseText);
                            toastr.error(response.error);
                        }
                        else if (xhr.status === 422) {
                            // Validation error
                            let response = JSON.parse(xhr.responseText);

                            if (response.type === 'validation' && response.error) {
                                // Display each validation error
                                $.each(response.error, function(key, messages) {
                                    if (Array.isArray(messages)) {
                                        messages.forEach(function(msg) {
                                            toastr.error(msg);
                                        });
                                    } else {
                                        toastr.error(messages);
                                    }
                                });
                            } else {
                                toastr.error("Validation failed.");
                            }
                        }
                        else {
                            toastr.error("An unexpected error occurred.");
                        }
                    }

                });
            } else {
                toastr.warning("Please fill all fields");
            }
        });



        $(document).on('click', '.master_update', function() {

            var master_parent_id = $('#master_id_edit').val();
            var masters_description = $('#master_description_edit').val();
            var masters_status = $('#master_status_edit').val();

            if (master_parent_id && masters_description && masters_status) {
                showLoader();
                var url = "{{route('master-parent-update')}}";
                $.ajax({
                    url: url,
                    method: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        master_parent_id: master_parent_id,
                        masters_description: masters_description,
                        masters_status: masters_status,
                    },
                    cache: false,
                    //   async: false,
                    success: function(data) {
                        hideLoader();
                        if (data.status) {
                            toastr.success(data.success);
                            table.draw();
                            $('#master_id_edit').val('');
                            $('#master_name_edit').val('');
                            $('#master_code_edit').val('');
                            $('#master_description_edit').val('');
                            $('#master_status_edit').val('');
                            toggle_model('view-sidebar');
                        } else
                            toastr.error(data.error);
                    },
                    error: function(xhr) {
                        hideLoader();
                        if (xhr.status === 400) {
                            let response = JSON.parse(xhr.responseText);
                            toastr.error(response.error);
                        } else {
                            toastr.error("An unexpected error occurred.");
                        }
                    }
                });
            } else {
                toastr.warning("Please fill all fields");
            }
        });


    });

    $(document).on('click', '.view-btn', function () {
    const modal = $(this).data('modal');
    const master_parent_id = $(this).data('id');

    view_model(modal, master_parent_id);
});


    // document.addEventListener('keydown', function(e) {
    //     if (e.key === "Escape") {s
    //         closeAllSidebars();
    //     }
    // });
</script>


