@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
@endsection
@section('content')
    <div>

    @php echo breadcrums('common.tax-type-list',['common.home','common.tax-type-list'],['dashboard']) @endphp
    @section('back_button') @php echo back_button('dashboard') @endphp @endsection
    @section('extra_buttons')

    <button type="button" onclick="toggle_model('add-sidebar')"
        class="topbarbtn">
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
                        <th>{{__('common.name')}}</th>
                        <th>{{__('common.code')}}</th>
                        <th>{{__('common.description')}}</th>
                        <th>{{__('common.is-active')}} </th>
                        <th>{{ __('common.action') }}</th>
                    </tr>
                </thead>
                <tbody class="text-xs">

                </tbody>

            </table>
        </div>
    </div>

@include('dynamicmaster::taxtype.modal')

@endsection
@section('scripts')
    @include('layouts.sidebar_modal_js')
    @include('pages.common.custom_filter_js')
    @include('layouts.datatable_js')
    @include('dynamicmaster::taxtype.extra_js')


@endsection
