<?php

namespace Modules\Course\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Log;
use Modules\Course\Models\Course;
use Modules\Course\Models\CourseSupportPage;

class CourseSupportPageController extends Controller
{
    use ApiResponseTrait;

    /**
     * GET /api/courses/{courseSlug}/support/syllabus
     */
    public function syllabus(string $courseSlug)
    {
        return $this->getSupportPageResponse($courseSlug, CourseSupportPage::TYPE_SYLLABUS, 'Syllabus');
    }

    /**
     * GET /api/courses/{courseSlug}/support/exam-format
     */
    public function examFormat(string $courseSlug)
    {
        return $this->getSupportPageResponse($courseSlug, CourseSupportPage::TYPE_EXAM_FORMAT, 'Exam format');
    }

    /**
     * GET /api/courses/{courseSlug}/support/exam or /api/courses/{courseSlug}/exam
     */
    public function exam(string $courseSlug)
    {
        return $this->getSupportPageResponse($courseSlug, CourseSupportPage::TYPE_EXAM_FORMAT, 'Exam format');
    }

    /**
     * GET /api/courses/{courseSlug}/support/{pageType}
     * Supports all Scope 5.8 types: eligibility, study-plan, corporate-training, practice-test, faqs, certification-benefits, certification-guide, notes, etc.
     */
    public function showPage(string $courseSlug, string $pageType)
    {
        return $this->getSupportPageResponse($courseSlug, $pageType, ucfirst(str_replace('-', ' ', $pageType)));
    }

    /**
     * Shared helper to fetch and format dynamic support pages
     */
    protected function getSupportPageResponse(string $courseSlug, string $type, string $displayName)
    {
        try {
            $types = ($type === CourseSupportPage::TYPE_EXAM_FORMAT || $type === 'exam')
                ? [CourseSupportPage::TYPE_EXAM_FORMAT, 'exam']
                : [$type];

            $course = Course::where('url_title', $courseSlug)
                ->where('status', 'Active')
                ->whereNull('deleted_at')
                ->with(['supportPages' => fn ($q) => $q->whereIn('type', $types)->published()])
                ->first();

            if (!$course) {
                return $this->errorResponse('Course not found', 404);
            }

            $page = $course->supportPages->first();

            if (!$page) {
                return $this->errorResponse("$displayName not found for this course", 404);
            }

            $courseName = trim($course->course_title ?: $course->course_short_name ?: '');
            $frontendUrl = rtrim((string) config('app.frontend_url'), '/');
            $courseUrl = $frontendUrl . '/courses/' . $course->url_title;
            $pageUrl = $courseUrl . '/support/' . $type;

            // Fetch other published support pages for this course if available
            $relatedPages = CourseSupportPage::where('course_id', $course->id)
                ->where('id', '!=', $page->id)
                ->published()
                ->get(['type', 'title'])
                ->map(function ($rel) use ($courseUrl) {
                    return [
                        'type' => $rel->type,
                        'title' => $rel->title,
                        'url' => $courseUrl . '/support/' . $rel->type,
                    ];
                });

            $data = [
                'course' => [
                    'id' => $course->id,
                    'name' => $courseName,
                    'slug' => $course->url_title,
                ],
                'support_page' => [
                    'type' => $page->type,
                    'title' => $page->title,
                    'content' => $page->content,
                    'status' => $page->status,
                ],
                'seo' => [
                    'title' => $page->seo_title ?: $page->title,
                    'description' => $page->seo_description ?: $course->meta_description,
                    'canonical' => $page->canonical_url ?: $pageUrl,
                    'indexable' => (bool) $page->is_indexable,
                ],
                'breadcrumb' => [
                    ['position' => 1, 'name' => 'Home', 'url' => $frontendUrl . '/'],
                    ['position' => 2, 'name' => $courseName, 'url' => $courseUrl],
                    ['position' => 3, 'name' => $page->title, 'url' => $pageUrl],
                ],
            ];

            if ($relatedPages->isNotEmpty()) {
                $data['related_support_pages'] = $relatedPages;
            }

            return $this->successResponse($data, "$displayName fetched successfully");

        } catch (\Exception $e) {
            Log::error("$displayName API error: " . $e->getMessage(), ['course_slug' => $courseSlug]);
            return $this->errorResponse('An unexpected error occurred', 500);
        }
    }
}
