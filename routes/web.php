<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KesehatanController;
use App\Http\Controllers\LogBeratController;
use App\Http\Controllers\LogistikController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SyariatController;
use App\Http\Controllers\TernakController;
use Illuminate\Support\Facades\Route;

// Auth Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout Route (Auth Only)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard Routes (Auth Required)
Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');

    Route::middleware('owner')->group(function () {
        Route::prefix('master')->group(function () {
            Route::get('/', [MasterController::class, 'index'])->name('master.index');
            Route::post('/tipe', [MasterController::class, 'storeTipe'])->name('tipe.store');
            Route::post('/ras', [MasterController::class, 'storeRas'])->name('ras.store');
            Route::post('/kandang', [MasterController::class, 'storeKandang'])->name('kandang.store');
            Route::post('/kriteria', [MasterController::class, 'storeKriteria'])->name('kriteria.store');

            Route::put('/tipe/{id}', [MasterController::class, 'updateTipe'])->name('tipe.update');
            Route::put('/ras/{id}', [MasterController::class, 'updateRas'])->name('ras.update');
            Route::put('/kandang/{id}', [MasterController::class, 'updateKandang'])->name('kandang.update');
            Route::put('/kriteria/{id}', [MasterController::class, 'updateKriteria'])->name('kriteria.update');

            Route::delete('/tipe/{id}', [MasterController::class, 'destroyTipe'])->name('tipe.destroy');
            Route::delete('/ras/{id}', [MasterController::class, 'destroyRas'])->name('ras.destroy');
            Route::delete('/kandang/{id}', [MasterController::class, 'destroyKandang'])->name('kandang.destroy');
            Route::delete('/kriteria/{id}', [MasterController::class, 'destroyKriteria'])->name('kriteria.destroy');  
        });

        Route::prefix('pengguna')->group(function () {
            Route::get('/', [PenggunaController::class, 'index'])->name('pengguna.index');
            Route::post('/', [PenggunaController::class, 'store'])->name('pengguna.store');
            Route::put('/{id}', [PenggunaController::class, 'update'])->name('pengguna.update');
            Route::delete('/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');
        });
    });

    Route::prefix('kesehatan')->group(function () {
        Route::get('/', [KesehatanController::class, 'index'])->name('kesehatan.index');
        Route::post('/', [KesehatanController::class, 'store']);
        Route::get('/{id}', [KesehatanController::class, 'show']);
        Route::delete('/{id}', [KesehatanController::class, 'destroy'])->middleware('owner');
    });

    Route::prefix('ternak')->group(function () {
        Route::get('/', [TernakController::class, 'index'])->name('ternak.index');
        Route::post('/', [TernakController::class, 'store'])->name('ternak.store');
        Route::patch('/{id}/nama-panggilan', [TernakController::class, 'updateNamaPanggilan']);
        Route::put('/{id}', [TernakController::class, 'update']);
        Route::delete('/{id}', [TernakController::class, 'destroy'])->middleware('owner');
        Route::get('/{id}/keuangan', [TernakController::class, 'keuangan'])->middleware('owner');

        Route::get('/{id}/log-berat', [LogBeratController::class, 'index']);
        Route::post('/{id}/log-berat', [LogBeratController::class, 'store']);
    });

    Route::prefix('logistik')->group(function () {
        Route::get('/', [LogistikController::class, 'index'])->name('logistik.index');
        Route::post('/pakan', [LogistikController::class, 'storePakan']);
        Route::post('/distribusi', [LogistikController::class, 'storeDistribusi']);
    });

    Route::prefix('syariat')->group(function () {
        Route::get('/', [SyariatController::class, 'index'])->name('syariat.index');
        Route::post('/pemeriksaan', [SyariatController::class, 'storePemeriksaan']);
        Route::post('/skkh', [SyariatController::class, 'storeSkkh']);
        Route::get('/skkh/{id}', [SyariatController::class, 'showSkkh']);
        Route::get('/pemeriksaan/{id}', [SyariatController::class, 'showPemeriksaan']);
        Route::delete('/pemeriksaan/{id}', [SyariatController::class, 'destroyPemeriksaan']);
        Route::delete('/skkh/{id}', [SyariatController::class, 'destroySkkh']);
    });
});