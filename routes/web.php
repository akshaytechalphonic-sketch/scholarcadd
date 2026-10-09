<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

Route::post('/encrypt-request', function (Request $request) {
    return response()->json([
        'encrypted' => encrypt(json_encode($request->input('payload')))
    ]);
})->name('encrypt-request');

// Route::get('/language-switch', [\Modules\Core\Http\Controllers\LanguageController::class, 'languageSwitch'])
//     ->name('language.switch');


// Route::get('/test', function (Request $request) {
//      dd('hhh');

// });


include_once __DIR__.'/test.php';
require __DIR__ . '/../Modules/LandingPage/Routes/web.php';


