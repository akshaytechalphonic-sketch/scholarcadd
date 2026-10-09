<script>






    function view_model(modal,master_child_id) {
        toggle_model(modal);
        if (master_child_id) {
            var url = "{{route('master-parent-edit')}}";
            $('#master_parent_name').val('');
            $('#master_id_edit').val('');
            $('#master_code_edit').val('');
            $('#master_name_edit').val('');
            $('#master_description_edit').val('');
            showLoader();
            $.ajax({
                url: url,
                method: 'get',
                data: {
                    master_child_id: master_child_id
                },
                success: function(data) {
                    hideLoader();
                    if(data.status){
                        $('#master_child_id').val(master_child_id);
                        $('#master_name_edit').val(data.data['name']);
                        $('#master_code_edit').val(data.data['code']);
                        $('#master_description_edit').val(data.data['description']);
                        $('#master_parent_name').val(data.data['master_parent']['name']);
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
    });
        var type = $('#type').val();
        var url = "{{route('get-parent-list')}}";
        $.ajax({
            url: url,
            method: 'get',
            data: {
                type: type
            },
            success: function(data) {
                hideLoader();
                if(data.status){
                    $('#master_parent_id').append('<option value="">Please Select</option>');
                    $.each(data.data, function(index, data) {
                        $('#master_parent_id').append('<option value="'+data['id']+'">'+data['name']+'</option>');
                    });
                }
                else{
                    toastr.error("Data not found");
                }
            }
        });

        var parent_filter = "{{$parent}}";
        var parent_id="{{$parent_id}}";

        $.append_to_search(['name','code','master_parent_id'], ['Name','Code',parent_filter]);


        var master_type = $('#type').val();
        var url = "{{route('master-child-list')}}";

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
                    d.parent_id =parent_id;
                },
                dataSrc: function(json) {
                    return json.data;
                }
            },
            columns: [{ data: 'code', name: 'code', },
            { data: 'parent', name: 'parent' },
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
            var master_parent_id = $('#master_parent_id').val();
            var master_type = $('#type').val();

            if (masters_name && masters_description && master_parent_id) {
                var count = 0;
                if (master_type == 'Warehouse') {
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
                            master_parent_id: master_parent_id
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


        // $('#master_description_edit').val('');

        $(document).on('click', '.master_update', function() {

            var master_child_id = $('#master_child_id').val();
            var masters_description = $('#master_description_edit').val();
            var masters_status = $('#master_status_edit').val();

            if (master_child_id && masters_description && masters_status) {
                showLoader();
                var url = "{{route('master-parent-update')}}";
                $.ajax({
                    url: url,
                    method: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        master_child_id: master_child_id,
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
                            $('#master_child_id').val('');
                            $('#master_name_edit').val('');
                            $('#master_code_edit').val('');
                            $('#master_description_edit').val('');
                            $('#master_status_edit').val('');
                            $('#master_parent_name').val('');
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

    // document.addEventListener('keydown', function(e) {
    //     if (e.key === "Escape") {s
    //         closeAllSidebars();
    //     }
    // });
</script>
