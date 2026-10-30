<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Course\Models\Course;
use Modules\Course\Models\CourseQuiz;
use Modules\Course\Models\CourseQuizQuestion;
use Modules\Course\Models\CourseQuizQuestionOption;
use Yajra\DataTables\DataTables;

class CourseQuizAdminController extends Controller
{
    /**
     * List all Course Quizzes / Mock Exams
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = CourseQuiz::with('course')->orderBy('id', 'desc');

                if ($request->filled('search.value')) {
                    $search = $request->input('search.value');
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%")
                            ->orWhere('type', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%")
                            ->orWhereHas('course', function ($cq) use ($search) {
                                $cq->where('course_title', 'like', "%{$search}%")
                                   ->orWhere('url_title', 'like', "%{$search}%");
                            });
                    });
                }

                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }

                if ($request->filled('type')) {
                    $query->where('type', $request->type);
                }

                return DataTables::of($query)
                    ->addColumn('course_name', function ($row) {
                        return $row->course?->course_title ?? 'N/A';
                    })
                    ->addColumn('quiz_title', function ($row) {
                        return '<div><div class="font-semibold text-gray-800">' . e($row->title) . '</div><div class="text-xs text-gray-400">' . e($row->slug) . '</div></div>';
                    })
                    ->addColumn('type_badge', function ($row) {
                        $types = [
                            'mock_exam' => ['bg' => 'bg-purple-100 text-purple-800 border-purple-200', 'label' => 'Full Mock Exam'],
                            'practice_quiz' => ['bg' => 'bg-cyan-100 text-cyan-800 border-cyan-200', 'label' => 'Practice Quiz'],
                            'matching_set' => ['bg' => 'bg-blue-100 text-blue-800 border-blue-200', 'label' => 'AI Matching Set'],
                            'case_study' => ['bg' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'label' => 'Case Study'],
                        ];
                        $conf = $types[$row->type] ?? ['bg' => 'bg-gray-100 text-gray-800 border-gray-200', 'label' => ucfirst(str_replace('_', ' ', $row->type))];
                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border ' . $conf['bg'] . '">' . $conf['label'] . '</span>';
                    })
                    ->addColumn('exam_details', function ($row) {
                        return '<div class="text-xs text-gray-600">⏱ <strong>' . $row->duration_minutes . '</strong> mins &nbsp;|&nbsp; 🎯 <strong>' . $row->passing_score . '%</strong> passing</div>';
                    })
                    ->addColumn('questions_count', function ($row) {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700">' . ($row->total_questions ?? 0) . ' Qs</span>';
                    })
                    ->addColumn('status_badge', function ($row) {
                        $encId = encrypt($row->id);
                        $isPub = ($row->status === 'published');
                        $btnClass = $isPub ? 'bg-green-100 text-green-700 hover:bg-green-200 border-green-300' : 'bg-rose-100 text-rose-700 hover:bg-rose-200 border-rose-300';
                        $label = $isPub ? '● Published (Enabled)' : '○ Draft (Disabled)';
                        $title = $isPub ? 'Click to Disable (Set to Draft)' : 'Click to Enable (Set to Published)';

                        return '<button type="button" title="' . $title . '" onclick="toggleQuizStatus(\'' . $encId . '\')" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border cursor-pointer transition ' . $btnClass . '">' . $label . '</button>';
                    })
                    ->addColumn('actions', function ($row) {
                        $encId = encrypt($row->id);
                        $editUrl = route('course-quiz-edit', ['id' => $encId]);
                        $courseSlug = $row->course?->url_title ?? '';
                        $frontendBase = rtrim((string) (config('app.frontend_url') ?: env('FRONTEND_URL', 'https://scolarcadfront.vercel.app')), '/');
                        if (empty($frontendBase) || str_contains($frontendBase, 'localhost') || str_contains($frontendBase, '127.0.0.1') || str_contains($frontendBase, 'yourserver.com')) {
                            $frontendBase = 'https://scolarcadfront.vercel.app';
                        }
                        $previewUrl = ($courseSlug && !empty($row->slug))
                            ? $frontendBase . '/' . $courseSlug . '/quizzes/' . $row->slug . '/questions?mode=exam'
                            : ($courseSlug ? $frontendBase . '/' . $courseSlug . '/quizzes' : '#');

                        $questionsUrl = route('course-quiz-questions', ['quizId' => $encId]);

                        $html = '<div class="relative inline-block text-left dropdown">
                            <button type="button" class="dropdown-toggle text-gray-500 hover:text-gray-700 p-1 rounded hover:bg-gray-100">
                                <i class="ri-more-2-fill text-lg"></i>
                            </button>
                            <div class="dropdown-menu hidden absolute right-0 z-50 mt-1 w-48 bg-white border border-gray-200 rounded-md shadow-lg py-1">
                                <a href="' . $questionsUrl . '" class="flex items-center px-4 py-2 text-sm text-purple-700 hover:bg-purple-50 font-medium">
                                    <i class="ri-questionnaire-line mr-2 text-purple-600"></i> Manage Questions (' . ($row->total_questions ?? 0) . ')
                                </a>
                                <a href="' . $editUrl . '" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="ri-pencil-line mr-2 text-blue-500"></i> Edit Quiz
                                </a>
                                <a href="' . $previewUrl . '" target="_blank" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="ri-external-link-line mr-2 text-emerald-500"></i> View on Frontend
                                </a>
                                <button type="button" onclick="confirmDeleteQuiz(\'' . $encId . '\')" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 text-left">
                                    <i class="ri-delete-bin-line mr-2"></i> Delete
                                </button>
                            </div>
                        </div>';

                        return $html;
                    })
                    ->rawColumns(['quiz_title', 'type_badge', 'exam_details', 'questions_count', 'status_badge', 'actions'])
                    ->make(true);
            }

            return view('Course::quizzes.index');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show form to create a new Quiz
     */
    public function create()
    {
        $courses = Course::where('status', 'Active')
            ->whereNull('deleted_at')
            ->orderBy('course_title', 'asc')
            ->get(['id', 'course_title', 'url_title']);

        $data = null;
        return view('Course::quizzes.create', compact('courses', 'data'));
    }

