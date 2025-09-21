<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Core\User;
use App\Models\SuperAdmin\Instansi;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run()
    {
        // Create a test instansi
        $instansi = Instansi::firstOrCreate([
            'subdomain' => 'test'
        ], [
            'nama_instansi' => 'Test Instansi',
            'status_langganan' => 'active'
        ]);

        // Create test users for each role
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@test.com',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'instansi_id' => $instansi->id,
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@test.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'instansi_id' => $instansi->id,
            ],
            [
                'name' => 'Employee User',
                'email' => 'employee@test.com',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'instansi_id' => $instansi->id,
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}