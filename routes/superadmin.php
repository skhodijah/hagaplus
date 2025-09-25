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
    Route::patch('/instansi/{instansi}/toggle-status', [InstansiController::class, 'toggleStatus'])->name('instansi.toggle-status');
    Route::resource('instansi', InstansiController::class);

    // Package management
    Route::resource('packages', PackageController::class);

    // Subscription management
    Route::get('/subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('/subscriptions/{subscription}/edit', [SubscriptionController::class, 'edit'])->name('subscriptions.edit');
    Route::patch('/subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
    Route::patch('/subscriptions/{subscription}/extend', [SubscriptionController::class, 'extend'])->name('subscriptions.extend');
    Route::resource('subscriptions', SubscriptionController::class)->except(['create', 'store']);
});
