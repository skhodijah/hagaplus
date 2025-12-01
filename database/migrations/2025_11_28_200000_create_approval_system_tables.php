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
        if (!Schema::hasTable('approval_flows')) {
            Schema::create('approval_flows', function (Blueprint $table) {
                $table->id();
                $table->foreignId('instansi_id')->constrained('instansis')->cascadeOnDelete();
                $table->string('name');
                $table->string('flow_type'); // leave, attendance_revision, etc.
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('approval_levels')) {
            Schema::create('approval_levels', function (Blueprint $table) {
                $table->id();
                $table->foreignId('approval_flow_id')->constrained('approval_flows')->cascadeOnDelete();
                $table->integer('level_order');
                $table->string('level_name');
                $table->string('approver_type'); // manager, supervisor, specific_role, specific_user
                $table->foreignId('instansi_role_id')->nullable()->constrained('instansi_roles')->nullOnDelete();
                $table->boolean('is_required')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('approval_requests')) {
            Schema::create('approval_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('approval_flow_id')->constrained('approval_flows')->cascadeOnDelete();
                $table->morphs('approvable');
                $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
                $table->string('status')->default('pending'); // pending, approved, rejected
                $table->integer('current_level')->default(1);
                $table->text('rejection_reason')->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('approval_steps')) {
            Schema::create('approval_steps', function (Blueprint $table) {
                $table->id();
                $table->foreignId('approval_request_id')->constrained('approval_requests')->cascadeOnDelete();
                $table->foreignId('approval_level_id')->nullable()->constrained('approval_levels')->nullOnDelete();
                $table->integer('step_order');
                $table->foreignId('approver_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('status')->default('pending'); // pending, approved, rejected
                $table->text('notes')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_steps');
        Schema::dropIfExists('approval_requests');
        Schema::dropIfExists('approval_levels');
        Schema::dropIfExists('approval_flows');
    }
};
