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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('nik')->nullable()->after('employee_id');
            $table->string('npwp')->nullable()->after('nik');
            $table->date('date_of_birth')->nullable()->after('npwp');
            $table->enum('gender', ['male', 'female'])->nullable()->after('date_of_birth');
            $table->text('address')->nullable()->after('gender');
            $table->string('phone')->nullable()->after('address');
            $table->string('bank_name')->nullable()->after('salary');
            $table->string('bank_account_number')->nullable()->after('bank_name');
            $table->string('bank_account_holder')->nullable()->after('bank_account_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'nik',
                'npwp',
                'date_of_birth',
                'gender',
                'address',
                'phone',
                'bank_name',
                'bank_account_number',
                'bank_account_holder'
            ]);
        });
    }
};
