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
        // Rename table
        Schema::rename('roles', 'instansi_roles');

        // Add system_role_id column
        Schema::table('instansi_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('system_role_id')->nullable()->after('instansi_id');
            $table->foreign('system_role_id')->references('id')->on('system_roles')->onDelete('cascade');
        });

        // Migrate existing data: map system_role enum to system_role_id
        // 'admin' -> system_role_id = 2 (Admin)
        // 'employee' -> system_role_id = 3 (Employee)
        DB::statement("UPDATE instansi_roles SET system_role_id = 2 WHERE system_role = 'admin'");
        DB::statement("UPDATE instansi_roles SET system_role_id = 3 WHERE system_role = 'employee'");

        // Drop old system_role column
        Schema::table('instansi_roles', function (Blueprint $table) {
            $table->dropColumn('system_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back system_role column
        Schema::table('instansi_roles', function (Blueprint $table) {
            $table->enum('system_role', ['admin', 'employee'])->default('employee')->after('name');
        });

        // Migrate data back
        DB::statement("UPDATE instansi_roles SET system_role = 'admin' WHERE system_role_id = 2");
        DB::statement("UPDATE instansi_roles SET system_role = 'employee' WHERE system_role_id = 3");

        // Drop FK and column
        Schema::table('instansi_roles', function (Blueprint $table) {
            $table->dropForeign(['system_role_id']);
            $table->dropColumn('system_role_id');
        });

        // Rename back
        Schema::rename('instansi_roles', 'roles');
    }
};
