<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

class ContentDocs
{
    #[OA\Post(
        path: '/api/dynamic-page',
        operationId: 'getDynamicPage',
        description: 'Get dynamic CMS page content by slug',
        summary: 'Get dynamic page content by slug',
        tags: ['Dynamic Pages & CMS'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['slug'],
                properties: [
                    new OA\Property(property: 'slug', type: 'string', example: 'about-us')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Page content retrieved')
        ]
    )]
    public function getDynamicPage() {}

    #[OA\Post(
        path: '/api/list/all-pages',
        operationId: 'getAllPages',
        description: 'Get list of all published pages',
        summary: 'Get list of all published pages',
        tags: ['Dynamic Pages & CMS'],
        responses: [
            new OA\Response(response: 200, description: 'Pages list retrieved')
        ]
    )]
    public function getAllPages() {}

    #[OA\Post(
        path: '/api/list/pages-detail',
        operationId: 'getPageDetail',
        description: 'Get full page details with sections by slug',
        summary: 'Get full page details with sections',
        tags: ['Dynamic Pages & CMS'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['slug'],
                properties: [
                    new OA\Property(property: 'slug', type: 'string', example: 'privacy-policy')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Page detail retrieved')
        ]
    )]
    public function getPageDetail() {}

    #[OA\Get(
        path: '/api/list/page/{slug}',
        operationId: 'getPageBySlug',
        description: 'Get an active dynamic page by slug passed in the URL',
        summary: 'Get dynamic page by slug',
        tags: ['Dynamic Pages & CMS'],
        parameters: [
            new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string'), example: 'about-us')
        ],
        responses: [
            new OA\Response(response: 200, description: 'Page detail retrieved'),
            new OA\Response(response: 404, description: 'Page not found')
        ]
    )]
    public function getPageBySlug() {}

    #[OA\Post(
        path: '/api/section-list',
        operationId: 'getSectionList',
        description: 'Get list of sections belonging to a page',
        summary: 'Get sections list for a page',
        tags: ['Dynamic Pages & CMS'],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'page_id', type: 'integer', example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Sections retrieved')
        ]
    )]
    public function getSectionList() {}

    #[OA\Post(
        path: '/api/section-view',
        operationId: 'viewSection',
        description: 'Get single section details and HTML blocks by ID',
        summary: 'Get single section details',
        tags: ['Dynamic Pages & CMS'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id'],
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Section details')
        ]
    )]
    public function viewSection() {}

    #[OA\Get(
        path: '/api/footer-disclamier',
        operationId: 'getFooterDisclaimer',
        description: 'Get footer disclaimer text, copyright and company branding info',
        summary: 'Get footer disclaimer content',
        tags: ['Dynamic Pages & CMS'],
        responses: [
            new OA\Response(response: 200, description: 'Footer content')
        ]
    )]
    public function getFooterDisclaimer() {}

    #[OA\Get(
        path: '/api/list/blog-types',
        operationId: 'getBlogTypes',
        description: 'Get all blog categories/knowledge center types',
        summary: 'Get all blog categories/types',
        tags: ['Blogs & Knowledge Center'],
        responses: [
            new OA\Response(response: 200, description: 'Blog types list')
        ]
    )]
    public function getBlogTypes() {}

    #[OA\Post(
        path: '/api/list/blogs',
        operationId: 'getAllBlogs',
        description: 'Get paginated list of blog articles',
        summary: 'Get list of blogs with pagination',
        tags: ['Blogs & Knowledge Center'],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'category_id', type: 'string', example: ''),
                    new OA\Property(property: 'page', type: 'integer', example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Blogs list')
        ]
    )]
    public function getAllBlogs() {}

    #[OA\Post(
        path: '/api/list/blogs-detail',
        operationId: 'getBlogDetail',
        description: 'Get full blog post article details by URL slug',
        summary: 'Get full blog post detail by slug',
        tags: ['Blogs & Knowledge Center'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['slug'],
                properties: [
                    new OA\Property(property: 'slug', type: 'string', example: 'how-to-prepare-for-pmp')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Blog detail')
        ]
    )]
    public function getBlogDetail() {}

    #[OA\Post(
        path: '/api/list/recent-posts',
        operationId: 'getRecentBlogs',
        description: 'Get recent 5 blog posts',
        summary: 'Get recent blog posts',
        tags: ['Blogs & Knowledge Center'],
        responses: [
            new OA\Response(response: 200, description: 'Recent blogs')
        ]
    )]
    public function getRecentBlogs() {}

    #[OA\Post(
        path: '/api/blog-search',
        operationId: 'searchBlogs',
        description: 'Search blogs matching keyword in title or content',
        summary: 'Search blogs by keyword',
        tags: ['Blogs & Knowledge Center'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['keyword'],
                properties: [
                    new OA\Property(property: 'keyword', type: 'string', example: 'scrum')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Search results')
        ]
    )]
    public function searchBlogs() {}

    #[OA\Post(
        path: '/api/testimonials/list',
        operationId: 'getTestimonialList',
        description: 'Get featured testimonials for homepage',
        summary: 'Get featured testimonials',
        tags: ['Testimonials & FAQs'],
        responses: [
            new OA\Response(response: 200, description: 'Testimonials list')
        ]
    )]
    public function getTestimonialList() {}

    #[OA\Post(
        path: '/api/testimonials/all-list',
        operationId: 'getAllTestimonials',
        description: 'Get all learner reviews and testimonials',
        summary: 'Get all testimonials',
        tags: ['Testimonials & FAQs'],
        responses: [
            new OA\Response(response: 200, description: 'All testimonials')
        ]
    )]
    public function getAllTestimonials() {}

    #[OA\Post(
        path: '/api/faq/list',
        operationId: 'getFaqList',
        description: 'Get all general Frequently Asked Questions',
        summary: 'Get all general FAQs',
        tags: ['Testimonials & FAQs'],
        responses: [
            new OA\Response(response: 200, description: 'FAQ list')
        ]
    )]
    public function getFaqList() {}
}
