<?php

use Illuminate\Support\Facades\Route;
use Modules\PaymentHistory\Http\Controllers\PaymentHistoryController;

Route::prefix(strtolower('PaymentHistory'))->group(function () {
    Route::get('/', [PaymentHistoryController::class, 'index'])->name('paymenthistory.index');
});