<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Http\Controllers\CourseMasterController;
use Modules\Course\Http\Controllers\CourseScheduleController;
use Modules\Course\Http\Controllers\CourseCertificateTextController;
use Modules\Course\Http\Controllers\CourseCertificateImageController;
use Modules\Course\Http\Controllers\CourseCompanyAlumniController;
use Modules\Course\Http\Controllers\DynamicCourseTuitionFeeController;
use Modules\Course\Http\Controllers\CourseSeoController;
use Modules\Course\Http\Controllers\PromoCodeController;
use Modules\Course\Http\Controllers\CourseRegionController;
use Modules\Course\Http\Controllers\CourseSupportPageAdminController;
use Modules\Course\Http\Controllers\CourseQuizAdminController;


 Route::get('/get-child-stateName', [CourseMasterController::class, 'get_filtered_stateName'])->name('get-child-stateName');

Route::prefix('Course-Course')->middleware(['web','auth'])->group(function () {
    Route::get('/list', [CourseMasterController::class, 'CourseList'])->name('course-list');
    Route::get('/create', [CourseMasterController::class, 'CourseCreate'])->name('course-create');
    Route::post('/store', [CourseMasterController::class, 'CourseStore'])->name('course-store');
    Route::get('/edit', [CourseMasterController::class, 'CourseEdit'])->name('course-edit');
    Route::post('/update', [CourseMasterController::class, 'CourseUpdate'])->name('course-update');
    Route::get('/delete/{id}', [CourseMasterController::class, 'CourseDestroy'])->name('course-destroy');
    Route::get('/view', [CourseMasterController::class, 'CourseView'])->name('course-view');
    Route::get('/get-child-Course', [CourseMasterController::class, 'get_child'])->name('get-child-Course');
    Route::get('/get-filtered-Course', [CourseMasterController::class, 'get_filtered_Course'])->name('get-filtered-Course');
});

//Course-Country
Route::prefix('course-country')->middleware(['web','auth'])->group(function () {
    Route::get('/list', [CourseMasterController::class, 'countryList'])->name('course-country-list');
    Route::get('/create', [CourseMasterController::class, 'countryCreate'])->name('course-country-create');
    Route::post('/store', [CourseMasterController::class, 'countryStore'])->name('course-country-store');
    Route::get('/edit', [CourseMasterController::class, 'countryEdit'])->name('course-country-edit');
    Route::post('/update', [CourseMasterController::class, 'countryUpdate'])->name('course-country-update');
    Route::get('/delete/{id}', [CourseMasterController::class, 'countryDestroy'])->name('course-country-destroy');
    Route::get('/view', [CourseMasterController::class, 'countryView'])->name('course-country-view');
   Route::post('/update-currency', [CourseMasterController::class, 'updateCurrencyRates'])->name('countries.update.currency');

});

//Course-State
Route::prefix('course-state')->middleware(['web','auth'])->group(function () {
    Route::get('/list', [CourseMasterController::class, 'stateList'])->name('course-state-list');
    Route::get('/create', [CourseMasterController::class, 'stateCreate'])->name('course-state-create');
    Route::post('/store', [CourseMasterController::class, 'stateStore'])->name('course-state-store');
    Route::get('/edit', [CourseMasterController::class, 'stateEdit'])->name('course-state-edit');
    Route::post('/update', [CourseMasterController::class, 'stateUpdate'])->name('course-state-update');
    Route::get('/delete/{id}', [CourseMasterController::class, 'stateDestroy'])->name('course-state-destroy');
    Route::get('/view', [CourseMasterController::class, 'stateView'])->name('course-state-view');
    Route::get('/download', [CourseMasterController::class, 'download'])->name('course-state-excel');
});

//Course-City
Route::prefix('course-city')->middleware(['web','auth'])->group(function () {
    Route::get('/list', [CourseMasterController::class, 'cityList'])->name('course-city-list');
    Route::get('/create', [CourseMasterController::class, 'cityCreate'])->name('course-city-create');
    Route::post('/store', [CourseMasterController::class, 'cityStore'])->name('course-city-store');
    Route::get('/edit', [CourseMasterController::class, 'cityEdit'])->name('course-city-edit');
    Route::post('/update', [CourseMasterController::class, 'cityUpdate'])->name('course-city-update');
    Route::get('/delete/{id}', [CourseMasterController::class, 'cityDestroy'])->name('course-city-destroy');
    Route::get('/view', [CourseMasterController::class, 'cityView'])->name('course-city-view');
});


