<?php
use Illuminate\Support\Facades\Route;

Route::get('brand-logo/upload', function () {
    return view('pages.brand-logo');
});
