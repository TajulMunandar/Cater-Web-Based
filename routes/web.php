<?php

use App\Http\Controllers\InfoController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard.pages.index');
});

Route::resource('info', InfoController::class);


route::prefix('/pelanggan')->group(function () {
    Route::get('/index', function () {
        return view('dashboard.pages.pelanggan.pelanggan');
    });
    Route::get('/pelanggan-baru', function () {
        return view('dashboard.pages.pelanggan.pelanggan-baru');
    });
    Route::get('/peta', function () {
        return view('dashboard.pages.pelanggan.peta');
    });
    Route::get('/dsml', function () {
        return view('dashboard.pages.pelanggan.dsml');
    });
});

route::prefix('/cater')->group(function () {
    Route::get('/index', function () {
        return view('dashboard.pages.catat-meter.catat-meter');
    });
    Route::get('/tidak-terdaftar', function () {
        return view('dashboard.pages.catat-meter.catat-meter-tidak-terdaftar');
    });
    Route::get('/urutan', function () {
        return view('dashboard.pages.catat-meter.urutan-catat-meter');
    });
});

route::prefix('/rekap')->group(function () {
    Route::get('/index', function () {
        return view('dashboard.pages.data-rekap.rekap-catat-meter');
    });
    Route::get('/kondisi', function () {
        return view('dashboard.pages.data-rekap.rekap-kondisi');
    });
    Route::get('/wilayah', function () {
        return view('dashboard.pages.data-rekap.rekap-wilayah');
    });
});

route::prefix('/settings')->group(function () {
    Route::resource('/wilayah', WilayahController::class);

    Route::get('/wilayahs/data', [WilayahController::class, 'data'])->name('wilayah.data');

    Route::get('/kondisi', function () {
        return view('dashboard.pages.settings.kondisi');
    });
    Route::get('/petugas', function () {
        return view('dashboard.pages.settings.petugas');
    });
});
