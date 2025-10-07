<?php

use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\InstansiController;
use App\Http\Controllers\SuperAdmin\PackageController;
use App\Http\Controllers\SuperAdmin\SubscriptionController;
use App\Http\Controllers\SuperAdmin\SupportRequestController;
use App\Http\Controllers\SuperAdmin\NotificationController;
use App\Http\Controllers\SuperAdmin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
    Route::get('/packages/feature-configuration', [PackageController::class, 'featureConfiguration'])->name('packages.feature-configuration');
    Route::post('/packages/feature-configuration', [PackageController::class, 'updateFeatureConfiguration'])->name('packages.update-feature-configuration');
    Route::resource('packages', PackageController::class);

    // Subscription management
    Route::get('/subscriptions/analytics', [SubscriptionController::class, 'analytics'])->name('subscriptions.analytics');
    Route::get('/subscriptions/subscription-requests', [SubscriptionController::class, 'subscriptionRequests'])->name('subscriptions.subscription-requests');
    Route::post('/subscriptions/payment/{paymentId}/process', [SubscriptionController::class, 'processPayment'])->name('subscriptions.process-payment');
    Route::get('/subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('/subscriptions/{subscription}/edit', [SubscriptionController::class, 'edit'])->name('subscriptions.edit');
    Route::patch('/subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
    Route::patch('/subscriptions/{subscription}/extend', [SubscriptionController::class, 'extend'])->name('subscriptions.extend');
    Route::resource('subscriptions', SubscriptionController::class)->except(['create', 'store']);

    // Support Requests
    Route::get('/support-requests', [SupportRequestController::class, 'index'])->name('support_requests.index');
    Route::get('/support-requests/{id}', [SupportRequestController::class, 'show'])->name('support_requests.show');
    Route::patch('/support-requests/{id}', [SupportRequestController::class, 'update'])->name('support_requests.update');

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

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/bulk', [NotificationController::class, 'bulk'])->name('notifications.bulk');
    Route::post('/notifications/send-bulk', [NotificationController::class, 'sendBulk'])->name('notifications.send-bulk');
    Route::put('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::put('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [NotificationController::class, 'destroyAll'])->name('notifications.destroy-all');
    Route::get('/notifications/count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('/api/employees', [NotificationController::class, 'getEmployees'])->name('api.employees');

    // Settings
    Route::get('/settings/profile', [DashboardController::class, 'settingsProfile'])->name('settings.profile');
    Route::patch('/settings/profile', [DashboardController::class, 'updateSettingsProfile'])->name('settings.profile.update');
    Route::post('/settings/validate-password', [DashboardController::class, 'validateCurrentPassword'])->name('settings.validate-password');
    Route::get('/settings/notifications', [DashboardController::class, 'settingsNotifications'])->name('settings.notifications');
    Route::patch('/settings/notifications', [DashboardController::class, 'updateSettingsNotifications'])->name('settings.notifications.update');

});
