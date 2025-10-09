<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\EmployeePolicyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Employee management
    Route::resource('employees', EmployeeController::class);

    // Attendance management
    Route::resource('attendance', AttendanceController::class);
    Route::get('attendance/employee/{userId}', [AttendanceController::class, 'employeeAttendance'])->name('attendance.employee');

    // Payroll management
    Route::resource('payroll', PayrollController::class);

    // Branch management
    Route::resource('branches', BranchController::class);

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/profile', [SettingsController::class, 'profileSettings'])->name('settings.profile');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/reset', [SettingsController::class, 'reset'])->name('settings.reset');

    // Subscription management
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::get('/subscription/{id}/edit', [SubscriptionController::class, 'edit'])->name('subscription.edit');
    Route::put('/subscription/{id}', [SubscriptionController::class, 'update'])->name('subscription.update');
    Route::post('/subscription/request', [SubscriptionController::class, 'handleRequest'])->name('subscription.request');
    Route::post('/subscription/extend', [SubscriptionController::class, 'requestExtension'])->name('subscription.extend');
    Route::post('/subscription/upgrade', [SubscriptionController::class, 'requestUpgrade'])->name('subscription.upgrade');
    Route::post('/subscription/payment/{paymentId}/process', [SubscriptionController::class, 'processPayment'])->name('subscription.process-payment');
    Route::post('/subscription/payment/{paymentId}/cancel', [SubscriptionController::class, 'cancelPayment'])->name('subscription.cancel-payment');

    // Transaction management
    Route::get('/subscription/transaction/{requestId}', [TransactionController::class, 'show'])->name('subscription.transaction');
    Route::post('/subscription/transaction/{requestId}/process', [TransactionController::class, 'processPayment'])->name('subscription.transaction.process');

    // Employee Policies
    Route::resource('employee-policies', EmployeePolicyController::class);
    Route::patch('employee-policies/{employee_policy}/toggle-status', [EmployeePolicyController::class, 'toggleStatus'])->name('employee-policies.toggle-status');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications/{id}/read', [App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::put('/notifications/{id}/unread', [App\Http\Controllers\Admin\NotificationController::class, 'markAsUnread'])->name('notifications.unread');
    Route::put('/notifications/mark-all-read', [App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'destroyAll'])->name('notifications.destroy-all');
    Route::get('/notifications/unread-count', [App\Http\Controllers\Admin\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
});
