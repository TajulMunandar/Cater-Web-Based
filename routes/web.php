<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard.pages.index');
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
