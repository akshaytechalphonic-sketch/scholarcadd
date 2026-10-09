@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('Course.testimonial-list'), [__('common.home'), __('Course.testimonial-list')], ['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')
        <a title="{{ __('common.Add') }}" href="{{ route('testimonial-create') }}" class="topbarbtn">
            <i class="ri-add-line"></i>
            {{ __('common.Add') }}
        </a>
        {{-- @php echo filter_button(); @endphp --}}
    @endsection
</div>
{{-- @include('pages.common.custom_filter_html') --}}

<section>
    <div class="overflow-x-auto bg-white rounded-md border p-4">
        <table id="data-table-list" class="display table-auto w-full py-1 text-xs">
            <thead class="bg-primary text-white ">
                <tr>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Sl.No.') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Course Name') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Course-Country') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.CountryIs02') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('common.name') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('common.comment') }}</th>
                    {{-- <th class="px-4 py-3 whitespace-nowrap">{{ __('common.created-by') }}</th> --}}
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('common.status') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Actions') }}</th>
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
@include('website::testimonial.extra_js')
@include('website::testimonial.table_js')
@endsection
