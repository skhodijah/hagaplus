<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            // Approval fields
            $table->foreignId('approved_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('approved_at');
            $table->text('rejection_reason')->nullable()->after('approval_status');
            
            // Bank details (from employee record, but can be overridden)
            $table->string('bank_name')->nullable()->after('rejection_reason');
            $table->string('bank_account_number')->nullable()->after('bank_name');
            $table->string('bank_account_holder')->nullable()->after('bank_account_number');
        });
    }

    public function down()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'approved_by',
                'approved_at',
                'approval_status',
                'rejection_reason',
                'bank_name',
                'bank_account_number',
                'bank_account_holder'
            ]);
        });
    }
};
