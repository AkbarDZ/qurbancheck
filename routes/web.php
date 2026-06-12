<?php

use App\Http\Controllers\KesehatanController;
use App\Http\Controllers\LogistikController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\SyariatController;
use App\Http\Controllers\TernakController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboards.index');
});

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

Route::prefix('kesehatan')->group(function () {
    Route::get('/', [KesehatanController::class, 'index'])->name('kesehatan.index');
});

Route::prefix('ternak')->group(function () {
    Route::get('/', [TernakController::class, 'index'])->name('ternak.index');
});

Route::prefix('logistik')->group(function () {
    Route::get('/', [LogistikController::class, 'index'])->name('logistik.index');
});

Route::prefix('syariat')->group(function () {
    Route::get('/', [SyariatController::class, 'index'])->name('syariat.index');
});
