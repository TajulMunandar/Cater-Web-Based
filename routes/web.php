<?php

use App\Http\Controllers\CatatMeterController;
use App\Http\Controllers\DsmlPelangganController;
use App\Http\Controllers\GolonganController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\KondisiController;
use App\Http\Controllers\PelangganBaruController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PetaPelangganController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware('auth')->group(function () {
    // Main dashboard route
    Route::get('/', function () {
        $page = "dashboard";
        return view('dashboard.pages.index')->with(compact('page'));
    })->middleware('role:admin');

    // Resource routes for 'info'
    Route::resource('info', InfoController::class);

    // Grouped routes for '/pelanggan'
    Route::prefix('pelanggan')->group(function () {
        
        // Pelanggan Baru - using 'baru' path
        Route::get('baru', [PelangganBaruController::class, 'index'])->name('pelanggan.baru.index');
        Route::post('baru', [PelangganBaruController::class, 'store'])->name('pelanggan.baru.store');
        Route::get('baru/create', [PelangganBaruController::class, 'create'])->name('pelanggan.baru.create');
        Route::get('baru/{id}/edit', [PelangganBaruController::class, 'edit'])->name('pelanggan.baru.edit');
        Route::get('baru/{id}', [PelangganBaruController::class, 'show'])->name('pelanggan.baru.show');
        Route::put('baru/{id}', [PelangganBaruController::class, 'update'])->name('pelanggan.baru.update');
        Route::delete('baru/{id}', [PelangganBaruController::class, 'destroy'])->name('pelanggan.baru.destroy');
        
        // Pelanggan resource routes
        Route::get('/', [PelangganController::class, 'index'])->name('pelanggan.index');
        Route::post('/', [PelangganController::class, 'store'])->name('pelanggan.store');
        Route::get('/{pelanggan}/edit', [PelangganController::class, 'edit'])->name('pelanggan.edit');
        Route::put('/{pelanggan}', [PelangganController::class, 'update'])->name('pelanggan.update');
        Route::delete('/{pelanggan}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');
        Route::get('data', [PelangganController::class, 'data'])->name('pelanggan.data');
        
        Route::resource('peta', PetaPelangganController::class);
        Route::get('peta/data', [PetaPelangganController::class, 'data']);
        Route::resource('dsml', DsmlPelangganController::class);
    });

    // Grouped routes for '/cater'
    Route::prefix('cater')->group(function () {
        Route::resource('', CatatMeterController::class)->names('cater');
        Route::get('data', [CatatMeterController::class, 'data'])->name('cater.data');
        Route::get('tidak-terdaftar', function () {
            $page = 'Catat Meter Tidak Terdaftar';
            return view('dashboard.pages.catat-meter.catat-meter-tidak-terdaftar')->with(compact('page'));
        })->name('cater.tidak-terdaftar');
        Route::get('urutan', function () {
            $page = 'Urutan Catat Meter';
            return view('dashboard.pages.catat-meter.urutan-catat-meter')->with(compact('page'));
        })->name('cater.urutan');
    });

    // Grouped routes for '/rekap'
    Route::prefix('rekap')->group(function () {
        Route::get('index', [RekapController::class, 'index']);
        Route::get('data-catat-meter', [RekapController::class, 'dataCatatMeter'])->name('rekap.data-catat-meter');
        Route::post('', [RekapController::class, 'store'])->name('rekap.store');
        Route::get('{id}', [RekapController::class, 'show'])->name('rekap.show');
        Route::put('{id}', [RekapController::class, 'update'])->name('rekap.update');
        Route::delete('{id}', [RekapController::class, 'destroy'])->name('rekap.destroy');
        Route::get('kondisi', [RekapController::class, 'kondisi']);
        Route::get('data-kondisi', [RekapController::class, 'dataKondisi'])->name('rekap.data-kondisi');
        Route::get('wilayah', [RekapController::class, 'wilayah']);
        Route::get('data-wilayah', [RekapController::class, 'dataWilayah'])->name('rekap.data-wilayah');
    });

    // Grouped routes for '/settings'
    Route::prefix('settings')->group(function () {
        Route::resource('wilayah', WilayahController::class);
        Route::get('wilayahs/data', [WilayahController::class, 'data'])->name('wilayah.data');
        Route::resource('kondisi', KondisiController::class);
        Route::resource('petugas', PetugasController::class);
        Route::get('petugases/data', [PetugasController::class, 'data'])->name('petugas.data');
        
        // Golongan routes
        Route::resource('golongan', GolonganController::class)->except(['show']);
        Route::get('golongans/data', [GolonganController::class, 'data'])->name('golongan.data');
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
