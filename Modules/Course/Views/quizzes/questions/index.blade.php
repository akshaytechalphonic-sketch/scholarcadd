@extends('layouts.app')
@section('css')
    <style>
        .option-item.correct {
            border-color: #10b981;
            background-color: #ecfdf5;
        }
    </style>
@endsection

@section('content')
<div>
    @php
        echo breadcrums('Questions', ['common.home', 'Course Quizzes', 'Questions'], ['dashboard', 'course-quiz-list']);
    @endphp
    @section('back_button')
        @php echo back_button('course-quiz-list') @endphp
    @endsection
    @section('extra_buttons')
        <a title="Add Question" href="{{ route('course-quiz-question-create', ['quizId' => encrypt($quiz->id)]) }}" class="topbarbtn bg-primary hover:bg-primary-dark text-white px-3 py-1.5 rounded-md flex items-center gap-1 text-xs">
            <i class="ri-add-line text-sm"></i>
            Add New Question
        </a>
    @endsection
</div>

<!-- Quiz Header Card -->
<div class="bg-white border rounded-md p-4 mb-4 shadow-sm text-xs mt-3">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <div class="text-xs text-gray-500 uppercase font-semibold">Course: {{ $quiz->course?->course_title ?? 'N/A' }}</div>
            <h2 class="text-base font-bold text-gray-800 mt-0.5">{{ $quiz->title }}</h2>
            <div class="text-gray-500 mt-1 flex items-center gap-3">
                <span>⏱ Duration: <strong>{{ $quiz->duration_minutes }} mins</strong></span>
                <span>🎯 Passing Score: <strong>{{ $quiz->passing_score }}%</strong></span>
                <span>📝 Total Questions: <strong>{{ $questions->count() }}</strong></span>
                <span>Type: <strong>{{ $quiz->type === 'mock_exam' ? 'Full Mock Exam' : 'Practice Quiz' }}</strong></span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('course-quiz-questions-sample-csv', ['quizId' => encrypt($quiz->id)]) }}" class="inline-flex items-center gap-1 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded border text-xs" title="Download template format for Excel/CSV import">
                <i class="ri-file-download-line text-sm text-emerald-600"></i> Sample CSV
            </a>
            <button type="button" onclick="document.getElementById('csvImportModal').classList.remove('hidden')" class="inline-flex items-center gap-1 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded shadow text-xs">
                <i class="ri-upload-cloud-line text-sm"></i> Bulk Import CSV
            </button>
            <a href="{{ route('course-quiz-question-create', ['quizId' => encrypt($quiz->id)]) }}" class="inline-flex items-center gap-1 px-4 py-2 bg-primary hover:bg-primary-dark text-white font-semibold rounded shadow text-xs">
                <i class="ri-add-line text-sm"></i> Add Question
            </a>
        </div>
    </div>
</div>

<!-- Bulk CSV Import Modal -->
<div id="csvImportModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-5 text-xs">
        <div class="flex items-center justify-between border-b pb-2 mb-3">
            <h3 class="font-bold text-gray-800 text-sm flex items-center gap-1.5">
                <i class="ri-upload-cloud-line text-indigo-600"></i> Bulk Import Questions (CSV / Excel)
            </h3>
            <button type="button" onclick="document.getElementById('csvImportModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-lg">
                &times;
            </button>
        </div>
        <form action="{{ route('course-quiz-questions-import-csv', ['quizId' => encrypt($quiz->id)]) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="block text-gray-700 font-semibold mb-1">Select CSV File:</label>
                <input type="file" name="csv_file" accept=".csv,text/csv" class="form-input w-full p-2 border rounded" required>
                <p class="text-[11px] text-gray-400 mt-1">Columns: Question Text, Option A, Option B, Option C, Option D, Correct Option (A/B/C/D), Explanation, Marks</p>
            </div>
            <div class="bg-gray-50 border rounded p-2.5 mb-4 text-[11px] text-gray-600 flex items-center justify-between">
                <span>Need formatting reference?</span>
                <a href="{{ route('course-quiz-questions-sample-csv', ['quizId' => encrypt($quiz->id)]) }}" class="text-primary font-bold hover:underline">Download Sample CSV</a>
            </div>
            <div class="flex items-center justify-end gap-2 border-t pt-3">
                <button type="button" onclick="document.getElementById('csvImportModal').classList.add('hidden')" class="px-3 py-1.5 border rounded text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded shadow">Start Import</button>
            </div>
        </form>
    </div>
</div>

