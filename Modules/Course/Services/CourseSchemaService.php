<?php

namespace Modules\Course\Services;

use Illuminate\Support\Collection;
use Modules\Course\Models\Course;
use Modules\Course\Models\CourseCountry;
use Modules\Course\Models\CourseCurriculumFaq;
use Modules\Course\Models\CourseImages;
use Modules\Course\Models\CourseOverviews;
use Modules\Course\Models\CourseHighlights;
use Modules\Course\Models\CourseSchedule;
use Modules\Course\Models\DynamicCourseHeadline;
use Modules\Course\Models\DynamicCoursePages;
use Modules\DynamicMaster\Models\Plan;
use Modules\DynamicMaster\Models\PlanMapping;
use Modules\FAQS\Models\CourseFaqs;

class CourseSchemaService
{
    /**
     * Resolve frontend base URL cleanly (always production domain for canonical/schema URLs).
     */
    public static function getFrontendBaseUrl(): string
    {
        $base = rtrim((string) (config('app.frontend_url') ?: env('FRONTEND_URL', 'https://scholaracad.com')), '/');
        if (
            empty($base) ||
            str_contains($base, 'localhost') ||
            str_contains($base, '127.0.0.1') ||
            str_contains($base, 'yourserver.com') ||
            str_contains($base, 'vercel.app')
        ) {
            return 'https://scholaracad.com';
        }
        return $base;
    }

