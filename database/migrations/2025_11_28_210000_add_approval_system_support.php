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
        // Add supervisor_id to employees table
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'supervisor_id')) {
                $table->foreignId('supervisor_id')->nullable()->after('manager_id')->constrained('employees')->nullOnDelete();
            }
        });

        // Add approval_request_id to leaves (store which approval flow instance this belongs to)
        Schema::table('leaves', function (Blueprint $table) {
            if (!Schema::hasColumn('leaves', 'approval_flow_id')) {
                $table->foreignId('approval_flow_id')->nullable()->after('user_id')->constrained('approval_flows')->nullOnDelete();
            }
        });

        // Add approval_request_id to attendance_revisions
        Schema::table('attendance_revisions', function (Blueprint $table) {
            if (!Schema::hasColumn('attendance_revisions', 'approval_flow_id')) {
                $table->foreignId('approval_flow_id')->nullable()->after('user_id')->constrained('approval_flows')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'supervisor_id')) {
                $table->dropForeign(['supervisor_id']);
                $table->dropColumn('supervisor_id');
            }
        });

        Schema::table('leaves', function (Blueprint $table) {
            if (Schema::hasColumn('leaves', 'approval_flow_id')) {
                $table->dropForeign(['approval_flow_id']);
                $table->dropColumn('approval_flow_id');
            }
        });

        Schema::table('attendance_revisions', function (Blueprint $table) {
            if (Schema::hasColumn('attendance_revisions', 'approval_flow_id')) {
                $table->dropForeign(['approval_flow_id']);
                $table->dropColumn('approval_flow_id');
            }
        });
    }
};
