@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('Course.Course Trainer List'), [__('common.home'), __('Course.Course Trainer List')], ['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')
        <a title="{{ __('Course.Add') }}" href="{{ route('course-trainer-create') }}" class="topbarbtn">
            <i class="ri-add-line"></i>
            {{ __('Course.Add') }}
        </a>
    @endsection
</div>

<section>
    <div class="overflow-x-auto bg-white rounded-md border p-4">
        <table id="data-table-list" class="display table-auto w-full py-1 text-xs">
            <thead class="bg-primary text-white ">
                <tr>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Course Name') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Country Name') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.ISO2') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Expirance') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('common.Name') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('common.created_at') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('common.created_by') }}</th>
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
{{-- @include('Course::CourseYouTube.extra_js') --}}
@include('Course::coursetrainer.table')
@endsection
