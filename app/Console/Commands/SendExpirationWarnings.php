<?php

namespace App\Console\Commands;

use App\Models\SuperAdmin\Instansi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendExpirationWarnings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-expiration-warnings {--days=3 : Days before expiration to send warning}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send expiration warnings for subscriptions expiring soon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $this->info("Checking for subscriptions expiring in {$days} days...");

        // Get instansi that will expire in the specified days
        $expiringInstansi = Instansi::where('status_langganan', 'active')
            ->whereBetween('subscription_end', [
                now()->startOfDay(),
                now()->addDays($days)->endOfDay()
            ])
            ->get();

        if ($expiringInstansi->isEmpty()) {
            $this->info("No subscriptions expiring in {$days} days.");
            return;
        }

        $this->info("Found {$expiringInstansi->count()} subscriptions expiring soon.");

        // Get superadmin user
        $superAdmin = DB::table('users')->where('role', 'superadmin')->first();

        if (!$superAdmin) {
            $this->error('No superadmin user found!');
            return;
        }

        $warningsSent = 0;

        foreach ($expiringInstansi as $instansi) {
            $daysUntilExpiry = now()->diffInDays($instansi->subscription_end, false);

            // Create notification for superadmin
            DB::table('notifications')->insert([
                'user_id' => $superAdmin->id,
                'title' => 'Peringatan Kadaluarsa Langganan',
                'message' => "Langganan {$instansi->nama_instansi} akan berakhir dalam {$daysUntilExpiry} hari ({$instansi->subscription_end->format('d M Y')}).",
                'type' => 'warning',
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $warningsSent++;
            $this->line("Sent warning for: {$instansi->nama_instansi} (expires: {$instansi->subscription_end->format('Y-m-d')})");
        }

        $this->info("Successfully sent {$warningsSent} expiration warnings.");
    }
}
