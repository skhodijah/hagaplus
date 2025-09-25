<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('data_deletion_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansis')->onDelete('cascade');
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->enum('request_type', ['employee_data', 'attendance_data', 'payroll_data', 'full_instansi', 'specific_period']);
            $table->json('data_specification'); // Specific data to delete (user IDs, date ranges, etc.)
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->date('scheduled_deletion_date');
            $table->timestamp('completed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['instansi_id', 'status']);
            $table->index('scheduled_deletion_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_deletion_requests');
    }
};