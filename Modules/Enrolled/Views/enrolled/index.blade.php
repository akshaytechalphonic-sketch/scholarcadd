@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('admin.Enrolled Request List'), [__('common.home'), __('Course.About Geography List')], ['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')
        <a title="{{ __('Course.Add') }}" href="{{route('course-will-learn-create')}}" class="topbarbtn">
            <i class="ri-add-line"></i>
            {{ __('Course.Add') }}
        </a>
        {{-- @php echo filter_button(); @endphp --}}
    @endsection
</div>
@include('pages.common.custom_filter_html')
<section>
    <div class="overflow-x-auto bg-white rounded-md border p-4">
        <table id="data-table-list" class="display table-auto w-full py-1 text-xs">
            <thead class="bg-primary text-white ">
                <tr>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('admin.requestType') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('common.name') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('common.email') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('common.phone-number') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('common.created_at') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('common.created_at') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Course Name') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Country Name') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('admin.mode') }}</th>
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
@include('enrolled::enrolled-request-corporate.table_js')
@endsection
