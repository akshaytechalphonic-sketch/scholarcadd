<?php

/*
 * return [

    'paths' => [
        'sanctum/csrf-cookie',
        'login',
        'logout',
        'course/category-and-course',
        'course/view_course',
        'course/category-list',
        'course/cource_category_list',
        'course/view-course',
        'course-master/category-list',
        'course-master/category-show',
        'address/city-show',
        'address/state-show',
        'address/state-list',
        'address/country-show',
        'address/country-list',
        'api/tests',
        'api/*',
	'/*',
	'https://scholaracad.com/*',
        'fetch-features'
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://scholaracad.com/*',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
 ];
 */
return [

    'paths' => [
        'api/*',
        'login',
        'logout',
	'/*',
	'sanctum/csrf-cookie'
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://scolarcadfront.vercel.app',
        'https://scholaracad.com',
        'https://www.scholaracad.com',
        'http://localhost:3000',
        'http://127.0.0.1:3000'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];

