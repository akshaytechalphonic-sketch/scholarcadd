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
    <form action="{{ route('course-quiz-question-store', ['quizId' => encrypt($quiz->id)]) }}" method="post">
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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

        <!-- Explanation Section (Crucial for Scope 5.14 simulator) -->
        <div class="mb-5">
            <label class="block text-xs font-semibold text-gray-700 mb-1">
                Detailed Answer Explanation (Practice Mode & Review)
            </label>
            <textarea name="explanation" rows="4" class="form-input w-full text-xs p-2.5 border rounded"
                placeholder="Explain why the correct answer is right and why other options are incorrect. This is shown when a student clicks 'Check Answer' in Practice Mode or reviews completed exams.">{{ old('explanation', $data?->explanation ?? '') }}</textarea>
            <p class="text-gray-400 text-xs mt-1">
                Supports markdown or plain text explanations matching the Sabri C Enhanced Simulator format.
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
