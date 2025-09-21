<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Core\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Admin users untuk setiap company
        $admins = [
            [
                'company_id' => 1,
                'branch_id' => 1,
                'employee_id' => 'TMB001',
                'name' => 'Budi Santoso',
                'email' => 'admin@teknologimaju.com',
                'password' => Hash::make('admin123'),
                'phone' => '081234567891',
                'role' => 'admin',
                'position' => 'HR Manager',
                'department' => 'Human Resources',
                'hire_date' => '2023-01-15',
                'salary' => 12000000.00,
            ],
            [
                'company_id' => 2,
                'branch_id' => 4,
                'employee_id' => 'KDS001',
                'name' => 'Sari Dewi',
                'email' => 'hr@kreatifdigital.com',
                'password' => Hash::make('admin123'),
                'phone' => '082345678901',
                'role' => 'admin',
                'position' => 'People Operations',
                'department' => 'Human Resources',
                'hire_date' => '2023-03-01',
                'salary' => 8500000.00,
            ],
            [
                'company_id' => 3,
                'branch_id' => 6,
                'employee_id' => 'BJ001',
                'name' => 'Ahmad Wijaya',
                'email' => 'owner@berkahjaya.com',
                'password' => Hash::make('admin123'),
                'phone' => '083456789012',
                'role' => 'admin',
                'position' => 'Owner',
                'department' => 'Management',
                'hire_date' => '2022-05-10',
                'salary' => 15000000.00,
            ],
            [
                'company_id' => 4,
                'branch_id' => 7,
                'employee_id' => 'IMN001',
                'name' => 'Diana Permata',
                'email' => 'hrd@manufakturnusantara.co.id',
                'password' => Hash::make('admin123'),
                'phone' => '084567890123',
                'role' => 'admin',
                'position' => 'HRD Manager',
                'department' => 'Human Resources',
                'hire_date' => '2022-08-20',
                'salary' => 18000000.00,
            ],
        ];

        foreach ($admins as $admin) {
            User::create($admin);
        }

        // Generate employees untuk Company 1 (PT Teknologi Maju)
        $positions = ['Software Engineer', 'UI/UX Designer', 'Project Manager', 'Quality Assurance', 'DevOps Engineer', 'Business Analyst', 'Marketing Specialist'];
        $departments = ['Engineering', 'Design', 'Project Management', 'Quality Assurance', 'Infrastructure', 'Business', 'Marketing'];

        for ($i = 2; $i <= 25; $i++) {
            $position = $faker->randomElement($positions);
            $department = $faker->randomElement($departments);

            User::create([
                'company_id' => 1,
                'branch_id' => $faker->randomElement([1, 2, 3]),
                'employee_id' => 'TMB' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('employee123'),
                'phone' => $faker->phoneNumber,
                'role' => 'employee',
                'position' => $position,
                'department' => $department,
                'hire_date' => $faker->dateTimeBetween('-2 years', '-1 month')->format('Y-m-d'),
                'salary' => $faker->numberBetween(5000000, 15000000),
            ]);
        }

        // Generate employees untuk Company 2 (CV Kreatif Digital)
        for ($i = 2; $i <= 15; $i++) {
            User::create([
                'company_id' => 2,
                'branch_id' => $faker->randomElement([4, 5]),
                'employee_id' => 'KDS' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('employee123'),
                'phone' => $faker->phoneNumber,
                'role' => 'employee',
                'position' => $faker->randomElement(['Web Developer', 'Graphic Designer', 'Content Creator', 'Digital Marketer']),
                'department' => $faker->randomElement(['Development', 'Creative', 'Marketing']),
                'hire_date' => $faker->dateTimeBetween('-1 year', '-1 month')->format('Y-m-d'),
                'salary' => $faker->numberBetween(4000000, 10000000),
            ]);
        }

        // Generate employees untuk Company 3 (Toko Berkah Jaya)
        for ($i = 2; $i <= 8; $i++) {
            User::create([
                'company_id' => 3,
                'branch_id' => 6,
                'employee_id' => 'BJ' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('employee123'),
                'phone' => $faker->phoneNumber,
                'role' => 'employee',
                'position' => $faker->randomElement(['Sales Associate', 'Cashier', 'Store Manager', 'Inventory Staff']),
                'department' => $faker->randomElement(['Sales', 'Operations']),
                'hire_date' => $faker->dateTimeBetween('-3 years', '-1 month')->format('Y-m-d'),
                'salary' => $faker->numberBetween(3000000, 7000000),
            ]);
        }

        // Generate employees untuk Company 4 (PT Industri Manufaktur) - lebih banyak
        for ($i = 2; $i <= 50; $i++) {
            User::create([
                'company_id' => 4,
                'branch_id' => $faker->randomElement([7, 8]),
                'employee_id' => 'IMN' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('employee123'),
                'phone' => $faker->phoneNumber,
                'role' => 'employee',
                'position' => $faker->randomElement(['Production Operator', 'Quality Control', 'Maintenance', 'Supervisor', 'Foreman', 'Safety Officer']),
                'department' => $faker->randomElement(['Production', 'Quality Control', 'Maintenance', 'Safety']),
                'hire_date' => $faker->dateTimeBetween('-5 years', '-1 month')->format('Y-m-d'),
                'salary' => $faker->numberBetween(4500000, 12000000),
            ]);
        }
    }
}
