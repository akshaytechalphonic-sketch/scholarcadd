<?php

use Illuminate\Support\Facades\Route;
use Modules\Website\Http\Controllers\BusinessgroupController;
use Modules\Website\Http\Controllers\TestimonialController;
use Modules\Website\Http\Controllers\WebsiteController;
use Modules\Website\Http\Controllers\HiringController;
use Modules\Website\Http\Controllers\ConsultingController;

Route::prefix(strtolower('Website-slider'))->middleware(['web', 'auth'])->group(function () {
    Route::get('/list', [WebsiteController::class, 'sliderList'])->name('slider-list');
    Route::get('/create', [WebsiteController::class, 'sliderCreate'])->name('slider-create');
    Route::post('/store', [WebsiteController::class, 'sliderStore'])->name('slider-store');
    Route::get('/edit', [WebsiteController::class, 'sliderEdit'])->name('slider-edit');
    Route::post('/update', [WebsiteController::class, 'sliderUpdate'])->name('slider-update');

});

// Location Route
Route::prefix('knowledge-center')->middleware(['web', 'auth'])->group(function () {
    Route::get('/list', [WebsiteController::class, 'blogsList'])->name('knowledge-center-list');
    Route::get('/create', [WebsiteController::class, 'blogsCreate'])->name('knowledge-center-create');
    Route::post('/store', [WebsiteController::class, 'blogsStore'])->name('knowledge-center-store');
    Route::get('/edit', [WebsiteController::class, 'blogsEdit'])->name('knowledge-center-edit');
    Route::post('/update', [WebsiteController::class, 'blogsUpdate'])->name('knowledge-center-update');
    Route::get('/delete/{id}', [WebsiteController::class, 'blogsDestroy'])->name('knowledge-center-destroy');
    Route::get('/view', [WebsiteController::class, 'blogsView'])->name('knowledge-center-view');

    Route::get('/get-child-Course', [WebsiteController::class, 'get_child'])->name('get-child-Course');
});

Route::prefix('pages')->middleware(['web', 'auth'])->group(function () {
    Route::get('/list', [WebsiteController::class, 'pageList'])->name('page-list');
    Route::get('/create', [WebsiteController::class, 'pageCreate'])->name('page-create');
    Route::post('/store', [WebsiteController::class, 'pageStore'])->name('page-store');
    Route::get('/edit', [WebsiteController::class, 'pageEdit'])->name('page-edit');
    Route::post('/update', [WebsiteController::class, 'pageUpdate'])->name('page-update');
    Route::get('/delete/{id}', [WebsiteController::class, 'pagesDestroy'])->name('page-destroy');
    Route::get('/view', [WebsiteController::class, 'pageView'])->name('page-view');
});
// // Contact
Route::prefix('contact')->middleware(['web', 'auth'])->group(function () {
    Route::get('/list', [WebsiteController::class, 'contactList'])->name('contact-list');
    Route::get('/create', [WebsiteController::class, 'contactCreate'])->name('contact-create');
    Route::post('/store', [WebsiteController::class, 'contactStore'])->name('contact-store');
    Route::get('/edit', [WebsiteController::class, 'contactEdit'])->name('contact-edit');
    Route::post('/update', [WebsiteController::class, 'contactUpdate'])->name('contact-update');
    Route::get('/delete/{id}', [WebsiteController::class, 'contactDestroy'])->name('contact-destroy');
    Route::get('/view', [WebsiteController::class, 'contactView'])->name('contact-view');
});
// Tags Route
Route::prefix('tags')->middleware(['web', 'auth'])->group(function () {
    Route::get('/list', [WebsiteController::class, 'tagsList'])->name('tags-list');
    Route::get('/create', [WebsiteController::class, 'tagsCreate'])->name('tags-create');
    Route::post('/store', [WebsiteController::class, 'tagsStore'])->name('tags-store');
    Route::get('/edit', [WebsiteController::class, 'tagsEdit'])->name('tags-edit');
    Route::post('/update', [WebsiteController::class, 'tagsUpdate'])->name('tags-update');
    Route::get('/delete/{id}', [WebsiteController::class, 'tagsDestroy'])->name('tags-destroy');
    Route::get('/view', [WebsiteController::class, 'tagsView'])->name('tags-view');
});

Route::prefix('testimonial')->middleware(['web', 'auth'])->group(function () {
    Route::get('/list', [TestimonialController::class, 'TestimonialIndex'])->name('testimonial-list');
    Route::get('/create', [TestimonialController::class, 'TestimonialCreate'])->name('testimonial-create');
    Route::post('/store', [TestimonialController::class, 'TestimonialStore'])->name('testimonial-store');
    Route::get('/edit', [TestimonialController::class, 'TestimonialEdit'])->name('testimonial-edit');
    Route::post('/update', [TestimonialController::class, 'TestimonialUpdate'])->name('testimonial-update');
    Route::get('/delete/{id}', [TestimonialController::class, 'TestimonialsDestroy'])->name('testimonial-destroy');

});

Route::prefix('hiring-companies')->middleware(['web', 'auth'])->group(function () {
    Route::get('/list', [HiringController::class, 'hiringList'])->name('hiring-companies-list');
    Route::get('/create', [HiringController::class, 'hiringCreate'])->name('hiring-companies-create');
    Route::post('/store', [HiringController::class, 'hiringStore'])->name('hiring-companies-store');
    Route::get('/edit', [HiringController::class, 'hiringEdit'])->name('hiring-companies-edit');
    Route::post('/update', [HiringController::class, 'hiringUpdate'])->name('hiring-companies-update');
    Route::get('/hiring-download', [HiringController::class, 'downloadHiringCompanies'])->name('course-hiring-company-excel');
    // Route::get('/delete/{id}', [TestimonialController::class, 'TestimonialsDestroy'])->name('testimonial-destroy');

});

Route::prefix('consulting')->middleware(['web', 'auth'])->group(function () {
    Route::get('/list', [ConsultingController::class, 'consultingIndex'])->name('consulting-list');
    Route::get('/create', [ConsultingController::class, 'consultingCreate'])->name('consulting-create');
    Route::post('/store', [ConsultingController::class, 'consultingStore'])->name('consulting-store');
    Route::get('/edit', [ConsultingController::class, 'consultingEdit'])->name('consulting-edit');
    Route::post('/update', [ConsultingController::class, 'consultingUpdate'])->name('consulting-update');
    Route::get('/delete/{id}', [ConsultingController::class, 'consultingsDestroy'])->name('consulting-destroy');

});
// // Contact

Route::prefix('business-group')->middleware(['web', 'auth'])->group(function () {
    Route::get('/index', [BusinessgroupController::class, 'index'])->name('businessgroup-index');
    Route::get('/create', [BusinessgroupController::class, 'create'])->name('businessgroup-create');
});