//course-category
Route::prefix('course-category')->middleware(['web','auth'])->group(function () {
    Route::get('/list', [CourseMasterController::class, 'coursecategoryList'])->name('course-category-list');
    Route::get('/create', [CourseMasterController::class, 'courseCategoryCreate'])->name('course-category-create');
    Route::post('/store', [CourseMasterController::class, 'cousecategoryStore'])->name('course-category-store');
    Route::get('/edit', [CourseMasterController::class, 'coursecategoryEdit'])->name('course-category-edit');
    Route::post('/update', [CourseMasterController::class, 'courseCategoryUpdate'])->name('course-category-update');
    Route::get('/delete/{id}', [CourseMasterController::class, 'courseCategoryDestroy'])->name('course-category-destroy');
    Route::get('/view', [CourseMasterController::class, 'coursecategoryView'])->name('course-category-view');
    // child category 
    Route::get('/course-category-addchild', [CourseMasterController::class, 'addChildCategory'])->name('course-category-addchild');
    Route::post('/course-childcategory-store', [CourseMasterController::class, 'couseChildcategoryStore'])->name('course-childcategory-store');
    Route::post('/course-childcategory-update', [CourseMasterController::class, 'couseChildcategoryUpdate'])->name('course-childcategory-update');
    Route::get('/course-category-editchid', [CourseMasterController::class, 'courseChildcategoryEdit'])->name('course-category-editchild');

});

//course-category
Route::prefix('course-schedule')->middleware(['web','auth'])->group(function () {
    Route::get('/list', [CourseScheduleController::class, 'courseScheduleList'])->name('course-schedule-list');
    Route::get('/create', [CourseScheduleController::class, 'courseScheduleCreate'])->name('course-schedule-create');
    Route::post('/store', [CourseScheduleController::class, 'courseScheduleStore'])->name('course-schedule-store');
    Route::get('/edit', [CourseScheduleController::class, 'courseScheduleEdit'])->name('course-schedule-edit');
    Route::post('/update', [CourseScheduleController::class, 'courseScheduleUpdate'])->name('course-schedule-update');
    Route::get('/delete/{id}', [CourseScheduleController::class, 'CourseScheduleDestroy'])->name('course-schedule-destroy');
    Route::get('/view', [CourseScheduleController::class, 'courseScheduleView'])->name('course-schedule-view');
    Route::get('/plans/{course_id}', [CourseScheduleController::class, 'getPlansByCourse']);
    Route::post('/bulk-delete',[CourseScheduleController::class, 'CourseScheduleBulkDestroy'])->name('course-schedule-bulk-delete');


});

//course-Will Learn about
Route::prefix('course-will-learn')->middleware(['web','auth'])->group(function () {
    Route::get('/list', [CourseScheduleController::class, 'whatisLearnAboutList'])->name('course-will-learn-list');
    Route::get('/create', [CourseScheduleController::class, 'courseWillLearnCreate'])->name('course-will-learn-create');
    Route::post('/store', [CourseScheduleController::class, 'couseWillLearnStore'])->name('course-will-learn-store');
    Route::get('/edit', [CourseScheduleController::class, 'courseWillLearnEdit'])->name('course-will-learn-edit');
    Route::post('/update', [CourseScheduleController::class, 'courseWillLearnUpdate'])->name('course-will-learn-update');
    Route::get('/delete/{id}', [CourseScheduleController::class, 'courseWillLearnDestroy'])->name('course-will-learn-destroy');
    Route::get('/view', [CourseScheduleController::class, 'courseWillLearnleView'])->name('course-will-learn-view');
});

// // About Geography
// Route::prefix('about-geography')->middleware(['web','auth'])->group(function () {
//     Route::get('/list', [CourseScheduleController::class, 'aboutGeographyList'])->name('about-geography-list');
//     Route::get('/create', [CourseScheduleController::class, 'aboutGeographyCreate'])->name('about-geography-create');
//     Route::post('/store', [CourseScheduleController::class, 'couseWillLearnStore'])->name('about-geography-store');
//     Route::get('/edit', [CourseScheduleController::class, 'aboutGeographyEdit'])->name('about-geography-edit');
//     Route::post('/update', [CourseScheduleController::class, 'aboutGeographyUpdate'])->name('about-geography-update');
//     Route::get('/delete/{id}', [CourseScheduleController::class, 'aboutGeographyDestroy'])->name('about-geography-destroy');
//     Route::get('/view', [CourseScheduleController::class, 'aboutGeographyleView'])->name('about-geography-view');
// });

// Course Read More
Route::prefix('course-read-more')->middleware(['web','auth'])->group(function () {
    Route::get('/list', [CourseScheduleController::class, 'Course_ReadmoreList'])->name('course-read-more-list');
    Route::get('/create', [CourseScheduleController::class, 'Course_ReadmoreCreate'])->name('course-read-more-create');
    Route::post('/store', [CourseScheduleController::class, 'couseWillLearnStore'])->name('course-read-more-store');
    Route::get('/edit', [CourseScheduleController::class, 'Course_ReadmoreEdit'])->name('course-read-more-edit');
    Route::post('/update', [CourseScheduleController::class, 'Course_ReadmoreUpdate'])->name('course-read-more-update');
    Route::get('/delete/{id}', [CourseScheduleController::class, 'Course_ReadmoreDestroy'])->name('course-read-more-destroy');
    Route::get('/view', [CourseScheduleController::class, 'Course_ReadmoreleView'])->name('course-read-more-view');
});

