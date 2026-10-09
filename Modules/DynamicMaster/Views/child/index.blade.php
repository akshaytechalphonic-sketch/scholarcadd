@extends('layouts.app')
@section('page_title')  {{__('common.'.$type.'-list')}} @endsection
@section('css')
    @include('layouts.datatable_css')
@endsection
@section('content')

    @php echo breadcrums('common.'.$type.'-list',['common.home','common.'.$type.'-list'],['dashboard']) @endphp
@section('back_button')
    @php echo back_button('dashboard') @endphp

    <button type="button" onclick="toggle_model('add-sidebar')"
        class="topbarbtn">
        <i class=" ri-add-line text-blue-500 font-semibold"></i>
        {{ __('common.add') }}
    </button>
    @php echo filter_button(); @endphp
@endsection

@include('pages.common.custom_filter_html')
 
<input type="hidden" id="type" value="{{ $type }}">
<div class="text-xs w-full border bg-white rounded-md">
    <div class="w-full table_wrapper p-2">
        <table id="data-table-list" class="display w-full min-w-full border-collapse">
                <thead class="bg-primary text-white text-xs">
                    <tr>
                        <th>{{ __('common.names') }}</th>
                        <th>{{ __('common.'.$parent.'') }}</th>
                        <th>{{ __('common.description') }}</th>                          
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

@include('dynamicmaster::child.model') 
@endsection

@section('scripts')
@include('pages.common.custom_filter_js')

@include('layouts.sidebar_modal_js')


@include('layouts.datatable_js')

@include('dynamicmaster::child.extra_js')

@endsection
