<?php

use App\Http\Controllers\DsmlPelangganController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\KondisiController;
use App\Http\Controllers\PelangganBaruController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PetaPelangganController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

// Main dashboard route
Route::get('/', function () {
    return view('dashboard.pages.index');
});

// Resource routes for 'info'
Route::resource('info', InfoController::class);

// Grouped routes for '/pelanggan'
Route::prefix('pelanggan')->group(function () {
    Route::resource('pelanggan-baru', PelangganBaruController::class);
    Route::resource('', PelangganController::class);  // Changed from '/'
    Route::resource('peta', PetaPelangganController::class);
    Route::resource('dsml', DsmlPelangganController::class);
});

// Grouped routes for '/cater'
Route::prefix('cater')->group(function () {
    Route::get('index', function () {
        return view('dashboard.pages.catat-meter.catat-meter');
    });
    Route::get('tidak-terdaftar', function () {
        return view('dashboard.pages.catat-meter.catat-meter-tidak-terdaftar');
    });
    Route::get('urutan', function () {
        return view('dashboard.pages.catat-meter.urutan-catat-meter');
    });
});

// Grouped routes for '/rekap'
Route::prefix('rekap')->group(function () {
    Route::get('index', function () {
        return view('dashboard.pages.data-rekap.rekap-catat-meter');
    });
    Route::get('kondisi', function () {
        return view('dashboard.pages.data-rekap.rekap-kondisi');
    });
    Route::get('wilayah', function () {
        return view('dashboard.pages.data-rekap.rekap-wilayah');
    });
});

// Grouped routes for '/settings'
Route::prefix('settings')->group(function () {
    Route::resource('wilayah', WilayahController::class);
    Route::get('wilayahs/data', [WilayahController::class, 'data'])->name('wilayah.data');
    Route::resource('kondisi', KondisiController::class);
    Route::resource('petugas', PetugasController::class);
    Route::get('petugases/data', [PetugasController::class, 'data'])->name('petugas.data');
});
