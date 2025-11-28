<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\PayrollController;

use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\EmployeePolicyController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\HierarchyController;
use App\Http\Controllers\Admin\SystemUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Employee management
    Route::middleware('permission:create-employees')->group(function () {
        Route::get('employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store');
    });

    Route::middleware('permission:view-employees')->group(function () {
        Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    });

    Route::middleware('permission:edit-employees')->group(function () {
        Route::get('employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    });

    Route::middleware('permission:delete-employees')->group(function () {
        Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    });

    // Role management
    Route::middleware('permission:manage-roles')->group(function () {
        Route::resource('roles', RoleController::class);
        Route::get('roles/{role}/permissions', [App\Http\Controllers\Admin\RolePermissionController::class, 'edit'])->name('roles.permissions.edit');
        Route::put('roles/{role}/permissions', [App\Http\Controllers\Admin\RolePermissionController::class, 'update'])->name('roles.permissions.update');
    });

    // Division management
    Route::middleware('permission:manage-divisions')->group(function () {
        Route::resource('divisions', DivisionController::class);
    });

    // Get next employee ID (accessible by those who can create employees OR manage divisions)
    Route::get('divisions/{division}/next-employee-id', [DivisionController::class, 'getNextEmployeeId'])
        ->name('divisions.next-employee-id')
        ->middleware('permission:create-employees,manage-divisions');

    // Organization Hierarchy
    Route::middleware('permission:manage-departments|manage-positions')->group(function () {
        Route::get('hierarchy', [HierarchyController::class, 'index'])->name('hierarchy.index');
        Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class)->only(['store', 'update', 'destroy']);
        Route::resource('positions', \App\Http\Controllers\Admin\PositionController::class)->only(['store', 'update', 'destroy']);
    });

    // Attendance Revisions
    Route::middleware('permission:approve-attendance-revisions')->group(function () {
        Route::get('attendance/revisions', [App\Http\Controllers\Admin\AttendanceRevisionController::class, 'index'])->name('attendance.revisions.index');
        Route::patch('attendance/revisions/{id}/approve', [App\Http\Controllers\Admin\AttendanceRevisionController::class, 'approve'])->name('attendance.revisions.approve');
        Route::patch('attendance/revisions/{id}/reject', [App\Http\Controllers\Admin\AttendanceRevisionController::class, 'reject'])->name('attendance.revisions.reject');
    });

    // Attendance management
    Route::middleware('permission:view-attendance')->group(function () {
        Route::resource('attendance', AttendanceController::class);
        Route::get('attendance/employee/{userId}', [AttendanceController::class, 'employeeAttendance'])->name('attendance.employee');
    });

    // Payroll management
    Route::middleware('permission:view-payroll')->group(function () {
        Route::resource('payroll', PayrollController::class);
    });

    // Branch management
    Route::middleware('permission:manage-branches')->group(function () {
        Route::resource('branches', BranchController::class);
    });

    // Company Profile Routes
    Route::get('/company-profile', [App\Http\Controllers\Admin\CompanyProfileController::class, 'index'])->name('company-profile.index');
    Route::put('/company-profile', [App\Http\Controllers\Admin\CompanyProfileController::class, 'update'])->name('company-profile.update');



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

    // Division Policies
    Route::middleware('permission:manage-division-policies')->group(function () {
        Route::resource('division-policies', App\Http\Controllers\Admin\DivisionPolicyController::class);
    });

    // Employee Policies (Overrides)
    Route::middleware('permission:manage-employee-policies')->group(function () {
        Route::resource('employee-policies', App\Http\Controllers\Admin\EmployeePolicyController::class);
    });

    // Leave management
    Route::middleware('permission:view-leaves')->group(function () {
        Route::resource('leaves', LeaveController::class)->parameters(['leaves' => 'leave']);
        Route::patch('leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve')->middleware('permission:approve-leaves');
        Route::patch('leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject')->middleware('permission:approve-leaves');
    });

    // Reimbursement management
    Route::middleware('permission:view-reimbursements')->group(function () {
        Route::get('reimbursements', [App\Http\Controllers\Admin\ReimbursementController::class, 'index'])
            ->name('reimbursements.index');
        Route::get('reimbursements/export', [App\Http\Controllers\Admin\ReimbursementController::class, 'export'])
            ->name('reimbursements.export');
        Route::get('reimbursements/{reimbursement}', [App\Http\Controllers\Admin\ReimbursementController::class, 'show'])
            ->name('reimbursements.show');
        Route::post('reimbursements/{reimbursement}/approve', [App\Http\Controllers\Admin\ReimbursementController::class, 'approve'])
            ->name('reimbursements.approve');
        Route::post('reimbursements/{reimbursement}/reject', [App\Http\Controllers\Admin\ReimbursementController::class, 'reject'])
            ->name('reimbursements.reject');
        Route::post('reimbursements/bulk-approve', [App\Http\Controllers\Admin\ReimbursementController::class, 'bulkApprove'])
            ->name('reimbursements.bulk-approve');
        Route::post('reimbursements/bulk-reject', [App\Http\Controllers\Admin\ReimbursementController::class, 'bulkReject'])
            ->name('reimbursements.bulk-reject');
    });

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications/{id}/read', [App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::put('/notifications/{id}/unread', [App\Http\Controllers\Admin\NotificationController::class, 'markAsUnread'])->name('notifications.unread');
    Route::put('/notifications/mark-all-read', [App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'destroyAll'])->name('notifications.destroy-all');
    Route::get('/notifications/unread-count', [App\Http\Controllers\Admin\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
});
