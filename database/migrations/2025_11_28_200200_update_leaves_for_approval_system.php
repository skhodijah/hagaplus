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
        // Add approval_request_id to leaves table to link a leave request with its approval workflow
        Schema::table('leaves', function (Blueprint $table) {
            if (!Schema::hasColumn('leaves', 'approval_request_id')) {
                $table->foreignId('approval_request_id')
                    ->nullable()
                    ->after('approval_flow_id')
                    ->constrained('approval_requests')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            if (Schema::hasColumn('leaves', 'approval_request_id')) {
                $table->dropForeign(['approval_request_id']);
                $table->dropColumn('approval_request_id');
            }
        });
    }
};
