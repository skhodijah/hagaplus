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
        Schema::create('reimbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            
            // Reimburse Info
            $table->string('reference_number')->unique();
            $table->string('category'); // Transport, Makan, Kesehatan, etc.
            $table->text('description');
            $table->date('date_of_expense');
            $table->decimal('amount', 15, 2);
            $table->string('currency')->default('IDR');
            $table->string('proof_file')->nullable();
            $table->string('project_code')->nullable();
            
            // Payment Info
            $table->string('payment_method'); // Transfer, Cash, Payroll
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_holder')->nullable();
            
            // Approval & Status
            $table->enum('status', [
                'pending', 
                'approved_supervisor', 
                'approved_manager', 
                'verified_finance', 
                'paid', 
                'rejected'
            ])->default('pending');
            
            $table->text('rejection_reason')->nullable();
            $table->decimal('approved_amount', 15, 2)->nullable();
            
            // Approvers Snapshot
            $table->foreignId('supervisor_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('manager_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('finance_approver_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamp('supervisor_approved_at')->nullable();
            $table->timestamp('manager_approved_at')->nullable();
            $table->timestamp('finance_verified_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reimbursements');
    }
};
