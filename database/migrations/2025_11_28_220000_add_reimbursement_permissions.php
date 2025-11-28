<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin\Permission;
use App\Models\Admin\InstansiRole;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create Permissions
        $permissions = [
            [
                'name' => 'View Reimbursements',
                'slug' => 'view-reimbursements',
                'group' => 'reimbursement',
                'description' => 'Can view reimbursement requests',
            ],
            [
                'name' => 'Approve Reimbursements',
                'slug' => 'approve-reimbursements',
                'group' => 'reimbursement',
                'description' => 'Can approve or reject reimbursement requests',
            ],
            [
                'name' => 'Manage Reimbursements',
                'slug' => 'manage-reimbursements', // For export, etc. if needed
                'group' => 'reimbursement',
                'description' => 'Can manage reimbursement settings and exports',
            ],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['slug' => $perm['slug']],
                $perm
            );
        }

        // 2. Assign to Admin Roles
        $viewPerm = Permission::where('slug', 'view-reimbursements')->first();
        $approvePerm = Permission::where('slug', 'approve-reimbursements')->first();
        $managePerm = Permission::where('slug', 'manage-reimbursements')->first();

        // Get all Admin roles (assuming name 'Admin' or system_role_id for admin)
        // We'll search by name 'Admin' for now as per HierarchySeeder
        $adminRoles = InstansiRole::where('name', 'Admin')->get();

        foreach ($adminRoles as $role) {
            if ($viewPerm) $role->permissions()->syncWithoutDetaching([$viewPerm->id]);
            if ($approvePerm) $role->permissions()->syncWithoutDetaching([$approvePerm->id]);
            if ($managePerm) $role->permissions()->syncWithoutDetaching([$managePerm->id]);
        }
        
        // Also assign to 'Approver' role if it exists (from HierarchySeeder)
        $approverRoles = InstansiRole::where('name', 'Approver')->get();
        foreach ($approverRoles as $role) {
            if ($viewPerm) $role->permissions()->syncWithoutDetaching([$viewPerm->id]);
            if ($approvePerm) $role->permissions()->syncWithoutDetaching([$approvePerm->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't strictly need to delete them as they might be used, 
        // but for completeness we could detach them. 
        // For safety, we'll leave them.
    }
};
