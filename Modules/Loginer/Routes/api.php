<?php
use Illuminate\Support\Facades\Route;
use Modules\Loginer\Http\Controllers\Api\LoginController;

Route::post('/login', [LoginController::class, 'login']); // public route

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/me', [LoginController::class, 'me']);
});
