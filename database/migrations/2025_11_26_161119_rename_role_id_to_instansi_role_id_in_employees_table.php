<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Drop old FK constraint
            $table->dropForeign(['role_id']);
            
            // Rename column
            $table->renameColumn('role_id', 'instansi_role_id');
        });

        // Add new FK constraint to instansi_roles table
        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('instansi_role_id')->references('id')->on('instansi_roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Drop new FK constraint
            $table->dropForeign(['instansi_role_id']);
            
            // Rename back
            $table->renameColumn('instansi_role_id', 'role_id');
        });

        // Add back old FK constraint
        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('instansi_roles')->onDelete('cascade');
        });
    }
};
