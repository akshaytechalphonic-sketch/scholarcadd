@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.select2css')
@endsection
@section('content')

    @php echo breadcrums('common.'.$mod.'-list',['common.home','common.'.$mod.'-list'],['dashboard']) @endphp
@section('back_button')
    @php echo back_button('dashboard') @endphp

    <button type="button" onclick="toggleadd_modelSidebar()"  
        class="inline-flex hover-scale gap-1 border-l-[1px] items-center px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
        <i class=" ri-add-line text-blue-500 font-semibold"></i>
        {{ __('common.add') }}
    </button>
    @php echo App\Helpers\MasterHelper::filter_button(); @endphp
@endsection

@include('pages.common.custom_filter_html')
 
<input type="hidden" id="mod" value="{{ $mod }}">
<input type="hidden" id="parent" value="{{ $parent }}">
<div class="text-xs w-full border bg-white rounded-md">
    <div class="w-full table_wrapper p-2">
        <table id="data-table-list" class="display w-full min-w-full border-collapse">
                <thead class="bg-primary text-white text-xs">
                    <tr>
                        <th>{{ __('common.names') }}</th>
                        <th>{{ __('common.'.$parent.'') }}</th>
                        <th>{{ __('common.description') }}</th> 
                        @if ($mod == 'PreScreeningQuestion')
                            <th>{{ __('common.attachement-required') }}</th>
                            <th>{{ __('common.score') }}</th>                        
                        @elseif($mod == "ScoreItem")
                            <th>{{ __('common.'.$mod.'-value') }}</th>
                            <th>{{ __('common.'.$mod.'-color_code') }}</th>
                        @else
                            <th></th>
                            <th></th>
                        @endif
                        <th>{{ __('common.status') }}</th>
                        <th>{{ __('common.created_by') }}</th>
                        <th>{{ __('common.created_at') }}</th>
                        <th>{{ __('common.action') }}</th> 
                    </tr>
                </thead>
                <tbody class="text-xs"> </tbody>
 

            </table>
        </div> 
</div>


<div id="add-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('common.add-' . $mod . '') }}
        </h5>
        <button type="button" onclick="toggleadd_modelSidebar()" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <div class="p-4">
        <div class="w-full">
            <label class="text-sm">{{ __('common.' . $mod . '-name') }}</label>
            <input type="text" id="master_name" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
        </div>
 
        @if ($mod == 'Warehouse')
        <div class="w-full">
            <label class="text-sm">{{ __('common.' . $mod . '-code') }}</label>
            <input type="text" id="master_code" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
        </div>
        @endif
        <div class="w-full">
            <label class="text-sm">{{ __('common.' . $parent . '-name') }}</label>
            <select id="parent_id" class="select-single" style="width:100%">
                <option value="">{{ __('common.chooseoption') }}</option>
                @foreach ($parentdata as $prnt)
                    <option value="{{ $prnt->id }}">{{ $prnt->name }}</option>
                @endforeach
            </select>
        </div>                 

        <div class="w-full mt-4">
            <label class="text-sm">{{ __('common.description') }}</label>
            <textarea id="master_description" class="input-box form-input border my-1 input-box border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5"></textarea>
        </div>

        <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="toggleadd_modelSidebar()" class="btn-primary-sm">
                    <i class="ri-close-circle-line"></i>
                    {{ __('common.close') }}
                </button>
                <button type="button" class="master_add btn-success-sm">
                    <i class="ri-add-circle-line"></i>
                    {{ __('common.add') }}
                </button>
            </div>
        </div>
    </div>
</div>

<div id="view-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('common.edit-' . $mod . '') }}
        </h5>
        <button type="button" onclick="togglview_modelSidebar()" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <div class="p-4">
        <input type="hidden"  id="master_id_edit" />
        <div class="w-full">
            <label class="text-sm">{{ __('common.' . $mod . '-name') }}</label>
            <input type="text" disabled id="master_name_edit" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
        </div>

        <div class="w-full">
            <label class="text-sm">{{ __('common.' . $parent . '-name') }}</label>
            <select id="parent_id_edit" class="form-input">
                <option value="">{{ __('common.chooseoption') }}</option>
                @foreach ($parentdata as $prnt)
                    <option  value="{{ $prnt->id }}">{{ $prnt->name }}</option>
                @endforeach
            </select>
        </div>   

        @if ($mod == 'Warehouse')
        <div class="w-full">
            <label class="text-sm">{{ __('common.' . $mod . '-code') }}</label>
            <input type="text" disabled id="master_code_edit" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
        </div>
        @endif
 
        <div class="w-full mt-4">
            <label class="text-sm">{{ __('common.description') }}</label>
            <textarea id="master_description_edit" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5"></textarea>
        </div>

        <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="togglview_modelSidebar()" class="btn-primary-sm">
                    <i class="ri-close-circle-line"></i>
                    {{ __('common.close') }}
                </button>
                <button type="button" class="master_update btn-success-sm">
                    <i class="ri-add-circle-line"></i>
                    {{ __('common.update') }}
                </button>
            </div>
        </div>
    </div>
</div>

 
@endsection

