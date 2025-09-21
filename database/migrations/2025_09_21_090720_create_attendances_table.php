<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->date('attendance_date');
            $table->timestamp('check_in_time')->nullable();
            $table->timestamp('check_out_time')->nullable();
            $table->enum('check_in_method', ['qr', 'gps', 'face_id', 'manual'])->nullable();
            $table->enum('check_out_method', ['qr', 'gps', 'face_id', 'manual'])->nullable();
            $table->string('check_in_location')->nullable(); // koordinat GPS
            $table->string('check_out_location')->nullable();
            $table->string('check_in_photo')->nullable();
            $table->string('check_out_photo')->nullable();
            $table->integer('work_duration')->default(0); // menit
            $table->integer('break_duration')->default(0);
            $table->integer('overtime_duration')->default(0);
            $table->integer('late_minutes')->default(0);
            $table->integer('early_checkout_minutes')->default(0);
            $table->enum('status', ['present', 'late', 'absent', 'partial', 'leave'])->default('absent');
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'attendance_date']);
            $table->index(['user_id', 'attendance_date']);
            $table->index(['branch_id']);
            $table->index(['attendance_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
