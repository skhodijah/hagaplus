<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add system_role_id column
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('system_role_id')->nullable()->after('email');
            $table->foreign('system_role_id')->references('id')->on('system_roles')->onDelete('cascade');
        });

        // Migrate existing data: map role enum to system_role_id
        // 'superadmin' -> 1, 'admin' -> 2, 'employee' -> 3
        // 'hr' and 'approver' will be mapped to 'admin' (2)
        DB::statement("UPDATE users SET system_role_id = 1 WHERE role = 'superadmin'");
        DB::statement("UPDATE users SET system_role_id = 2 WHERE role IN ('admin', 'hr', 'approver')");
        DB::statement("UPDATE users SET system_role_id = 3 WHERE role = 'employee'");

        // Drop old role column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back role column
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['superadmin', 'admin', 'employee', 'hr', 'approver'])->default('employee')->after('email');
        });

        // Migrate data back
        DB::statement("UPDATE users SET role = 'superadmin' WHERE system_role_id = 1");
        DB::statement("UPDATE users SET role = 'admin' WHERE system_role_id = 2");
        DB::statement("UPDATE users SET role = 'employee' WHERE system_role_id = 3");

        // Drop FK and column
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['system_role_id']);
            $table->dropColumn('system_role_id');
        });
    }
};
