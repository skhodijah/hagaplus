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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('instansi_id')->constrained('instansis')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null');
            $table->string('employee_id')->unique();
            $table->string('position');
            $table->string('department');
            $table->decimal('salary', 10, 2);
            $table->date('hire_date');
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
