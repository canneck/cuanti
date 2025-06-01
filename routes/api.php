<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;

Route::apiResource('categories', CategoryController::class);
Route::post('categories/{id}/updateStatus', [CategoryController::class, 'updateStatus']);
Route::post('categories/many', [CategoryController::class, 'storeMany']);

Route::apiResource('entities', EntityController::class);
Route::post('entities/{id}/updateStatus', [EntityController::class, 'updateStatus']);
Route::post('entities/many', [EntityController::class, 'storeMany']);

Route::apiResource('incomes', IncomeController::class);
/*
    ?date_from=2024-05-01&date_to=2024-05-31 : filtros por rango de fechas
    ?entity_ids=1,2,3 : filtros por entidades (puede ser un array de IDs)
    ?reason=venta : filtros por motivo (LIKE)
    ?amount_from=100&amount_to=500
*/
Route::post('incomes/{id}/updateStatus', [IncomeController::class, 'updateStatus']);
Route::post('incomes/many', [IncomeController::class, 'storeMany']);

Route::apiResource('expenses', ExpenseController::class);
/*
    ?date_from=2024-05-01&date_to=2024-05-31
    &entity_ids=1,2,3
    &category_ids=4,5
    &description=papelería
    &amount_from=100&amount_to=500
*/
Route::post('expenses/{id}/updateStatus', [ExpenseController::class, 'updateStatus']);
Route::post('expenses/many', [ExpenseController::class, 'storeMany']);

Route::get('balance', [ReportController::class, 'balance']);
/*
    ?date_from=2024-05-01&date_to=2024-05-31
*/
