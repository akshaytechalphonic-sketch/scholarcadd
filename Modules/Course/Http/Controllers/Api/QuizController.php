<?php

namespace Modules\Course\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Course\Models\Course;
use Modules\Course\Models\CourseQuiz;
use Modules\Course\Models\CourseQuizQuestion;

class QuizController extends Controller
{
    use ApiResponseTrait;

    /**
     * GET /api/courses/{courseSlug}/quizzes
     * List all published quizzes / mock exams for a course (permission-filtered)
     */
    public function quizzes(Request $request, string $courseSlug)
    {
        try {
            $course = Course::where('url_title', $courseSlug)
                ->where('status', 'Active')
                ->whereNull('deleted_at')
                ->first();

            if (!$course) {
                return $this->errorResponse('Course not found', 404);
            }

            $query = CourseQuiz::where('course_id', $course->id)
                ->published();

            // Filter by type if provided (e.g. mock_exam, practice_quiz)
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            // Filter by specific quiz slug if requested
            if ($request->filled('slug')) {
                $query->where('slug', $request->slug);
            }

            $quizzes = $query->orderBy('id', 'asc')
                ->get([
                    'id',
                    'course_id',
                    'title',
                    'slug',
                    'type',
                    'description',
                    'total_questions',
                    'duration_minutes',
                    'passing_score',
                    'status',
                ]);

            if ($quizzes->isEmpty()) {
                return $this->errorResponse('No published quizzes or mock exams available for this course', 404);
            }

            return $this->successResponse($quizzes, 'Quizzes fetched successfully');

        } catch (\Exception $e) {
            Log::error('Quiz list API error: ' . $e->getMessage(), ['course_slug' => $courseSlug]);
            return $this->errorResponse('An unexpected error occurred', 500);
        }
    }

    /**
     * GET /api/courses/{courseSlug}/quizzes/{quizSlug}
     * Get details of a specific published quiz for a course
     */
    public function show(string $courseSlug, string $quizSlug)
    {
        try {
            $course = Course::where('url_title', $courseSlug)
                ->where('status', 'Active')
                ->whereNull('deleted_at')
                ->first();

            if (!$course) {
                return $this->errorResponse('Course not found', 404);
            }

            $quiz = CourseQuiz::where('course_id', $course->id)
                ->where('slug', $quizSlug)
                ->published()
                ->first();

            if (!$quiz) {
                return $this->errorResponse('Quiz not found for this course', 404);
            }

            $courseName = trim($course->course_title ?: $course->course_short_name ?: '');

            $data = [
                'course' => [
                    'id' => $course->id,
                    'name' => $courseName,
                    'slug' => $course->url_title,
                ],
                'quiz' => [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'slug' => $quiz->slug,
                    'type' => $quiz->type,
                    'description' => $quiz->description,
                    'total_questions' => $quiz->total_questions,
                    'duration_minutes' => $quiz->duration_minutes,
                    'description' => $quiz->description,
                    'description' => $quiz->description,
                    'passing_score' => $quiz->passing_score,
                    'status' => $quiz->status,
                ],
            ];

            return $this->successResponse($data, 'Quiz details fetched successfully');

        } catch (\Exception $e) {
            Log::error('Quiz details API error: ' . $e->getMessage(), [
                'course_slug' => $courseSlug,
                'quiz_slug' => $quizSlug,
            ]);
            return $this->errorResponse('An unexpected error occurred', 500);
        }
    }

