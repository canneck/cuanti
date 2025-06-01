<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;

Route::apiResource('categories', CategoryController::class);
Route::post('categories/{id}/updateStatus', [CategoryController::class, 'updateStatus']);
Route::post('categories/many', [CategoryController::class, 'storeMany']);

Route::apiResource('entities', EntityController::class);
Route::post('entities/{id}/updateStatus', [EntityController::class, 'updateStatus']);
Route::post('entities/many', [EntityController::class, 'storeMany']);

Route::apiResource('incomes', IncomeController::class);
Route::post('incomes/{id}/updateStatus', [IncomeController::class, 'updateStatus']);
Route::post('incomes/many', [IncomeController::class, 'storeMany']);

Route::apiResource('expenses', ExpenseController::class);
Route::post('expenses/{id}/updateStatus', [ExpenseController::class, 'updateStatus']);
Route::post('expenses/many', [ExpenseController::class, 'storeMany']);
