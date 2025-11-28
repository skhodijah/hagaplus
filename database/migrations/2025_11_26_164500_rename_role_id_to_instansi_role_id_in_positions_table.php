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
        Schema::table('positions', function (Blueprint $table) {
            // Drop foreign key first if it exists
            // We assume the foreign key name follows Laravel convention or we check if it exists
            // Since we can't easily check constraint name, we'll try to drop it by column name
            $table->dropForeign(['role_id']);
            
            // Rename column
            $table->renameColumn('role_id', 'instansi_role_id');
            
            // Add new foreign key
            $table->foreign('instansi_role_id')->references('id')->on('instansi_roles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropForeign(['instansi_role_id']);
            $table->renameColumn('instansi_role_id', 'role_id');
            $table->foreign('role_id')->references('id')->on('instansi_roles')->onDelete('set null');
        });
    }
};
