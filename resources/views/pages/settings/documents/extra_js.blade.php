<script>
    function toggleadd_modelSidebar() {
        const addSidebar = document.getElementById('add-sidebar');
        const overlay = document.querySelector(".drawer-overlay");
        const isOpen = !addSidebar.classList.contains('translate-x-full');
        if (isOpen) {
            addSidebar.classList.add('translate-x-full');
        } else {
            addSidebar.classList.remove('translate-x-full');
        }
        toggleOverlay();
    }
    document.querySelector(".drawer-overlay").addEventListener('click', function() {
        toggleadd_modelSidebar();
    });

    function togglview_modelSidebar(doc_id = null) {
        const viewSidebar = document.getElementById('view-sidebar');
        const overlay = document.querySelector(".drawer-overlay");
        const isOpen = !viewSidebar.classList.contains('translate-x-full');

        if (isOpen) {
            viewSidebar.classList.add('translate-x-full');
        } else {
            viewSidebar.classList.remove('translate-x-full');
        }
        
        toggleOverlay();
        if (doc_id) {
            
            var model = $('#mod').val();
            var full_url = model+"-edit/";
            var url = "{{ url('setup',['urls'],'') }}";
            url = url.replace('urls',full_url);
            $('#master_id_edit').val('');
            $('#document_type_edit').val('');
            $('#master_name_edit').val('');
            $('#master_description_edit').val('');
            showLoader();
            $.ajax({
                url: url,
                method: 'get',
                data: {
                   // _token: '{{ csrf_token() }}',
                    doc_id:doc_id
                },
                success: function(data) {
                    var fileUrl;
                    if(data['images'] && data['images'].length >0)
                    {
                        fileUrl = `{{ route('secure-documents', ['path' => '__path__']) }}`.replace('__path__', encodeURIComponent(data['images'][0]['url']));

                    }
                    hideLoader();
                if((model=='TenderRequiredDocument' || model=='QuotationRequiredDocument')&& data['images'].length >0)
                    $('#prev_docs').html(`<a href="${fileUrl}" target="_blank">Download</a>`);
                else
                    $('#prev_docs').html("");
                $('#master_id_edit').val(data['id']);
                $('#master_name_edit').val(data['name']);
                $('#document_type_edit').val(data['type']);
                $('#master_description_edit').val(data['description']);
                }

            });

 
        }
    }
 
    $(document).ready(function() {
        // var name_array = {!! json_encode($name_array) !!};
        // var show_name_array = {!! json_encode($show_name_array) !!};

        // $.append_to_search(name_array, show_name_array);
 
        var model = $('#mod').val();
        var full_url = model + "-yajra";
        var url = "{{ url('setup', ['urls'], '') }}";
        url = url.replace('urls', full_url);

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
                    d.filter = type;
                    d.custom_filter = custom_filter;
                },
                dataSrc: function(json) {
                    return json.data;
                }
            },
            columns: [{ data: 'name', name: 'name', },
                { data: 'doc_type', name: 'doc_type', },
                { data: 'description', name: 'description' },              
                { data: 'creator_name', name: 'creator_name' },
                { data: 'create_on', name: 'create_on' },
                { data: 'actions', name: 'actions', searchable: false }
            ],

            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    text: 'Export Excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'csvHtml5',
                    text: 'Export CSV',
                    className: 'btn btn-info btn-sm'
                }
            ],
            lengthMenu: [ // Pagination options for Yajra dropdown
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            pageLength: 10

        });

        @include('layouts.yajra_table_js')


        $(document).on('click','.master_update',function(){
            var model = $('#mod').val();
            var full_url = model+"-update";
            var master_id = $('#master_id_edit').val();
            var master_name= $('#master_name_edit').val();
            var document_type_edit= $('#document_type_edit').val();
            var master_description = $('#master_description_edit').val();
            // upload_file_edit

            if(master_id && master_name && master_description){
                var url = "{{ url('setup',['urls'],'') }}";
                url = url.replace('urls',full_url);

                if(model == 'BidderRequiredDocument' || model == 'Ranking'){
                    if(document_type_edit){
                        showLoader();
                        $.ajax({
                            url: url,
                            method: 'post',
                            data: {
                                _token: '{{ csrf_token() }}',
                                master_id:master_id,
                                master_name:master_name,
                                document_type_edit:document_type_edit,
                                master_description:master_description
                            },
                            cache: false,
                            //   async: false,
                            success: function(data) {
                                hideLoader();
                            if(data){
                                toastr.success("Record updated successfully.");
                                table.draw();
                                togglview_modelSidebar();
                            }
                            else
                                toastr.error("Something went wrong");
                            }
                        });
                    }
                    else{
                        toastr.warning("Document type is mandatory");
                    }

                }
                else if(model == 'TenderRequiredDocument' || model == 'QuotationRequiredDocument'){
                    var files = $('#upload_file_edit')[0].files;
                    if(files.length>0){
                        var fd = new FormData();
                        fd.append('file', files[0]);
                        fd.append('master_id', master_id);
                        fd.append('master_name', master_name);
                        fd.append('master_description', master_description);
                        fd.append('_token', '{{ csrf_token() }}');
                        showLoader();
                        $.ajax({
                            url: url,
                            method: 'post',
                            data: fd,
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            //   async: false,
                            success: function(data) {
                                hideLoader();
                            if(data){
                                toastr.success("Record updated successfully.");
                                table.draw();
                                togglview_modelSidebar();
                            }
                            else
                                toastr.error("Something went wrong");
                            }
                        });
                    }
                    else{
                        toastr.warning("Please upload document");
                    }

                }

            }
            else{
                toastr.warning("Please fill all fields");
            }
        });


        $(document).on('click','.master_add',function(){
            var model = $('#mod').val();
            var full_url = model+"-add";
            var masters_name = $('#master_name').val();
            var masters_description = $('#master_description').val();
            var documents_type = $('#document_type').val();

            if(masters_name && masters_description){

                var url = "{{ url('setup',['urls'],'') }}";
                    url = url.replace('urls',full_url);
                if(model == 'BidderRequiredDocument' || model == 'Ranking'){
                    if(documents_type){
                        showLoader();
                        $.ajax({
                            url: url,
                            method: 'post',
                            data: {
                                _token: '{{ csrf_token() }}',
                                masters_name:masters_name,
                                documents_type:documents_type,
                                masters_description:masters_description
                            },
                            cache: false,
                            //   async: false,
                            success: function(data) {
                                hideLoader();
                            if(data){
                                toastr.success("Record created successfully.");
                                table.draw();
                                toggleadd_modelSidebar();
                            }
                            else
                                toastr.error("Something went wrong");
                            }
                        });
                    }
                    else{
                        toastr.warning("Document type is mandatory");
                    }

                }
                else if(model == 'TenderRequiredDocument' || model == 'QuotationRequiredDocument'){
                    var files = $('#upload_file')[0].files;
                    if(files.length>0){
                        var fd = new FormData();
                        fd.append('file', files[0]);
                        fd.append('masters_name', masters_name);
                        fd.append('masters_description', masters_description);
                        fd.append('_token', '{{ csrf_token() }}');
                        showLoader();
                        $.ajax({
                            url: url,
                            method: 'post',
                            data: fd,
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            //   async: false,
                            success: function(data) {
                                hideLoader();
                            if(data){
                                toastr.success("Record created successfully.");
                                table.draw();
                                toggleadd_modelSidebar();
                            }
                            else
                                toastr.error("Something went wrong");
                            }
                        });
                    }
                    else{
                        toastr.warning("Please upload document");
                    }

                }

            }
            else{
                toastr.warning("Please fill all fields");
            }
        });


        $(document).on('click','.master_delete',function(){
            var doc_id = $(this).attr('data-id');

            if(doc_id){
                var model = $('#mod').val();
                var full_url = model+"-delete/";
                var url = "{{ url('setup',['urls'],'') }}";
                url = url.replace('urls',full_url);
                delete_by_url(url,doc_id);
            }
            else{
                toastr.warning("Please select valid record");
            }

        });


    });

    function closeAllSidebars() {
        const addSidebar = document.getElementById('add-sidebar');
        const viewSidebar = document.getElementById('view-sidebar');
        const overlay = document.querySelector(".drawer-overlay");
        addSidebar.classList.add('translate-x-full');
        viewSidebar.classList.add('translate-x-full');
        overlay.classList.add('hidden');
    }

    function toggleOverlay() {
        const addSidebar = document.getElementById('add-sidebar');
        const viewSidebar = document.getElementById('view-sidebar');
        const overlay = document.querySelector(".drawer-overlay");
        if (
            !addSidebar.classList.contains('translate-x-full') ||
            !viewSidebar.classList.contains('translate-x-full')
        ) {
            overlay.classList.remove('hidden');
        } else {
            overlay.classList.add('hidden');
        }
    }
    document.querySelector(".drawer-overlay").addEventListener('click', closeAllSidebars);
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") {
            closeAllSidebars();
        }
    });
</script>
