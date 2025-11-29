<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Administrator dengan akses penuh ke sistem',
            ],
            [
                'name' => 'employee',
                'display_name' => 'Employee',
                'description' => 'Karyawan dengan akses terbatas',
            ],
        ];

        foreach ($roles as $roleData) {
            \App\Models\Admin\SystemRole::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        $this->command->info('System roles seeded successfully!');
    }
}
