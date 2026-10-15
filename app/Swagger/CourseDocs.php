<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

class CourseDocs
{
    #[OA\Post(
        path: '/api/course/course-list',
        operationId: 'getCourseList',
        description: 'Returns list of courses filtered optionally by country, category, etc.',
        summary: 'Get list of all active courses with pricing',
        tags: ['Courses'],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'country_id', description: 'Country ISO2 code (e.g. IN, US)', type: 'string', example: 'IN'),
                    new OA\Property(property: 'category_id', type: 'integer', example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of courses retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Data fetched successfully'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object'))
                    ]
                )
            )
        ]
    )]
    public function getCourseList() {}

    #[OA\Get(
        path: '/api/courses/{courseSlug}/support/syllabus',
        operationId: 'getCourseSyllabus',
        description: 'Returns the published syllabus support page of a course (found by course slug / url_title) with SEO and breadcrumb data',
        summary: 'Get course syllabus support page',
        tags: ['Courses'],
        parameters: [
            new OA\Parameter(name: 'courseSlug', in: 'path', required: true, schema: new OA\Schema(type: 'string'), example: 'itil-foundation')
        ],
        responses: [
            new OA\Response(response: 200, description: 'Syllabus fetched successfully'),
            new OA\Response(response: 404, description: 'Course not found / Syllabus not found for this course')
        ]
    )]
    public function getCourseSyllabus() {}

    #[OA\Get(
        path: '/api/courses/{courseSlug}/support/exam-format',
        operationId: 'getCourseExamFormat',
        description: 'Returns the published exam format support page of a course (found by course slug / url_title) with SEO, breadcrumb, and related pages data',
        summary: 'Get course exam format support page',
        tags: ['Courses'],
        parameters: [
            new OA\Parameter(name: 'courseSlug', in: 'path', required: true, schema: new OA\Schema(type: 'string'), example: 'project-management-professional-pmp')
        ],
        responses: [
            new OA\Response(response: 200, description: 'Exam format fetched successfully'),
            new OA\Response(response: 404, description: 'Course not found / Exam format not found for this course')
        ]
    )]
    public function getCourseExamFormat() {}

    #[OA\Get(
        path: '/api/courses/{courseSlug}/quizzes',
        operationId: 'getCourseQuizzes',
        description: 'Returns list of published quizzes and mock exams for a course (found by course slug)',
        summary: 'Get course quizzes and mock exams list',
        tags: ['Courses'],
        parameters: [
            new OA\Parameter(name: 'courseSlug', in: 'path', required: true, schema: new OA\Schema(type: 'string'), example: 'project-management-professional-pmp')
        ],
        responses: [
            new OA\Response(response: 200, description: 'Quizzes fetched successfully'),
            new OA\Response(response: 404, description: 'Course not found / No published quizzes or mock exams available for this course')
        ]
    )]
    public function getCourseQuizzes() {}

    #[OA\Get(
        path: '/api/courses/{courseSlug}/quizzes/{quizSlug}',
        operationId: 'getCourseQuizDetail',
        description: 'Returns complete details of a specific published quiz or mock exam for a course (found by courseSlug and quizSlug)',
        summary: 'Get single quiz / mock exam details',
        tags: ['Courses'],
        parameters: [
            new OA\Parameter(name: 'courseSlug', in: 'path', required: true, schema: new OA\Schema(type: 'string'), example: 'project-management-professional-pmp'),
            new OA\Parameter(name: 'quizSlug', in: 'path', required: true, schema: new OA\Schema(type: 'string'), example: 'pmp-full-mock-exam-1')
        ],
        responses: [
            new OA\Response(response: 200, description: 'Quiz details fetched successfully'),
            new OA\Response(response: 404, description: 'Course not found / Quiz not found for this course')
        ]
    )]
    public function getCourseQuizDetail() {}

    #[OA\Get(
        path: '/api/courses/{courseSlug}/quizzes/{quizSlug}/questions',
        operationId: 'getCourseQuizQuestions',
        description: 'Returns list of questions and choices for a specific published quiz (correct answers and explanations safely excluded)',
        summary: 'Get quiz questions and choices',
        tags: ['Courses'],
        parameters: [
            new OA\Parameter(name: 'courseSlug', in: 'path', required: true, schema: new OA\Schema(type: 'string'), example: 'project-management-professional-pmp'),
            new OA\Parameter(name: 'quizSlug', in: 'path', required: true, schema: new OA\Schema(type: 'string'), example: 'pmp-full-mock-exam-1')
        ],
        responses: [
            new OA\Response(response: 200, description: 'Questions fetched successfully'),
            new OA\Response(response: 404, description: 'Course not found / Quiz not found for this course')
        ]
    )]
    public function getCourseQuizQuestions() {}

    #[OA\Post(
        path: '/api/courses/{courseSlug}/quizzes/{quizSlug}/start',
        operationId: 'startQuizAttempt',
        description: 'Start or resume an exam simulation attempt',
        summary: 'Start or resume quiz attempt',
        tags: ['Courses'],
        parameters: [
            new OA\Parameter(name: 'courseSlug', in: 'path', required: true, schema: new OA\Schema(type: 'string'), example: 'project-management-professional-pmp'),
            new OA\Parameter(name: 'quizSlug', in: 'path', required: true, schema: new OA\Schema(type: 'string'), example: 'pmp-full-mock-exam-1')
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'mode', type: 'string', example: 'exam')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Attempt started / resumed successfully'),
            new OA\Response(response: 404, description: 'Course or Quiz not found')
        ]
    )]
    public function startQuizAttempt() {}

    #[OA\Post(
        path: '/api/quizzes/attempts/{attemptId}/save-answer',
        operationId: 'saveQuizAnswer',
        description: 'Save or update answer and flag status for a single question',
        summary: 'Save / update question answer',
        tags: ['Courses'],
        parameters: [
            new OA\Parameter(name: 'attemptId', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['question_id'],
                properties: [
                    new OA\Property(property: 'question_id', type: 'integer', example: 1),
                    new OA\Property(property: 'selected_option_id', type: 'integer', example: 2),
                    new OA\Property(property: 'is_flagged', type: 'boolean', example: false),
                    new OA\Property(property: 'time_spent_seconds', type: 'integer', example: 45)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Answer saved successfully')
        ]
    )]
    public function saveQuizAnswer() {}

    #[OA\Post(
        path: '/api/quizzes/attempts/{attemptId}/submit',
        operationId: 'submitQuizAttempt',
        description: 'Submit exam attempt, grade all responses and calculate score',
        summary: 'Submit exam attempt',
        tags: ['Courses'],
        parameters: [
            new OA\Parameter(name: 'attemptId', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)
        ],
        responses: [
            new OA\Response(response: 200, description: 'Exam submitted and graded successfully')
        ]
    )]
    public function submitQuizAttempt() {}

    #[OA\Get(
        path: '/api/quizzes/attempts/{attemptId}/result',
        operationId: 'getQuizResult',
        description: 'Get detailed score breakdown, pass/fail status, and question-by-question review with explanations',
        summary: 'Get exam attempt result and review',
        tags: ['Courses'],
        parameters: [
            new OA\Parameter(name: 'attemptId', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)
        ],
        responses: [
            new OA\Response(response: 200, description: 'Result and review fetched successfully')
        ]
    )]
    public function getQuizResult() {}

    #[OA\Post(
        path: '/api/course/view-course',
        operationId: 'viewCourse',
        description: 'Fetches full details of a course including curriculum, schedules, FAQs, trainers, overview, and pricing',
        summary: 'Get detailed course page by URL slug/title',
        tags: ['Courses'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['course_id'],
                properties: [
                    new OA\Property(property: 'course_id', type: 'integer', example: 8, description: 'Course ID'),
                    new OA\Property(property: 'country_id', type: 'string', example: 'IN', description: 'Country ISO2'),
                    new OA\Property(property: 'city_id', type: 'integer', example: 1, description: 'City ID')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Detailed course information retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'object')
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Course not found')
        ]
    )]
    public function viewCourse() {}

    #[OA\Post(
        path: '/api/course/category-and-course',
        operationId: 'getCategoryAndCourses',
        description: 'Returns grouped categories with active courses under each',
        summary: 'Get categories with associated courses',
        tags: ['Courses'],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'country_id', type: 'string', example: 'IN')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Categories and courses retrieved successfully')
        ]
    )]
    public function getCategoryAndCourses() {}

    #[OA\Post(
        path: '/api/all-courses',
        operationId: 'allCourses',
        description: 'Get list of all active courses',
        summary: 'Get all courses listing',
        tags: ['Courses'],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'country_id', type: 'string', example: 'IN')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'All courses retrieved successfully')
        ]
    )]
    public function allCourses() {}

    #[OA\Get(
        path: '/api/all-courses-content',
        operationId: 'allCoursesContent',
        description: 'Get full all-courses landing page dynamic content',
        summary: 'Get full all courses page content',
        tags: ['Courses'],
        responses: [
            new OA\Response(response: 200, description: 'Content retrieved successfully')
        ]
    )]
    public function allCoursesContent() {}

    #[OA\Post(
        path: '/api/categorised-course-list',
        operationId: 'categorisedCourseList',
        description: 'Get courses filtered by category and country',
        summary: 'Get categorised course list',
        tags: ['Courses'],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'category_id', type: 'integer', example: 1),
                    new OA\Property(property: 'country_id', type: 'string', example: 'IN')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Categorised course list retrieved successfully')
        ]
    )]
    public function categorisedCourseList() {}

    #[OA\Post(
        path: '/api/get-course-category/{name}',
        operationId: 'getCourseCategoryByName',
        description: 'Get category details and courses by category slug',
        summary: 'Get course category details by name',
        tags: ['Courses'],
        parameters: [
            new OA\Parameter(
                name: 'name',
                description: 'Category slug/name',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', example: 'project-management')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Course category fetched successfully')
        ]
    )]
    public function getCourseCategoryByName() {}

    #[OA\Post(
        path: '/api/course-plans',
        operationId: 'getCoursePlans',
        description: 'Get plans, batches and pricing mapped to a course',
        summary: 'Get plans and pricing for a course',
        tags: ['Courses'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['course_id'],
                properties: [
                    new OA\Property(property: 'course_id', type: 'integer', example: 1),
                    new OA\Property(property: 'country_id', type: 'string', example: 'IN')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Plans retrieved successfully')
        ]
    )]
    public function getCoursePlans() {}

    #[OA\Post(
        path: '/api/fetch-features',
        operationId: 'fetchFeatures',
        description: 'Fetch detailed features list of a plan',
        summary: 'Fetch features of a plan',
        tags: ['Courses'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['plan_id'],
                properties: [
                    new OA\Property(property: 'plan_id', type: 'integer', example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Plan features fetched successfully')
        ]
    )]
    public function fetchFeatures() {}
}
