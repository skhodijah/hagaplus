<?php

use App\Models\PaymentMethod;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Update or Create BCA
$bca = PaymentMethod::where('name', 'BCA')->orWhere('name', 'BCA Transfer')->first();
if ($bca) {
    $bca->update([
        'name' => 'BCA Transfer',
        'type' => 'bank_transfer',
        'account_name' => 'PT. Haga Plus Indonesia',
        'account_number' => '1234567890',
        'bank_name' => 'Bank Central Asia (BCA)',
        'description' => 'Transfer ke rekening BCA untuk pembayaran subscription',
        'is_active' => true,
    ]);
} else {
    PaymentMethod::create([
        'name' => 'BCA Transfer',
        'type' => 'bank_transfer',
        'account_name' => 'PT. Haga Plus Indonesia',
        'account_number' => '1234567890',
        'bank_name' => 'Bank Central Asia (BCA)',
        'description' => 'Transfer ke rekening BCA untuk pembayaran subscription',
        'is_active' => true,
    ]);
}

// Update or Create Mandiri
$mandiri = PaymentMethod::where('name', 'Mandiri')->orWhere('name', 'Mandiri Transfer')->first();
if ($mandiri) {
    $mandiri->update([
        'name' => 'Mandiri Transfer',
        'type' => 'bank_transfer',
        'account_name' => 'PT. Haga Plus Indonesia',
        'account_number' => '0987654321',
        'bank_name' => 'Bank Mandiri',
        'description' => 'Transfer ke rekening Mandiri untuk pembayaran subscription',
        'is_active' => true,
    ]);
} else {
    PaymentMethod::create([
        'name' => 'Mandiri Transfer',
        'type' => 'bank_transfer',
        'account_name' => 'PT. Haga Plus Indonesia',
        'account_number' => '0987654321',
        'bank_name' => 'Bank Mandiri',
        'description' => 'Transfer ke rekening Mandiri untuk pembayaran subscription',
        'is_active' => true,
    ]);
}

// Update or Create QRIS
PaymentMethod::updateOrCreate(
    ['type' => 'qris'], 
    [
        'name' => 'QRIS Payment',
        'type' => 'qris',
        'description' => 'Pembayaran menggunakan QRIS yang dapat diakses melalui berbagai aplikasi e-wallet',
        'is_active' => true,
        'qris_data' => '00020101021126690021ID.CO.BANKMANDIRI.WWW01189360000801178300070211711783000730303UKE51440014ID.CO.QRIS.WWW0215ID10232654103820303UKE5204274153033605802ID5908LM Store6015Tangerang (Kab)61051582062070703A0163040C26',
    ]
);

// Update or Create GoPay
PaymentMethod::updateOrCreate(
    ['name' => 'GoPay'], 
    [
        'type' => 'ewallet',
        'account_name' => 'Haga Plus',
        'account_number' => '081234567890',
        'description' => 'Transfer melalui GoPay',
        'is_active' => true,
    ]
);

// Update or Create OVO
PaymentMethod::updateOrCreate(
    ['name' => 'OVO'], 
    [
        'type' => 'ewallet',
        'account_name' => 'Haga Plus',
        'account_number' => '081234567891',
        'description' => 'Transfer melalui OVO',
        'is_active' => true,
    ]
);

// Update or Create DANA
PaymentMethod::updateOrCreate(
    ['name' => 'DANA'], 
    [
        'type' => 'ewallet',
        'account_name' => 'Haga Plus',
        'account_number' => '081234567892',
        'description' => 'Transfer melalui DANA',
        'is_active' => true,
    ]
);

// Update or Create ShopeePay
PaymentMethod::updateOrCreate(
    ['name' => 'ShopeePay'], 
    [
        'type' => 'ewallet',
        'account_name' => 'Haga Plus',
        'account_number' => '081234567893',
        'description' => 'Transfer melalui ShopeePay',
        'is_active' => true,
    ]
);

echo "Payment methods updated with QRIS data and E-Wallet accounts.\n";
