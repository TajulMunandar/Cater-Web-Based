<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatatMeterController;
use App\Http\Controllers\Api\PelangganController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/pelanggan/belum-dicatat', [PelangganController::class, 'belumDicatat']);

    Route::post('/catat-meter', [CatatMeterController::class, 'store']);
    Route::get('/kondisi', [CatatMeterController::class, 'kondisi']);
});
