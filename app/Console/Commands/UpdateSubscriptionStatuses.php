<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SuperAdmin\Subscription;

class UpdateSubscriptionStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:update-statuses {--dry-run : Show what would be updated without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update subscription statuses based on current dates (expired, active, etc.)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('DRY RUN MODE - No changes will be made');
        }

        $this->info('Checking subscription statuses...');

        // Get all subscriptions that are not manually suspended or canceled
        $subscriptions = Subscription::whereNotIn('status', ['suspended', 'canceled'])->get();

        $updatedCount = 0;
        $expiredCount = 0;
        $activatedCount = 0;

        foreach ($subscriptions as $subscription) {
            $currentStatus = $subscription->getCurrentStatusAttribute();

            if ($subscription->status !== $currentStatus) {
                if ($dryRun) {
                    $this->line("Would update subscription ID {$subscription->id} from '{$subscription->status}' to '{$currentStatus}'");
                } else {
                    $subscription->updateStatusBasedOnDate();
                    $this->line("Updated subscription ID {$subscription->id} from '{$subscription->status}' to '{$currentStatus}'");
                }

                $updatedCount++;

                if ($currentStatus === 'expired') {
                    $expiredCount++;
                } elseif ($currentStatus === 'active') {
                    $activatedCount++;
                }
            }
        }

        $this->info("Status update complete!");
        $this->info("Total subscriptions checked: " . $subscriptions->count());
        $this->info("Subscriptions updated: " . $updatedCount);
        $this->info("Subscriptions expired: " . $expiredCount);
        $this->info("Subscriptions activated: " . $activatedCount);

        if ($dryRun) {
            $this->warn('This was a dry run - no actual changes were made.');
        }
    }
}
