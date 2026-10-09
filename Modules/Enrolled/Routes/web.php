<?php

use Illuminate\Support\Facades\Route;
use Modules\Enrolled\Http\Controllers\EnrolledController;
use Modules\Enrolled\Http\Controllers\EnrolledCorporateController;
use Modules\Enrolled\Http\Controllers\EnrolledCourseCorporateController;
// use Modules\DynamicMaster\Http\Controllers\API\ApiController;

Route::prefix('enrolled')->middleware(['web','auth'])->group(function () {
    Route::get('list/', [EnrolledController::class, 'index'])->name('enrolled-request-individual.index');
    Route::get('views/', [EnrolledController::class, 'enrolledStore'])->name('enrolled-request-individual.views');
    Route::get('create/', [EnrolledController::class, 'enrolledCreate'])->name('enrolled-request-individual.create');
    Route::get('edit/', [EnrolledController::class, 'enrolledCreate'])->name('enrolled-request-individual.edit');
     Route::get('enrollment-list/', [EnrolledController::class, 'CompletedEnrollmentList'])->name('complete-enrollment-list');
     Route::get('enrollment-view/', [EnrolledController::class, 'CompletedEnrollmentView'])->name('complete-enrollment-view');

    Route::get('corporate-list/', [EnrolledCorporateController::class, 'Corporateindex'])->name('enrolled-request-corporate.index');
    Route::get('corporate-views/', [EnrolledCorporateController::class, 'enrolledCorporateStore'])->name('enrolled-request-corporate.views');
    Route::get('corporate-create/', [EnrolledCorporateController::class, 'enrolledCreate'])->name('enrolled-request-corporate.create');
    Route::get('corporate-edit/', [EnrolledCorporateController::class, 'enrolledCreate'])->name('enrolled-request-corporate.edit');


     Route::get('enroll-course-corporate-list/', [EnrolledCourseCorporateController::class, 'CorporatenewDataindex'])->name('enroll-course-corporate-list');
     Route::get('enroll-course-corporate-view/', [EnrolledCourseCorporateController::class, 'enrollcourseCorporateview'])->name('enroll-course-corporate-view');
     Route::get('enroll-course-individual-list/', [EnrolledCourseCorporateController::class, 'CourseIndividualindex'])->name('enroll-course-individual-list');

Route::post('/download', [EnrolledController::class, 'downloadInvoicePDF'])->name('invoice.download');

});

