<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin User
        User::create([
            'username' => 'admin',
            'name' => 'Administrator Gandaria',
            'email' => 'admin@gandaria.com',
            'role' => 'admin',
            'password' => Hash::make('password123'), // Kredensial: admin / password123
        ]);

        // 2. Staff User
        User::create([
            'username' => 'staff1',
            'name' => 'Staff Arsip',
            'email' => 'staff1@gandaria.com',
            'role' => 'staff',
            'password' => Hash::make('password123'), // Kredensial: staff1 / password123
        ]);
    }
}