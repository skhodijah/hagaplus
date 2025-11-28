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
        // Drop payroll_details and payroll_components tables
        Schema::dropIfExists('payroll_details');
        Schema::dropIfExists('payroll_components');

        // Modify payrolls table - add all payroll component columns
        Schema::table('payrolls', function (Blueprint $table) {
            // Drop old JSON columns if they exist
            if (Schema::hasColumn('payrolls', 'allowances')) {
                $table->dropColumn('allowances');
            }
            if (Schema::hasColumn('payrolls', 'deductions')) {
                $table->dropColumn('deductions');
            }
            if (Schema::hasColumn('payrolls', 'overtime_amount')) {
                $table->dropColumn('overtime_amount');
            }
            if (Schema::hasColumn('payrolls', 'total_gross')) {
                $table->dropColumn('total_gross');
            }
            if (Schema::hasColumn('payrolls', 'total_deductions')) {
                $table->dropColumn('total_deductions');
            }
            if (Schema::hasColumn('payrolls', 'net_salary')) {
                $table->dropColumn('net_salary');
            }

            // PENDAPATAN (Allowances)
            $table->decimal('gaji_pokok', 15, 2)->default(0)->after('basic_salary');
            $table->decimal('tunjangan_jabatan', 15, 2)->default(0);
            $table->decimal('tunjangan_makan', 15, 2)->default(0);
            $table->decimal('tunjangan_transport', 15, 2)->default(0);
            $table->decimal('lembur', 15, 2)->default(0);
            $table->decimal('bonus', 15, 2)->default(0);
            $table->decimal('reimburse', 15, 2)->default(0);
            $table->decimal('thr', 15, 2)->default(0);

            // POTONGAN (Deductions)
            $table->decimal('bpjs_kesehatan', 15, 2)->default(0);
            $table->decimal('bpjs_tk', 15, 2)->default(0);
            $table->decimal('pph21', 15, 2)->default(0);
            $table->decimal('potongan_absensi', 15, 2)->default(0);
            $table->decimal('kasbon', 15, 2)->default(0);
            $table->decimal('potongan_lainnya', 15, 2)->default(0);

            // TOTALS
            $table->decimal('total_pendapatan', 15, 2)->default(0);
            $table->decimal('total_potongan', 15, 2)->default(0);
            $table->decimal('gaji_bersih', 15, 2)->default(0);

            // Drop basic_salary column (replaced by gaji_pokok)
            if (Schema::hasColumn('payrolls', 'basic_salary')) {
                $table->dropColumn('basic_salary');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            // Remove new columns
            $table->dropColumn([
                'gaji_pokok',
                'tunjangan_jabatan',
                'tunjangan_makan',
                'tunjangan_transport',
                'lembur',
                'bonus',
                'reimburse',
                'thr',
                'bpjs_kesehatan',
                'bpjs_tk',
                'pph21',
                'potongan_absensi',
                'kasbon',
                'potongan_lainnya',
                'total_pendapatan',
                'total_potongan',
                'gaji_bersih',
            ]);

            // Restore old columns
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->json('allowances')->nullable();
            $table->json('deductions')->nullable();
            $table->decimal('overtime_amount', 15, 2)->default(0);
            $table->decimal('total_gross', 15, 2)->default(0);
            $table->decimal('total_deductions', 15, 2)->default(0);
            $table->decimal('net_salary', 15, 2)->default(0);
        });

        // Recreate tables
        Schema::create('payroll_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansis')->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', ['allowance', 'deduction']);
            $table->boolean('is_taxable')->default(true);
            $table->boolean('is_default')->default(false);
            $table->decimal('default_amount', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('payroll_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained('payrolls')->cascadeOnDelete();
            $table->foreignId('payroll_component_id')->nullable()->constrained('payroll_components')->nullOnDelete();
            $table->string('name');
            $table->enum('type', ['allowance', 'deduction']);
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
};
