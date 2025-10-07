<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('instansi_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();

            // Work schedule
            $table->json('work_days')->nullable(); // e.g., ["monday", "tuesday", "wednesday", "thursday", "friday"]
            $table->time('work_start_time')->nullable();
            $table->time('work_end_time')->nullable();
            $table->integer('work_hours_per_day')->default(8);

            // Break times
            $table->json('break_times')->nullable(); // e.g., [{"start": "12:00", "end": "13:00", "duration": 60}]

            // Attendance rules
            $table->integer('grace_period_minutes')->default(15); // Minutes allowed for late arrival
            $table->integer('max_late_minutes')->default(120); // Maximum late minutes before considered absent
            $table->integer('early_leave_grace_minutes')->default(15); // Minutes allowed for early leave
            $table->boolean('allow_overtime')->default(false);
            $table->integer('max_overtime_hours_per_day')->default(2);
            $table->integer('max_overtime_hours_per_week')->default(10);

            // Leave policies
            $table->integer('annual_leave_days')->default(12);
            $table->integer('sick_leave_days')->default(14);
            $table->integer('personal_leave_days')->default(3);
            $table->boolean('allow_negative_leave_balance')->default(false);

            // Special permissions
            $table->boolean('can_work_from_home')->default(false);
            $table->boolean('flexible_hours')->default(false);
            $table->boolean('skip_weekends')->default(false);
            $table->boolean('skip_holidays')->default(true);

            // Location and remote work
            $table->boolean('require_location_check')->default(true);
            $table->decimal('allowed_radius_meters', 10, 2)->default(100); // GPS check radius
            $table->json('allowed_locations')->nullable(); // Specific allowed locations

            // Shift and rotation
            $table->boolean('has_shifts')->default(false);
            $table->json('shift_schedule')->nullable(); // Complex shift rotations

            // Custom rules
            $table->json('custom_rules')->nullable(); // For future extensibility

            $table->boolean('is_active')->default(true);
            $table->timestamp('effective_from')->nullable();
            $table->timestamp('effective_until')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['employee_id', 'is_active']);
            $table->index(['instansi_id']);
            $table->index(['effective_from', 'effective_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_policies');
    }
};
