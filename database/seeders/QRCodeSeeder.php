<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QRCode;
use Illuminate\Support\Str;
use Carbon\Carbon;

class QRCodeSeeder extends Seeder
{
    public function run()
    {
        // Generate QR codes for each branch
        for ($branchId = 1; $branchId <= 8; $branchId++) {
            // Current active QR code
            QRCode::create([
                'branch_id' => $branchId,
                'code' => 'QR_' . $branchId . '_' . Str::random(16),
                'expires_at' => Carbon::now()->addHours(24),
                'is_active' => true,
            ]);

            // Some expired QR codes for history
            QRCode::create([
                'branch_id' => $branchId,
                'code' => 'QR_' . $branchId . '_' . Str::random(16),
                'expires_at' => Carbon::now()->subHours(25),
                'is_active' => false,
            ]);
        }
    }
}
