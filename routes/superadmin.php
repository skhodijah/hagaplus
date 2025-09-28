<?php

use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\InstansiController;
use App\Http\Controllers\SuperAdmin\PackageController;
use App\Http\Controllers\SuperAdmin\SubscriptionController;
use App\Http\Controllers\SuperAdmin\SupportRequestController;
use App\Http\Controllers\SuperAdmin\NotificationController;
use App\Http\Controllers\SuperAdmin\ChatController;
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
    Route::get('/packages/feature-configuration', [PackageController::class, 'featureConfiguration'])->name('packages.feature-configuration');
    Route::post('/packages/feature-configuration', [PackageController::class, 'updateFeatureConfiguration'])->name('packages.update-feature-configuration');
    Route::get('/packages/pricing-settings', [PackageController::class, 'pricingSettings'])->name('packages.pricing-settings');
    Route::post('/packages/pricing-settings', [PackageController::class, 'updatePricingSettings'])->name('packages.update-pricing-settings');
    Route::resource('packages', PackageController::class);

    // Subscription management
    Route::get('/subscriptions/analytics', [SubscriptionController::class, 'analytics'])->name('subscriptions.analytics');
    Route::get('/subscriptions/payment-history', [SubscriptionController::class, 'paymentHistory'])->name('subscriptions.payment-history');
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

    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/start', [ChatController::class, 'startChat'])->name('chat.start');
    Route::get('/chat/{chat}', [ChatController::class, 'show'])->name('chat.show');
    Route::get('/chat/{chat}/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/{chat}/messages', [ChatController::class, 'sendMessage'])->name('chat.send-message');
});