    /**
     * Show form to edit an existing Quiz
     */
    public function edit($id)
    {
        try {
            $decId = decrypt($id);
            $data = CourseQuiz::with('course')->findOrFail($decId);

            $courses = Course::where('status', 'Active')
                ->whereNull('deleted_at')
                ->orderBy('course_title', 'asc')
                ->get(['id', 'course_title', 'url_title']);

            return view('Course::quizzes.create', compact('courses', 'data'));
        } catch (\Exception $e) {
            return redirect()->route('course-quiz-list')->with('error', 'Invalid Quiz ID.');
        }
    }

    /**
     * Store or Update Quiz
     */
    public function store(Request $request)
    {
        $id = $request->filled('id') ? decrypt($request->input('id')) : null;

        $request->validate([
            'course_id' => 'required|exists:tbl_courses,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tbl_course_quizzes,slug,' . ($id ?? 'NULL') . ',id',
            'type' => 'required|string|in:mock_exam,practice_quiz,matching_set,case_study',
            'status' => 'required|string|in:published,draft,inactive',
            'duration_minutes' => 'nullable|integer|min:0',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'total_questions' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        try {
            [$userId, $companyId] = Controller::loginandcompany();

            $slug = $request->filled('slug')
                ? Str::slug($request->input('slug'))
                : Str::slug($request->input('title'));

            // Ensure slug is unique if generating
            if (!$id) {
                $originalSlug = $slug;
                $count = 1;
                while (CourseQuiz::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }

            $payload = [
                'course_id' => $request->course_id,
                'title' => $request->title,
                'slug' => $slug,
                'type' => $request->type,
                'description' => $request->description,
                'duration_minutes' => $request->input('duration_minutes', 60),
                'passing_score' => $request->input('passing_score', 70),
                'total_questions' => $request->input('total_questions', 0),
                'status' => $request->status,
                'company_id' => $companyId,
                'updated_by' => $userId,
            ];

            if ($id) {
                $quiz = CourseQuiz::findOrFail($id);
                $quiz->update($payload);
                return redirect()->route('course-quiz-list')->with('success', 'Quiz updated successfully.');
            }

            $payload['created_by'] = $userId;
            CourseQuiz::create($payload);

            return redirect()->route('course-quiz-list')->with('success', 'Quiz created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error saving quiz: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Soft delete Quiz
     */
    public function destroy($id)
    {
        try {
            $decId = decrypt($id);
            $quiz = CourseQuiz::findOrFail($decId);
            $quiz->delete();

            if (request()->ajax()) {
                return response()->json(['status' => true, 'message' => 'Quiz deleted successfully.']);
            }

            return redirect()->route('course-quiz-list')->with('success', 'Quiz deleted successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Quick Toggle Status (published <-> draft)
     */
    public function toggleStatus(Request $request)
    {
        try {
            $decId = decrypt($request->input('id'));
            $quiz = CourseQuiz::findOrFail($decId);

            $quiz->status = ($quiz->status === 'published') ? 'draft' : 'published';
            $quiz->save();

            return response()->json([
                'status' => true,
                'message' => 'Quiz status changed to ' . ucfirst($quiz->status) . ' successfully.',
                'new_status' => $quiz->status,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * List all questions for a specific quiz
     */
    public function questions($quizId)
    {
        try {
            $decId = decrypt($quizId);
            $quiz = CourseQuiz::with(['course', 'questions.options'])->findOrFail($decId);
            $questions = $quiz->questions()->with('options')->orderBy('order_no', 'asc')->get();

            return view('Course::quizzes.questions.index', compact('quiz', 'questions'));
        } catch (\Exception $e) {
            return redirect()->route('course-quiz-list')->with('error', 'Quiz not found.');
        }
    }

    /**
     * Create or Edit question form
     */
    public function createQuestion($quizId, $questionId = null)
    {
        try {
            $decQuizId = decrypt($quizId);
            $quiz = CourseQuiz::with('course')->findOrFail($decQuizId);

            $data = null;
            if ($questionId) {
                $decQuestionId = decrypt($questionId);
                $data = CourseQuizQuestion::with('options')->findOrFail($decQuestionId);
            }

            return view('Course::quizzes.questions.create', compact('quiz', 'data'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store or update question with its options
     */
    public function storeQuestion(Request $request, $quizId)
    {
        $request->validate([
            'question_text' => 'required|string',
            'type' => 'nullable|string|in:single_choice,dropdown,matching',
            'order_no' => 'nullable|integer',
            'marks' => 'nullable|integer',
            'correct_option' => 'required|string|in:A,B,C,D',
            'options' => 'required|array',
            'options.A' => 'required|string',
            'options.B' => 'required|string',
            'options.C' => 'required|string',
            'options.D' => 'required|string',
            'explanation' => 'nullable|string',
        ]);

        try {
            $decQuizId = decrypt($quizId);
            $quiz = CourseQuiz::findOrFail($decQuizId);

            $questionId = $request->filled('question_id') ? decrypt($request->input('question_id')) : null;

            // Handle optional Matching Visual / Question Image upload
            $imageUrl = null;
            if ($questionId) {
                $existingQ = CourseQuizQuestion::find($questionId);
                $imageUrl = $existingQ?->image_url;
            }

            if ($request->hasFile('question_image')) {
                $file = $request->file('question_image');
                $uploadDir = public_path('uploads/quiz-questions');
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $filename = 'quiz_q_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadDir, $filename);
                $imageUrl = 'uploads/quiz-questions/' . $filename;
            }

            if ($questionId) {
                $question = CourseQuizQuestion::findOrFail($questionId);
                $question->update([
                    'question_text' => $request->question_text,
                    'type' => $request->input('type', $question->type ?? 'single_choice'),
                    'image_url' => $imageUrl,
                    'order_no' => $request->input('order_no', 1),
                    'marks' => $request->input('marks', 1),
                    'explanation' => $request->explanation,
                    'mode' => $request->input('mode', 'both'),
                ]);
            } else {
                $question = CourseQuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $request->question_text,
                    'image_url' => $imageUrl,
                    'type' => $request->input('type', 'single_choice'),
                    'order_no' => $request->input('order_no', 1),
                    'marks' => $request->input('marks', 1),
                    'explanation' => $request->explanation,
                    'mode' => $request->input('mode', 'both'),
                    'status' => 'active',
                ]);
            }

            // Sync Options
            $correctOption = $request->input('correct_option');
            $optionsData = $request->input('options', []);

            foreach ($optionsData as $label => $text) {
                CourseQuizQuestionOption::updateOrCreate(
                    [
                        'question_id' => $question->id,
                        'option_label' => $label,
                    ],
                    [
                        'option_text' => $text,
                        'is_correct' => ($label === $correctOption),
                        'order_no' => ord($label) - ord('A') + 1,
                    ]
                );
            }

            // Automatically update quiz total_questions count
            $quiz->update([
                'total_questions' => $quiz->questions()->count()
            ]);

            return redirect()->route('course-quiz-questions', ['quizId' => encrypt($quiz->id)])
                ->with('success', 'Question saved successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error saving question: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete a question
     */
    public function destroyQuestion($quizId, $questionId)
    {
        try {
            $decQuizId = decrypt($quizId);
            $decQuestionId = decrypt($questionId);

            $quiz = CourseQuiz::findOrFail($decQuizId);
            $question = CourseQuizQuestion::where('quiz_id', $quiz->id)->findOrFail($decQuestionId);
            $question->delete();

            $quiz->update([
                'total_questions' => $quiz->questions()->count()
            ]);

            if (request()->ajax()) {
                return response()->json(['status' => true, 'message' => 'Question deleted successfully.']);
            }

            return redirect()->route('course-quiz-questions', ['quizId' => encrypt($quiz->id)])
                ->with('success', 'Question deleted successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Download Sample CSV template for bulk questions upload
     */
    public function downloadSampleCsv($quizId)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample_quiz_questions.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Question Text', 'Option A', 'Option B', 'Option C', 'Option D', 'Correct Option', 'Explanation', 'Marks']);
            fputcsv($file, [
                'Which of the following is a core principle of Scrum?',
                'Empirical Process Control',
                'Comprehensive Documentation',
                'Contract Negotiation',
                'Sequential Stage Gates',
                'A',
                'Scrum is based on empirical process control theory which relies on transparency, inspection, and adaptation.',
                '1'
            ]);
            fputcsv($file, [
                'In Scrum, what is the maximum duration for a Daily Scrum meeting?',
                '15 minutes',
                '30 minutes',
                '45 minutes',
                '60 minutes',
                'A',
                'The Daily Scrum is a 15-minute time-boxed event for the Developers to inspect progress toward the Sprint Goal.',
                '1'
            ]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk Import questions via CSV
     */
    public function importQuestions(Request $request, $quizId)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        try {
            $decQuizId = decrypt($quizId);
            $quiz = CourseQuiz::findOrFail($decQuizId);

            $file = $request->file('csv_file');
            $handle = fopen($file->getRealPath(), 'r');

            $header = fgetcsv($handle); // Read header row
            $importedCount = 0;
            $currentOrder = $quiz->questions()->max('order_no') ?: 0;

            while (($row = fgetcsv($handle)) !== false) {
                if (empty($row[0])) continue;

                $questionText = trim($row[0]);
                $optA = trim($row[1] ?? '');
                $optB = trim($row[2] ?? '');
                $optC = trim($row[3] ?? '');
                $optD = trim($row[4] ?? '');
                $correctOpt = strtoupper(trim($row[5] ?? 'A'));
                $explanation = trim($row[6] ?? '');
                $marks = isset($row[7]) && is_numeric($row[7]) ? (int)$row[7] : 1;

                if (!$optA || !$optB) continue;

                $currentOrder++;
                $question = CourseQuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $questionText,
                    'type' => $request->input('type', 'single_choice'),
                    'order_no' => $currentOrder,
                    'marks' => $marks,
                    'explanation' => $explanation,
                    'status' => 'active',
                ]);

                $options = [
                    'A' => $optA,
                    'B' => $optB,
                    'C' => $optC,
                    'D' => $optD,
                ];

                foreach ($options as $lbl => $text) {
                    if (!$text) continue;
                    CourseQuizQuestionOption::create([
                        'question_id' => $question->id,
                        'option_label' => $lbl,
                        'option_text' => $text,
                        'is_correct' => ($lbl === $correctOpt),
                        'order_no' => ord($lbl) - ord('A') + 1,
                    ]);
                }

                $importedCount++;
            }

            fclose($handle);

            // Update quiz total_questions count
            $quiz->update([
                'total_questions' => $quiz->questions()->count()
            ]);

            return redirect()->route('course-quiz-questions', ['quizId' => encrypt($quiz->id)])
                ->with('success', "Successfully imported {$importedCount} questions from CSV!");
        } catch (\Exception $e) {
            return back()->with('error', 'CSV Import error: ' . $e->getMessage());
        }
    }
}
