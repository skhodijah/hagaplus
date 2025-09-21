<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('leave_type', ['annual', 'sick', 'maternity', 'emergency', 'other']);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days_count');
            $table->text('reason');
            $table->string('attachment')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            
            $table->index(['user_id']);
            $table->index(['start_date', 'end_date']);
            $table->index(['status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('leaves');
    }
};

