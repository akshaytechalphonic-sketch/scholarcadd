<?php

use Illuminate\Support\Facades\Route;
use Modules\NumberSequence\Http\Controllers\NumberSequenceController;

//Number Sequence
Route::group(['prefix' => 'number-sequence', 'middleware' => ['auth','web']], function(){
    Route::get('/number-sequence-list', [NumberSequenceController::class, 'index'])->name('numbersequence-list');
    Route::get('/add-number-sequence', [NumberSequenceController::class, 'add'])->name('numbersequence-add');
    Route::post('/add-number-sequence/store', [NumberSequenceController::class, 'store'])->name('numbersequence-store');
    Route::get('/number-sequence-map-list', [NumberSequenceController::class, 'index_map'])->name('numbersequence-map-list');
    Route::post('number-sequence-maping',[NumberSequenceController::class, 'store_mapping'])->name('numbersequence-maping');
    Route::get('/sequence-delete',[NumberSequenceController::class, 'destroy'])->name('numbersequence-delete');
});
