<?php

use App\Http\Controllers\CatatMeterController;
use App\Http\Controllers\DashboardController;
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
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('role:admin');
    Route::get('/dashboard/notifikasi', [DashboardController::class, 'notifikasi'])->name('dashboard.notifikasi');
    Route::post('/dashboard/refresh', [DashboardController::class, 'refresh'])->name('dashboard.refresh');

    // Resource routes for 'info'
    Route::resource('info', InfoController::class);

    // Grouped routes for '/pelanggan'
    Route::prefix('pelanggan')->group(function () {
        Route::get('/peta', [PetaPelangganController::class, 'index'])->name('peta.index');
        Route::get('data-peta', [PetaPelangganController::class, 'data'])->name('pelanggan.data-peta');
        Route::resource('dsml', DsmlPelangganController::class);
        Route::get('data', [PelangganController::class, 'data'])->name('pelanggan.data');
        // Pelanggan Baru - using 'baru' path
        Route::get('baru', [PelangganBaruController::class, 'index'])->name('pelanggan.baru.index');
        Route::post('baru', [PelangganBaruController::class, 'store'])->name('pelanggan.baru.store');
        Route::get('baru/create', [PelangganBaruController::class, 'create'])->name('pelanggan.baru.create');
        Route::get('baru/{pelanggan}/edit', [PelangganBaruController::class, 'edit'])->name('pelanggan.baru.edit');
        Route::get('baru/{id}', [PelangganBaruController::class, 'show'])->name('pelanggan.baru.show');
        Route::put('baru/{id}', [PelangganBaruController::class, 'update'])->name('pelanggan.baru.update');
        Route::delete('baru/{id}', [PelangganBaruController::class, 'destroy'])->name('pelanggan.baru.destroy');
        Route::post('baru/{id}/restore', [PelangganBaruController::class, 'restore'])->name('pelanggan.baru.restore');

        // Pelanggan resource routes
        Route::get('/', [PelangganController::class, 'index'])->name('pelanggan.index');
        Route::post('/', [PelangganController::class, 'store'])->name('pelanggan.store');
        Route::get('/{pelanggan}', [PelangganController::class, 'show'])->name('pelanggan.show');
        Route::patch('/{pelanggan}/toggle-status', [PelangganController::class, 'toggleStatus'])->name('pelanggan.toggle-status');
    });

    // Grouped routes for '/cater'
    Route::prefix('cater')->group(function () {
        // Static routes first
        Route::get('/', [CatatMeterController::class, 'index'])->name('cater.index');
        Route::post('/', [CatatMeterController::class, 'store'])->name('cater.store');
        Route::get('create', [CatatMeterController::class, 'create'])->name('cater.create');
        Route::get('data', [CatatMeterController::class, 'data'])->name('cater.data');
        Route::get('excel', [CatatMeterController::class, 'excel'])->name('cater.excel');
        Route::get('tidak-terdaftar', function () {
            $page = 'Catat Meter Tidak Terdaftar';
            return view('dashboard.pages.catat-meter.catat-meter-tidak-terdaftar')->with(compact('page'));
        })->name('cater.tidak-terdaftar');
        Route::get('data-tidak-terdaftar', [CatatMeterController::class, 'dataTidakTerdaftar'])->name('cater.data-tidak-terdaftar');
        Route::get('urutan', function () {
            $page = 'Urutan Catat Meter';
            return view('dashboard.pages.catat-meter.urutan-catat-meter')->with(compact('page'));
        })->name('cater.urutan');
        Route::get('data-urutan', [CatatMeterController::class, 'dataUrutan'])->name('cater.data-urutan');
        // Parameterized routes last
        Route::get('{cater}', [CatatMeterController::class, 'show'])->name('cater.show');
        Route::get('{cater}/edit', [CatatMeterController::class, 'edit'])->name('cater.edit');
        Route::put('{cater}', [CatatMeterController::class, 'update'])->name('cater.update');
        Route::delete('{cater}', [CatatMeterController::class, 'destroy'])->name('cater.destroy');
        Route::post('{cater}/update-stand', [CatatMeterController::class, 'updateStand'])->name('cater.update-stand');
    });

    // Grouped routes for '/rekap'
    Route::prefix('rekap')->group(function () {
        Route::get('index', [RekapController::class, 'index'])->name('rekap.index');
        Route::get('data-catat-meter', [RekapController::class, 'dataCatatMeter'])->name('rekap.data-catat-meter');
        Route::get('kondisi', [RekapController::class, 'kondisi'])->name('rekap.kondisi');
        Route::get('data-kondisi', [RekapController::class, 'dataKondisi'])->name('rekap.data-kondisi');
        Route::get('wilayah', [RekapController::class, 'wilayah'])->name('rekap.wilayah');
        Route::get('data-wilayah', [RekapController::class, 'dataWilayah'])->name('rekap.data-wilayah');
        Route::post('', [RekapController::class, 'store'])->name('rekap.store');
        Route::get('{id}', [RekapController::class, 'show'])->name('rekap.show');
        Route::put('{id}', [RekapController::class, 'update'])->name('rekap.update');
        Route::delete('{id}', [RekapController::class, 'destroy'])->name('rekap.destroy');
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
