<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Http\Controllers\Api\CourseController;
use Modules\DynamicMaster\Http\Controllers\API\ApiController;
use Modules\DynamicMaster\Http\Controllers\API\PaymentGatewayController;
use Modules\DynamicMaster\Http\Controllers\PlanController;



Route::group(['middleware' => 'api', 'prefix' => 'list'], function () {

    Route::get('/blog-types', [ApiController::class, 'blogType']);
    Route::post('/blogs', [ApiController::class, 'allBlogs']);
    Route::post('/recent-posts', [ApiController::class, 'recentBlogs']);
    Route::post('/blogs-detail', [ApiController::class, 'blogDetail']);

    Route::post('/all-pages', [ApiController::class, 'allPages']);
    Route::post('/pages-detail', [ApiController::class, 'pageDetail']);
    Route::get('/page/{slug}', [ApiController::class, 'pageBySlug']);

    Route::get('/contact-details', [ApiController::class, 'contactDetail']);


});




Route::post('/contact-store', [ApiController::class, 'storeContact']);
Route::post('/learning-calendar-store', [ApiController::class, 'storeLearningCalender']);
Route::post('/consultation-store', [ApiController::class, 'storeConsultationEnquiry']);
Route::post('/appointment-store', [ApiController::class, 'storeScheduleAppointment']);
Route::post('/team-application-store', [ApiController::class, 'storeTeamApplication']);
Route::post('/store-request-for-service', [ApiController::class, 'storeRequestForService']);
Route::post('/get-course-category/{name}', [ApiController::class, 'getCourseCategory']);
Route::post('/enrollments-store', [ApiController::class, 'storeEnrollment']);
Route::post('/enrollments-update', [PaymentGatewayController::class, 'validPayment']);

Route::post('/blog-search', [ApiController::class, 'blogSearch']);


Route::post('/send-otp', [ApiController::class, 'sendOtp']);
Route::post('/verify-otp', [ApiController::class, 'verifyOtp']);





Route::post('/categorised-course-list', [CourseController::class, 'categorisedCourse_list']);
Route::post('/all-courses', [CourseController::class, 'allCourses']);
Route::post('/section-list', [ApiController::class, 'sectionList']);
Route::post('/section-view', [ApiController::class, 'viewSection']);
Route::post('/testimonials/list', [ApiController::class, 'testimonialList']);
Route::post('/dynamic-page', [ApiController::class, 'dynamicPages']);
Route::post('/faq/list', [ApiController::class, 'allFAQ']);
Route::post('/course-plans', [ApiController::class, 'coursePlans']);
Route::post('/consulting/list', [ApiController::class, 'consultingList']);
Route::post('/consulting/view', [ApiController::class, 'consultigPage']);
Route::post('/consultig-page', [ApiController::class, 'consultigPage']);
Route::post('/testimonials/all-list', [ApiController::class, 'allTestimonial']);
Route::get('/footer-disclamier', [ApiController::class, 'footer']);
Route::get('/all-courses-content', [ApiController::class, 'AllCourses']);

Route::prefix('payment')->group(function () {
    Route::post('/initiate', [PaymentGatewayController::class, 'payment']);
    Route::post('/create-order', [PaymentGatewayController::class, 'createRazorPayOrder']);
    Route::post('/verify-payment', [PaymentGatewayController::class, 'verifyRazorPayPayment']);
    Route::post('/ccavenue/pay', [PaymentGatewayController::class, 'ccAvenueCreatePayment']);
    Route::post('/ccavenue/callback', [PaymentGatewayController::class, 'ccAvenueCallback'])->withoutMiddleware('api');
    Route::get('/payment/status', [PaymentGatewayController::class, 'paymentStatus']);



    // Route::post('/ccavenue/pay', [PaymentGatewayController::class, 'initiate']);
    Route::post('/ccavenue/response', [PaymentGatewayController::class, 'triggerRespose']);


    });

    // Route::post('/ccavenue', [PaymentGatewayController::class, 'decypher'])->withoutMiddleware('api');



Route::post('fetch-features', [PlanController::class, 'fetchFeatures'])->name('fetch-features');
Route::post('/download', [ApiController::class, 'downloadInvoicePDF']);
Route::get('/state-url', [ApiController::class, 'stateURLList'])->withoutMiddleware([App\Http\Middleware\ApiMiddleware::class]);



