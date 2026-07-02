<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::post('/visits', [DashboardController::class, 'storeVisit'])->name('visits.store');
Route::post('/visits/{id}/exit', [DashboardController::class, 'exitVisit'])->name('visits.exit');
Route::post('/payments', [DashboardController::class, 'payFee'])->name('payments.store');
Route::post('/fees', [DashboardController::class, 'generateFee'])->name('fees.store');

