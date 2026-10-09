@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('Course.Course List'), [__('common.home'), __('Course.Course List')], ['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')
        <a title="{{ __('Course.Add') }}" href="{{ route('course-create') }}" class="topbarbtn">
            <i class="ri-add-line"></i>
            {{ __('Course.Add') }}
        </a>
        {{-- @php echo filter_button(); @endphp --}}
    @endsection
</div>
{{-- @include('pages.common.custom_filter_html') --}}
{{-- <section class="toggle-status-section grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-4 gap-2 mb-4">
    <!-- All Records -->
    <div class="flex items-center justify-between bg-white border rounded-md p-4 hover:shadow transition status-filter"
        data-status="">
        <div class="flex items-center gap-2">
            <i class="ri-database-2-line text-purple-500 text-xl"></i>
            <h3 class="text-sm font-semibold text-gray-700">{{ __('common.All Records') }}</h3>
        </div>
        <div class="bg-purple-100 text-purple-800 text-sm font-bold px-4 py-2 rounded-md">
            <span id="total"></span>
        </div>
    </div>
    <!-- Created By Me -->
    <div class="flex items-center justify-between bg-white border rounded-md p-4 hover:shadow transition status-filter"
        data-status="created_by_me">
        <div class="flex items-center gap-2">
            <i class="ri-user-3-line text-indigo-500 text-xl"></i>
            <h3 class="text-sm font-semibold text-gray-700">{{ __('common.Created By Me') }}</h3>
        </div>
        <div class="bg-indigo-100 text-indigo-800 text-sm font-bold px-4 py-2 rounded-md">
            <span id="created_by_me_count"></span>
        </div>
    </div>
    <!-- Draft -->
    <div class="flex items-center justify-between bg-white border rounded-md p-4 hover:shadow transition status-filter"
        data-status="Draft">
        <div class="flex items-center gap-2">
            <i class="ri-edit-2-line text-blue-500 text-xl"></i>
            <h3 class="text-sm font-semibold text-gray-700">{{ __('common.Draft') }}</h3>
        </div>
        <div class="bg-blue-100 text-blue-800 text-sm font-bold px-4 py-2 rounded-md">
            <span id="draft_count"></span>
        </div>
    </div>
    <!-- In Review -->
    <div class="flex items-center justify-between bg-white border rounded-md p-4 hover:shadow transition status-filter"
        data-status="In review">
        <div class="flex items-center gap-2">
            <i class="ri-file-search-line text-cyan-500 text-xl"></i>
            <h3 class="text-sm font-semibold text-gray-700">{{ __('common.In Review') }}</h3>
        </div>
        <div class="bg-cyan-100 text-cyan-800 text-sm font-bold px-4 py-2 rounded-md">
            <span id="inreview_count"></span>
        </div>
    </div>
    <!-- Approved -->
    <div class="flex items-center justify-between bg-white border rounded-md p-4 hover:shadow transition status-filter"
        data-status="Approved">
        <div class="flex items-center gap-2">
            <i class="ri-checkbox-circle-line text-green-500 text-xl"></i>
            <h3 class="text-sm font-semibold text-gray-700">{{ __('common.Approved') }}</h3>
        </div>
        <div class="bg-green-100 text-green-800 text-sm font-bold px-4 py-2 rounded-md">
            <span id="approved_count"></span>
        </div>
    </div>
    <!-- Rejected -->
    <div class="flex items-center justify-between bg-white border rounded-md p-4 hover:shadow transition status-filter"
        data-status="Rejected">
        <div class="flex items-center gap-2">
            <i class="ri-close-circle-line text-red-500 text-xl"></i>
            <h3 class="text-sm font-semibold text-gray-700">{{ __('common.Rejected') }}</h3>
        </div>
        <div class="bg-red-100 text-red-800 text-sm font-bold px-4 py-2 rounded-md">
            <span id="rejected_count"></span>
        </div>
    </div>

</section> --}}
<section>
    <div class="overflow-x-auto bg-white rounded-md border p-4">
        <table id="data-table-list" class="display table-auto w-full py-1 text-xs">
            <thead class="bg-primary text-white ">
                <tr>
                    {{-- <th class="px-4 py-3 rounded-l-md whitespace-nowrap">{{ __('Course.Seq.No.') }}</th> --}}
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Course Name(Short Form)') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Short Description') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Category') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Created At') }}</th>
                    {{-- <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Course Title') }}</th> --}}
                    <th class="px-4 py-3 rounded-r-md whitespace-nowrap">{{ __('Course.Actions') }}</th>
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
@include('Course::Master.Course.extra_js')
@include('Course::Master.Course.table_js')
@endsection
