<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Seed default permissions
        $permissions = [
            // Employee Management
            ['name' => 'View Employees', 'slug' => 'view-employees', 'group' => 'employees', 'description' => 'View employee list and details'],
            ['name' => 'Create Employees', 'slug' => 'create-employees', 'group' => 'employees', 'description' => 'Add new employees'],
            ['name' => 'Edit Employees', 'slug' => 'edit-employees', 'group' => 'employees', 'description' => 'Edit employee information'],
            ['name' => 'Delete Employees', 'slug' => 'delete-employees', 'group' => 'employees', 'description' => 'Delete employees'],
            
            // Attendance Management
            ['name' => 'View Attendance', 'slug' => 'view-attendance', 'group' => 'attendance', 'description' => 'View attendance records'],
            ['name' => 'Edit Attendance', 'slug' => 'edit-attendance', 'group' => 'attendance', 'description' => 'Edit attendance records'],
            ['name' => 'Approve Attendance Revisions', 'slug' => 'approve-attendance-revisions', 'group' => 'attendance', 'description' => 'Approve attendance revision requests'],
            
            // Leave Management
            ['name' => 'View Leaves', 'slug' => 'view-leaves', 'group' => 'leaves', 'description' => 'View leave requests'],
            ['name' => 'Approve Leaves', 'slug' => 'approve-leaves', 'group' => 'leaves', 'description' => 'Approve or reject leave requests'],
            ['name' => 'Manage Leave Policies', 'slug' => 'manage-leave-policies', 'group' => 'leaves', 'description' => 'Create and edit leave policies'],
            
            // Payroll Management
            ['name' => 'View Payroll', 'slug' => 'view-payroll', 'group' => 'payroll', 'description' => 'View payroll records'],
            ['name' => 'Process Payroll', 'slug' => 'process-payroll', 'group' => 'payroll', 'description' => 'Process and generate payroll'],
            ['name' => 'Edit Payroll', 'slug' => 'edit-payroll', 'group' => 'payroll', 'description' => 'Edit payroll records'],
            
            // Department & Division Management
            ['name' => 'Manage Departments', 'slug' => 'manage-departments', 'group' => 'organization', 'description' => 'Create, edit, delete departments'],
            ['name' => 'Manage Divisions', 'slug' => 'manage-divisions', 'group' => 'organization', 'description' => 'Create, edit, delete divisions'],
            ['name' => 'Manage Positions', 'slug' => 'manage-positions', 'group' => 'organization', 'description' => 'Create, edit, delete positions'],
            
            // Policy Management
            ['name' => 'Manage Employee Policies', 'slug' => 'manage-employee-policies', 'group' => 'policies', 'description' => 'Create and edit employee policies'],
            ['name' => 'Manage Division Policies', 'slug' => 'manage-division-policies', 'group' => 'policies', 'description' => 'Create and edit division policies'],
            
            // Reports
            ['name' => 'View Reports', 'slug' => 'view-reports', 'group' => 'reports', 'description' => 'View attendance and payroll reports'],
            ['name' => 'Export Reports', 'slug' => 'export-reports', 'group' => 'reports', 'description' => 'Export reports to Excel/PDF'],
            
            // Settings
            ['name' => 'Manage Roles', 'slug' => 'manage-roles', 'group' => 'settings', 'description' => 'Create and edit roles'],
            ['name' => 'Manage Permissions', 'slug' => 'manage-permissions', 'group' => 'settings', 'description' => 'Assign permissions to roles'],
            ['name' => 'Manage Branches', 'slug' => 'manage-branches', 'group' => 'settings', 'description' => 'Create and edit branches'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insert([
                'name' => $permission['name'],
                'slug' => $permission['slug'],
                'group' => $permission['group'],
                'description' => $permission['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('permissions')->truncate();
    }
};
