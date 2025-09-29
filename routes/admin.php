<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\SubscriptionController;
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
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/reset', [SettingsController::class, 'reset'])->name('settings.reset');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications/{id}/read', [App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::put('/notifications/{id}/unread', [App\Http\Controllers\Admin\NotificationController::class, 'markAsUnread'])->name('notifications.unread');
    Route::put('/notifications/mark-all-read', [App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'destroyAll'])->name('notifications.destroy-all');
    Route::get('/notifications/unread-count', [App\Http\Controllers\Admin\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
});
