<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;

Route::get('/doctors', [DoctorController::class, 'index']);
Route::post('/doctors', [DoctorController::class, 'store']);
Route::get('/doctors/{doctor}', [DoctorController::class, 'show']);
Route::put('/doctors/{doctor}', [DoctorController::class, 'update']);
Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy']);
