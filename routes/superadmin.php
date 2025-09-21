<?php

use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\InstansiController;
use App\Http\Controllers\SuperAdmin\PackageController;
use App\Http\Controllers\SuperAdmin\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Instansi management
    Route::resource('instansi', InstansiController::class);

    // Package management
    Route::resource('packages', PackageController::class);

    // Subscription management
    Route::resource('subscriptions', SubscriptionController::class)->except(['create', 'store', 'edit', 'update']);
});
