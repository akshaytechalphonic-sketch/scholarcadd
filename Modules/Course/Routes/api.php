<?php
use Illuminate\Support\Facades\Route;
use Modules\Course\Http\Controllers\Api\MasterController;
use Modules\Course\Http\Controllers\Api\CourseController;
use Modules\Course\Http\Controllers\Api\CourseMasterController;
use Modules\Course\Http\Controllers\Api\PromoCodeController;
use Modules\Course\Http\Controllers\Api\CourseSupportPageController;
use Modules\Course\Http\Controllers\Api\QuizController;
use Modules\Course\Http\Controllers\Api\QuizAttemptController;


Route::group(['middleware' => 'api', 'prefix' => 'dn-master'], function () {
    Route::post('parent-list', [MasterController::class, 'parent_list']);
    Route::post('parent-show', [MasterController::class, 'parent_show']);
    // Route::post('child-list', [MasterController::class, 'child_list']);
    // Route::post('child-show', [MasterController::class, 'child_show']);
});

Route::group(['middleware' => 'api', 'prefix' => 'dn-child-master'], function () {
    Route::get('category-list', [MasterController::class, 'category_list']);
    Route::post('category-show', [MasterController::class, 'category_show']);
});


Route::group(['middleware' => 'api', 'prefix' => 'address'], function () {
    Route::get('country-list', [MasterController::class, 'country_list']);
    Route::post('country-show', [MasterController::class, 'country_show']);
    Route::post('state-list', [MasterController::class, 'state_list']);
    Route::post('state-show', [MasterController::class, 'state_show']);
    Route::post('city-list', [MasterController::class, 'city_list']);
    Route::post('city-show', [MasterController::class, 'city_show']);
});

Route::group(['middleware' => 'api', 'prefix' => 'course-master'], function () {
    Route::get('category-list', [MasterController::class, 'category_list']);
    Route::post('category-show', [MasterController::class, 'category_show']);
});


// Route::group(['middleware' => 'api', 'prefix' => 'course'], function () {
// Route::get('category-list', [CourseController::class, 'cource_category_list']);
// Route::post('category-show', [CourseController::class, 'cource_category_show']);
// });

Route::group(['middleware' => 'api', 'prefix' => 'course'], function () {
    Route::post('category-and-course', [CourseController::class, 'category_and_course']);
    Route::post('view-course', [CourseController::class, 'view_course']);
    Route::post('course-list', [CourseController::class, 'course_list']);
});

Route::group(['middleware' => 'api', 'prefix' => 'course'], function () {
    Route::get('promo-list', [PromoCodeController::class, 'promo_list']);
    Route::post('verify-promo', [PromoCodeController::class, 'verifyPromoCode']);
    // Route::post('course-list', [CourseController::class, 'course_list']);
});

Route::group(['middleware' => 'api', 'prefix' => 'courses'], function () {
    Route::get('{courseSlug}/support/syllabus', [CourseSupportPageController::class, 'syllabus']);
    Route::get('{courseSlug}/support/exam-format', [CourseSupportPageController::class, 'examFormat']);
    Route::get('{courseSlug}/quizzes', [QuizController::class, 'quizzes']);
    Route::get('{courseSlug}/quizzes/{quizSlug}', [QuizController::class, 'show']);
    Route::get('{courseSlug}/quizzes/{quizSlug}/questions', [QuizController::class, 'questions']);
    Route::post('{courseSlug}/quizzes/{quizSlug}/start', [QuizAttemptController::class, 'start']);
});

Route::group(['middleware' => 'api', 'prefix' => 'quizzes'], function () {
    Route::post('attempts/{attemptId}/save-answer', [QuizAttemptController::class, 'saveAnswer']);
    Route::post('attempts/{attemptId}/submit', [QuizAttemptController::class, 'submit']);
    Route::get('attempts/{attemptId}/result', [QuizAttemptController::class, 'result']);
});