    /**
     * Generate unified dynamic JSON-LD Schema (@graph) for any Course.
     * Automatically works for all existing and newly added courses.
     */
    public static function generateForCourse(
        Course $course,
        ?CourseCountry $country = null,
        string $locationName = '',
        array $dynamicData = []
    ): array {
        $baseUrl = self::getFrontendBaseUrl();
        $courseSlug = trim((string) $course->url_title);
        $countryIso = strtolower(trim((string) ($country?->iso2 ?? '')));

        $countryPrefix = ($countryIso && $countryIso !== 'in') ? ($countryIso . '/') : '';
        $courseUrl = $baseUrl . '/' . $countryPrefix . $courseSlug;

        // 1. Resolve Course Title & Description dynamically
        $headlineObj = $dynamicData['dynamicCourseHeading'] ?? null;
        if (!$headlineObj) {
            try {
                $headlineObj = DynamicCourseHeadline::where('course_id', $course->id)
                    ->when($country?->id, fn($q) => $q->where('country_id', $country->id))
                    ->first();
            } catch (\Throwable $e) {
                $headlineObj = null;
            }
        }

        $rawTitle = $headlineObj?->meta_title
            ?: $headlineObj?->page_title
            ?: $course->page_title
            ?: $course->course_short_name
            ?: $course->course_title
            ?: 'Certification Training Course';

        $highlightObj = $dynamicData['bulletPoints'] ?? null;
        $overviewObj = $dynamicData['courseOverview'] ?? null;

        $rawDescription = $headlineObj?->metaDescription
            ?: $headlineObj?->meta_description
            ?: $course->meta_description
            ?: $course->short_description
            ?: $highlightObj?->course_description
            ?: $overviewObj?->course_overview
            ?: $course->overview
            ?: ($rawTitle . ' Certification Training by ScholarAcad.');

        $title = self::cleanText($rawTitle, $locationName);
        $description = self::cleanText($rawDescription, $locationName);

        // 2. Resolve Image URL
        $imageObj = $dynamicData['courseImage'] ?? null;
        $rawImage = $imageObj?->banner_image_path ?: $course->header_image ?: null;
        $imageUrl = $rawImage
            ? (str_starts_with($rawImage, 'http') ? $rawImage : asset($rawImage))
            : ($baseUrl . '/assets/image/logo/scholaracad_logo.svg');

        // 3. Resolve Ratings dynamically (with safe defaults for newly created courses)
        $ratingRecord = null;
        if (!empty($dynamicData['ratings'])) {
            $rData = $dynamicData['ratings'];
            $ratingRecord = ($rData instanceof Collection) ? $rData->first() : (is_array($rData) ? ($rData[0] ?? null) : $rData);
        }
        if (!$ratingRecord) {
            try {
                $ratingRecord = DynamicCoursePages::where('course_id', $course->id)
                    ->when($country?->id, fn($q) => $q->where('country_id', $country->id))
                    ->first()
                    ?: DynamicCoursePages::where('course_id', $course->id)->first();
            } catch (\Throwable $e) {
                $ratingRecord = null;
            }
        }

        $ratingValue = self::parseRatingValue($ratingRecord?->googleRating ?? '4.9/5');
        $ratingCount = self::parseEnrolledCount($ratingRecord?->enrolled ?? '6,000 + Learners Enrolled');

        // 4. Resolve Pricing & Currency dynamically
        $currency = $country?->currency ?: 'USD';
        $multiplier = (float) ($country?->value_in_dollar ?: 1);
        $baseFee = null;

        try {
            $basicPlan = Plan::where('name', 'Basic plan')->first();
            $mapping = PlanMapping::where('course_id', $course->id)
                ->when($basicPlan, fn($q) => $q->where('plan_id', $basicPlan->id))
                ->first()
                ?: PlanMapping::where('course_id', $course->id)->first();
            $baseFee = $mapping?->course_fee;
        } catch (\Throwable $e) {
            $baseFee = null;
        }

        $finalPrice = $baseFee ? round(((float) $baseFee) * $multiplier, 2) : 499;

        // 5. Resolve Course Instances (Schedules or default Online/Classroom instance)
        $courseInstances = [];
        $schedules = $dynamicData['courseSchedule'] ?? null;
        if ($schedules && ($schedules instanceof Collection || is_array($schedules)) && count($schedules) > 0) {
            foreach (collect($schedules)->take(3) as $sched) {
                $schedObj = (object) $sched;
                $instance = [
                    '@type' => 'CourseInstance',
                    'courseMode' => !empty($schedObj->training_mode) ? strtolower((string) $schedObj->training_mode) : 'online',
                    'courseWorkload' => 'PT36H',
                ];
                if (!empty($schedObj->class_start_date)) {
                    $instance['startDate'] = date('Y-m-d', strtotime((string) $schedObj->class_start_date));
                }
                if (!empty($schedObj->class_end_date)) {
                    $instance['endDate'] = date('Y-m-d', strtotime((string) $schedObj->class_end_date));
                }
                $courseInstances[] = $instance;
            }
        }

        if (empty($courseInstances)) {
            $courseInstances[] = [
                '@type' => 'CourseInstance',
                'courseMode' => ['online', 'onsite'],
                'courseWorkload' => 'PT36H',
            ];
        }

        // 6. Build Organization Node
        $organizationNode = [
            '@type' => 'EducationalOrganization',
            '@id' => $baseUrl . '/#organization',
            'name' => 'ScholarAcad',
            'url' => $baseUrl,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => 'https://knowledgeadmin.scholaracad.com/assets/image/logo/scholaracad_logo.svg',
            ],
        ];

