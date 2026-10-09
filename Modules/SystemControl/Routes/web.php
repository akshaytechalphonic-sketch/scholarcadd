<?php

use Illuminate\Support\Facades\Route;
use Modules\SystemControl\Http\Controllers\SystemControlController;

Route::prefix('system-control')->middleware(['web','auth'])->group(function () { 
    Route::get('/sidebar', [SystemControlController::class, 'index'])->name('system-control-sidebar');
    Route::post('/sidebar-store', [SystemControlController::class, 'sidebar_store'])->name('system-control-sidebar-store');
});