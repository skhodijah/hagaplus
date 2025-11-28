<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instansi;
use App\Models\Admin\Branch;
use App\Models\Admin\Division;
use App\Models\Admin\Department;
use App\Models\Admin\Position;
use App\Models\Admin\InstansiRole;
use App\Models\Admin\Employee;
use App\Models\Core\User;
use App\Models\Admin\SystemRole;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class IndonesianDemoSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Ensure System Roles exist
        $superadminRole = SystemRole::firstOrCreate(['slug' => 'superadmin'], ['name' => 'Superadmin', 'description' => 'System Superadmin']);
        $adminRole = SystemRole::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin', 'description' => 'Instansi Admin']);
        $employeeRole = SystemRole::firstOrCreate(['slug' => 'employee'], ['name' => 'Employee', 'description' => 'Standard Employee']);

        // Create Superadmin User if not exists
        if (!User::where('email', 'superadmin@hagaplus.com')->exists()) {
            User::create([
                'name' => 'Super Administrator',
                'email' => 'superadmin@hagaplus.com',
                'password' => Hash::make('password'),
                'system_role_id' => $superadminRole->id,
                'email_verified_at' => now(),
            ]);
        }

        $instansiNames = [
            'PT Teknologi Nusantara Jaya',
            'CV Maju Makmur Abadi',
            'PT Sinergi Solusi Digital',
            'Yayasan Pendidikan Harapan Bangsa',
            'Koperasi Sejahtera Bersama'
        ];

        foreach ($instansiNames as $index => $instansiName) {
            $instansi = Instansi::create([
                'nama_instansi' => $instansiName,
                'subdomain' => strtolower(substr(str_replace(' ', '', $instansiName), 0, 10)) . rand(100, 999),
                'address' => $faker->address,
                'phone' => $faker->phoneNumber,
                'email' => 'contact@' . strtolower(str_replace(' ', '', $instansiName)) . '.com',
                'status_langganan' => 'active', // Changed from status to status_langganan based on migration
                'is_active' => true,
            ]);

            // Create Instansi Admin
            $adminUser = User::create([
                'name' => 'Admin ' . $instansi->nama_instansi,
                'email' => 'admin' . ($index + 1) . '@hagaplus.com',
                'password' => Hash::make('password'),
                'system_role_id' => $adminRole->id,
                'instansi_id' => $instansi->id,
                'email_verified_at' => now(),
            ]);

            // Create Branches
            $branch = Branch::create([
                'company_id' => $instansi->id, // Note: Model might use company_id or instansi_id, checking migration it seems to be company_id in branches table based on previous file list
                'name' => 'Kantor Pusat',
                'address' => $instansi->address,
                'radius' => 100,
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'timezone' => 'Asia/Jakarta',
                'is_active' => true,
            ]);

            // Create Instansi Roles
            $hrRole = InstansiRole::create([
                'instansi_id' => $instansi->id,
                'name' => 'HR Manager',
                'system_role_id' => $adminRole->id,
                'is_active' => true,
            ]);

            $staffRole = InstansiRole::create([
                'instansi_id' => $instansi->id,
                'name' => 'Staff',
                'system_role_id' => $employeeRole->id,
                'is_active' => true,
            ]);

            // Create Divisions
            $divIT = Division::create(['instansi_id' => $instansi->id, 'name' => 'Teknologi Informasi', 'code' => 'IT']);
            $divHR = Division::create(['instansi_id' => $instansi->id, 'name' => 'Sumber Daya Manusia', 'code' => 'HR']);
            $divFin = Division::create(['instansi_id' => $instansi->id, 'name' => 'Keuangan', 'code' => 'FIN']);

            // Create Departments
            $deptDev = Department::create(['instansi_id' => $instansi->id, 'division_id' => $divIT->id, 'name' => 'Pengembangan Software']);
            $deptInfra = Department::create(['instansi_id' => $instansi->id, 'division_id' => $divIT->id, 'name' => 'Infrastruktur']);
            $deptRecruit = Department::create(['instansi_id' => $instansi->id, 'division_id' => $divHR->id, 'name' => 'Rekrutmen']);
            $deptPayroll = Department::create(['instansi_id' => $instansi->id, 'division_id' => $divFin->id, 'name' => 'Penggajian']);

            // Create Positions
            $posSeniorDev = Position::create([
                'instansi_id' => $instansi->id,
                'division_id' => $divIT->id,
                'department_id' => $deptDev->id,
                'instansi_role_id' => $staffRole->id,
                'name' => 'Senior Developer',
                'is_active' => true
            ]);

            $posHRStaff = Position::create([
                'instansi_id' => $instansi->id,
                'division_id' => $divHR->id,
                'department_id' => $deptRecruit->id,
                'instansi_role_id' => $hrRole->id,
                'name' => 'HR Staff',
                'is_active' => true
            ]);

            // Create 10 Employees
            for ($i = 1; $i <= 10; $i++) {
                $gender = $faker->randomElement(['male', 'female']);
                $name = $faker->name($gender);
                $email = strtolower(str_replace(' ', '.', $name)) . '@' . strtolower(str_replace(' ', '', $instansiName)) . '.com';
                
                // Ensure email is unique
                $counter = 1;
                while(User::where('email', $email)->exists()) {
                    $email = strtolower(str_replace(' ', '.', $name)) . $counter . '@' . strtolower(str_replace(' ', '', $instansiName)) . '.com';
                    $counter++;
                }

                $position = $i <= 5 ? $posSeniorDev : $posHRStaff;
                $roleId = $position->instansi_role_id;
                $systemRoleId = $roleId == $hrRole->id ? $adminRole->id : $employeeRole->id;

                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'system_role_id' => $systemRoleId,
                    'instansi_id' => $instansi->id,
                    'email_verified_at' => now(),
                ]);

                Employee::create([
                    'user_id' => $user->id,
                    'instansi_id' => $instansi->id,
                    'branch_id' => $branch->id,
                    'division_id' => $position->division_id,
                    'department_id' => $position->department_id,
                    'position_id' => $position->id,
                    'instansi_role_id' => $roleId,
                    'employee_id' => 'EMP-' . strtoupper(substr(str_replace(' ', '', $instansiName), 0, 4)) . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'status' => 'active',
                    'hire_date' => $faker->dateTimeBetween('-2 years', 'now'),
                    'salary' => $faker->numberBetween(5000000, 15000000),
                ]);
            }
        }
    }
}