        // 7. Build Course Node
        $courseNode = [
            '@type' => 'Course',
            '@id' => $courseUrl . '#course',
            'name' => $title,
            'description' => $description,
            'url' => $courseUrl,
            'courseCode' => $course->course_title ?: $course->url_title,
            'image' => [$imageUrl],
            'provider' => [
                '@id' => $baseUrl . '/#organization',
            ],
            'inLanguage' => 'en',
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => (string) $ratingValue,
                'bestRating' => '5',
                'worstRating' => '1',
                'ratingCount' => (string) $ratingCount,
            ],
            'offers' => [
                '@type' => 'Offer',
                'category' => 'Paid',
                'priceCurrency' => $currency,
                'price' => (string) $finalPrice,
                'availability' => 'https://schema.org/InStock',
                'url' => $courseUrl,
                'validFrom' => date('Y-01-01'),
            ],
            'hasCourseInstance' => $courseInstances,
        ];

        if (!empty($course->meta_keywords)) {
            $courseNode['keywords'] = self::cleanText($course->meta_keywords, $locationName);
        }

        // 8. Build BreadcrumbList Node
        $breadcrumbItems = [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => $baseUrl . '/',
            ],
        ];

        $catName = $course->course_category?->category_name ?? null;
        $catSlug = $course->course_category?->page_link ?? null;
        if ($catName) {
            $breadcrumbItems[] = [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $catName,
                'item' => $baseUrl . '/' . ($catSlug ?: 'courses'),
            ];
            $breadcrumbItems[] = [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => self::cleanText($course->course_short_name ?: $course->course_title ?: $title, $locationName),
                'item' => $courseUrl,
            ];
        } else {
            $breadcrumbItems[] = [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => self::cleanText($course->course_short_name ?: $course->course_title ?: $title, $locationName),
                'item' => $courseUrl,
            ];
        }

        $breadcrumbNode = [
            '@type' => 'BreadcrumbList',
            '@id' => $courseUrl . '#breadcrumb',
            'itemListElement' => $breadcrumbItems,
        ];

        $graph = [
            $organizationNode,
            $courseNode,
            $breadcrumbNode,
        ];

        // 9. Build FAQPage Node dynamically if FAQs exist for this course
        $faqEntities = self::extractFaqEntities($course->id, $dynamicData, $locationName);
        if (!empty($faqEntities)) {
            $graph[] = [
                '@type' => 'FAQPage',
                '@id' => $courseUrl . '#faq',
                'mainEntity' => $faqEntities,
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];
    }

    /**
     * Generate schema directly from DynamicPages response data
     */
    public static function generateFromDynamicPageData(
        $courseId,
        array $responseData,
        ?string $countryIso = null,
        string $countryName = '',
        string $cityName = ''
    ): ?array {
        try {
            if (!$courseId) {
                return null;
            }
            $course = Course::with('course_category')->find($courseId);
            if (!$course) {
                return null;
            }
            $country = $countryIso
                ? CourseCountry::where('iso2', strtoupper($countryIso))->first()
                : null;
            $locationName = $cityName ?: $countryName ?: ($country?->name ?? '');

            return self::generateForCourse($course, $country, $locationName, $responseData);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Generate schema for a Course Support Page (Syllabus, Exam Format, Notes, etc.)
     */
    public static function generateForSupportPage(
        Course $course,
        $supportPage,
        array $seo,
        array $breadcrumbs
    ): array {
        $baseSchema = self::generateForCourse($course);
        $canonicalUrl = $seo['canonical'] ?? (self::getFrontendBaseUrl() . '/' . $course->url_title . '/' . ($supportPage->slug ?: $supportPage->type));

        $breadcrumbItems = [];
        foreach ($breadcrumbs as $b) {
            $breadcrumbItems[] = [
                '@type' => 'ListItem',
                'position' => $b['position'],
                'name' => $b['name'],
                'item' => $b['url'],
            ];
        }

        // Update BreadcrumbList and add WebPage node
        foreach ($baseSchema['@graph'] as &$node) {
            if (($node['@type'] ?? '') === 'BreadcrumbList' && !empty($breadcrumbItems)) {
                $node['@id'] = $canonicalUrl . '#breadcrumb';
                $node['itemListElement'] = $breadcrumbItems;
            }
        }
        unset($node);

        $baseSchema['@graph'][] = [
            '@type' => 'WebPage',
            '@id' => $canonicalUrl . '#webpage',
            'url' => $canonicalUrl,
            'name' => self::cleanText($seo['title'] ?? $supportPage->title ?? ''),
            'description' => self::cleanText($seo['description'] ?? $course->meta_description ?? ''),
            'about' => [
                '@id' => self::getFrontendBaseUrl() . '/' . $course->url_title . '#course',
            ],
        ];

        return $baseSchema;
    }

    /**
     * Extract FAQ entities from CourseFaqs and CourseCurriculumFaq
     */
    protected static function extractFaqEntities(int $courseId, array $dynamicData, string $locationName = ''): array
    {
        $entities = [];
        $seenQuestions = [];

        $courseFaqs = $dynamicData['CourseFaqs'] ?? null;
        if ($courseFaqs === null) {
            try {
                $courseFaqs = CourseFaqs::where('course_id', $courseId)->limit(10)->get();
            } catch (\Throwable $e) {
                $courseFaqs = collect();
            }
        }

        foreach (collect($courseFaqs) as $faq) {
            $fObj = (object) $faq;
            $q = self::cleanText($fObj->question ?? $fObj->faq_question ?? $fObj->title ?? '', $locationName);
            $a = self::cleanText($fObj->answer ?? $fObj->faq_answer ?? $fObj->description ?? '', $locationName);
            if ($q && $a && !isset($seenQuestions[strtolower($q)])) {
                $seenQuestions[strtolower($q)] = true;
                $entities[] = [
                    '@type' => 'Question',
                    'name' => $q,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $a,
                    ],
                ];
            }
        }

        if (empty($entities)) {
            $currFaqs = $dynamicData['courseCurriculumFAQ'] ?? null;
            if ($currFaqs === null) {
                try {
                    $currFaqs = CourseCurriculumFaq::where('course_id', $courseId)->limit(8)->get();
                } catch (\Throwable $e) {
                    $currFaqs = collect();
                }
            }
            foreach (collect($currFaqs) as $faq) {
                $fObj = (object) $faq;
                $q = self::cleanText($fObj->title ?? '', $locationName);
                $a = self::cleanText($fObj->description ?? '', $locationName);
                if ($q && $a && !isset($seenQuestions[strtolower($q)])) {
                    $seenQuestions[strtolower($q)] = true;
                    $entities[] = [
                        '@type' => 'Question',
                        'name' => $q,
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $a,
                        ],
                    ];
                }
            }
        }

        return array_slice($entities, 0, 10);
    }

    /**
     * Clean HTML, entities, and replace dynamic location placeholders
     */
    protected static function cleanText(?string $text, string $locationName = ''): string
    {
        if (empty($text)) {
            return '';
        }
        $clean = stripslashes($text);
        $clean = html_entity_decode($clean, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $clean = strip_tags($clean);

        if (!empty($locationName)) {
            $clean = str_replace(['{{country}}', '{{city}}', '<Dynamic>'], $locationName, $clean);
        } else {
            $clean = str_replace([' in {{country}}', ' in {{city}}', ' in <Dynamic>', '{{country}}', '{{city}}', '<Dynamic>'], '', $clean);
        }

        $clean = preg_replace('/\s+/u', ' ', $clean);
        return trim($clean);
    }

    /**
     * Parse rating string like "4.9/5" into float 4.9
     */
    protected static function parseRatingValue(?string $raw): float
    {
        if (empty($raw)) {
            return 4.9;
        }
        if (preg_match('/(\d+(?:\.\d+)?)/', (string) $raw, $m)) {
            $val = (float) $m[1];
            if ($val >= 1.0 && $val <= 5.0) {
                return $val;
            }
        }
        return 4.9;
    }

    /**
     * Parse enrolled string like "6,000 + Learners Enrolled" into integer count
     */
    protected static function parseEnrolledCount(?string $raw): int
    {
        if (empty($raw)) {
            return 1250;
        }
        $cleaned = str_replace(',', '', (string) $raw);
        if (preg_match('/(\d+)/', $cleaned, $m)) {
            $val = (int) $m[1];
            if ($val > 0) {
                return $val;
            }
        }
        return 1250;
    }
}

