<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ServicesController;

Route::get('/doctors', [DoctorController::class, 'index']);
Route::post('/doctors', [DoctorController::class, 'store']);
Route::get('/doctors/{doctor}', [DoctorController::class, 'show']);
Route::put('/doctors/{doctor}', [DoctorController::class, 'update']);
Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('doctors', DoctorController::class);

Route::apiResource('services', ServicesController::class);
Route::get('/services/category/{category}', [ServicesController::class, 'getByCategory']);
Route::get('/service-categories', [ServicesController::class, 'getCategories']);
