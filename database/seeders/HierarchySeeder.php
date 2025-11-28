<?php

namespace Database\Seeders;

use App\Models\Admin\Division;
use App\Models\Admin\Department;
use App\Models\Admin\Position;
use App\Models\Admin\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HierarchySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // Get the first instansi (you can modify this to target specific instansi)
            $instansiId = DB::table('instansis')->first()->id ?? 1;

            // Ensure required roles exist
            $roleUser = Role::firstOrCreate(
                ['name' => 'User', 'instansi_id' => $instansiId],
                ['description' => 'Regular user role', 'is_active' => true]
            );

            $roleApprover = Role::firstOrCreate(
                ['name' => 'Approver', 'instansi_id' => $instansiId],
                ['description' => 'Approver role', 'is_active' => true]
            );

            $roleAdmin = Role::firstOrCreate(
                ['name' => 'Admin', 'instansi_id' => $instansiId],
                ['description' => 'Administrator role', 'is_active' => true]
            );

            // Create Divisions
            $marketing = Division::firstOrCreate(
                ['name' => 'Marketing', 'instansi_id' => $instansiId],
                ['code' => 'MKT', 'description' => 'Marketing Division', 'is_active' => true]
            );

            $finance = Division::firstOrCreate(
                ['name' => 'Finance', 'instansi_id' => $instansiId],
                ['code' => 'FIN', 'description' => 'Finance Division', 'is_active' => true]
            );

            $operations = Division::firstOrCreate(
                ['name' => 'Operations', 'instansi_id' => $instansiId],
                ['code' => 'OPS', 'description' => 'Operations Division', 'is_active' => true]
            );

            // Seed Marketing Division
            $this->seedDivision($marketing, [
                'Social Media' => [
                    ['name' => 'Social Media Staff', 'role' => $roleUser, 'description' => 'Handles social media content'],
                    ['name' => 'Social Media Manager', 'role' => $roleApprover, 'description' => 'Manages social media team'],
                ],
                'Branding' => [
                    ['name' => 'Branding Staff', 'role' => $roleUser, 'description' => 'Works on branding materials'],
                    ['name' => 'Branding Lead', 'role' => $roleAdmin, 'description' => 'Leads branding initiatives'],
                ],
            ]);

            // Seed Finance Division
            $this->seedDivision($finance, [
                'Accounting' => [
                    ['name' => 'Accounting Staff', 'role' => $roleUser, 'description' => 'Handles accounting tasks'],
                    ['name' => 'Accounting Manager', 'role' => $roleApprover, 'description' => 'Manages accounting team'],
                ],
                'Tax' => [
                    ['name' => 'Tax Officer', 'role' => $roleUser, 'description' => 'Handles tax compliance'],
                    ['name' => 'Tax Manager', 'role' => $roleApprover, 'description' => 'Manages tax department'],
                ],
            ]);

            // Seed Operations Division
            $this->seedDivision($operations, [
                'Production' => [
                    ['name' => 'Operator', 'role' => $roleUser, 'description' => 'Production line operator'],
                    ['name' => 'Production Supervisor', 'role' => $roleApprover, 'description' => 'Supervises production'],
                ],
                'Warehouse' => [
                    ['name' => 'Warehouse Staff', 'role' => $roleUser, 'description' => 'Manages warehouse inventory'],
                    ['name' => 'Warehouse Manager', 'role' => $roleApprover, 'description' => 'Manages warehouse operations'],
                ],
            ]);

            DB::commit();

            $this->command->info('✅ Hierarchy seeded successfully!');
            $this->command->info('📊 Created:');
            $this->command->info('   - 3 Divisions (Marketing, Finance, Operations)');
            $this->command->info('   - 6 Departments');
            $this->command->info('   - 12 Positions');
            $this->command->info('   - 3 Roles (User, Approver, Admin)');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Error seeding hierarchy: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Helper to seed a division with departments and positions.
     */
    private function seedDivision(Division $division, array $departments): void
    {
        foreach ($departments as $deptName => $positions) {
            $department = Department::firstOrCreate([
                'instansi_id' => $division->instansi_id,
                'division_id' => $division->id,
                'name' => $deptName,
            ], [
                'description' => "{$deptName} Department",
                'is_active' => true,
            ]);

            foreach ($positions as $posData) {
                Position::firstOrCreate([
                    'instansi_id' => $division->instansi_id,
                    'division_id' => $division->id,
                    'department_id' => $department->id,
                    'name' => $posData['name'],
                ], [
                    'role_id' => $posData['role']->id,
                    'description' => $posData['description'] ?? null,
                    'is_active' => true,
                ]);
            }
        }
    }
}
