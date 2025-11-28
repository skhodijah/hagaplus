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
        Schema::create('employee_policy_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansis')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            
            // Work Schedule
            $table->json('work_days')->nullable();
            $table->time('work_start_time')->nullable();
            $table->time('work_end_time')->nullable();
            $table->integer('work_hours_per_day')->nullable();
            $table->json('break_times')->nullable();
            
            // Attendance Rules
            $table->integer('grace_period_minutes')->default(15);
            $table->integer('max_late_minutes')->default(120);
            $table->integer('early_leave_grace_minutes')->default(15);
            
            // Overtime
            $table->boolean('allow_overtime')->default(false);
            $table->integer('max_overtime_hours_per_day')->nullable();
            $table->integer('max_overtime_hours_per_week')->nullable();
            
            // Leave Policies
            $table->integer('annual_leave_days')->default(12);
            $table->integer('sick_leave_days')->default(14);
            $table->integer('personal_leave_days')->default(3);
            $table->boolean('allow_negative_leave_balance')->default(false);
            
            // Work Flexibility
            $table->boolean('can_work_from_home')->default(false);
            $table->boolean('flexible_hours')->default(false);
            $table->boolean('skip_weekends')->default(false);
            $table->boolean('skip_holidays')->default(true);
            
            // Location
            $table->boolean('require_location_check')->default(true);
            $table->decimal('allowed_radius_meters', 10, 2)->default(100);
            $table->json('allowed_locations')->nullable();
            
            // Shifts
            $table->boolean('has_shifts')->default(false);
            $table->json('shift_schedule')->nullable();
            
            // Custom Rules
            $table->json('custom_rules')->nullable();
            
            // Status
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_policy_templates');
    }
};
