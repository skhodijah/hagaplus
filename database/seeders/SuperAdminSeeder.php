<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@hagaplus.com',
            'password' => Hash::make('SuperAdmin123!'),
            'role' => 'super_admin',
            'is_active' => true,
            'phone' => '081234567890',
            'position' => 'System Administrator',
        ]);
    }
}
