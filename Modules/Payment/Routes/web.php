<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\PaymentController;

{
    Route::get('invoice', function () {
        return view('payment::invoice.create');
    })->name('invoice.create');

}