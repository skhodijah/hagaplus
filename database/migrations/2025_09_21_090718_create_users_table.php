<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('employee_id', 50)->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->enum('role', ['super_admin', 'admin', 'employee']);
            $table->string('position', 100)->nullable();
            $table->string('department', 100)->nullable();
            $table->date('hire_date')->nullable();
            $table->decimal('salary', 15, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->text('face_encoding')->nullable(); // untuk Face ID
            $table->rememberToken();
            $table->timestamps();
            
            $table->index(['company_id']);
            $table->index(['role']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
