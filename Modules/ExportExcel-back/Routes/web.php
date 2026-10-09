<?php

use Illuminate\Support\Facades\Route;
use Modules\ExportExcel\Http\Controllers\ExcelController;






Route::prefix('excel-upload')->middleware(['web','auth'])->group(function () {
    Route::get('export-excel', function () {
    return view('ExportExcel::export-excel.index');
})->name('export-excel.index');
Route::post('/excel-update', [ExcelController::class, 'updateFromExcel'])->name('course.excel.update');
Route::post('/excel-highlights', [ExcelController::class, 'updateCourseHighlights'])->name('course.excel.highlights');
Route::post('/excel-choose', [ExcelController::class, 'updateCourseWhyChooseUs'])->name('course.excel.whyChooseUs');
Route::post('/excel-eligiblity', [ExcelController::class, 'updateCourseEligibility'])->name('course.excel.eligiblity');
Route::post('/excel-roadmap', [ExcelController::class, 'updateCourseRoadmap'])->name('course.excel.roadmap');
Route::post('/excel-objective', [ExcelController::class, 'updateCourseLearningObjective'])->name('course.excel.learningObjectives');
Route::post('/excel-geography', [ExcelController::class, 'updateCourseAboutGeography'])->name('course.excel.aboutGeography');
Route::post('/excel-download', [ExcelController::class, 'downloadFromFormateExcel'])->name('course.excel.download');
Route::post('/excel-faqs-update', [ExcelController::class, 'updateFAQS'])->name('course.excel.faqs');
Route::get('/faqs-download', [ExcelController::class, 'downloadFAQSFormateExcel'])->name('faqs.excel.download');
Route::post('/excel-state-update', [ExcelController::class, 'updateState'])->name('course.excel.state');
Route::get('/state-download', [ExcelController::class, 'downloadStateFormateExcel'])->name('state.excel.download');
Route::get('/pointer-download', [ExcelController::class, 'downloadPointerFormateCsv'])->name('download-pointer-excel');
Route::get('/hghlights-download', [ExcelController::class, 'downloadHighlightsFormateCsv'])->name('download-highlights-excel');
Route::get('/whyChooseUs-download', [ExcelController::class, 'downloadWhyChooseUsFormateCsv'])->name('download-whyChooseUs-excel');
Route::get('/eligiblity-download', [ExcelController::class, 'downloadEligiblityFormateCsv'])->name('download-eligiblity-excel');
Route::get('/roadmap-download', [ExcelController::class, 'downloadRoadmapFormateCsv'])->name('download-roadmap-excel');
Route::get('/objective-download', [ExcelController::class, 'downloadObjectiveFormateCsv'])->name('download-objective-excel');
Route::get('/geography-download', [ExcelController::class, 'downloadGeographyFormateCsv'])->name('download-geography-excel');
});
