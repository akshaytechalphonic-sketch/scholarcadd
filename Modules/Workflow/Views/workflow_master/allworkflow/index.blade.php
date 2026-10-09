@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
@endsection
@section('content')
    <div>
        
    @php echo breadcrums('company.all-workflow',['common.home','company.all-workflow'],['dashboard']) @endphp 
    @section('back_button') @php echo back_button('dashboard') @endphp @endsection        
    @section('extra_buttons')

    <button type="button" onclick="toggle_model('add-sidebar')"
        class="inline-flex hover-scale gap-1 border-l-[1px] items-center px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
        <i class=" ri-add-line text-blue-500 font-semibold"></i>
        {{ __('common.add') }}
    </button>
  
    @php echo filter_button(); @endphp
    @endsection
 
    </div>
@include('pages.common.custom_filter_html')

<div class="text-xs w-full border bg-white rounded-md">
    <div class="table_wrapper">
        <table id="data-table-list" class="display w-full min-w-full border-collapse">
                <thead class="bg-primary text-white text-xs">
                    <tr>
                        <th>{{ __('company.workflow-code') }}</th>
                        <th>{{ __('common.apply-on') }}</th>
                        <th>{{ __('company.workflow-name') }}</th>
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
    </div> 
 
@include('workflow::workflow_master.allworkflow.modal')

@endsection
@section('scripts')
    @include('layouts.sidebar_modal_js') 
    @include('pages.common.custom_filter_js')
    @include('layouts.datatable_js')   
    @include('workflow::workflow_master.allworkflow.extra_js')


@endsection