<!-- Questions List -->
<div class="space-y-4">
    @forelse($questions as $index => $q)
        <div class="bg-white border rounded-md p-4 shadow-sm text-xs">
            <div class="flex items-start justify-between gap-4 border-b pb-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white font-bold text-xs">
                        {{ $index + 1 }}
                    </span>
                    <span class="font-bold text-gray-800 text-sm">Question #{{ $q->order_no ?? ($index + 1) }}</span>
                    <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-[11px] font-medium">Marks: {{ $q->marks ?? 1 }}</span>
                    @if(($q->type ?? '') === 'dropdown')
                        <span class="bg-indigo-100 text-indigo-800 border border-indigo-200 px-2 py-0.5 rounded text-[11px] font-bold">🔽 Dropdown Selection</span>
                    @elseif(($q->type ?? '') === 'matching')
                        <span class="bg-purple-100 text-purple-800 border border-purple-200 px-2 py-0.5 rounded text-[11px] font-bold">🔄 Matching</span>
                    @else
                        <span class="bg-slate-100 text-slate-700 border border-slate-200 px-2 py-0.5 rounded text-[11px] font-medium">🔘 Multiple Choice</span>
                    @endif
                    @if($q->mode === 'practice')
                        <span class="bg-blue-100 text-blue-800 border border-blue-200 px-2 py-0.5 rounded text-[11px] font-semibold">📝 Practice Only</span>
                    @elseif($q->mode === 'exam')
                        <span class="bg-purple-100 text-purple-800 border border-purple-200 px-2 py-0.5 rounded text-[11px] font-semibold">🏆 Exam Only</span>
                    @else
                        <span class="bg-emerald-100 text-emerald-800 border border-emerald-200 px-2 py-0.5 rounded text-[11px] font-semibold">🌟 Both (Practice & Exam)</span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('course-quiz-question-edit', ['quizId' => encrypt($quiz->id), 'questionId' => encrypt($q->id)]) }}" class="text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-0.5">
                        <i class="ri-pencil-line"></i> Edit
                    </a>
                    <span class="text-gray-300">|</span>
                    <button type="button" onclick="confirmDeleteQuestion('{{ encrypt($quiz->id) }}', '{{ encrypt($q->id) }}')" class="text-red-600 hover:text-red-800 font-semibold flex items-center gap-0.5">
                        <i class="ri-delete-bin-line"></i> Delete
                    </button>
                </div>
            </div>

            <div class="text-gray-800 font-medium mb-3 text-xs leading-relaxed">
                {!! nl2br(e($q->question_text)) !!}
            </div>

            <!-- Options Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-3">
                @foreach($q->options as $opt)
                    <div class="p-2.5 border rounded-md text-xs flex items-center gap-2 {{ $opt->is_correct ? 'border-emerald-400 bg-emerald-50 text-emerald-900 font-semibold' : 'border-gray-200 bg-gray-50 text-gray-700' }}">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full {{ $opt->is_correct ? 'bg-emerald-600 text-white font-bold' : 'bg-gray-200 text-gray-700 font-medium' }} text-[11px]">
                            {{ $opt->option_label ?? chr(65 + $loop->index) }}
                        </span>
                        <span class="flex-1">{{ $opt->option_text }}</span>
                        @if($opt->is_correct)
                            <span class="text-emerald-600 font-bold text-[11px] ml-auto">✓ Correct Answer</span>
                        @endif
                    </div>
                @endforeach
            </div>

            @if($q->explanation)
                <div class="bg-amber-50 border border-amber-200 rounded p-2.5 text-xs text-amber-900 mt-2">
                    <div class="font-bold mb-0.5 flex items-center gap-1 text-amber-800">
                        <i class="ri-lightbulb-line"></i> Explanation (Shown in Practice Mode / Review):
                    </div>
                    <div>{!! nl2br(e($q->explanation)) !!}</div>
                </div>
            @endif
        </div>
    @empty
        <div class="bg-white border rounded-md p-8 text-center text-gray-500 text-xs">
            <div class="text-4xl text-gray-300 mb-2">📋</div>
            <p class="font-semibold text-gray-700 mb-1">No questions added to this quiz yet.</p>
            <p class="text-gray-400 mb-4">Add your first question with multiple-choice options and explanations for the simulator.</p>
            <a href="{{ route('course-quiz-question-create', ['quizId' => encrypt($quiz->id)]) }}" class="inline-flex items-center gap-1 px-4 py-2 bg-primary hover:bg-primary-dark text-white font-semibold rounded shadow text-xs">
                <i class="ri-add-line"></i> Add First Question
            </a>
        </div>
    @endforelse
</div>
@endsection

@section('scripts')
<script>
    window.confirmDeleteQuestion = function(quizId, questionId) {
        if (confirm("Are you sure you want to delete this question?")) {
            $.ajax({
                url: "{{ url('course-quiz') }}/" + quizId + "/questions/" + questionId + "/delete",
                type: 'GET',
                success: function(res) {
                    if (res.status) {
                        toastr ? toastr.success(res.message) : alert(res.message);
                        window.location.reload();
                    } else {
                        toastr ? toastr.error(res.message) : alert(res.message);
                    }
                },
                error: function(err) {
                    var msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Error deleting question';
                    toastr ? toastr.error(msg) : alert(msg);
                }
            });
        }
    };
</script>
@endsection
