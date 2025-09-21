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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['superadmin', 'admin', 'employee'])->default('employee');
            }
            if (!Schema::hasColumn('users', 'instansi_id')) {
                $table->foreignId('instansi_id')->nullable()->constrained('instansis')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'instansi_id')) {
                $table->dropForeign(['instansi_id']);
                $table->dropColumn('instansi_id');
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
