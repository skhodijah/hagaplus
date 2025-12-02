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
        // Drop foreign key first if exists
        if (Schema::hasColumn('leaves', 'approval_request_id')) {
            try {
                Schema::table('leaves', function (Blueprint $table) {
                    $table->dropForeign(['approval_request_id']);
                });
            } catch (\Exception $e) {
                // Ignore if FK doesn't exist
            }
            
            Schema::table('leaves', function (Blueprint $table) {
                $table->dropColumn('approval_request_id');
            });
        }

        if (Schema::hasColumn('leaves', 'approval_flow_id')) {
            try {
                Schema::table('leaves', function (Blueprint $table) {
                    $table->dropForeign(['approval_flow_id']);
                });
            } catch (\Exception $e) {}
            
            Schema::table('leaves', function (Blueprint $table) {
                $table->dropColumn('approval_flow_id');
            });
        }

        // Cleanup attendance_revisions
        if (Schema::hasColumn('attendance_revisions', 'approval_flow_id')) {
            try {
                Schema::table('attendance_revisions', function (Blueprint $table) {
                    $table->dropForeign(['approval_flow_id']);
                });
            } catch (\Exception $e) {}
            
            Schema::table('attendance_revisions', function (Blueprint $table) {
                $table->dropColumn('approval_flow_id');
            });
        }

        if (Schema::hasColumn('attendance_revisions', 'approval_request_id')) {
            try {
                Schema::table('attendance_revisions', function (Blueprint $table) {
                    $table->dropForeign(['approval_request_id']);
                });
            } catch (\Exception $e) {}
            
            Schema::table('attendance_revisions', function (Blueprint $table) {
                $table->dropColumn('approval_request_id');
            });
        }

        Schema::dropIfExists('approval_steps');
        Schema::dropIfExists('approval_requests');
        Schema::dropIfExists('approval_levels');
        Schema::dropIfExists('approval_flows');
    }

    public function down(): void
    {
        // We don't really need to recreate them as they are being deleted permanently.
        // But for completeness we can leave it empty or try to recreate.
        // Leaving empty as this is a cleanup migration.
    }
};
