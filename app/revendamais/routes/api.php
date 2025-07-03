<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//Route::apiResource('suppliers', SupplierController::class);
//Route::get('/externalsearch/{cnpj}', [ExternalSearchController::class,'search']);
