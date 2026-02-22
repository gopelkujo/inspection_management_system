<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/master-data', [\App\Http\Controllers\Api\MasterDataController::class, 'index']);
Route::apiResource('inspections', \App\Http\Controllers\Api\InspectionController::class);
