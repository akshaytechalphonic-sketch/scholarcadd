<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Http\Controllers\DynamicCoursePagesController;

// Course Read More
Route::prefix('dynamic-course-pages')->middleware(['web', 'auth'])->group(function () {
    Route::get('/list', [DynamicCoursePagesController::class, 'DynamicCoursePagesList'])->name('dynamic-course-pages-list');
    Route::get('/create', [DynamicCoursePagesController::class, 'DynamicCoursePagesCreate'])->name('dynamic-course-pages-create');
    Route::post('/store', [DynamicCoursePagesController::class, 'DynamicCoursePagesStore'])->name('dynamic-course-pages-store');
    Route::get('/edit', [DynamicCoursePagesController::class, 'DynamicCoursePagesEdit'])->name('dynamic-course-pages-edit');
    Route::post('/update', [DynamicCoursePagesController::class, 'DynamicCoursePagesUpdate'])->name('dynamic-course-pages-update');
    Route::get('/delete/{id}', [DynamicCoursePagesController::class, 'DynamicCoursePagesDestroy'])->name('dynamic-course-pages-destroy');
    Route::get('/view', [DynamicCoursePagesController::class, 'DynamicCoursePagesleView'])->name('dynamic-course-pages-view');
    Route::get('/get-states-by-country', [DynamicCoursePagesController::class, 'getStates'])->name('get.states.by.country');
    Route::get('/download-data', [DynamicCoursePagesController::class, 'DynamicPagesData'])->name('get.dynamic-course-pages');
});

// Course Read More
Route::prefix('course-trainer')->middleware(['web', 'auth'])->group(function () {
    Route::get('/list', [DynamicCoursePagesController::class, 'coursetrainerPagesList'])->name('course-trainer-list');
    Route::get('/create', [DynamicCoursePagesController::class, 'coursetrainerPagesCreate'])->name('course-trainer-create');
    Route::post('/store', [DynamicCoursePagesController::class, 'coursetrainerPagesStore'])->name('course-trainer-store');
    Route::get('/edit', [DynamicCoursePagesController::class, 'coursetrainerPagesCreate'])->name('course-trainer-edit');
    Route::post('/update', [DynamicCoursePagesController::class, 'coursetrainerPagesUpdate'])->name('course-trainer-update');
    Route::get('/delete/{id}', [DynamicCoursePagesController::class, 'coursetrainerPagesDestroy'])->name('course-trainer-destroy');
    Route::get('/view', [DynamicCoursePagesController::class, 'coursetrainerPagesleView'])->name('course-trainer-view');
});

// include_once __DIR__.'/test.php';
