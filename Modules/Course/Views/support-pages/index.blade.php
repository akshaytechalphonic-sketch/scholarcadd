@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    <style>
        .dropdown-menu {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection

@section('content')
<div>
    @php echo breadcrums('Course Support Pages', ['common.home', 'Course Support Pages'], ['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')
        <a title="Add Support Page" href="{{ route('course-support-page-create') }}" class="topbarbtn bg-primary hover:bg-primary-dark text-white px-3 py-1.5 rounded-md flex items-center gap-1 text-xs">
            <i class="ri-add-line text-sm"></i>
            Add Support Page
        </a>
    @endsection
</div>

<div class="mb-3 flex flex-wrap items-center justify-between gap-3 bg-white p-3 rounded-md border text-xs">
    <div class="flex items-center gap-2">
        <span class="font-semibold text-gray-700">Filter by Type:</span>
        <select id="filter-type" class="form-select text-xs border-gray-300 rounded p-1">
            <option value="">All Types</option>
            <option value="syllabus">Syllabus</option>
            <option value="exam-format">Exam Format</option>
            <option value="case-study">Case Study</option>
            <option value="eligibility">Eligibility</option>
            <option value="study-plan">Study Plan</option>
            <option value="corporate-training">Corporate Training</option>
            <option value="practice-test">Practice Test</option>
            <option value="faqs">FAQs</option>
            <option value="certification-benefits">Certification Benefits</option>
            <option value="certification-guide">Certification Guide</option>
            <option value="notes">Study Notes</option>
        </select>

        <span class="font-semibold text-gray-700 ml-3">Status:</span>
        <select id="filter-status" class="form-select text-xs border-gray-300 rounded p-1">
            <option value="">All Status</option>
            <option value="published">Published (Enabled)</option>
            <option value="draft">Draft (Disabled)</option>
        </select>
    </div>
    <div class="text-gray-500">
        💡 <em>Click any status badge below to toggle <strong>Published (Active)</strong> or <strong>Draft (Hidden/404)</strong> instantly!</em>
    </div>
</div>

<div class="text-xs w-full border bg-white rounded-md">
    <div class="w-full table_wrapper p-2">
        <table id="data-table-list" class="display w-full min-w-full border-collapse">
            <thead class="bg-primary text-white text-xs">
                <tr>
                    <th class="py-2 px-3 text-left">Course Name</th>
                    <th class="py-2 px-3 text-left">Page Type</th>
                    <th class="py-2 px-3 text-left">Page Title</th>
                    <th class="py-2 px-3 text-left">Visibility / Status</th>
                    <th class="py-2 px-3 text-left">Last Updated</th>
                    <th class="py-2 px-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
@include('layouts.datatable_js')
@include('layouts.sidebar_modal_js')
@include('Course::support-pages.table_js')
@endsection
