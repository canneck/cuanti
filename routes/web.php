<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::resource('categories', CategoryController::class);
Route::post('categories/{id}/updateStatus', [CategoryController::class, 'updateStatus'])->name('categories.updateStatus');
