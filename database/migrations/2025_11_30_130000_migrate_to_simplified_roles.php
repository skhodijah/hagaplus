<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SuperAdmin\Instansi;
use App\Services\DefaultRoleService;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate all existing instansi to simplified 3-role structure
        $instansis = \App\Models\SuperAdmin\Instansi::all();
        
        foreach ($instansis as $instansi) {
            echo "Migrating roles for: {$instansi->nama_instansi}\n";
            
            DB::transaction(function () use ($instansi) {
                // 1. Rename old roles to avoid name conflicts
                DB::table('instansi_roles')
                    ->where('instansi_id', $instansi->id)
                    ->update(['name' => DB::raw("CONCAT(name, '_OLD')")]);
                
                // 2. Create new simplified roles
                DefaultRoleService::createDefaultRoles($instansi->id);
                
                // 3. Move employees to new roles
                $employees = DB::table('employees')
                    ->where('instansi_id', $instansi->id)
                    ->get();
                    
                foreach ($employees as $employee) {
                    if (!$employee->instansi_role_id) continue;
                    
                    $oldRole = DB::table('instansi_roles')->find($employee->instansi_role_id);
                    if (!$oldRole) continue;
                    
                    // Strip _OLD suffix to get original name
                    $oldName = str_replace('_OLD', '', $oldRole->name);
                    $newName = $this->mapOldRoleToNew($oldName);
                    
                    $newRole = DB::table('instansi_roles')
                        ->where('instansi_id', $instansi->id)
                        ->where('name', $newName)
                        ->first();
                        
                    if ($newRole) {
                        DB::table('employees')
                            ->where('id', $employee->id)
                            ->update(['instansi_role_id' => $newRole->id]);
                            
                        echo "    Employee ID {$employee->id}: {$oldName} -> {$newName}\n";
                    }
                }
                
                // 4. Delete old roles
                // Since employees have been moved, they won't be deleted by cascade
                DB::table('instansi_roles')
                    ->where('instansi_id', $instansi->id)
                    ->where('name', 'LIKE', '%_OLD')
                    ->delete();
                
                echo "  ✓ Roles migrated successfully\n";
            });
        }
    }

    /**
     * Migrate user roles from old to new structure
     * @deprecated Integrated into up()
     */
    private function migrateUserRoles(int $instansiId): void
    {
        // Deprecated
    }
    
    /**
     * Map old role names to new simplified roles
     */
    private function mapOldRoleToNew(string $oldRoleName): string
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reverse this migration easily
        // Would need to restore old role structure
        echo "Cannot reverse this migration. Please restore from backup if needed.\n";
    }
};