@section('scripts')
@include('pages.common.custom_filter_js')
@include('layouts.datatable_js')
@include('layouts.select2js')
<script> 
 function toggleadd_modelSidebar() {
        const sidebar = document.getElementById('add-sidebar');
        sidebar.classList.toggle('translate-x-full');        
    }

    function togglview_modelSidebar(doc_id=null) {
        const sidebar = document.getElementById('view-sidebar');
        sidebar.classList.toggle('translate-x-full'); 

        if(doc_id){
            var model = $('#mod').val();
            var prnt = $('#parent').val();
            var prnt_id = prnt.toLowerCase() + '_id';

            var full_url = prnt + '-' + model + "-edits";
            var url = "{{ url('master', ['urls'], '') }}";
            url = url.replace('urls', full_url);
            $('#master_id_edit').val('');
            $('#master_code_edit').val('');
            $('#master_name_edit').val('');
            $('#parent_id_edit').val('');
            $('#master_description_edit').val('');
            $('#score_id_edit').val('');
            $('#attachement_required_edit').val();
            $('#master_score_edit').val('');
            $('#master_color_edit').val('');
            showLoader();
            $.ajax({
                url: url,
                method: 'get',
                data: {
                    //_token: '{{ csrf_token() }}',
                    doc_id: doc_id
                },
                success: function(data) {
                    hideLoader();
                    $('#master_id_edit').val(data['id']);
                    $('#master_name_edit').val(data['name']);
                    $('#parent_id_edit').val(data[prnt_id]);
                    $('#master_code_edit').val(data['code']);
                    $('#master_description_edit').val(data['description']);
                    if (model == 'PreScreeningQuestion') {
                        $('#attachement_required_edit').val(data['attachement']);
                        $('#score_id_edit').val(data['score_id']);

                    }
                    if(model=='ScoreItem'){
                        $('#master_score_edit').val(data['value']);
                        $('#master_color_edit').val(data['color_code']);
                    }

                }

            });
        }       
    }

    function closeSidebarOnClickOutside(event) {
        const sidebar = document.getElementById('view-sidebar');
        if (!sidebar.contains(event.target) && !event.target.closest('.dropdown')) {
            sidebar.classList.add('translate-x-full');  // Close the sidebar
        } 
    }
    document.addEventListener('click', closeSidebarOnClickOutside);


    $(document).ready(function() {
 
        var parent_colname = "{{$parent}}";
        $.append_to_search(['name','parent_id'], ['name',parent_colname]);

        var model = $('#mod').val();
        var prnt = $('#parent').val();
        var full_url = prnt + '-' + model + "-yajras";  
        var url = "{{ url('master', ['urls'], '') }}";
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
                   { data: 'parent', name: 'parent' },
                   { data: 'description', name: 'description' },
                   { data: 'extra1', name: 'extra1' },
                   { data: 'extra2', name: 'extra2' },
                   { data: 'status', name: 'status' }, 
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
 
 
        $(document).on('click', '.master_add', function() {
            var model = $('#mod').val();
            var prnt = $('#parent').val();
            var full_url = prnt + '-' + model + "-adds";
            var masters_name = $('#master_name').val();
            var parent_id = $('#parent_id').val();
            var masters_code = $('#master_code').val();
            var masters_description = $('#master_description').val();
            var attachement_required = $('#attachement_required').val();
            var score_id = $('#score_id').val();

            var master_score = $('#master_score').val();
            var master_color = $('#master_color').val();
            if(model=='PreScreeningQuestion')
            {
                parent_id = $(".pre-screen_type:visible select, .assessment_type:visible select").val();
            }

            if (masters_name && masters_description && (parent_id || model == "Position" || model == "Department")) {
                var count = 0;
                if (model == 'Warehouse') {
                    if (masters_code);
                    else
                        count++;
                }

                if (count == 0) {

                    var url = "{{ url('master', ['urls'], '') }}";
                    url = url.replace('urls', full_url);
                    showLoader();

                    $.ajax({
                        url: url,
                        method: 'post',
                        data: {
                            _token: '{{ csrf_token() }}',
                            masters_name: masters_name,
                            parent_id: parent_id,
                            masters_code: masters_code,
                            masters_description: masters_description,
                            attachement_required:attachement_required,
                            score_id:score_id,
                            master_score:master_score,
                            master_color:master_color
                        },
                        cache: false,
                        //   async: false,
                        success: function(data) {
                            hideLoader();
                            if (data) {
                                toastr.success("Record created successfully.");
                                table.draw();
                                   toggleadd_modelSidebar();
                            } else
                                toastr.error("Something went wrong");

                        }
                    });
                } else {
                    toastr.warning(model + " Code is mandatory");
                }
            } else {
                toastr.warning("Please fill all fields");
            }
        });

        $(document).on('click', '.master_update', function() {
            var model = $('#mod').val();
            var prnt = $('#parent').val();
            var full_url = prnt + '-' + model + "-updates";
            var master_id = $(master_id_edit).val();
            var parent_id = $(parent_id_edit).val();
            var master_description = $(master_description_edit).val();
            var attachement_required = $('#attachement_required_edit').val();
            var score_id = $('#score_id_edit').val();
            var master_option_type = $('#master_option_type_edit').val();
            var master_score = $('#master_score_edit').val();
            var master_color = $('#master_color_edit').val();

            if (master_id && master_description && parent_id) {
                var url = "{{ url('master', ['urls'], '') }}";
                url = url.replace('urls', full_url);
                showLoader();
                $.ajax({
                    url: url,
                    method: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        master_id: master_id,
                        parent_id: parent_id,
                        master_description: master_description,
                        attachement_required:attachement_required,
                        score_id:score_id,
                        'master_score':master_score,
                        'master_color':master_color
                    },
                    cache: false,
                    //   async: false,
                    success: function(data) {
                        hideLoader();
                        if (data) {
                            toastr.success("Record updated successfully.");
                            table.draw();
                            togglview_modelSidebar();
                        } else
                            toastr.error("Something went wrong");
                    }
                });
            } else {
                toastr.warning("Please fill all fields");
            }
        }); 



 
    });
</script>
@endsection
