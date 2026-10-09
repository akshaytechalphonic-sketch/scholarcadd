<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

class MasterDocs
{
    #[OA\Get(
        path: '/api/course-master/category-list',
        operationId: 'getCourseMasterCategories',
        description: 'Get list of course categories from course master',
        summary: 'Get list of course categories',
        tags: ['Masters & Locations'],
        responses: [
            new OA\Response(response: 200, description: 'Categories list retrieved')
        ]
    )]
    public function getCourseMasterCategories() {}

    #[OA\Post(
        path: '/api/course-master/category-show',
        operationId: 'showCourseMasterCategory',
        description: 'Show single course master category details',
        summary: 'Show single course master category',
        tags: ['Masters & Locations'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id'],
                properties: [
                    new OA\Property(property: 'id', description: 'Encrypted category ID', type: 'string', example: 'eyJpdiI6...')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Category details retrieved')
        ]
    )]
    public function showCourseMasterCategory() {}

    #[OA\Post(
        path: '/api/dn-master/parent-list',
        operationId: 'getDynamicMasterParentList',
        description: 'Get dynamic master parent list by master_name (e.g. Course Category, Knowledge Center Category)',
        summary: 'Get Dynamic Master Parent List by master name',
        tags: ['Masters & Locations'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['master_name'],
                properties: [
                    new OA\Property(property: 'master_name', type: 'string', example: 'Course Category')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Master list fetched')
        ]
    )]
    public function getDynamicMasterParentList() {}

    #[OA\Post(
        path: '/api/dn-master/parent-show',
        operationId: 'getDynamicMasterParentShow',
        description: 'Get single Dynamic Master Parent item by encrypted ID',
        summary: 'Get single Dynamic Master Parent item',
        tags: ['Masters & Locations'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id'],
                properties: [
                    new OA\Property(property: 'id', description: 'Encrypted ID', type: 'string', example: 'eyJpdiI6...')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Master item details')
        ]
    )]
    public function getDynamicMasterParentShow() {}

    #[OA\Get(
        path: '/api/dn-child-master/category-list',
        operationId: 'getChildMasterCategoryList',
        description: 'Get child master category list',
        summary: 'Get child master category list',
        tags: ['Masters & Locations'],
        responses: [
            new OA\Response(response: 200, description: 'Child master list fetched')
        ]
    )]
    public function getChildMasterCategoryList() {}

    #[OA\Post(
        path: '/api/dn-child-master/category-show',
        operationId: 'showChildMasterCategory',
        description: 'Show single child master category',
        summary: 'Show single child master category',
        tags: ['Masters & Locations'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id'],
                properties: [
                    new OA\Property(property: 'id', type: 'string', example: 'eyJpdiI6...')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Child category details')
        ]
    )]
    public function showChildMasterCategory() {}

    #[OA\Get(
        path: '/api/address/country-list',
        operationId: 'getCountryList',
        description: 'Get list of all supported countries with currencies and dial codes',
        summary: 'Get list of countries',
        tags: ['Masters & Locations'],
        responses: [
            new OA\Response(response: 200, description: 'List of countries')
        ]
    )]
    public function getCountryList() {}

    #[OA\Post(
        path: '/api/address/country-show',
        operationId: 'showCountry',
        description: 'Show single country details by ID',
        summary: 'Show country details by ID',
        tags: ['Masters & Locations'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id'],
                properties: [
                    new OA\Property(property: 'id', type: 'string', example: '1')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Country details')
        ]
    )]
    public function showCountry() {}

    #[OA\Post(
        path: '/api/address/state-list',
        operationId: 'getStateList',
        description: 'Get list of states for a country',
        summary: 'Get list of states by country',
        tags: ['Masters & Locations'],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'country_id', type: 'string', example: 'IN')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'List of states')
        ]
    )]
    public function getStateList() {}

    #[OA\Post(
        path: '/api/address/state-show',
        operationId: 'showState',
        description: 'Show single state details',
        summary: 'Show state details',
        tags: ['Masters & Locations'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id'],
                properties: [
                    new OA\Property(property: 'id', type: 'string', example: '1')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'State details')
        ]
    )]
    public function showState() {}

    #[OA\Post(
        path: '/api/address/city-list',
        operationId: 'getCityList',
        description: 'Get list of cities by state ID',
        summary: 'Get list of cities by state/country',
        tags: ['Masters & Locations'],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'state_id', type: 'string', example: '1')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'List of cities')
        ]
    )]
    public function getCityList() {}

    #[OA\Post(
        path: '/api/address/city-show',
        operationId: 'showCity',
        description: 'Show single city details',
        summary: 'Show city details',
        tags: ['Masters & Locations'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id'],
                properties: [
                    new OA\Property(property: 'id', type: 'string', example: '1')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'City details')
        ]
    )]
    public function showCity() {}

    #[OA\Get(
        path: '/api/course/promo-list',
        operationId: 'getPromoList',
        description: 'Get list of active promotional coupon codes',
        summary: 'Get list of active promotional codes',
        tags: ['Promo Codes'],
        responses: [
            new OA\Response(response: 200, description: 'Promo codes list')
        ]
    )]
    public function getPromoList() {}

    #[OA\Post(
        path: '/api/course/verify-promo',
        operationId: 'verifyPromoCode',
        description: 'Verify coupon code validity and calculate discount amount',
        summary: 'Verify and apply promo code discount',
        tags: ['Promo Codes'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['promo_code'],
                properties: [
                    new OA\Property(property: 'promo_code', type: 'string', example: 'DISCOUNT10'),
                    new OA\Property(property: 'course_id', type: 'integer', example: 1),
                    new OA\Property(property: 'amount', type: 'number', example: 1000)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Promo code valid'),
            new OA\Response(response: 400, description: 'Invalid or expired promo code')
        ]
    )]
    public function verifyPromoCode() {}

    #[OA\Get(
        path: '/api/state-url',
        operationId: 'getStateUrls',
        description: 'Get all SEO friendly state/location URLs',
        summary: 'Get all SEO state URLs',
        tags: ['Masters & Locations'],
        responses: [
            new OA\Response(response: 200, description: 'List of state URLs')
        ]
    )]
    public function getStateUrls() {}
}