    /**
     * GET /api/courses/{courseSlug}/quizzes/{quizSlug}/questions
     * Get list of questions and options for a published quiz (answers/explanations safely excluded)
     */
    public function questions(Request $request, string $courseSlug, string $quizSlug)
    {
        try {
            $course = Course::where('url_title', $courseSlug)
                ->where('status', 'Active')
                ->whereNull('deleted_at')
                ->first();

            if (!$course) {
                return $this->errorResponse('Course not found', 404);
            }

            $quiz = CourseQuiz::where('course_id', $course->id)
                ->where('slug', $quizSlug)
                ->published()
                ->first();

            if (!$quiz) {
                return $this->errorResponse('Quiz not found for this course', 404);
            }

            $mode = $request->query('mode');
            $isPractice = ($mode === 'practice' || $quiz->type === 'practice_quiz');

            $questions = CourseQuizQuestion::where('quiz_id', $quiz->id)
                ->active()
                ->forMode($mode)
                ->with(['options' => function ($query) {
                    $query->orderBy('order_no', 'asc');
                }])
                ->orderBy('order_no', 'asc')
                ->get();

            $formattedQuestions = $questions->map(function ($q, $index) use ($isPractice) {
                $correctOpt = $q->options->where('is_correct', true)->first();

                return [
                    'id' => $q->id,
                    'order_no' => $q->order_no ?? ($index + 1),
                    'question_text' => $q->question_text,
                    'type' => $q->type ?? 'single_choice',
                    'image_url' => $q->image_url ? (str_starts_with($q->image_url, 'http') ? $q->image_url : asset($q->image_url)) : null,
                    'marks' => $q->marks ?? 1,
                    'case_study' => $q->case_study ?? null,
                    'explanation' => $q->explanation,
                    'correct_option_id' => $correctOpt?->id,
                    'correct_option_label' => $correctOpt?->option_label,
                    'options' => $q->options->map(function ($opt) use ($isPractice) {
                        $optItem = [
                            'id' => $opt->id,
                            'option_label' => $opt->option_label,
                            'option_text' => $opt->option_text,
                            'image_url' => $opt->image_url ? (str_starts_with($opt->image_url, 'http') ? $opt->image_url : asset($opt->image_url)) : null,
                        ];
                        $optItem['is_correct'] = (bool) $opt->is_correct;
                        return $optItem;
                    })->values(),
                ];
            })->values();

            $courseName = trim($course->course_title ?: $course->course_short_name ?: '');

            $data = [
                'course' => [
                    'id' => $course->id,
                    'name' => $courseName,
                    'slug' => $course->url_title,
                ],
                'quiz' => [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'slug' => $quiz->slug,
                    'type' => $quiz->type,
                    'total_questions' => $formattedQuestions->count(),
                    'duration_minutes' => $quiz->duration_minutes,
                    'description' => $quiz->description,
                ],
                'questions' => $formattedQuestions,
            ];

            return $this->successResponse($data, 'Questions fetched successfully');

        } catch (\Exception $e) {
            Log::error('Quiz questions API error: ' . $e->getMessage(), [
                'course_slug' => $courseSlug,
                'quiz_slug' => $quizSlug,
            ]);
            return $this->errorResponse('An unexpected error occurred', 500);
        }
    }

    /**
     * GET/POST /api/resource/quizzes
     * List all published quizzes across all courses, optionally filtered by course_category_id
     */
    public function allQuizzes(Request $request)
    {
        try {
            $categoryId = $request->input('course_category_id') ?: $request->input('category_id');
            $search = $request->input('search') ?: $request->input('filter');
            $perPage = (int) $request->input('per_page', 9);

            $query = CourseQuiz::published()
                ->whereHas('course', function ($q) use ($categoryId) {
                    $q->where('status', 'Active')->whereNull('deleted_at');
                    if (!empty($categoryId) && $categoryId != 0) {
                        $q->where(function ($sub) use ($categoryId) {
                            $sub->where('course_category_id', $categoryId)
                                ->orWhereHas('course_category', function ($cCat) use ($categoryId) {
                                    $cCat->where('main_category_id', $categoryId);
                                });
                        });
                    }
                })
                ->with(['course' => function ($q) {
                    $q->select('id', 'course_title', 'url_title', 'course_category_id')
                        ->with(['course_category:id,category_name,main_category_id']);
                }]);

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            $quizzes = $query->orderBy('id', 'desc')->paginate($perPage);

            $formatted = collect($quizzes->items())->map(function ($quiz) {
                return [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'slug' => $quiz->slug,
                    'type' => $quiz->type,
                    'description' => $quiz->description,
                    'total_questions' => $quiz->total_questions,
                    'duration_minutes' => $quiz->duration_minutes,
                    'passing_score' => $quiz->passing_score,
                    'course_id' => $quiz->course_id,
                    'course_title' => $quiz->course?->course_title,
                    'course_slug' => $quiz->course?->url_title,
                    'category_id' => $quiz->course?->course_category_id,
                    'category_name' => $quiz->course?->course_category?->category_name,
                    'parent_category_id' => $quiz->course?->course_category?->main_category_id,
                    'quiz_url' => "/course-quizzes/{$quiz->course?->url_title}/{$quiz->slug}",
                    'created_at' => $quiz->created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formatted,
                'pagination' => [
                    'current_page' => $quizzes->currentPage(),
                    'per_page' => $quizzes->perPage(),
                    'total' => $quizzes->total(),
                    'last_page' => $quizzes->lastPage(),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch quizzes',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

