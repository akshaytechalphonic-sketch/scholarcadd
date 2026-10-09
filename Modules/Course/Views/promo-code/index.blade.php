@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('common.promo-code',['common.home','common.promo-code'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
     @section('extra_buttons')
        <a title="{{ __('Course.Add') }}" href="{{route('promo-code-create')}}" class="topbarbtn">
            <i class="ri-add-line"></i>
            {{ __('Course.Add') }}
        </a>
        {{--  @php echo filter_button(); @endphp  --}}
    @endsection
</div>
@include('pages.common.custom_filter_html')
<div class="text-xs w-full border bg-white rounded-md">
    <div class="w-full table_wrapper p-2">
        <table id="data-table-list" class="display w-full min-w-full border-collapse">
            <thead class="bg-primary text-white text-xs">
                <tr>
                    <th>{{ __('Course.Code') }}</th>
                    <th>{{ __('Course.course-name') }}</th>
                    <th>{{ __('Course.Publish Type') }}</th>
                    <th>{{ __('Course.discount-percentage') }}</th>
                    <th>{{ __('Course.Status') }}</th>
                    <th>{{ __('Course.Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
@include('pages.common.custom_filter_js')
@include('layouts.datatable_js')
@include('layouts.sidebar_modal_js')
@include('Course::promo-code.table_js')
@endsection
