<?php

namespace Database\Seeders;

use App\Models\SuperAdmin\Instansi;
use App\Models\SuperAdmin\Package;
use App\Models\SuperAdmin\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $instansis = Instansi::all();
        $packages = Package::all();

        foreach ($instansis as $instansi) {
            // Skip instansi without active package
            if (!$instansi->package_id) {
                continue;
            }

            $package = $packages->find($instansi->package_id);

            if ($package) {
                // Create active subscription
                Subscription::create([
                    'instansi_id' => $instansi->id,
                    'package_id' => $package->id,
                    'status' => $instansi->status_langganan === 'active' ? 'active' : 'pending_verification',
                    'start_date' => $instansi->subscription_start ?? now(),
                    'end_date' => $instansi->subscription_end ?? now()->addMonth(),
                    'price' => $package->price,
                    'payment_method' => 'transfer',
                    'notes' => 'Initial subscription created via seeder',
                ]);

                // Create a pending subscription for one instansi to show the flow
                if ($instansi->id === 1) {
                    Subscription::create([
                        'instansi_id' => $instansi->id,
                        'package_id' => $packages->where('name', 'Premium')->first()?->id ?? $package->id,
                        'status' => 'pending_verification',
                        'start_date' => now(),
                        'end_date' => now()->addMonth(),
                        'price' => 200000,
                        'payment_method' => 'transfer',
                        'notes' => 'Upgrade request - pending verification',
                    ]);
                }
            }
        }
    }
}