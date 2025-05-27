<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::apiResource('categories', CategoryController::class);
Route::post('categories/{id}/updateStatus', [CategoryController::class, 'updateStatus']);
Route::post('categories/many', [CategoryController::class, 'storeMany']);
