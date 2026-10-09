<?php

use Illuminate\Support\Facades\Route;
use Modules\FAQS\Http\Controllers\FAQSController;
use Modules\FAQS\Http\Controllers\WebFAQSController;



Route::group(['prefix' => 'course-faqs-list', 'middleware' => ['auth','web']], function(){
    Route::get('/list', [FAQSController::class, 'FaqsIndex'])->name('faqs-list-index');
     Route::get('/create', [FAQSController::class, 'FaqsCourseCreate'])->name('faqs-list-create');
    Route::post('/store', [FAQSController::class, 'FaqsCourselStore'])->name('faqs-course-store');
    Route::get('/edit', [FAQSController::class, 'FaqsCourseEdit'])->name('faqs-course-edit');
    Route::post('/update', [FAQSController::class, 'FaqsCourseUpdate'])->name('faqs-course-update');
    Route::get('/delete/{id}', [FAQSController::class, 'FaqsCourseDestroy'])->name('faqs-course-destroy');
    Route::get('/view', [FAQSController::class, 'FaqsCourseleView'])->name('faqs-course-view');

    Route::get('/get-child-state', [FAQSController::class, 'get_stateName'])->name('get-child-state');
});
Route::group(['prefix' => 'faqs-list', 'middleware' => ['auth','web']], function(){
    Route::get('/list', [WebFAQSController::class, 'FaqsIndexlist'])->name('faqs-list');
     Route::get('/create', [WebFAQSController::class, 'FaqsGeneralCreate'])->name('faqs-general-create');
    Route::post('/store', [WebFAQSController::class, 'FaqsGeneralStore'])->name('faqs-general-store');
    Route::get('/edit', [WebFAQSController::class, 'FaqsGeneralEdit'])->name('faqs-general-edit');
    Route::post('/update', [WebFAQSController::class, 'FaqsGeneralUpdate'])->name('faqs-general-update');
    Route::get('/delete/{id}', [WebFAQSController::class, 'FaqsGeneralDestroy'])->name('faqs-general-destroy');
    Route::get('/view', [WebFAQSController::class, 'FaqsGeneralleView'])->name('faqs-general-view');
});

Route::group(['prefix' => 'faqs-category', 'middleware' => ['auth','web']], function(){
    Route::get('/category-list', [WebFAQSController::class, 'FaqsCategoryIndexList'])->name('faqs.category.list');
     Route::get('/create', [WebFAQSController::class, 'FaqsCategoryListCreate'])->name('faqs-category-create');
    Route::post('/store', [WebFAQSController::class, 'FaqsCategoryStore'])->name('faqs-category-store');
    Route::get('/edit', [WebFAQSController::class, 'FaqsCategoryEdit'])->name('faqs-category-edit');
    Route::post('/update', [WebFAQSController::class, 'FaqsCategoryUpdate'])->name('faqs-category-update');
    Route::get('/delete', [WebFAQSController::class, 'FaqsCetegoryDestroy'])->name('faqs-category-destroy');
    Route::get('/view', [WebFAQSController::class, 'FaqsCategoryView'])->name('faqs-category-view');
});
