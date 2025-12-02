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
        Schema::table('attendance_revisions', function (Blueprint $table) {
            $table->unsignedBigInteger('supervisor_id')->nullable()->after('status');
            $table->timestamp('supervisor_approved_at')->nullable()->after('supervisor_id');
            $table->text('supervisor_note')->nullable()->after('supervisor_approved_at');
            
            $table->unsignedBigInteger('hrd_id')->nullable()->after('supervisor_note');
            $table->timestamp('hrd_approved_at')->nullable()->after('hrd_id');
            $table->text('hrd_note')->nullable()->after('hrd_approved_at');
        });

        // Modify status enum to include 'approved_supervisor'
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE attendance_revisions MODIFY COLUMN status ENUM('pending', 'approved_supervisor', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_revisions', function (Blueprint $table) {
            $table->dropColumn([
                'supervisor_id', 
                'supervisor_approved_at', 
                'supervisor_note',
                'hrd_id',
                'hrd_approved_at',
                'hrd_note'
            ]);
        });
        
        // Revert status enum
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE attendance_revisions MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");
    }
};
