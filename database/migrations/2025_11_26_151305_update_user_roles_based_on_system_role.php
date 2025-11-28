<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing users whose employee role has system_role = 'admin'
        // This will set the users.role column to match the system_role of the linked role.
        DB::statement(
            "UPDATE `users` u 
            JOIN `employees` e ON u.id = e.user_id 
            JOIN `roles` r ON e.role_id = r.id 
            SET u.role = r.system_role 
            WHERE u.role = 'employee' AND r.system_role = 'admin'"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the changes: set role back to 'employee' for users that were set to 'admin' via this migration.
        DB::statement(
            "UPDATE `users` u 
            JOIN `employees` e ON u.id = e.user_id 
            JOIN `roles` r ON e.role_id = r.id 
            SET u.role = 'employee' 
            WHERE u.role = 'admin' AND r.system_role = 'admin'"
        );
    }
};
