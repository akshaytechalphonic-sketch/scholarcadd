<?php
use Illuminate\Support\Facades\Route;
use Modules\Course\Http\Controllers\CourseYouTubeController;


// Course Read More
Route::prefix('course-you-tube')->middleware(['web','auth'])->group(function () {
    Route::get('/list', [CourseYouTubeController::class, 'CourseYoutubeList'])->name('course-you-tube-list');
    Route::get('/create', [CourseYouTubeController::class, 'CourseYoutubeCreate'])->name('course-you-tube-create');
    Route::post('/store', [CourseYouTubeController::class, 'CourseYoutubeStore'])->name('course-you-tube-store');
    Route::get('/edit', [CourseYouTubeController::class, 'CourseYoutubeEdit'])->name('course-you-tube-edit');
    Route::post('/update', [CourseYoutubeController::class, 'CourseYoutubeUpdate'])->name('course-you-tube-update');
    Route::get('/delete/{id}', [CourseYoutubeController::class, 'CourseYoutubeDestroy'])->name('course-you-tube-destroy');
    Route::get('/view', [CourseYoutubeController::class, 'CourseYoutubeleView'])->name('course-you-tube-view');
    Route::post('/excel-update', [CourseYoutubeController::class, 'updateFromExcel'])
    ->name('course.excel.update');
});
//include_once __DIR__.'/test.php';

