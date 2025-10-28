<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Admin Users
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'unit' => 'Diskominfo',
                'phone' => '081234567890',
                'is_active' => true,
            ],
            [
                'name' => 'Ismu Haqi',
                'username' => 'ismu',
                'email' => 'ismuhaqi@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'unit' => 'Bidang TIK',
                'phone' => '081234567891',
                'is_active' => true,
            ],

            // Staff Users
            [
                'name' => 'Akun Adinata',
                'username' => 'akun',
                'email' => 'akunadinata@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'unit' => 'Bidang E-Government',
                'phone' => '081234567892',
                'is_active' => true,
            ],
            [
                'name' => 'Syafira',
                'username' => 'syafira',
                'email' => 'syafira@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'unit' => 'Bidang Infrastruktur',
                'phone' => '081234567893',
                'is_active' => true,
            ],
            [
                'name' => 'Asmaul Husna',
                'username' => 'asmaul',
                'email' => 'asmaulhusna@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'unit' => 'Bidang Aplikasi',
                'phone' => '081234567894',
                'is_active' => true,
            ],
            [
                'name' => 'Dewi Kartika',
                'username' => 'dewikartika',
                'email' => 'dewi@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'unit' => 'Bidang Statistik',
                'phone' => '081234567895',
                'is_active' => true,
            ],
            [
                'name' => 'Akmal Fitrianto',
                'username' => 'akmal',
                'email' => 'akmal@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'unit' => 'Bidang Komunikasi',
                'phone' => '081234567896',
                'is_active' => true,
            ],
            [
                'name' => 'Aldy Rahmatahalu',
                'username' => 'aldy',
                'email' => 'aldy@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'unit' => 'Bidang Persandian',
                'phone' => '081234567897',
                'is_active' => false, // User nonaktif untuk testing
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        $this->command->info('✅ ' . count($users) . ' user berhasil dibuat!');
        $this->command->info('📧 Default password untuk semua user: password');
    }
}