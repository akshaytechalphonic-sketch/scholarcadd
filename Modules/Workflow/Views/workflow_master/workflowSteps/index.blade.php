@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    <style>
        :root {
            --background: #dad7cd;
            --lines: #a3b18a;
            --border: #588157;
            --text: #344e41;
            --warn-body: #344e41;
            --warn-text: #dad7cd;
        }

        .popup {
            width: 300px;
            height: 30px;
            font-size: 11px;
            background: var(--warn-body);
            line-height: 15px;
            display: flex;
            color: var(--warn-text);
            align-items: center;
            justify-content: space-between;
            border-radius: 25px;
            padding: 5px 15px 5px 15px;
            position: absolute;
            top: 5%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 100;
            cursor: default;
            visibility: visible;
            -webkit-animation: fadeIn 2s;
            animation: fadeIn 2s;
        }

        @-webkit-keyframes fadeIn {
            0% {
                opacity: 0;
            }

            75% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .popup .close {
            font-size: 25px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .clicked {
            display: none;
        }

        .tree {
            display: grid;
            place-items: center;
        }

        .tree ul {
            padding-top: 20px;
            position: relative;

            transition: all 0.5s;
            -webkit-transition: all 0.5s;
            -moz-transition: all 0.5s;
        }

        .tree li {
            float: left;
            text-align: center;
            list-style-type: none;
            position: relative;
            padding: 20px 5px 0 5px;
            transition: all 0.5s;
            -webkit-transition: all 0.5s;
            -moz-transition: all 0.5s;
            z-index: 999;
        }

        .tree li::before,
        .tree li::after {
            content: '';
            position: absolute;
            top: 0;
            right: 50%;
            border-top: 1px solid var(--lines);
            width: 50%;
            height: 20px;
        }

        .tree li::after {
            right: auto;
            left: 50%;
            border-left: 1px solid var(--lines);
        }

        .tree li:only-child::after,
        .tree li:only-child::before {
            display: none;
        }

        .tree li:only-child {
            padding-top: 0;
        }

        .tree li:first-child::before,
        .tree li:last-child::after {
            border: 0 none;
        }

        .tree li:last-child::before {
            border-right: 1px solid var(--lines);
            border-radius: 0 5px 0 0;
            -webkit-border-radius: 0 5px 0 0;
            -moz-border-radius: 0 5px 0 0;
        }

        .tree li:first-child::after {
            border-radius: 5px 0 0 0;
            -webkit-border-radius: 5px 0 0 0;
            -moz-border-radius: 5px 0 0 0;
        }

        .tree ul ul::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            border-left: 1px solid var(--lines);
            width: 0;
            height: 20px;
        }

        .tree li a {
            border: 3px solid var(--border);
            border-image-slice: 1;
            border-radius: 8px;
            background: var(--background);
            width: 100px;
            padding: 5px 10px;
            text-decoration: none;
            color: var(--text);
            font-size: 13px;
            display: inline-block;
            z-index: 20000;

            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;

            transition: all 0.5s;
            -webkit-transition: all 0.5s;
            -moz-transition: all 0.5s;
        }

        .tree li a:hover,
        .tree li a:hover+ul li a {
            background: var(--border);
            color: var(--background);
            border: 3px solid var(--border);
        }

        .tree li a:hover+ul li::after,
        .tree li a:hover+ul li::before,
        .tree li a:hover+ul::before,
        .tree li a:hover+ul ul::before {
            border-color: var(--border);
        }

        .tree .head {
            width: 240px;
        }

        .tree .sub-head {
            width: 110px;
        }

        .low-heads .main-container ul li a {
            margin-bottom: 20px;
        }

        .low-heads ul ul {
            height: 12px;
        }
    </style>
@endsection
@section('content')
    <div>
        @php echo breadcrums('company.workflow-steps',['common.home','company.all-workflow','company.workflow-steps'],['dashboard','allworkflow.list']) @endphp
    @section('back_button')
        @php echo back_button('allworkflow.list') @endphp
    @endsection
    @section('extra_buttons')
        <button type="button" onclick="toggle_model('add-sidebar')"
            class="inline-flex hover-scale gap-1 border-l-[1px] items-center px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
            <i class=" ri-add-line text-blue-500 font-semibold"></i>
            {{ __('common.add') }}
        </button>
        @php echo filter_button(); @endphp

        <button type="button" onclick="$.validate_hierarchy()"
            class="open-drawer-btn  inline-flex hover-scale gap-2 border-l-[1px] items-center px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
            <i class="ri-pass-valid-fill"></i>
            {{ __('common.validate') }}
        </button>
        <button type="button" onclick="$.view_hierarchy()"
            class="inline-flex hover-scale gap-2 border-l-[1px] items-center px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
            <i class="ri-node-tree"></i>
            {{ __('common.view-hierarchy') }}
        </button>
        {{-- <button onclick="togglview_conditional_model('.$encr_id.')" class="block w-full  py-2 hover:bg-gray-100">  <i class="ri-contract-left-right-line"></i> Condition </button> --}}
        
    @endsection

</div>
@include('pages.common.custom_filter_html')

<div class="text-xs w-full border bg-white rounded-md">
    <div class="w-full table_wrapper p-2">
        <table id="data-table-list" class="display w-full min-w-full border-collapse">
                <thead class="bg-primary text-white text-xs">
                    <tr>
                        <th>{{ __('company.workflow-code-name') }}</th>
                        <th>{{ __('company.step-name') }}</th>
                        <th>{{ __('company.condition') }}</th>
                        <th>{{ __('common.next') }}</th>
                        <th>{{ __('common.created_by') }}</th>
                        <th>{{ __('common.created_at') }}</th>
                        <th>{{ __('company.status') }}</th>
                        <th>{{ __('common.action') }}</th>

                    </tr>
                </thead>
                <tbody class="text-xs">

                </tbody>

            </table>
        </div>
   

    <!--  New Modal -->
   

</div>
@include('workflow::workflow_master.workflowSteps.modal')
{{-- </div> --}}
@endsection
@section('scripts')
@include('layouts.sidebar_modal_js') 
@include('pages.common.custom_filter_js')
@include('layouts.datatable_js')
@include('layouts.modal_js')
@include('workflow::workflow_master.workflowSteps.table_js')

<script>
    var field = "<select class='form-input' style='width:100%' name='field[]' required></select>";
 
    function view_model(modal,w_id) {
        toggle_model(modal);
        if (w_id) {
            document.getElementById("edit_system_check").checked = false;
            document.getElementById("edit_email_check").checked = false;
            $('#edit_email_template_html').html('');
            $('#email_template_html').html('');

            var url = "{{ route('workflow-steps.show', [':w_id']) }}";
            url = url.replace(':w_id', w_id);
            showLoader();
            $.ajax({
                     url: url,
                     method: 'post',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                     success: function(data) {
                        hideLoader(); 
                        var step_name = data.step_name;
                        var step_condition = data.condition;
                        var no_of_approver=data.no_of_approver;
                        if(no_of_approver<1){
                            no_of_approver = '';
                        }
                        var status=data.status;
                        $('#step_id').val(w_id);
                        $('#step_name').val(step_name);
                        $('#step_condition').val(step_condition);
                        if(data.system_notification == "Yes"){
                            document.getElementById("edit_system_check").checked = true;
                        }
                        if(data.email_notification == "Yes"){
                            document.getElementById("edit_email_check").checked = true;
                            $.email_notification(data.email_template_id,'edit_email_check');
                        }
            
                        if(step_condition=='Employee' || step_condition == 'Conditional'){
                            $('#edit_approvalType').html('<div class="w-full"><label class="text-sm">No of approver <span class="text-danger">*</span></label><div class="filled w-100 d-flex flex-column"><input type="text" name="no_of_approver" value="'+no_of_approver+'" id="no_of_approver" onkeypress="return $.numeric();" maxlength="2" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5"></div>');
                        }
                        else{
                            $('#edit_approvalType').html('');
                        }

                        if (status == "Active")
                            $('#step_status').html(
                                '<option value="">Select Status</option> <option value="Active" selected>Active</option> <option value="Inactive">Inactive</option>'
                            );
                        else if (status == "Inactive")
                            $('#step_status').html(
                                '<option value="">Select Status</option> <option value="Active">Active</option> <option value="Inactive" selected>Inactive</option>'
                            );
                        else
                            $('#step_status').html(
                                '<option value="">Select Status</option> <option value="Active">Active</option> <option value="Inactive">Inactive</option>'
                            );
                    }

                 });
        }


    }

    function togglview_conditional_model(w_id = null) {             
        if (w_id) { 
            openNewModal('view-conditional');        
            $('#add_new_line').html('');
            $('#wf_step_id').val(w_id);
            var url = "{{ route('workflow-steps.show', [':w_id']) }}";
            url = url.replace(':w_id', w_id);
            showLoader();
            $.ajax({
                url: url,
                method: 'post',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    hideLoader();
                    var type = data.setup.apply_on.workflow_name;                    
                    field =
                        "<select class='form-input' name='field[]' style='width:100%' required></select>";
                    if (type == "Purchase Request") {
                        field =
                            "<select class='form-input' name='field[]' style='width:100%' required><option value='Department'>Department</option><option value='Amount'>Amount</option><option value='Project'>Project</option><option value='Budget Amount'>Budget Amount</option><option value='Is Emergency PR'>Is Emergency PR</option></select>";
                    } else if (type == "Quotation") {
                        field =
                            "<select class='form-input' name='field[]' style='width:100%' required><option value='Department'>Department</option><option value='Amount'>Amount</option></select>";
                    } else if (type == "Purchase Order") {
                        field =
                            "<select class='form-input' name='field[]' style='width:100%' required><option value='Department'>Department</option><option value='Amount'>Amount</option><option value='Project'>Project</option><option value='Budget Amount'>Budget Amount</option></select>";
                    } else if (type == "GRN") {
                        field =
                            "<select class='form-input' name='field[]' style='width:100%' required><option value='Amount'>Amount</option></select>";
                    } else if (type == "Invoice") {
                        field =
                            "<select class='form-input' name='field[]' style='width:100%' required><option value='Amount'>Amount</option></select>";
                    }  
                    if (data.conditions.length > 0) {
                        $.each(data.conditions, function(index, cdns) {
                            $('#add_new_line').append(
                                '<tr class="text-center"><td><select class="form-input" style="width:100%" name="field[]" required><option value="' +
                                cdns.field + '">' + cdns.field +
                                '</option></select></td><td><select name="condition[]" class="form-input" style="width:100%" required><option value="' +
                                cdns.condition + '">' + cdns.condition +
                                '</option></select></td><td><input type="text" name="condition_value[]" value="' +
                                cdns.condition_value +
                                '" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5" required></td> <td class="text-center"><button type="button" class="delete-line" ><i class="ri-delete-bin-6-line text-xl text-red-500"></i></button></td></tr>'
                            );
                        });
                    }
                }
            });
        }



    }



    function togglview_nextmodel(w_id = null) {
       toggle_model('view-next');  
       

        if (w_id) {
            $('#next_step_id').html('');
            $('#wf_next_step_id').val(w_id);
            var url = "{{ route('workflow-steps-get-next', [':w_id']) }}";
            url = url.replace(':w_id', w_id);
            showLoader();
            $.ajax({
                url: url,
                method: 'post',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    hideLoader();
                    $('#next_step_id').html(data[0]);
                    $('#next_w_name').val(data[1]);
                }
            });
        }



    }
</script>
@endsection
