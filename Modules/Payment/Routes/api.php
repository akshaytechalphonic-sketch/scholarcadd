<?php
use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\API\InvoiceController;

Route::group(['middleware' => 'api', 'prefix' => 'invoice'], function () {
    Route::post('/download', [InvoiceController::class, 'downloadInvoicePDF']);
});
