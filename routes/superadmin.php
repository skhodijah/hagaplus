<?php

use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\InstansiController;
use App\Http\Controllers\SuperAdmin\PackageController;
use App\Http\Controllers\SuperAdmin\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Analytics
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics.index');

    // Financial
    Route::get('/financial', [DashboardController::class, 'financial'])->name('financial.index');

    // System
    Route::get('/system/health', [DashboardController::class, 'systemHealth'])->name('system.health');

    // Reports
    Route::get('/reports/activities', [DashboardController::class, 'reportsActivities'])->name('reports.activities');

    // Instansi management
    Route::get('/instansi/monitoring', [InstansiController::class, 'monitoring'])->name('instansi.monitoring');
    Route::resource('instansi', InstansiController::class);

    // Package management
    Route::resource('packages', PackageController::class);

    // Subscription management
    Route::resource('subscriptions', SubscriptionController::class)->except(['create', 'store', 'edit', 'update']);
});
