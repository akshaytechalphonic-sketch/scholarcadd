<?php

use Illuminate\Support\Facades\Route;
use Modules\Loginer\Http\Controllers\LoginerController;
use Modules\Loginer\Http\Controllers\RoleController;
use Modules\Core\Http\Controllers\LanguageController;


Route::middleware(['web'])->group(function () {
    Route::get('/', [LoginerController::class, 'login'])->name('login');
    Route::get('/login', [LoginerController::class, 'login'])->name('login');
    Route::get('/sign-up', [LoginerController::class, 'sign_up'])->name('sign-up');
    Route::post('/registration-request-store', [LoginerController::class, 'registrationRequest_Store'])->name('registration.request.store');
    Route::post('/registration-otp-matched-verify', [LoginerController::class, 'registrationRequest_otpStore'])->name('registration-otp-matched-verify');
    Route::post('/registration-request-store-final', [LoginerController::class, 'registrationRequest_StoreFinal'])->name('registration.request.store-final');
    Route::get('/user-profile', [LoginerController::class, 'user_profile'])->name('user-profile');
    Route::post('/login-check', [LoginerController::class, 'login_check'])->name('login-check');
    Route::get('forgot-password', [LoginerController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [LoginerController::class, 'store'])
                ->name('password.email');

     Route::get('reset-password/{token}', [LoginerController::class, 'create_password'])
                ->name('password.reset');

    Route::post('reset-password', [LoginerController::class, 'passwordstore'])
                ->name('password.store');
});

Route::middleware(['web','auth'])->group(function () {
    Route::Post('/logout', [LoginerController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [LoginerController::class, 'dashboard'])->name('dashboard');
    Route::get('/2fa/verify', [LoginerController::class, 'showVerifyForm'])->name('2fa.verify');
    Route::post('/2fa/verify', [LoginerController::class, 'verifyToken']);
    Route::post('/2fa/resend', [LoginerController::class, 'reSendToken'])->name('2fa.resend');
});

//Role & Permission

Route::prefix('roles')->middleware(['web','auth'])->group(function () {
    Route::get('/list', [RoleController::class, 'getAllRoles'])->name('roles-list')->middleware('can:list master');
    Route::get('/edit', [RoleController::class, 'createRole'])->name('roles-edit')->middleware('can:create master');
    Route::get('/create', [RoleController::class, 'createRole'])->name('roles-create')->middleware('can:create master');
    Route::post('/store', [RoleController::class, 'storeRole'])->name('roles-store')->middleware('can:create master');
    Route::post('/update', [RoleController::class, 'updateExistingRole'])->name('roles-update')->middleware('can:create master');
    Route::get('/destroy/{id}', [RoleController::class, 'deleteExistingRole'])->name('roles-destroy')->middleware('can:create master');
});

Route::prefix('permissions')->middleware(['web','auth'])->group(function () {
    Route::get('/list', [RoleController::class, 'getAllPermissions'])->name('permissions-list')->middleware('can:list master');
    Route::post('/store', [RoleController::class, 'storePermission'])->name('permissions-store')->middleware('can:create master');
    Route::get('/destroy', [RoleController::class, 'deleteExistingPermission'])->name('permissions-destroy')->middleware('can:create master');

});

Route::middleware(['web','auth'])->group(function () {
     Route::get('/get-employee-by-id', [RoleController::class, 'get_employee_by_id'])->name('get-employee-by-id');
});

Route::prefix('users')->middleware(['web','auth'])->group(function () {
    Route::get('/list', [RoleController::class, 'userList'])->name('users-list')->middleware('can:list user');
    Route::get('/edit', [RoleController::class, 'userEdit'])->name('users-edit')->middleware('can:create user');
    Route::post('/update', [RoleController::class, 'userUpdate'])->name('users-update')->middleware('can:create user');
    Route::get('/view', [RoleController::class, 'viewUser'])->name('users-view')->middleware('can:view user');
    Route::get('/add', [RoleController::class, 'addUser'])->name('users-add')->middleware('can:create user');
    Route::post('/store', [RoleController::class, 'storeUser'])->name('users-store')->middleware('can:create user');

});
Route::middleware(['web'])->group(function () {
    Route::any('language-switch', [LanguageController::class, 'languageSwitch'])->name('language-switch');
});

