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
        Schema::table('leaves', function (Blueprint $table) {
            $table->foreignId('supervisor_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->timestamp('supervisor_approved_at')->nullable();
            $table->foreignId('hrd_id')->nullable()->constrained('users')->nullOnDelete(); // Final approver
            $table->timestamp('hrd_approved_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropForeign(['supervisor_id']);
            $table->dropColumn('supervisor_id');
            $table->dropColumn('supervisor_approved_at');
            $table->dropForeign(['hrd_id']);
            $table->dropColumn('hrd_id');
            $table->dropColumn('hrd_approved_at');
        });
    }
};
