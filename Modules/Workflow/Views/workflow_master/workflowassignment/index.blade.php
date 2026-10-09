@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
@endsection
@section('content')
    <div>
         
        @php echo breadcrums('company.workflow-assignment',['common.home','company.all-workflow','company.workflow-steps','company.workflow-assignment'],['dashboard','allworkflow.list',['allworkflowSteps.list',$setup_id]]) @endphp 
        @section('back_button') @php echo back_button('allworkflowSteps.list',['id'],[$setup_id]) @endphp @endsection        
        @section('extra_buttons') 
        @if ($count < 1 || $step_data->condition == 'Employee' || $step_data->condition == 'Conditional')
        <button type="button" onclick="toggle_model('add-sidebar')"
            class="inline-flex hover-scale gap-1 border-l-[1px] items-center px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
            <i class=" ri-add-line text-blue-500 font-semibold"></i>
            {{ __('common.add') }}
        </button>
        @endif
      
    @php echo filter_button(); @endphp
    @endsection
 
    </div>
@include('pages.common.custom_filter_html')

<div class="text-xs w-full border bg-white rounded-md">
    <div class="table_wrapper">
        <table id="data-table-list" class="display w-full min-w-full border-collapse">
                <thead class="bg-primary text-white text-xs">
                    <tr> 
                        <th>{{ __('company.step-name') }}</th>
                        <th>{{ __('company.condition') }}</th>
                        <th>{{ __('company.assignedTo') }}</th> 
                        <th>{{ __('common.created_by') }}</th>
                        <th>{{ __('common.created_at') }}</th>
                        <th>{{ __('common.action') }}</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                  
                </tbody>
            </table>
        </div>
    </div> 

  

@include('workflow::workflow_master.workflowassignment.modal')

 
@endsection
@section('scripts')
    @include('layouts.sidebar_modal_js') 
    @include('pages.common.custom_filter_js')
    @include('layouts.datatable_js')   
    @include('workflow::workflow_master.workflowassignment.table_js')
    
<script>
   
    function view_model(modal,assign_id) {
        toggle_model(modal);
        if(assign_id){                         
            var url = "{{ route('workflow-assignment.show',[':assign_id']) }}";
            url = url.replace(':assign_id',assign_id);

            $("#edit_linemanager_cndn").css("display","none");
            $("#edit_emp_cndn").css("display","none");
            $("#edit_dept_cndn").css("display","none");
            showLoader();
            $.ajax({
                url: url,
                method: 'get',
                dataType: 'json',
                success: function(data) {  
                    hideLoader();              
                var asgn_id=assign_id;
                var level_val=data.level;
                var dept_val=data.dept_id;
                var user_val=data.employee_id;                    
                $('#asgn_id').val(asgn_id);
                $("#condition_user option[value='" + user_val + "']").attr('selected', 'selected');
                $("#condition_dept option[value='" + dept_val + "']").attr('selected', 'selected');
                $("#condition_LM option[value='" + level_val + "']").attr('selected', 'selected');

                if(level_val){
                    $("#condition_user option[value='']").attr('selected', 'selected');
                    $("#condition_dept option[value='']").attr('selected', 'selected');                    
                    $("#edit_linemanager_cndn").css("display","block");
                }
                else if(dept_val){
                    $("#condition_user option[value='']").attr('selected', 'selected');                    
                    $("#condition_LM option[value='']").attr('selected', 'selected');
                    $("#edit_dept_cndn").css("display","block");
                }
                else if(user_val){
                    $("#condition_dept option[value='']").attr('selected', 'selected');
                    $("#condition_LM option[value='']").attr('selected', 'selected');
                    $("#edit_emp_cndn").css("display","block");
                }
 
                }
            });              
          
        }

                           
    }
  
     
</script>


@endsection