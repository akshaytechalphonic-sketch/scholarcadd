<?php

namespace Modules\Course\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\Course\Models\Course;
use Modules\Course\Models\CourseQuiz;
use Modules\Course\Models\CourseQuizAttempt;
use Modules\Course\Models\CourseQuizAttemptAnswer;
use Modules\Course\Models\CourseQuizQuestion;
use Modules\Course\Models\CourseQuizQuestionOption;

class QuizAttemptController extends Controller
{
    use ApiResponseTrait;

    /**
     * Resolve currently authenticated user via JWT or session
     */
    protected function getAuthenticatedUser()
    {
        $user = null;
        try {
            if (class_exists(\Tymon\JWTAuth\Facades\JWTAuth::class)) {
                $user = \Tymon\JWTAuth\Facades\JWTAuth::parseToken()->authenticate();
            }
        } catch (\Exception $e) {
            // Token missing, expired, or invalid
        }

        if (!$user) {
            $user = Auth::guard('api')->user() ?? Auth::user();
        }

        return $user;
    }

    /**
     * POST /api/courses/{courseSlug}/quizzes/{quizSlug}/start
     * Start or resume an exam attempt (Strictly Requires Login)
     */
    public function start(Request $request, string $courseSlug, string $quizSlug)
    {
        try {
            $user = $this->getAuthenticatedUser();

            if (!$user) {
                return $this->errorResponse('Please login to start the exam simulation.', 401);
            }

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

            $mode = $request->input('mode', 'exam');

            // Check for existing in_progress attempt for this user
            $attempt = CourseQuizAttempt::where('quiz_id', $quiz->id)
                ->where('user_id', $user->id)
                ->where('status', CourseQuizAttempt::STATUS_IN_PROGRESS)
                ->latest()
                ->first();

            // If existing attempt has expired by duration time limit, mark as expired
            if ($attempt && $attempt->duration_minutes > 0 && $attempt->remaining_seconds <= 0) {
                $attempt->update(['status' => CourseQuizAttempt::STATUS_EXPIRED]);
                $attempt = null;
            }

            // If no active attempt, create a new one
            if (!$attempt) {
                $totalQuestions = CourseQuizQuestion::where('quiz_id', $quiz->id)->active()->forMode($mode)->count();

                $attempt = CourseQuizAttempt::create([
                    'quiz_id' => $quiz->id,
                    'user_id' => $user->id,
                    'mode' => $mode,
                    'status' => CourseQuizAttempt::STATUS_IN_PROGRESS,
                    'total_questions' => $totalQuestions,
                    'duration_minutes' => $quiz->duration_minutes,
                    'started_at' => now(),
                ]);
            }

            // Fetch saved answers for resuming state
            $savedAnswers = CourseQuizAttemptAnswer::where('attempt_id', $attempt->id)
                ->get()
                ->keyBy('question_id')
                ->map(function ($ans) {
                    return [
                        'question_id' => $ans->question_id,
                        'selected_option_id' => $ans->selected_option_id,
                        'is_flagged' => (bool) $ans->is_flagged,
                        'time_spent_seconds' => $ans->time_spent_seconds,
                    ];
                });

            $data = [
                'attempt_id' => $attempt->id,
                'quiz' => [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'slug' => $quiz->slug,
                    'type' => $quiz->type,
                    'passing_score' => $quiz->passing_score,
                ],
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'mode' => $attempt->mode,
                'status' => $attempt->status,
                'started_at' => $attempt->started_at,
                'duration_minutes' => $attempt->duration_minutes,
                'remaining_seconds' => $attempt->remaining_seconds,
                'total_questions' => $attempt->total_questions,
                'saved_answers' => $savedAnswers,
            ];

            return $this->successResponse($data, 'Attempt started successfully');

        } catch (\Exception $e) {
            Log::error('Start attempt error: ' . $e->getMessage());
            return $this->errorResponse('Failed to start attempt: ' . $e->getMessage(), 500);
        }
    }

