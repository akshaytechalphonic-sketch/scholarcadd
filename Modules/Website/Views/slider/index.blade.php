@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('website.Slider List'), [__('common.home'), __('website.Slider List')], ['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')
        <a title="{{ __('website.Add') }}" href="{{route('slider-create')}}" class="topbar_btn">
            <i class="ri-add-line"></i>
            {{ __('website.Add') }}
        </a>
        @php echo filter_button(); @endphp
    @endsection
</div>
@include('pages.common.custom_filter_html')
<section>
    <div class="overflow-x-auto bg-white rounded-md border p-4">
        <table id="data-table-list" class="display table-auto w-full py-1 text-xs">
            <thead class="bg-primary text-white ">
                <tr>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('website.Seq No') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('website.Heading') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('website.main Content') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('website.Created At') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('website.Updated At') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('website.Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            </tbody>
        </table>
    </div>
</section>
@endsection
@section('scripts')
@include('pages.common.custom_filter_js')
@include('layouts.datatable_js')
@include('layouts.sweetalert_js')
@include('website::slider.table_js')
@endsection
