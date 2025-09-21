<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Allowance;
use App\Models\Deduction;

class AllowanceDeductionSeeder extends Seeder
{
    public function run()
    {
        // Allowances for each company
        $allowances = [
            // Company 1: PT Teknologi Maju
            [
                'company_id' => 1,
                'name' => 'Tunjangan Transport',
                'type' => 'fixed',
                'amount' => 500000.00,
                'is_taxable' => false,
            ],
            [
                'company_id' => 1,
                'name' => 'Tunjangan Makan',
                'type' => 'fixed',
                'amount' => 300000.00,
                'is_taxable' => false,
            ],
            [
                'company_id' => 1,
                'name' => 'Bonus Performance',
                'type' => 'percentage',
                'amount' => 15.00, // 15%
                'is_taxable' => true,
            ],
            [
                'company_id' => 1,
                'name' => 'Tunjangan Kesehatan',
                'type' => 'fixed',
                'amount' => 200000.00,
                'is_taxable' => false,
            ],

            // Company 2: CV Kreatif Digital
            [
                'company_id' => 2,
                'name' => 'Tunjangan Kreativitas',
                'type' => 'fixed',
                'amount' => 400000.00,
                'is_taxable' => true,
            ],
            [
                'company_id' => 2,
                'name' => 'Internet Allowance',
                'type' => 'fixed',
                'amount' => 150000.00,
                'is_taxable' => false,
            ],

            // Company 3: Toko Berkah Jaya
            [
                'company_id' => 3,
                'name' => 'Insentif Penjualan',
                'type' => 'percentage',
                'amount' => 2.00, // 2% of sales
                'is_taxable' => true,
            ],
            [
                'company_id' => 3,
                'name' => 'Tunjangan Kehadiran',
                'type' => 'fixed',
                'amount' => 200000.00,
                'is_taxable' => false,
            ],

            // Company 4: PT Industri Manufaktur
            [
                'company_id' => 4,
                'name' => 'Tunjangan Shift',
                'type' => 'fixed',
                'amount' => 300000.00,
                'is_taxable' => true,
            ],
            [
                'company_id' => 4,
                'name' => 'Safety Bonus',
                'type' => 'fixed',
                'amount' => 250000.00,
                'is_taxable' => false,
            ],
        ];

        foreach ($allowances as $allowance) {
            Allowance::create($allowance);
        }

        // Deductions for each company
        $deductions = [
            // Standard deductions for all companies
            [
                'company_id' => 1,
                'name' => 'Pajak Penghasilan',
                'type' => 'percentage',
                'amount' => 5.00, // 5%
                'is_mandatory' => true,
            ],
            [
                'company_id' => 1,
                'name' => 'BPJS Kesehatan',
                'type' => 'percentage',
                'amount' => 1.00, // 1%
                'is_mandatory' => true,
            ],
            [
                'company_id' => 1,
                'name' => 'BPJS Ketenagakerjaan',
                'type' => 'percentage',
                'amount' => 2.00, // 2%
                'is_mandatory' => true,
            ],
            [
                'company_id' => 1,
                'name' => 'Denda Keterlambatan',
                'type' => 'fixed',
                'amount' => 50000.00,
                'is_mandatory' => false,
            ],

            // Company 2 deductions
            [
                'company_id' => 2,
                'name' => 'Pajak Penghasilan',
                'type' => 'percentage',
                'amount' => 5.00,
                'is_mandatory' => true,
            ],
            [
                'company_id' => 2,
                'name' => 'BPJS Kesehatan',
                'type' => 'percentage',
                'amount' => 1.00,
                'is_mandatory' => true,
            ],
            [
                'company_id' => 2,
                'name' => 'BPJS Ketenagakerjaan',
                'type' => 'percentage',
                'amount' => 2.00,
                'is_mandatory' => true,
            ],

            // Company 3 deductions
            [
                'company_id' => 3,
                'name' => 'Pajak Penghasilan',
                'type' => 'percentage',
                'amount' => 5.00,
                'is_mandatory' => true,
            ],
            [
                'company_id' => 3,
                'name' => 'BPJS Kesehatan',
                'type' => 'percentage',
                'amount' => 1.00,
                'is_mandatory' => true,
            ],
            [
                'company_id' => 3,
                'name' => 'Kas Karyawan',
                'type' => 'fixed',
                'amount' => 25000.00,
                'is_mandatory' => false,
            ],

            // Company 4 deductions
            [
                'company_id' => 4,
                'name' => 'Pajak Penghasilan',
                'type' => 'percentage',
                'amount' => 5.00,
                'is_mandatory' => true,
            ],
            [
                'company_id' => 4,
                'name' => 'BPJS Kesehatan',
                'type' => 'percentage',
                'amount' => 1.00,
                'is_mandatory' => true,
            ],
            [
                'company_id' => 4,
                'name' => 'BPJS Ketenagakerjaan',
                'type' => 'percentage',
                'amount' => 2.00,
                'is_mandatory' => true,
            ],
            [
                'company_id' => 4,
                'name' => 'Koperasi Karyawan',
                'type' => 'fixed',
                'amount' => 100000.00,
                'is_mandatory' => false,
            ],
        ];

        foreach ($deductions as $deduction) {
            Deduction::create($deduction);
        }
    }
}