Route::prefix('course-Certificate')->middleware(['web','auth'])->group(function () {
    Route::get('/list', [CourseCertificateTextController::class, 'CourseCertificateTextList'])->name('course-certificate-text-list');
    Route::get('/create', [CourseCertificateTextController::class, 'CourseCertificateTextCreate'])->name('course-certificate-text-create');
    Route::post('/store', [CourseCertificateTextController::class, 'CourseCertificateTextStore'])->name('course-certificate-text-store');
    Route::get('/edit', [CourseCertificateTextController::class, 'CourseCertficateEdit'])->name('course-certificate-text-edit');
     Route::post('/update', [CourseCertificateTextController::class, 'CourseCertificateUpdate'])->name('course-certificate-text-update');
    Route::get('/get-states', [CourseCertificateTextController::class, 'getStates'])->name('get-states');

    // Route::put('/update', [CourseCertificateTextController::class, 'CourseCertificateTextStore'])->name('course-certificate-text-store');

});

Route::prefix('course-Certificate-image')->middleware(['web','auth'])->group(function () {
    Route::get('/list', [CourseCertificateImageController::class, 'CourseCertificateImageList'])->name('course-certificate-image-list');
    Route::get('/create', [CourseCertificateImageController::class, 'CourseCertificateImageCreate'])->name('course-certificate-image-create');
    Route::post('/store', [CourseCertificateImageController::class, 'CourseCertificateImageStore'])->name('course-certificate-image-store');
    Route::get('/edit', [CourseCertificateImageController::class, 'CourseCertficateImageEdit'])->name('course-certificate-image-edit');
    Route::post('/update', [CourseCertificateImageController::class, 'CourseCertificateImageUpdate'])->name('course-certificate-image-update');
});

Route::prefix('course-company-alumni')->middleware(['web','auth'])->group(function () {
    Route::get('/list', [CourseCompanyAlumniController::class, 'CourseCompanyAlumniList'])->name('course-company-alumni-list');
    Route::get('/create', [CourseCompanyAlumniController::class, 'CourseCompanyAlumniCreate'])->name('course-company-alumni-create');
    Route::post('/store', [CourseCompanyAlumniController::class, 'CourseCompanyAlumniStore'])->name('course-company-alumni-store');
    Route::get('/edit', [CourseCompanyAlumniController::class, 'CourseCompanyAlumniEdit'])->name('course-company-alumni-edit');
    Route::post('/update', [CourseCompanyAlumniController::class, 'CourseCompanyAlumniUpdate'])->name('course-company-alumni-update');
    Route::get('/alumni-download', [CourseCompanyAlumniController::class, 'downloadAlumni'])->name('course-alumni-excel');
});

Route::prefix('tuition-fee')->middleware(['web','auth'])->group(function () {
    Route::get('/list', [DynamicCourseTuitionFeeController::class, 'tuitionFeeList'])->name('tuition-fee-list');
    Route::get('/create', [DynamicCourseTuitionFeeController::class, 'tuitionFeeCreate'])->name('tuition-fee-create');
    Route::post('/store', [DynamicCourseTuitionFeeController::class, 'tuitionFeeStore'])->name('tuition-fee-store');
    Route::get('/edit', [DynamicCourseTuitionFeeController::class, 'tuitionFeeEdit'])->name('tuition-fee-edit');
    Route::post('/update', [DynamicCourseTuitionFeeController::class, 'tuitionFeeUpdate'])->name('tuition-fee-update');
});

Route::prefix('course-seo')->middleware(['web','auth'])->group(function () {
    Route::get('/list', [CourseSeoController::class, 'CourseSeoList'])->name('course-seo-list');
    Route::get('/create', [CourseSeoController::class, 'CourseSeoCreate'])->name('course-seo-create');
    Route::post('/store', [CourseSeoController::class, 'CourseSeoStore'])->name('course-seo-store');
    Route::get('/edit', [CourseSeoController::class, 'CourseSeoEdit'])->name('course-seo-edit');
     Route::post('/update', [CourseSeoController::class, 'CourseSeoUpdate'])->name('course-seo-update');
    // Route::get('/get-states', [CourseCertificateTextController::class, 'getStates'])->name('get-states');

    // Route::put('/update', [CourseCertificateTextController::class, 'CourseCertificateTextStore'])->name('course-certificate-text-store');

});


