<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\Instansi;
use App\Models\Admin\InstansiRole;
use App\Services\DefaultRoleService;
use Illuminate\Support\Facades\DB;

class MigrateToSimplifiedRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:migrate-simplified {--instansi= : Specific instansi ID to migrate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate instansi roles to simplified 3-role structure (Employee, User, HRD)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting role migration to simplified structure...');
        $this->newLine();
        
        // Get instansi to migrate
        if ($instansiId = $this->option('instansi')) {
            $instansis = Instansi::where('id', $instansiId)->get();
            if ($instansis->isEmpty()) {
                $this->error("Instansi with ID {$instansiId} not found!");
                return 1;
            }
        } else {
            $instansis = Instansi::all();
        }
        
        $this->info("Found {$instansis->count()} instansi to migrate");
        $this->newLine();
        
        $bar = $this->output->createProgressBar($instansis->count());
        $bar->start();
        
        foreach ($instansis as $instansi) {
            try {
                DB::transaction(function () use ($instansi) {
                    $this->migrateInstansiRoles($instansi);
                });
                $bar->advance();
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Failed to migrate {$instansi->nama_instansi}: " . $e->getMessage());
                $bar->advance();
            }
        }
        
        $bar->finish();
        $this->newLine(2);
        $this->info('✅ Role migration completed!');
        
        return 0;
    }
    
    /**
     * Migrate roles for a single instansi
     */
    private function migrateInstansiRoles(Instansi $instansi): void
    {
        // Get all users in this instansi with their current roles
        $users = DB::table('users')
            ->leftJoin('instansi_roles', 'users.instansi_role_id', '=', 'instansi_roles.id')
            ->where('users.instansi_id', $instansi->id)
            ->select('users.id as user_id', 'users.name', 'instansi_roles.name as role_name')
            ->get();
        
        // Map users to new roles
        $userRoleMapping = [];
        foreach ($users as $user) {
            $newRoleName = $this->mapOldRoleToNew($user->role_name ?? 'Employee');
            $userRoleMapping[$user->user_id] = $newRoleName;
        }
        
        // Delete all existing roles for this instansi
        InstansiRole::where('instansi_id', $instansi->id)->delete();
        
        // Create new simplified roles
        DefaultRoleService::createDefaultRoles($instansi->id);
        
        // Get new role IDs
        $newRoles = InstansiRole::where('instansi_id', $instansi->id)
            ->get()
            ->keyBy('name');
        
        // Update users with new role IDs
        foreach ($userRoleMapping as $userId => $newRoleName) {
            if (isset($newRoles[$newRoleName])) {
                DB::table('users')
                    ->where('id', $userId)
                    ->update(['instansi_role_id' => $newRoles[$newRoleName]->id]);
            }
        }
    }
    
    /**
     * Map old role names to new simplified roles
     */
    private function mapOldRoleToNew(?string $oldRoleName): string
    {
        $mapping = [
            'Employee' => 'Employee',
            'Approver' => 'User',
            'Supervisor' => 'User',
            'Manager' => 'User',
            'HR' => 'HRD',
            'Finance' => 'HRD',
        ];
        
        return $mapping[$oldRoleName] ?? 'Employee';
    }
}
