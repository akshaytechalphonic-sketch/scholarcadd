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

@php $name_array = []; $show_name_array = [];@endphp
<input type="hidden" id="mod" value="{{ $mod }}">
<div class="text-xs w-full border bg-white rounded-md">
    <div class="w-full table_wrapper p-2">
        <table id="data-table-list" class="display w-full min-w-full border-collapse">
                <thead class="bg-primary text-white text-xs">
                    <tr>
                        <th>{{ __('common.names') }}</th>
                        <th>{{ __('common.document-type') }}</th>
                        <th>@if($mod == 'Ranking') {{ __('common.range')}} @else {{ __('common.description') }} @endif</th>
                        <th>{{ __('common.created_by') }}</th>
                        <th>{{ __('common.created_at') }}</th>
                        <th>{{ __('common.action') }} </th> 
                    </tr>
                </thead>
                <tbody class="text-xs"> </tbody>  
            </table>
        </div>
    </div> 

 
@include('pages.settings.documents.modal')
@endsection

@section('scripts')
@include('layouts.select2js')
@include('layouts.image_validation_js')
@include('pages.common.custom_filter_js')
@include('layouts.datatable_js')
@include('pages.settings.documents.extra_js')
@endsection
