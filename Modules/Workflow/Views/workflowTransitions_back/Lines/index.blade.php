@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
@endsection
@section('content')
    <div>

        @php echo breadcrums('company.workflow-transitions',['common.home','company.workflow-request','company.workflow-transitions'],['dashboard','workflow-all-transition-list']) @endphp
    @section('back_button')
        @php echo back_button('workflow-all-transition-list') @endphp
    @endsection

</div>

<div class="mt-3">
    <div class="w-full mb-3">
        <div class="p-3 bg-white dark:bg-black dark:text-white  rounded-md  ">
            <div class="flex justify-between md:flex-row flex-col w-full gap-5">
                <input id="document_id" value="{{ base64_encode(convert_uuencode($head_data?->document_id)) }}"
                    type="hidden" class="form-control">
                <input id="doc_type" value="{{ $head_data?->mapName?->workflow_name }}" type="hidden"
                    class="form-control">

                <div class="w-full">
                    <label for="" class="text-sm  font-semibold">{{ __('company.ref-code') }}</label>
                    <input value="{{ $head_data->ref_code }}"
                        class="form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md  block w-full  p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        disabled></input>
                </div>

                <div class="w-full">
                    <label for="" class="text-sm font-semibold">{{ __('company.type') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <input value="{{ $head_data?->mapName?->workflow_name }}"
                        class="form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md  block w-full  p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        disabled></input>
                </div>

                <div class="w-full">
                    <label for="" class="text-sm font-semibold">{{ __('company.ref-id') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <input value="{{ $head_data->document_code }}"
                        class="form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md  block w-full  p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        disabled></input>
                </div>

                <div class="w-full">
                    <label for="" class="text-sm font-semibold">{{ __('company.workflow') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <input value="{{ $head_data?->workflowName?->workflow_name }}"
                        class="form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md  block w-full  p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        disabled></input>
                </div>


            </div>
            <div class="flex  justify-between md:flex-row flex-col w-full gap-5">

                <div class="w-full">
                    <label for="" class="text-sm font-semibold">{{ __('company.requester') }}<span
                            class="text-red-500 font-bold">*</span></label>
                    <input
                        value="@php echo (App\Helpers\WorkflowHelper::doument_requester_byrequester_id($head_data?->requester_id)) @endphp"
                        class="form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md  block w-full  p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        disabled></input>
                </div>

                <div class="w-full">
                    <label for="" class="text-sm font-semibold">{{ __('company.date') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <input value="{{ $head_data?->date }}"
                        class="form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md  block w-full  p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        disabled></input>
                </div>

                <div class="w-full">
                    <label for="" class="text-sm font-semibold">{{ __('company.status') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <input value="{{ $head_data->status }}"
                        class="form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md  block w-full  p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        disabled></input>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold">{{ __('company.company') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <input value="{{ $head_data->company?->name }}"
                        class="form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md  block w-full  p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        disabled></input>
                </div>

            </div>

        </div>
    </div>
</div>

 

<div class="text-xs w-full  bg-white rounded-md mt-4">
    <div class="  p-2 ">

        <div class="overflow-x-auto p-2">
            <table id="wf-list" class="display table-auto w-full py-1">
                <thead class="bg-primary text-white text-xs">
                    <tr class="text-xs">
                        <th class="p-2">{{ __('company.ref-code') }} </th>
                        <th class="p-2">{{ __('company.type') }} </th>
                        <th class="p-2">{{ __('company.ref-id') }} </th>
                        <th class="p-2">{{ __('company.workflow') }}</th>
                        <th class="p-2">{{ __('company.requested-to') }}</th>
                        <th class="p-2">{{ __('company.date') }}</th>
                        <th class="p-2">{{ __('common.status') }} </th>
                        <th class="p-2">{{ __('common.action') }} </th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @foreach ($data as $alll_data)
                        <tr class="text-center">
                            <td>{{ $alll_data->ref_code }} </td>
                            <td>{{ $alll_data?->mapName?->workflow_name }}</td>
                            <td>{{ $alll_data?->document_code }}</td>
                            <td>{{ $alll_data?->workflowName?->workflow_name }}</td>
                            <td>{{ $alll_data?->emp_name?->name }}</td>
                            <td>@php echo (App\Helpers\CommonHelper::date_formate($alll_data?->assign_date)) @endphp</td>
                            <td>@php echo (App\Helpers\CommonHelper::status_check($alll_data?->status)) @endphp</td>
                            <td>
                                @canany(['Re assignment'])
                                @if ($alll_data?->status == 'Pending')
                                    <button type="button"
                                        class="inline-flex font-semibold gap-1 hover-scale items-center px-4 py-2 text-sm text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2"
                                        onclick="togglview_modelSidebar('{{ base64_encode(convert_uuencode($alll_data->id)) }}')">
                                        {{ __('company.reassign') }} </button>
                                @endif
                                @endcanany
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
</div>

@if($all_event && count($all_event)>0)
    <div class="w-full mt-3">
        <div class="p-5 bg-white dark:bg-black dark:text-white  rounded-md  ">
            <ol class="relative text-gray-500 border-s border-gray-200 dark:border-gray-700 dark:text-gray-400">
                @foreach($all_event as $event)
                    <li class="mb-10 ms-6">
                        <span class="absolute flex items-center justify-center w-8 h-8 bg-green-200 rounded-full -start-4 ring-4 ring-white dark:ring-gray-900 dark:bg-green-900"> </span>
                    <div class="flex gap-3 item-center ">
                        <div> <h3 class="font-medium leading-tight">{{$event?->creator?->name}}</h3> </div>
                        <div> <small> @php echo (App\Helpers\CommonHelper::date_time_formate($event?->created_at)) @endphp</small> </div>
                    </div> 
                        <p class="text-sm"> {!!$event?->statement!!} </p>
                    </li>
                @endforeach                
            </ol>
        </div>
    </div>
@endif

@include('pages.workflowTransitions.Lines.modal')

@endsection
@section('scripts')
@include('pages.workflowTransitions.Lines.extrajs')
{{-- @include('layouts.drawerjs')      --}}
@endsection
