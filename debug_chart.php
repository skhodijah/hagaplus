<?php
// Debug script untuk memeriksa data chart
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\SuperAdmin\Instansi;
use App\Models\SuperAdmin\Package;
use App\Models\SuperAdmin\Subscription;

echo "=== DEBUG CHART DATA ===\n";

// Check if subscription_history table exists
$hasSubscriptionHistory = Schema::hasTable('subscription_history');
echo "Has subscription_history table: " . ($hasSubscriptionHistory ? 'YES' : 'NO') . "\n";

// Check subscription_history data
if ($hasSubscriptionHistory) {
    $totalRecords = DB::table('subscription_history')->count();
    echo "Total subscription_history records: $totalRecords\n";
    
    if ($totalRecords > 0) {
        $sampleRecord = DB::table('subscription_history')->first();
        echo "Sample record: " . json_encode($sampleRecord) . "\n";
    }
}

// Check subscriptions table
$totalSubscriptions = Subscription::count();
echo "Total subscriptions: $totalSubscriptions\n";

if ($totalSubscriptions > 0) {
    $sampleSubscription = Subscription::first();
    echo "Sample subscription: " . json_encode($sampleSubscription->toArray()) . "\n";
}

// Test monthly revenue calculation
echo "\n=== MONTHLY REVENUE TEST ===\n";
$monthlyRevenue = [];
for ($i = 11; $i >= 0; $i--) {
    $date = now()->subMonths($i);
    $revenue = $hasSubscriptionHistory
        ? DB::table('subscription_history')
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->sum('amount')
        : 0;
    $monthlyRevenue[] = [
        'month' => $date->format('M Y'),
        'revenue' => $revenue
    ];
    echo "Month: {$date->format('M Y')}, Revenue: $revenue\n";
}

// Test monthly subscription counts
echo "\n=== MONTHLY SUBSCRIPTION COUNTS TEST ===\n";
$since = now()->subDays(30);
$monthlySubscriptionCounts = Subscription::select(
    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as ym'),
    DB::raw('count(*) as total')
)
    ->where('created_at', '>=', $since)
    ->groupBy('ym')
    ->orderBy('ym')
    ->get();

echo "Monthly subscription counts:\n";
foreach ($monthlySubscriptionCounts as $count) {
    echo "Period: {$count->ym}, Count: {$count->total}\n";
}

// Test package distribution
echo "\n=== PACKAGE DISTRIBUTION TEST ===\n";
$packageDistribution = Subscription::select('package_id', DB::raw('count(*) as total'))
    ->where('status', 'active')
    ->groupBy('package_id')
    ->with('package')
    ->get();

echo "Package distribution:\n";
foreach ($packageDistribution as $dist) {
    $packageName = $dist->package ? $dist->package->nama_paket : 'Unknown';
    echo "Package: $packageName, Count: {$dist->total}\n";
}

echo "\n=== CHART DATA SUMMARY ===\n";
echo "Monthly Revenue Data: " . json_encode($monthlyRevenue) . "\n";
echo "Monthly Subscription Counts: " . json_encode($monthlySubscriptionCounts->toArray()) . "\n";
echo "Package Distribution: " . json_encode($packageDistribution->toArray()) . "\n"; 