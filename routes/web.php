<?php

use App\Http\Controllers\DetectionController;
use App\Http\Controllers\StatsController;
use App\Http\Middleware\TrackVisitor;
use Illuminate\Support\Facades\Route;

Route::middleware(TrackVisitor::class)->group(function () {
    Route::get('/', [DetectionController::class, 'home'])->name('beranda');
    Route::get('/deteksi', [DetectionController::class, 'create'])->name('deteksi');
    Route::post('/deteksi', [DetectionController::class, 'store'])->name('deteksi.store');
    Route::get('/hasil/{analysis}', [DetectionController::class, 'show'])->name('hasil');
    Route::get('/riwayat', [DetectionController::class, 'history'])->name('riwayat');
    Route::delete('/riwayat/{analysis}', [DetectionController::class, 'destroy'])->name('riwayat.hapus');
    Route::get('/statistik', StatsController::class)->name('statistik');
});
