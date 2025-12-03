<?php

use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\InstansiController;
use App\Http\Controllers\SuperAdmin\PackageController;
use App\Http\Controllers\SuperAdmin\SubscriptionController;

use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\PaymentMethodController;
use App\Http\Controllers\SuperAdmin\TransactionProcessingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:superadmin', 'verified'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Analytics
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics.index');

    // Financial
    Route::get('/financial', [DashboardController::class, 'financial'])->name('financial.index');


    // Reports
    Route::get('/reports/activities', [DashboardController::class, 'reportsActivities'])->name('reports.activities');

    // User management (Superadmin only)
    Route::middleware(['role:superadmin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
    });

    // Instansi management
    Route::get('/instansi/monitoring', [InstansiController::class, 'monitoring'])->name('instansi.monitoring');
    Route::patch('/instansi/{instansi}/toggle-status', [InstansiController::class, 'toggleStatus'])->name('instansi.toggle-status');
    Route::resource('instansi', InstansiController::class);

    // Package management
    Route::resource('packages', PackageController::class);

    // Subscription management
    Route::get('/subscriptions/analytics', [SubscriptionController::class, 'analytics'])->name('subscriptions.analytics');
    Route::get('/subscriptions/subscription-requests', [SubscriptionController::class, 'subscriptionRequests'])->name('subscriptions.subscription-requests');
    Route::get('/subscriptions/invoice-preview', [SubscriptionController::class, 'invoicePreview'])->name('subscriptions.invoice-preview');
    Route::post('/subscriptions/payment/{paymentId}/process', [SubscriptionController::class, 'processPayment'])->name('subscriptions.process-payment');
    Route::get('/subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('/subscriptions/{subscription}/edit', [SubscriptionController::class, 'edit'])->name('subscriptions.edit');
    Route::patch('/subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
    Route::patch('/subscriptions/{subscription}/extend', [SubscriptionController::class, 'extend'])->name('subscriptions.extend');
    Route::resource('subscriptions', SubscriptionController::class)->except(['create', 'store']);

    // Transaction processing
    Route::get('/subscriptions/process-transaction/{requestId}', [TransactionProcessingController::class, 'show'])->name('subscriptions.process-transaction');
    Route::post('/subscriptions/process-transaction/{requestId}/approve', [TransactionProcessingController::class, 'approve'])->name('subscriptions.process-transaction.approve');
    Route::post('/subscriptions/process-transaction/{requestId}/reject', [TransactionProcessingController::class, 'reject'])->name('subscriptions.process-transaction.reject');

    // Reports extras
    Route::get('/reports/usage', function () {
        return view('superadmin.reports.usage');
    })->name('reports.usage');
    Route::get('/reports/performance', function () {
        return view('superadmin.reports.performance');
    })->name('reports.performance');
    Route::get('/reports/export', function () {
        return view('superadmin.reports.export');
    })->name('reports.export');

    // Settings
    Route::get('/settings/profile', [DashboardController::class, 'settingsProfile'])->name('settings.profile');
    Route::patch('/settings/profile', [DashboardController::class, 'updateSettingsProfile'])->name('settings.profile.update');
    Route::post('/settings/validate-password', [DashboardController::class, 'validateCurrentPassword'])->name('settings.validate-password');

    // Payment Methods
    Route::resource('payment-methods', PaymentMethodController::class);
    Route::patch('payment-methods/{payment_method}/toggle-status', [PaymentMethodController::class, 'toggleStatus'])->name('payment-methods.toggle-status');

    // System Settings
    Route::post('/settings/toggle-email-verification', [App\Http\Controllers\SuperAdmin\SystemSettingController::class, 'toggleEmailVerification'])->name('settings.toggle-email-verification');

});
