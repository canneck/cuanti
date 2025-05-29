<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IncomeController;

Route::apiResource('categories', CategoryController::class);
Route::post('categories/{id}/updateStatus', [CategoryController::class, 'updateStatus']);
Route::post('categories/many', [CategoryController::class, 'storeMany']);

Route::apiResource('incomes', IncomeController::class);
Route::post('incomes/{id}/updateStatus', [IncomeController::class, 'updateStatus']);
Route::post('incomes/many', [IncomeController::class, 'storeMany']);
