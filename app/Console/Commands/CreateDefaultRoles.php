<?php

namespace App\Console\Commands;

use App\Models\SuperAdmin\Instansi;
use App\Services\DefaultRoleService;
use Illuminate\Console\Command;

class CreateDefaultRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:create-defaults {--instansi-id= : Specific instansi ID to create roles for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default roles for all instansi or a specific instansi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $instansiId = $this->option('instansi-id');

        if ($instansiId) {
            // Create for specific instansi
            $instansi = Instansi::find($instansiId);
            
            if (!$instansi) {
                $this->error("Instansi with ID {$instansiId} not found.");
                return 1;
            }

            $this->createRolesForInstansi($instansi);
        } else {
            // Create for all instansi
            $instansis = Instansi::all();
            
            if ($instansis->isEmpty()) {
                $this->warn('No instansi found.');
                return 0;
            }

            $this->info("Creating default roles for {$instansis->count()} instansi...");
            
            foreach ($instansis as $instansi) {
                $this->createRolesForInstansi($instansi);
            }
        }

        $this->info('✅ Default roles created successfully!');
        return 0;
    }

    /**
     * Create default roles for an instansi
     */
    private function createRolesForInstansi(Instansi $instansi): void
    {
        // Check if instansi already has default roles
        $existingDefaultRoles = $instansi->instansiRoles()->where('is_default', true)->count();
        
        if ($existingDefaultRoles > 0) {
            $this->warn("⚠️  Instansi '{$instansi->name}' already has {$existingDefaultRoles} default roles. Skipping...");
            return;
        }

        $this->line("Creating default roles for: {$instansi->name}");
        DefaultRoleService::createDefaultRoles($instansi->id);
        $this->info("✓ Created 6 default roles for '{$instansi->name}'");
    }
}
