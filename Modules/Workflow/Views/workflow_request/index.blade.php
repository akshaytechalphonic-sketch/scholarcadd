@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
@endsection
@section('content')
    <div>
        
    @php echo breadcrums('company.all-workflow',['common.home','company.all-workflow'],['dashboard']) @endphp 
    @section('back_button') @php echo back_button('dashboard') @endphp @endsection        
    @section('extra_buttons')
 
    @php echo filter_button(); @endphp
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
                <tbody class="text-xs"> </tbody>
            </table>
        </div>
    </div> 
 
@include('workflow::workflow_request.wf_action_model')

@endsection
@section('scripts')
    @include('workflow::workflow_request.wf_action_js')
    @include('layouts.sidebar_modal_js') 
    @include('pages.common.custom_filter_js')
    @include('layouts.datatable_js')   
    @include('workflow::workflow_request.extra_js')


@endsection