    /**
     * POST /api/quizzes/attempts/{attemptId}/save-answer
     * Save/update selected answer and flag status for a question (Requires Login)
     */
    public function saveAnswer(Request $request, int $attemptId)
    {
        try {
            $user = $this->getAuthenticatedUser();

            if (!$user) {
                return $this->errorResponse('Please login to continue the exam simulation.', 401);
            }

            $validator = Validator::make($request->all(), [
                'question_id' => 'required|integer|exists:tbl_quiz_questions,id',
                'selected_option_id' => 'nullable|integer|exists:tbl_quiz_question_options,id',
                'is_flagged' => 'nullable|boolean',
                'time_spent_seconds' => 'nullable|integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $attempt = CourseQuizAttempt::find($attemptId);

            if (!$attempt) {
                return $this->errorResponse('Attempt not found', 404);
            }

            if ($attempt->user_id && $attempt->user_id !== $user->id) {
                return $this->errorResponse('Unauthorized access to this attempt', 403);
            }

            if ($attempt->status !== CourseQuizAttempt::STATUS_IN_PROGRESS) {
                return $this->errorResponse('Attempt is no longer in progress', 400);
            }

            $questionId = $request->input('question_id');
            $selectedOptionId = $request->input('selected_option_id');

            // Verify option belongs to question if option is provided
            if ($selectedOptionId) {
                $option = CourseQuizQuestionOption::where('id', $selectedOptionId)
                    ->where('question_id', $questionId)
                    ->first();

                if (!$option) {
                    return $this->errorResponse('Invalid option for this question', 422);
                }
            }

            $updateData = [];
            if ($request->has('selected_option_id')) {
                $updateData['selected_option_id'] = $selectedOptionId;
            }
            if ($request->has('is_flagged')) {
                $updateData['is_flagged'] = $request->boolean('is_flagged');
            }
            if ($request->has('time_spent_seconds')) {
                $updateData['time_spent_seconds'] = $request->input('time_spent_seconds');
            }

            $answer = CourseQuizAttemptAnswer::updateOrCreate(
                ['attempt_id' => $attempt->id, 'question_id' => $questionId],
                $updateData
            );

            // Update attempted count on attempt
            $attemptedCount = CourseQuizAttemptAnswer::where('attempt_id', $attempt->id)
                ->whereNotNull('selected_option_id')
                ->count();
            $attempt->update(['attempted_count' => $attemptedCount]);

            return $this->successResponse([
                'attempt_id' => $attempt->id,
                'question_id' => $answer->question_id,
                'selected_option_id' => $answer->selected_option_id,
                'is_flagged' => (bool) $answer->is_flagged,
                'attempted_count' => $attemptedCount,
                'total_questions' => $attempt->total_questions,
            ], 'Answer saved successfully');

        } catch (\Exception $e) {
            Log::error('Save answer error: ' . $e->getMessage());
            return $this->errorResponse('Failed to save answer', 500);
        }
    }

    /**
     * POST /api/quizzes/attempts/{attemptId}/submit
     * Submit exam attempt, grade all answers, calculate score and percentage (Requires Login)
     */
    public function submit(Request $request, int $attemptId)
    {
        try {
            $user = $this->getAuthenticatedUser();

            if (!$user) {
                return $this->errorResponse('Please login to submit the exam simulation.', 401);
            }

            $attempt = CourseQuizAttempt::with(['quiz'])->find($attemptId);

            if (!$attempt) {
                return $this->errorResponse('Attempt not found', 404);
            }

            if ($attempt->user_id && $attempt->user_id !== $user->id) {
                return $this->errorResponse('Unauthorized access to this attempt', 403);
            }

            if ($attempt->status === CourseQuizAttempt::STATUS_COMPLETED) {
                return $this->errorResponse('Attempt has already been submitted', 400);
            }

            // Save any bulk final answers passed in submit payload
            if ($request->has('answers') && is_array($request->input('answers'))) {
                foreach ($request->input('answers') as $ans) {
                    if (isset($ans['question_id'])) {
                        CourseQuizAttemptAnswer::updateOrCreate(
                            ['attempt_id' => $attempt->id, 'question_id' => $ans['question_id']],
                            [
                                'selected_option_id' => $ans['selected_option_id'] ?? null,
                                'is_flagged' => isset($ans['is_flagged']) ? (bool) $ans['is_flagged'] : false,
                            ]
                        );
                    }
                }
            }

            // Fetch all active questions and their correct options
            $questions = CourseQuizQuestion::where('quiz_id', $attempt->quiz_id)
                ->active()
                ->with(['options'])
                ->get();

            $totalQuestions = $questions->count();
            $correctCount = 0;
            $incorrectCount = 0;
            $attemptedCount = 0;

            foreach ($questions as $q) {
                $correctOption = $q->options->where('is_correct', true)->first();
                $userAnswer = CourseQuizAttemptAnswer::where('attempt_id', $attempt->id)
                    ->where('question_id', $q->id)
                    ->first();

                $isCorrect = false;
                if ($userAnswer && $userAnswer->selected_option_id) {
                    $attemptedCount++;
                    if ($correctOption && $userAnswer->selected_option_id == $correctOption->id) {
                        $isCorrect = true;
                        $correctCount++;
                    } else {
                        $incorrectCount++;
                    }

                    $userAnswer->update(['is_correct' => $isCorrect]);
                }
            }

            $unansweredCount = max(0, $totalQuestions - $attemptedCount);
            $scorePercentage = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0;
            $passingScore = $attempt->quiz?->passing_score ?? 70;
            $isPassed = $scorePercentage >= $passingScore;

            $attempt->update([
                'status' => CourseQuizAttempt::STATUS_COMPLETED,
                'completed_at' => now(),
                'total_questions' => $totalQuestions,
                'attempted_count' => $attemptedCount,
                'correct_count' => $correctCount,
                'incorrect_count' => $incorrectCount,
                'unanswered_count' => $unansweredCount,
                'score_percentage' => $scorePercentage,
                'is_passed' => $isPassed,
            ]);

            $data = [
                'attempt_id' => $attempt->id,
                'status' => CourseQuizAttempt::STATUS_COMPLETED,
                'total_questions' => $totalQuestions,
                'attempted_count' => $attemptedCount,
                'correct_count' => $correctCount,
                'incorrect_count' => $incorrectCount,
                'unanswered_count' => $unansweredCount,
                'score_percentage' => $scorePercentage,
                'passing_score' => $passingScore,
                'is_passed' => $isPassed,
                'result_status' => $isPassed ? 'PASSED' : 'FAILED',
                'started_at' => $attempt->started_at,
                'completed_at' => $attempt->completed_at,
            ];

            return $this->successResponse($data, 'Exam submitted and graded successfully');

        } catch (\Exception $e) {
            Log::error('Submit attempt error: ' . $e->getMessage());
            return $this->errorResponse('Failed to submit exam: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/quizzes/attempts/{attemptId}/result
     * Get detailed result breakdown and review with explanations (Requires Login)
     */
    public function result(int $attemptId)
    {
        try {
            $user = $this->getAuthenticatedUser();

            if (!$user) {
                return $this->errorResponse('Please login to view the exam result.', 401);
            }

            $attempt = CourseQuizAttempt::with(['quiz.course', 'answers'])->find($attemptId);

            if (!$attempt) {
                return $this->errorResponse('Attempt not found', 404);
            }

            if ($attempt->user_id && $attempt->user_id !== $user->id) {
                return $this->errorResponse('Unauthorized access to this result', 403);
            }

            $quiz = $attempt->quiz;
            $course = $quiz?->course;

            // Fetch questions, options, and user answers
            $questions = CourseQuizQuestion::where('quiz_id', $attempt->quiz_id)
                ->active()
                ->with(['options'])
                ->orderBy('order_no', 'asc')
                ->get();

            $userAnswers = $attempt->answers->keyBy('question_id');

            $reviewList = $questions->map(function ($q, $index) use ($userAnswers) {
                $ans = $userAnswers->get($q->id);
                $selectedOptionId = $ans?->selected_option_id;
                $correctOption = $q->options->where('is_correct', true)->first();

                return [
                    'id' => $q->id,
                    'order_no' => $q->order_no ?? ($index + 1),
                    'question_text' => $q->question_text,
                    'type' => $q->type,
                    'image_url' => $q->image_url ? asset($q->image_url) : null,
                    'marks' => $q->marks,
                    'selected_option_id' => $selectedOptionId,
                    'correct_option_id' => $correctOption?->id,
                    'is_correct' => (bool) ($ans?->is_correct ?? false),
                    'is_flagged' => (bool) ($ans?->is_flagged ?? false),
                    'explanation' => $q->explanation,
                    'options' => $q->options->map(function ($opt) use ($selectedOptionId, $correctOption) {
                        return [
                            'id' => $opt->id,
                            'option_label' => $opt->option_label,
                            'option_text' => $opt->option_text,
                            'image_url' => $opt->image_url ? asset($opt->image_url) : null,
                            'is_correct' => (bool) $opt->is_correct,
                            'is_selected' => $opt->id === $selectedOptionId,
                        ];
                    })->values(),
                ];
            })->values();

            $data = [
                'attempt' => [
                    'id' => $attempt->id,
                    'mode' => $attempt->mode,
                    'status' => $attempt->status,
                    'total_questions' => $attempt->total_questions,
                    'attempted_count' => $attempt->attempted_count,
                    'correct_count' => $attempt->correct_count,
                    'incorrect_count' => $attempt->incorrect_count,
                    'unanswered_count' => $attempt->unanswered_count,
                    'score_percentage' => $attempt->score_percentage,
                    'passing_score' => $quiz?->passing_score ?? 70,
                    'is_passed' => (bool) $attempt->is_passed,
                    'result_status' => $attempt->is_passed ? 'PASSED' : 'FAILED',
                    'started_at' => $attempt->started_at,
                    'completed_at' => $attempt->completed_at,
                ],
                'course' => [
                    'id' => $course?->id,
                    'name' => $course ? trim($course->course_title ?: $course->course_short_name) : null,
                    'slug' => $course?->url_title,
                ],
                'quiz' => [
                    'id' => $quiz?->id,
                    'title' => $quiz?->title,
                    'slug' => $quiz?->slug,
                    'type' => $quiz?->type,
                ],
                'review' => $reviewList,
            ];

            return $this->successResponse($data, 'Result fetched successfully');

        } catch (\Exception $e) {
            Log::error('Quiz result API error: ' . $e->getMessage());
            return $this->errorResponse('Failed to fetch result', 500);
        }
    }
}
