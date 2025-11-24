<?php

use App\Http\Controllers\Employee\DashboardController;
use App\Http\Controllers\Employee\AttendanceController;
use App\Http\Controllers\Employee\LeaveController;
use App\Http\Controllers\Employee\PayrollController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::redirect('/', 'dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Employee's own attendance
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/policy', [AttendanceController::class, 'showPolicy'])->name('attendance.policy');
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
    Route::post('/attendance/scan', [AttendanceController::class, 'processScan'])->name('attendance.scan');

    // Employee's own payroll
    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::get('/payroll/{id}', [PayrollController::class, 'show'])->name('payroll.show');

    // Employee's leave applications (Pengajuan Cuti)
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
    Route::get('/leaves/{leave}', [LeaveController::class, 'show'])->name('leaves.show');
    Route::put('/leaves/{leave}', [LeaveController::class, 'update'])->name('leaves.update');
    Route::delete('/leaves/{leave}', [LeaveController::class, 'destroy'])->name('leaves.destroy');
});
