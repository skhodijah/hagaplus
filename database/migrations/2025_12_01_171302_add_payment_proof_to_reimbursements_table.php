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
        Schema::table('reimbursements', function (Blueprint $table) {
            // Add payment proof file for transfer method
            $table->string('payment_proof_file')->nullable()->after('paid_at');
            
            // Add payroll_id to link with payroll when payment method is 'Payroll'
            $table->foreignId('payroll_id')->nullable()->after('payment_proof_file')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reimbursements', function (Blueprint $table) {
            $table->dropForeign(['payroll_id']);
            $table->dropColumn(['payment_proof_file', 'payroll_id']);
        });
    }
};
