@extends('layouts.app')
@section('css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('company.workflow-transitions-lines',['common.home','company.workflow-transitions','company.workflow-transitions-lines'],['dashboard','workflow-all-transition-list']) @endphp
    @section('back_button')
        @php echo back_button('workflow-all-transition-list') @endphp
    @endsection
    @section('extra_buttons')
    @endsection
    <div class="w-full mb-3">
        <div>
            <div class="p-3 bg-white  rounded-md border">
 
            <input id="document_id" value="{{ $head_data?->document_id }}" type="hidden" class="form-control">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-900 py-4">
                    <div class="w-full">
                        <label class="form-label">{{ __('common.Ref Code') }}</label>
                        <input type="text" value="{{ $head_data->ref_code }}" class="form-input" readonly>
                    </div>
                    <div class="w-full">
                        <label class="form-label">{{ __('common.type') }}</label>
                        <input type="text" value="{{ $head_data?->mapName?->workflow_name }}" class="form-input" readonly>
                    </div>
                    <div class="w-full">
                        <label class="form-label">{{ __('company.ref-id') }}</label>
                        <input type="text" value="{{ $head_data->document_code }}" class="form-input" readonly>
                    </div>
                    <div class="w-full">
                        <label class="form-label">{{ __('common.Workflow') }}</label>
                        <input type="text" value="{{ $head_data?->workflowName?->workflow_name }}" class="form-input" readonly>
                    </div>
                    <div class="w-full">
                        <label class="form-label">{{ __('company.requester') }}</label>
                        <input type="text" value="@php echo (App\Helpers\WorkflowHelper::doument_requester_byrequester_id($head_data?->requester_id)) @endphp" class="form-input"
                            readonly>
                    </div>
                    <div class="w-full">
                        <label class="form-label">{{ __('common.date') }}</label>
                        <input type="text" value="{{ $head_data?->date }}" class="form-input" readonly>
                    </div>
                    <div class="w-full">
                        <label class="form-label">{{ __('common.status') }}</label>
                        <input type="text" value="{{ $head_data->status }}" class="form-input" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-xs w-full border bg-white rounded-md mt-4">
            <div class="table_wrapper">
                <table id="data-table-list" class="display w-full min-w-full border-collapse">
                    <thead class="bg-primary text-white text-xs">
                        <tr class="">
                            <th>{{ __('common.Ref Code') }}</th>
                            <th>{{ __('common.type') }}</th>
                            <th>{{ __('company.ref-id') }} </th>
                            <th>{{ __('common.Workflow') }} </th>
                            <th>{{ __('common.Requested To') }}</th>
                            <th>{{ __('common.date') }}</th>
                            <th>{{ __('common.status') }}</th>
                            <th>{{ __('common.action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                        @foreach ($data as $alll_data)
                            <tr>
                                <td class="text-center">{{ $alll_data->ref_code }} </td>
                                <td class="text-center">{{ $alll_data?->mapName?->workflow_name }}</td>
                                <td class="text-center">{{ $alll_data?->document_code }}</td>
                                <td class="text-center">{{ $alll_data?->workflowName?->workflow_name }}</td>
                                <td class="text-center">{{ $alll_data?->emp_name?->name }}</td>
                                <td class="text-center">@php echo date_formate($alll_data?->assign_date) @endphp</td>
                                <td class="text-center">@php echo status_check($alll_data?->status) @endphp</td>
                                <td class="text-center">
                                    {{--@canany(['Re assignment'])  --}} 
                                    @if ($alll_data?->status == 'Pending') 

                                    <div class="dropdown relative inline-block"> <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">  <i class="ri-more-2-fill"></i>  </button>
                                    <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]"> <ul class="text-sm text-black">
                                    <li> <button onclick="view_model_reassign('{{encrypt($alll_data->id)}}')" class="block px-4 py-2 hover:bg-gray-100"> <i class=" ri-eye-line"></i> {{ __('company.reassign') }}</button> </li>
                                    </ul> </div> </div>
                                    

                                    @endif 
                                    {{--@endcanany --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('workflow::workflowTransitions.Lines.model')

@endsection
@section('scripts')
    @include('layouts.sidebar_modal_js') 
    @include('pages.common.custom_filter_js')
    @include('layouts.datatable_js')   
    @include('workflow::workflowTransitions.Lines.extra_js')
@endsection