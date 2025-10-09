<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            // Bank Transfer Methods
            [
                'name' => 'BCA Transfer',
                'type' => 'bank_transfer',
                'account_name' => 'PT. Haga Plus Indonesia',
                'account_number' => '1234567890',
                'bank_name' => 'Bank Central Asia (BCA)',
                'description' => 'Transfer ke rekening BCA untuk pembayaran subscription',
                'is_active' => true,
            ],
            [
                'name' => 'Mandiri Transfer',
                'type' => 'bank_transfer',
                'account_name' => 'PT. Haga Plus Indonesia',
                'account_number' => '0987654321',
                'bank_name' => 'Bank Mandiri',
                'description' => 'Transfer ke rekening Mandiri untuk pembayaran subscription',
                'is_active' => true,
            ],
            [
                'name' => 'BNI Transfer',
                'type' => 'bank_transfer',
                'account_name' => 'PT. Haga Plus Indonesia',
                'account_number' => '1122334455',
                'bank_name' => 'Bank Negara Indonesia (BNI)',
                'description' => 'Transfer ke rekening BNI untuk pembayaran subscription',
                'is_active' => true,
            ],

            // QRIS Payment
            [
                'name' => 'QRIS Payment',
                'type' => 'qris',
                'description' => 'Pembayaran menggunakan QRIS yang dapat diakses melalui berbagai aplikasi e-wallet',
                'is_active' => true,
            ],

            // E-Wallet Methods
            [
                'name' => 'GoPay',
                'type' => 'ewallet',
                'account_name' => 'Haga Plus',
                'account_number' => '081234567890',
                'description' => 'Transfer melalui GoPay untuk pembayaran subscription',
                'is_active' => true,
            ],
            [
                'name' => 'OVO',
                'type' => 'ewallet',
                'account_name' => 'Haga Plus',
                'account_number' => '081234567891',
                'description' => 'Transfer melalui OVO untuk pembayaran subscription',
                'is_active' => true,
            ],
            [
                'name' => 'DANA',
                'type' => 'ewallet',
                'account_name' => 'Haga Plus',
                'account_number' => '081234567892',
                'description' => 'Transfer melalui DANA untuk pembayaran subscription',
                'is_active' => true,
            ],
            [
                'name' => 'ShopeePay',
                'type' => 'ewallet',
                'account_name' => 'Haga Plus',
                'account_number' => '081234567893',
                'description' => 'Transfer melalui ShopeePay untuk pembayaran subscription',
                'is_active' => true,
            ],

            // Inactive example
            [
                'name' => 'BRI Transfer',
                'type' => 'bank_transfer',
                'account_name' => 'PT. Haga Plus Indonesia',
                'account_number' => '5566778899',
                'bank_name' => 'Bank Rakyat Indonesia (BRI)',
                'description' => 'Transfer ke rekening BRI (temporarily unavailable)',
                'is_active' => false,
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }
    }
}