Route::prefix('promo-code')->middleware(['web','auth'])->group(function () {
    Route::get('/list', [PromoCodeController::class, 'PromoCodeList'])->name('promo-code-list');
    Route::get('/create', [PromoCodeController::class, 'PromoCodeCreate'])->name('promo-code-create');
    Route::post('/store', [PromoCodeController::class, 'PromoCodeStore'])->name('promo-code-store');
    Route::get('/edit', [PromoCodeController::class, 'PromoCodeEdit'])->name('promo-code-edit');
     Route::post('/update', [PromoCodeController::class, 'PromoCodeUpdate'])->name('promo-code-update');
    // Route::get('/get-states', [CourseCertificateTextController::class, 'getStates'])->name('get-states');

    // Route::put('/update', [CourseCertificateTextController::class, 'CourseCertificateTextStore'])->name('course-certificate-text-store');

});

Route::prefix('region')->middleware(['web','auth'])->group(function () {
    Route::get('/list', [CourseRegionController::class, 'RegionList'])->name('region-list');
    Route::get('/create', [CourseRegionController::class, 'RegionCreate'])->name('region-create');
    Route::post('/store', [CourseRegionController::class, 'RegionStore'])->name('region-store');
    Route::get('/edit', [CourseRegionController::class, 'RegionEdit'])->name('region-edit');
     Route::post('/update', [CourseRegionController::class, 'RegionUpdate'])->name('region-update');
    // Route::get('/get-states', [CourseCertificateTextController::class, 'getStates'])->name('get-states');

    // Route::put('/update', [CourseCertificateTextController::class, 'CourseCertificateTextStore'])->name('course-certificate-text-store');

});

// Route::get('promo-code', function () {
//     return view('');
// })->name('promo-code.index');
// Route::get('promo-code-create', function () {
//     return view('Course::promo-code.create');
// })->name('promo-code.create');


// Course Support Pages (Exam Format, Syllabus, Notes)
Route::prefix('course-support-page')->middleware(['web', 'auth'])->group(function () {
    Route::get('/list', [CourseSupportPageAdminController::class, 'index'])->name('course-support-page-list');
    Route::get('/create', [CourseSupportPageAdminController::class, 'create'])->name('course-support-page-create');
    Route::post('/store', [CourseSupportPageAdminController::class, 'store'])->name('course-support-page-store');
    Route::get('/edit/{id}', [CourseSupportPageAdminController::class, 'edit'])->name('course-support-page-edit');
    Route::post('/update', [CourseSupportPageAdminController::class, 'store'])->name('course-support-page-update');
    Route::get('/delete/{id}', [CourseSupportPageAdminController::class, 'destroy'])->name('course-support-page-destroy');
    Route::post('/toggle-status', [CourseSupportPageAdminController::class, 'toggleStatus'])->name('course-support-page-toggle-status');
});

// Course Quizzes & Mock Exams
Route::prefix('course-quiz')->middleware(['web', 'auth'])->group(function () {
    Route::get('/list', [CourseQuizAdminController::class, 'index'])->name('course-quiz-list');
    Route::get('/create', [CourseQuizAdminController::class, 'create'])->name('course-quiz-create');
    Route::post('/store', [CourseQuizAdminController::class, 'store'])->name('course-quiz-store');
    Route::get('/edit/{id}', [CourseQuizAdminController::class, 'edit'])->name('course-quiz-edit');
    Route::post('/update', [CourseQuizAdminController::class, 'store'])->name('course-quiz-update');
    Route::get('/delete/{id}', [CourseQuizAdminController::class, 'destroy'])->name('course-quiz-destroy');
    Route::post('/toggle-status', [CourseQuizAdminController::class, 'toggleStatus'])->name('course-quiz-toggle-status');

    // Questions Management
    Route::get('/{quizId}/questions', [CourseQuizAdminController::class, 'questions'])->name('course-quiz-questions');
    Route::get('/{quizId}/questions/create', [CourseQuizAdminController::class, 'createQuestion'])->name('course-quiz-question-create');
    Route::post('/{quizId}/questions/store', [CourseQuizAdminController::class, 'storeQuestion'])->name('course-quiz-question-store');
    Route::get('/{quizId}/questions/{questionId}/edit', [CourseQuizAdminController::class, 'createQuestion'])->name('course-quiz-question-edit');
    Route::get('/{quizId}/questions/{questionId}/delete', [CourseQuizAdminController::class, 'destroyQuestion'])->name('course-quiz-question-destroy');
    Route::get('/{quizId}/questions/sample-csv', [CourseQuizAdminController::class, 'downloadSampleCsv'])->name('course-quiz-questions-sample-csv');
    Route::post('/{quizId}/questions/import-csv', [CourseQuizAdminController::class, 'importQuestions'])->name('course-quiz-questions-import-csv');
});

include_once __DIR__.'/youtube.php';
include_once __DIR__.'/dynamicCoursePages.php';
