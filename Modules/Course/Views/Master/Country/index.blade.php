@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('Course.Course Country'), [__('common.home'), __('Course.Course Country')], ['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
@section('extra_buttons')
    <form method="POST"
          action="{{ route('countries.update.currency') }}"
          class="inline-block">
        @csrf

        <button type="submit"
                class="topbarbtn cursor-pointer">
            <i class="ri-refresh-line mr-1"></i>
            Update Currency Rates
        </button>
    </form>
@endsection
</div>
{{-- @include('pages.common.custom_filter_html') --}}
<section>
    <div class="overflow-x-auto bg-white rounded-md border p-4">
        <table id="data-table-list" class="display table-auto w-full py-1 text-xs">
            <thead class="bg-primary text-white ">
                <tr>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Sl.No.') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Name') }}</th>
                     <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.URL') }}</th>
                     <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.ISO2') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Capital') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Continent Name') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Course Price') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Region') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.T-Id') }}</th>

                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Phone Code') }}</th>
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
@include('Course::Master.Country.extra_js')
@include('Course::Master.Country.table_js')
@endsection
