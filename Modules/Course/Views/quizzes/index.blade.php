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
    @php echo breadcrums('Course Quizzes & Mock Exams', ['common.home', 'Course Quizzes'], ['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')
        <a title="Add Quiz / Mock Exam" href="{{ route('course-quiz-create') }}" class="topbarbtn bg-primary hover:bg-primary-dark text-white px-3 py-1.5 rounded-md flex items-center gap-1 text-xs">
            <i class="ri-add-line text-sm"></i>
            Add Quiz / Mock Exam
        </a>
    @endsection
</div>

<div class="mb-3 flex flex-wrap items-center justify-between gap-3 bg-white p-3 rounded-md border text-xs">
    <div class="flex items-center gap-2">
        <span class="font-semibold text-gray-700">Filter by Type:</span>
        <select id="filter-type" class="form-select text-xs border-gray-300 rounded p-1">
            <option value="">All Types</option>
            <option value="mock_exam">Full Mock Exam</option>
            <option value="practice_quiz">Practice Quiz</option>
        </select>

        <span class="font-semibold text-gray-700 ml-3">Status:</span>
        <select id="filter-status" class="form-select text-xs border-gray-300 rounded p-1">
            <option value="">All Status</option>
            <option value="published">Published (Enabled)</option>
            <option value="draft">Draft (Disabled)</option>
        </select>
    </div>
    <div class="text-gray-500">
        💡 <em>Click any status badge below to toggle <strong>Published (Live)</strong> or <strong>Draft (Hidden/404)</strong> instantly!</em>
    </div>
</div>

<div class="text-xs w-full border bg-white rounded-md">
    <div class="w-full table_wrapper p-2">
        <table id="data-table-list" class="display w-full min-w-full border-collapse">
            <thead class="bg-primary text-white text-xs">
                <tr>
                    <th class="py-2 px-3 text-left">Course Name</th>
                    <th class="py-2 px-3 text-left">Quiz Title & Slug</th>
                    <th class="py-2 px-3 text-left">Type</th>
                    <th class="py-2 px-3 text-left">Duration & Passing</th>
                    <th class="py-2 px-3 text-left">Questions</th>
                    <th class="py-2 px-3 text-left">Visibility / Status</th>
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
@include('Course::quizzes.table_js')
@endsection
