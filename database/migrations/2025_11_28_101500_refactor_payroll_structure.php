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
        // 1. Create Payroll Components (Master Data)
        Schema::create('payroll_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansis')->cascadeOnDelete();
            $table->string('name'); // Gaji Pokok, Tunjangan Makan, dll
            $table->enum('type', ['allowance', 'deduction']); // Pendapatan / Potongan
            $table->boolean('is_taxable')->default(true); // Kena pajak?
            $table->boolean('is_default')->default(false); // Otomatis muncul di slip?
            $table->decimal('default_amount', 15, 2)->default(0);
            $table->timestamps();
        });

        // 2. Modify Payrolls Table
        Schema::table('payrolls', function (Blueprint $table) {
            // Add instansi_id for scoping
            $table->foreignId('instansi_id')->nullable()->after('id')->constrained('instansis')->cascadeOnDelete();
            
            // Add employee_id (better than user_id for HR systems)
            $table->foreignId('employee_id')->nullable()->after('user_id')->constrained('employees')->nullOnDelete();
        });

        // 3. Create Payroll Details (Transaction Items)
        Schema::create('payroll_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained('payrolls')->cascadeOnDelete();
            $table->foreignId('payroll_component_id')->nullable()->constrained('payroll_components')->nullOnDelete();
            $table->string('name'); // Snapshot nama komponen
            $table->enum('type', ['allowance', 'deduction']);
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_details');
        
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropForeign(['instansi_id']);
            $table->dropColumn('instansi_id');
            $table->dropForeign(['employee_id']);
            $table->dropColumn('employee_id');
        });

        Schema::dropIfExists('payroll_components');
    }
};
