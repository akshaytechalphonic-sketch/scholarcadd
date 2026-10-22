@extends('layouts.app')
@section('css')
    @include('layouts.summer_note_css')
@endsection

@section('content')
<div>
    @php
        $pageTitle = $data ? 'Edit Question' : 'Add Question';
        echo breadcrums($pageTitle, ['common.home', 'Course Quizzes', $pageTitle], ['dashboard', 'course-quiz-list']);
    @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
</div>

<div class="text-xs w-full bg-white border rounded-md p-5 shadow-sm mt-3">
    <form action="{{ route('course-quiz-question-store', ['quizId' => encrypt($quiz->id)]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @if ($data)
            <input type="hidden" name="question_id" value="{{ encrypt($data->id) }}">
        @endif

        <div class="border-b pb-3 mb-4 flex items-center justify-between">
            <div>
                <h3 class="text-base font-semibold text-gray-800">
                    {{ $data ? 'Edit Simulator Question' : 'Add Question to Exam Simulator' }}
                </h3>
                <p class="text-gray-500 text-xs">
                    Quiz: <strong>{{ $quiz->title }}</strong> &nbsp;|&nbsp; Course: <strong>{{ $quiz->course?->course_title ?? 'N/A' }}</strong>
                </p>
            </div>
            <div>
                <span class="bg-purple-100 text-purple-800 border border-purple-200 px-3 py-1 rounded-full font-bold text-xs">
                    {{ $quiz->type === 'mock_exam' ? 'Full Mock Exam' : 'Practice Quiz' }}
                </span>
            </div>
        </div>

        <!-- Question Text -->
        <div class="mb-4">
            <label class="block text-xs font-semibold text-gray-700 mb-1">
                Question Text <span class="text-red-500 font-bold">*</span>
            </label>
            <textarea name="question_text" rows="4" class="form-input w-full text-xs p-2.5 border rounded"
                placeholder="Enter the full exam question scenario or prompt here..." required>{{ old('question_text', $data?->question_text ?? '') }}</textarea>
        </div>

        <!-- Matching Question Visual / Image -->
        <div class="mb-4 bg-gray-50 p-3 rounded border">
            <label class="block text-xs font-semibold text-gray-800 mb-1 flex items-center gap-1">
                <i class="ri-image-line text-sm text-primary"></i> Question / Matching Visual Image (Optional)
            </label>
            <div class="flex items-center gap-3">
                <input type="file" name="question_image" id="question_image" accept="image/*" class="form-input text-xs p-1.5 border rounded bg-white w-full max-w-md">
                @if(!empty($data?->image_url))
                    <div class="flex items-center gap-2 bg-white border p-1 rounded">
                        <img src="{{ asset($data->image_url) }}" alt="Preview" class="w-8 h-8 object-cover rounded border">
                        <a href="{{ asset($data->image_url) }}" target="_blank" class="text-[11px] text-blue-600 underline font-medium">View current image</a>
                    </div>
                @endif
            </div>
            <span class="text-gray-500 text-[11px] mt-1 block">
                💡 Diagram, workflow, or matching scenario visual embedded for this question (supports AI Matching sets).
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Order / Question Number</label>
                <input type="number" name="order_no" class="form-input w-full text-xs p-2 border rounded"
                    value="{{ old('order_no', $data?->order_no ?? ($quiz->questions()->count() + 1)) }}" min="1">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Marks / Points</label>
                <input type="number" name="marks" class="form-input w-full text-xs p-2 border rounded"
                    value="{{ old('marks', $data?->marks ?? 1) }}" min="1">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">
                    Show In (Mode) <span class="text-red-500 font-bold">*</span>
                </label>
                <select name="mode" class="form-select w-full text-xs p-2 border rounded font-medium">
                    <option value="both" @selected(old('mode', $data?->mode ?? 'both') === 'both')>
                        🌟 Both (Start Practice & Start Exam)
                    </option>
                    <option value="practice" @selected(old('mode', $data?->mode) === 'practice')>
                        📝 Practice Mode Only (Learning/Hints)
                    </option>
                    <option value="exam" @selected(old('mode', $data?->mode) === 'exam')>
                        🏆 Exam Mode Only (Timed Simulator)
                    </option>
                </select>
            </div>
        </div>

        <!-- Options Section -->
        <div class="border rounded-md p-4 bg-gray-50 mb-4">
            <div class="flex items-center justify-between mb-3 border-b pb-2">
                <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-1">
                    <i class="ri-checkbox-multiple-line text-sm text-primary"></i> Answer Options (Select the Correct Option)
                </h4>
                <span class="text-xs text-gray-500">Choose the radio button next to the <strong>correct answer</strong></span>
            </div>

            @php
                $existingOptions = $data ? $data->options->keyBy('option_label')->toArray() : [];
            @endphp

            <div class="space-y-3">
                @foreach(['A', 'B', 'C', 'D'] as $label)
                    @php
                        $optText = $existingOptions[$label]['option_text'] ?? '';
                        $isCorrect = !empty($existingOptions[$label]['is_correct']);
                    @endphp
                    <div class="flex items-center gap-3 bg-white p-3 border rounded shadow-sm">
                        <!-- Radio Button for Correct Answer -->
                        <div class="flex items-center gap-1.5">
                            <input type="radio" name="correct_option" id="correct_{{ $label }}" value="{{ $label }}"
                                @checked(old('correct_option', $isCorrect ? $label : ($label === 'A' && !$data ? 'A' : '')) === $label)
                                class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 cursor-pointer" required>
                            <label for="correct_{{ $label }}" class="font-bold text-xs text-gray-700 cursor-pointer w-6 text-center">
                                Option {{ $label }}:
                            </label>
                        </div>

                        <!-- Option Input Text -->
                        <div class="flex-1">
                            <input type="text" name="options[{{ $label }}]" class="form-input w-full text-xs p-2 border rounded"
                                placeholder="Enter option text for {{ $label }}..."
                                value="{{ old('options.' . $label, $optText) }}" required>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Explanation Section -->
        <div class="mb-5 bg-amber-50/70 border border-amber-200 rounded-md p-4">
            <label class="block text-xs font-bold text-amber-900 mb-1 flex items-center gap-1.5">
                <i class="ri-lightbulb-line text-sm text-amber-600"></i> Answer Explanation & Concept ("Why is this answer correct / incorrect?")
            </label>
            <textarea name="explanation" rows="5" class="form-input w-full text-xs p-2.5 border border-amber-300 rounded bg-white font-medium"
                placeholder="Enter detailed explanation of why the correct option is right and incorrect options are wrong. Example:
- Option B is correct because...
- Option A is incorrect because...">{{ old('explanation', $data?->explanation ?? '') }}</textarea>
            <p class="text-amber-800 text-xs mt-1.5 font-medium flex items-center gap-1">
                <span>💡</span> <strong>How it appears on Frontend:</strong> When a user selects an answer during practice mode or views results, this explanation will automatically appear in the <strong>"Answer Explanation & Rationale"</strong> card.
            </p>
        </div>

        <div class="flex items-center justify-between border-t pt-4">
            <a href="{{ route('course-quiz-questions', ['quizId' => encrypt($quiz->id)]) }}" class="btn-secondary-sm text-gray-600 hover:text-gray-900 text-xs px-4 py-2 border rounded">
                Cancel
            </a>
            <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-5 py-2 rounded-md font-semibold text-xs flex items-center gap-1 shadow">
                <i class="ri-check-line text-sm"></i>
                {{ $data ? 'Update Question' : 'Save Question to Quiz' }}
            </button>
        </div>
    </form>
</div>
@endsection
