@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('website.Blog List'), [__('common.home'), __('website.Blog List')], ['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')
        <a title="{{ __('common.Add') }}" href="{{ route('knowledge-center-create') }}" class="topbarbtn">
            <i class="ri-add-line"></i>
            {{ __('common.Add') }}
        </a>
        {{-- @php echo filter_button(); @endphp --}}
    @endsection
</div>
{{-- @include('pages.common.custom_filter_html') --}}
<section class="toggle-status-section grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-4 gap-2 mb-4">
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
    <!-- In Review -->
    <div class="flex items-center justify-between bg-white border rounded-md p-4 hover:shadow transition status-filter"
        data-status="Blog">
        <div class="flex items-center gap-2">
            <i class="ri-file-search-line text-cyan-500 text-xl"></i>
            <h3 class="text-sm font-semibold text-gray-700">{{ __('common.Blog') }}</h3>
        </div>
        <div class="bg-cyan-100 text-cyan-800 text-sm font-bold px-4 py-2 rounded-md">
            <span id="inreview_count"></span>
        </div>
    </div>
    <!-- Approved -->
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
    <!-- Article -->
    <div class="flex items-center justify-between bg-white border rounded-md p-4 hover:shadow transition status-filter"
        data-status="Article">
        <div class="flex items-center gap-2">
            <i class="ri-file-search-line text-cyan-500 text-xl"></i>
            <h3 class="text-sm font-semibold text-gray-700">{{ __('common.Article') }}</h3>
        </div>
        <div class="bg-red-100 text-red-800 text-sm font-bold px-4 py-2 rounded-md">
            <span id="Article"></span>
        </div>
    </div>
    <!-- Info -->
    <div class="flex items-center justify-between bg-white border rounded-md p-4 hover:shadow transition status-filter"
        data-status="Info">
        <div class="flex items-center gap-2">
            <i class="ri-file-search-line text-cyan-500 text-xl"></i>
            <h3 class="text-sm font-semibold text-gray-700">{{ __('common.Info') }}</h3>
        </div>
        <div class="bg-red-100 text-red-800 text-sm font-bold px-4 py-2 rounded-md">
            <span id="Info"></span>
        </div>
    </div>
    <!-- Info -->
    <div class="flex items-center justify-between bg-white border rounded-md p-4 hover:shadow transition status-filter"
        data-status="Webinar">
        <div class="flex items-center gap-2">
            <i class="ri-file-search-line text-cyan-500 text-xl"></i>
            <h3 class="text-sm font-semibold text-gray-700">{{ __('common.Webinar') }}</h3>
        </div>
        <div class="bg-red-100 text-red-800 text-sm font-bold px-4 py-2 rounded-md">
            <span id="Webinar"></span>
        </div>
    </div>
</section>
<section>
    <div class="overflow-x-auto bg-white rounded-md border p-4">
        <table id="data-table-list" class="display table-auto w-full py-1 text-xs">
            <thead class="bg-primary text-white ">
                <tr>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Sl.No.') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('common.title') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('website.Blog Description') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('website.Category') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('common.created-by') }}</th>
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
@include('website::KnowledgeCenter.extra_js')
@include('website::KnowledgeCenter.table_js')
@endsection
