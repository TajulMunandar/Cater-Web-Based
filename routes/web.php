<?php

use App\Http\Controllers\CatatMeterController;
use App\Http\Controllers\DsmlPelangganController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\KondisiController;
use App\Http\Controllers\PelangganBaruController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PetaPelangganController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Main dashboard route
    Route::get('/', function () {
        $page = "dashboard";
        return view('dashboard.pages.index')->with(compact('page'));
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
        Route::resource('', CatatMeterController::class)->names('cater');
        Route::get('data', [CatatMeterController::class, 'data'])->name('cater.data');
        Route::get('tidak-terdaftar', function () {
            $page = 'Catat Meter Tidak Terdaftar';
            return view('dashboard.pages.catat-meter.catat-meter-tidak-terdaftar')->with(compact('page'));
        });
        Route::get('urutan', function () {
            $page = 'Urutan Catat Meter';
            return view('dashboard.pages.catat-meter.urutan-catat-meter')->with(compact('page'));
        });
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
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
