<?php

namespace Database\Seeders;

use App\Models\Core\User;
use App\Models\SuperAdmin\Instansi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $instansis = Instansi::all();

        // Create superadmin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@hagaplus.com',
            'phone' => '6281234567890',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'instansi_id' => null,
        ]);

        // Create admin users for each instansi
        foreach ($instansis as $instansi) {
            $subdomain = str_replace([' ', '.'], ['', ''], strtolower($instansi->subdomain));

            User::create([
                'name' => 'Admin ' . $instansi->nama_instansi,
                'email' => 'admin@' . $subdomain . '.com',
                'phone' => $this->generatePhoneNumber(),
                'password' => Hash::make('password'),
                'role' => 'admin',
                'instansi_id' => $instansi->id,
            ]);

            // Create some employee users for each instansi
            $employeeCount = min($instansi->max_employees, 5); // Create up to 5 employees per instansi

            for ($i = 1; $i <= $employeeCount; $i++) {
                User::create([
                    'name' => 'Employee ' . $i . ' ' . $instansi->nama_instansi,
                    'email' => 'employee' . $i . '@' . $subdomain . '.com',
                    'phone' => $this->generatePhoneNumber(),
                    'password' => Hash::make('password'),
                    'role' => 'employee',
                    'instansi_id' => $instansi->id,
                ]);
            }
        }
    }

    private function generatePhoneNumber(): string
    {
        $prefixes = ['62812', '62813', '62821', '62822', '62811'];
        $prefix = $prefixes[array_rand($prefixes)];
        $suffix = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
        return $prefix . $suffix;
    }
}