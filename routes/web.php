<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard.pages.index');
});
Route::get('/info', function () {
    return view('dashboard.pages.info');
});

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
    Route::get('/index', function () {
        return view('dashboard.pages.settings.wilayah');
    });
    Route::get('/kondisi', function () {
        return view('dashboard.pages.settings.kondisi');
    });
    Route::get('/petugas', function () {
        return view('dashboard.pages.settings.petugas');
    });
});
