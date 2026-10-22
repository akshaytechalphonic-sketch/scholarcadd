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
                ->with(['supportPages' => fn ($q) => $q->where(function ($subQ) use ($types, $type) {
                    $subQ->whereIn('type', $types)
                         ->orWhere('slug', $type);
                })->published()])
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
            $subSlug = $page->slug ?: $type;
            $pageUrl = $courseUrl . '/support/' . $subSlug;

            // Fetch other published support pages for this course if available
            $relatedPages = CourseSupportPage::where('course_id', $course->id)
                ->where('id', '!=', $page->id)
                ->published()
                ->get(['type', 'title', 'slug'])
                ->map(function ($rel) use ($courseUrl) {
                    $relSlug = $rel->slug ?: $rel->type;
                    return [
                        'type' => $rel->type,
                        'title' => $rel->title,
                        'url' => $courseUrl . '/support/' . $relSlug,
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
                    'image' => $page->image ? url($page->image) : null,
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

    /**
     * GET/POST /api/resource/support-pages
     * List all published support pages across all courses, optionally filtered by course_category_id
     */
    public function allSupportPages(\Illuminate\Http\Request $request)
    {
        try {
            $categoryId = $request->input('course_category_id') ?: $request->input('category_id');
            $type = $request->input('type');
            $search = $request->input('search') ?: $request->input('filter');
            $perPage = (int) $request->input('per_page', 9);

            $query = CourseSupportPage::published()
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

            if (!empty($type)) {
                $query->where('type', $type);
            }

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('type', 'like', "%{$search}%");
                });
            }

            $pages = $query->orderBy('id', 'desc')->paginate($perPage);

            $formatted = collect($pages->items())->map(function ($page) {
                $typeSlug = $page->type;
                $pageSlug = $page->slug ?: $typeSlug;
                $url = "/{$page->course?->url_title}/{$pageSlug}";
                return [
                    'id' => $page->id,
                    'title' => $page->title ?: ucfirst(str_replace('-', ' ', $page->type)),
                    'type' => $page->type,
                    'type_label' => ucfirst(str_replace('-', ' ', $page->type)),
                    'slug' => $page->slug,
                    'seo_description' => $page->seo_description,
                    'course_id' => $page->course_id,
                    'course_title' => $page->course?->course_title,
                    'course_slug' => $page->course?->url_title,
                    'category_id' => $page->course?->course_category_id,
                    'category_name' => $page->course?->course_category?->category_name,
                    'parent_category_id' => $page->course?->course_category?->main_category_id,
                    'page_url' => $url,
                    'created_at' => $page->created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formatted,
                'pagination' => [
                    'current_page' => $pages->currentPage(),
                    'per_page' => $pages->perPage(),
                    'total' => $pages->total(),
                    'last_page' => $pages->lastPage(),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch support pages',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

