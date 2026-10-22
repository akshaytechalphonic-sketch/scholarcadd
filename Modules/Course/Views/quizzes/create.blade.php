@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection

@section('content')
<div>
    @php
        $pageTitle = $data ? 'Edit Quiz / Mock Exam' : 'Add New Quiz / Mock Exam';
        echo breadcrums($pageTitle, ['common.home', 'Course Quizzes', $pageTitle], ['dashboard', 'course-quiz-list']);
    @endphp
    @section('back_button')
        @php echo back_button('course-quiz-list') @endphp
    @endsection
</div>

<div class="text-xs w-full bg-white border rounded-md p-5 shadow-sm mt-3">
    <form action="{{ route('course-quiz-store') }}" method="post" enctype="multipart/form-data">
        @csrf
        @if ($data)
            <input type="hidden" name="id" value="{{ encrypt($data->id) }}">
        @endif

        <div class="border-b pb-3 mb-4">
            <h3 class="text-base font-semibold text-gray-800">
                {{ $data ? 'Edit Quiz / Mock Exam' : 'Create New Quiz / Mock Exam' }}
            </h3>
            <p class="text-gray-500 text-xs">
                Configure exam duration, passing score, questions count, and publishing status. When set to <strong>Published</strong>, this quiz is accessible on <code>/{course-slug}/quizzes</code>. When set to <strong>Draft</strong>, the page safely displays 404 (Permission Denied).
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <!-- Course Selection -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">
                    Target Course <span class="text-red-500 font-bold">*</span>
                </label>
                <select name="course_id" id="course_id" class="form-select select2 w-full text-xs p-2 border rounded" required>
                    <option value="">-- Select Course --</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}" @selected(old('course_id', $data?->course_id) == $c->id)>
                            {{ $c->course_title }} ({{ $c->url_title }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Quiz Type -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">
                    Exam / Quiz Type <span class="text-red-500 font-bold">*</span>
                </label>
                <select name="type" id="type" class="form-select w-full text-xs p-2 border rounded" required>
                    <option value="mock_exam" @selected(old('type', $data?->type ?? 'mock_exam') === 'mock_exam')>
                        🏆 Full Mock Exam (Timed Simulator)
                    </option>
                    <option value="practice_quiz" @selected(old('type', $data?->type) === 'practice_quiz')>
                        📝 Practice Quiz (Quick Revision)
                    </option>
                    <option value="matching_set" @selected(old('type', $data?->type) === 'matching_set')>
                        🤖 AI Matching Set (Matching Question Simulator)
                    </option>
                    <option value="case_study" @selected(old('type', $data?->type) === 'case_study')>
                        💼 Case Study Simulator (Scenario Based)
                    </option>
                </select>
            </div>

            <!-- Status / Visibility -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">
                    Frontend Visibility / Permission <span class="text-red-500 font-bold">*</span>
                </label>
                <select name="status" id="status" class="form-select w-full text-xs p-2 border rounded font-semibold text-gray-800" required>
                    <option value="published" class="text-green-600" @selected(old('status', $data?->status ?? 'published') === 'published')>
                        ✅ Published (Enabled on Website)
                    </option>
                    <option value="draft" class="text-red-600" @selected(old('status', $data?->status) === 'draft')>
                        ❌ Draft (Disabled / 404 on Website)
                    </option>
                    <option value="inactive" class="text-gray-500" @selected(old('status', $data?->status) === 'inactive')>
                        ⏸ Inactive
                    </option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Quiz Title -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">
                    Quiz / Exam Title <span class="text-red-500 font-bold">*</span>
                </label>
                <input type="text" name="title" id="quiz_title" class="form-input w-full text-xs p-2 border rounded"
                    placeholder="e.g. PRINCE2 7 Foundation Official Mock Test 1"
                    value="{{ old('title', $data?->title ?? '') }}" required />
            </div>

            <!-- Quiz Slug -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">
                    URL Slug
                </label>
                <div class="flex gap-2">
                    <input type="text" name="slug" id="quiz_slug" class="form-input w-full text-xs p-2 border rounded bg-gray-50"
                        placeholder="e.g. prince2-7-foundation-mock-test-1"
                        value="{{ old('slug', $data?->slug ?? '') }}" />
                    <button type="button" id="btn_generate_slug" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded text-xs font-medium whitespace-nowrap">
                        Auto Slug
                    </button>
                </div>
            </div>
        </div>

        <!-- Exam Settings (Duration, Questions, Passing Score) -->
        <div class="border rounded-md p-4 bg-gray-50 mb-4">
            <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3 flex items-center gap-1">
                <i class="ri-timer-line text-sm text-primary"></i> Exam Rules & Configuration
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Duration (Minutes)</label>
                    <input type="number" name="duration_minutes" class="form-input w-full text-xs p-2 border rounded bg-white"
                        min="0" placeholder="60"
                        value="{{ old('duration_minutes', $data?->duration_minutes ?? 60) }}" />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Passing Score (%)</label>
                    <input type="number" name="passing_score" class="form-input w-full text-xs p-2 border rounded bg-white"
                        min="0" max="100" placeholder="70"
                        value="{{ old('passing_score', $data?->passing_score ?? 70) }}" />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Total Questions Displayed</label>
                    <input type="number" name="total_questions" class="form-input w-full text-xs p-2 border rounded bg-white"
                        min="0" placeholder="50"
                        value="{{ old('total_questions', $data?->total_questions ?? 0) }}" />
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label class="block text-xs font-semibold text-gray-700 mb-1">
                Exam Instructions / Description (Summernote WYSIWYG)
            </label>
            <textarea name="description" id="description_editor" class="form-input w-full summernote" rows="6">{{ old('description', $data?->description ?? '') }}</textarea>
        </div>

        <div class="flex items-center justify-between border-t pt-4">
            <a href="{{ route('course-quiz-list') }}" class="btn-secondary-sm text-gray-600 hover:text-gray-900 text-xs px-4 py-2 border rounded">
                Cancel
            </a>
            <div class="flex gap-2">
                <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-5 py-2 rounded-md font-semibold text-xs flex items-center gap-1 shadow">
                    <i class="ri-check-line text-sm"></i>
                    {{ $data ? 'Update Quiz / Exam' : 'Save & Publish Quiz' }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
@include('layouts.summer_note_js')
<script>
    $(document).ready(function() {
        if ($('.summernote').length > 0) {
            $('.summernote').summernote({
                height: 200,
                placeholder: 'Enter instructions, syllabus covered, or rules for this mock exam...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'hr']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });
        }

        // Auto slug generator
        $('#btn_generate_slug').on('click', function() {
            var title = $('#quiz_title').val();
            if (title) {
                var slug = title.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim();
                $('#quiz_slug').val(slug);
            }
        });

        $('#quiz_title').on('blur', function() {
            if (!$('#quiz_slug').val()) {
                $('#btn_generate_slug').click();
            }
        });
    });
</script>
@endsection
