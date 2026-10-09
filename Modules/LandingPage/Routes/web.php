<?php

use Illuminate\Support\Facades\Route;
use Modules\LandingPage\Http\Controllers\LandingPageController;

Route::prefix(strtolower('LandingPage'))->group(function () {
    Route::get('/', [LandingPageController::class, 'index']);
    Route::get('/index', [LandingPageController::class, 'index'])->name('landingpage.index');
    Route::get('/create', [LandingPageController::class, 'create'])->name('landingpage.create');
    Route::post('/section-store', [LandingPageController::class, 'storeSection'])->name('section.store');
    Route::get('section-data/ajax', [LandingPageController::class, 'getSectionData'])->name('section.data.ajax');
    Route::get('/section/{id}', [LandingPageController::class, 'deleteSection'])->name('section.delete');
    Route::get('/section/{id}/edit', [LandingPageController::class, 'editSection'])->name('section.edit');
    Route::post('/section/update/{id}', [LandingPageController::class, 'storeSection'])->name('section.update');




});
