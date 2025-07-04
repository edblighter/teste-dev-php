<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExternalSearchController;
use App\Http\Controllers\Api\SupplierController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('suppliers', SupplierController::class);
Route::get('/externalsearch/{cnpj}', [ExternalSearchController::class,'search']);
