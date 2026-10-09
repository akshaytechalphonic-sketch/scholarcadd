@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
@endsection
@section('content')
    <div>
        
    @php echo breadcrums('company.workflow-request',['common.home','company.workflow-request'],['dashboard']) @endphp 
    @section('back_button') @php echo back_button('dashboard') @endphp @endsection        
    @section('extra_buttons')     
    @php echo App\Helpers\CommonHelper::filter_button(); @endphp
    @endsection
  
    </div>
@include('pages.common.custom_filter_html')
<div class="text-xs w-full border bg-white rounded-md">
    <div class="table_wrapper">
        <table id="data-table-list" class="display w-full min-w-full border-collapse">
                <thead class="bg-primary text-white text-xs">
                    <tr>
                        <th>{{ __('company.ref-code') }} </th>
                        <th>{{ __('company.type') }} </th>
                        <th>{{ __('company.ref-id') }} </th>
                        <th>{{ __('company.workflow') }}</th>
                        <th>{{ __('company.requester') }}</th>
                        <th>{{ __('company.date') }}</th>
                        <th>{{ __('common.status') }} </th>
                        <th>{{ __('common.action') }} </th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                  
                </tbody>

            </table>
        </div>
    </div> 

  

{{-- @include('pages.workflowTransitions.modal')
</div> --}}
@endsection
@section('scripts')
    @include('layouts.drawerjs')
    @include('pages.common.custom_filter_js')
    @include('layouts.datatable_js')   
    @include('pages.workflowTransitions.table_js')    
<script>
  
    function togglview_modelSidebar(w_id) {
        const sidebar = document.getElementById('view-sidebar');
        sidebar.classList.toggle('translate-x-full');   
        if(w_id){                 
            var url = "{{ route('workflow-setup.show',[':w_id']) }}";
            url = url.replace(':w_id',w_id);
            showLoader();
            $.ajax({
                url: url,
                method: 'get',
                dataType: 'json',
                success: function(data) {  
                hideLoader();
                var wf_id = data.id;
                var workflow_code = data.workflow_code;
                var wName = data.workflow_name;
                var status=data.status;
                    
                $('#wf_id').val(wf_id);
                $('#w_code').val(workflow_code);
                $('#w_name').val(wName); 
                $('#w_apply_on').val(data.apply_on.workflow_name);

                if(status == "Active")
                    $('#w_status').html('<option value="">Select Status</option> <option value="Active" selected>Active</option> <option value="Inactive">In Active</option>');
                else if(status == "Inactive")
                    $('#w_status').html('<option value="">Select Status</option> <option value="Active">Active</option> <option value="Inactive" selected>In Active</option>');
                else
                    $('#w_status').html('<option value="">Select Status</option> <option value="Active">Active</option> <option value="Inactive">In Active</option>');
                }
            });  
        }                
    }
 
</script>


@endsection