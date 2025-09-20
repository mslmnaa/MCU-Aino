<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin accounts
        User::create([
            'name' => 'Admin',
            'email' => 'admin@mcu.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Dr. Maria Sari',
            'email' => 'maria@mcu.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Employee user accounts
        $employees = [
            ['name' => 'John Doe', 'email' => 'john.doe@company.com'],
            ['name' => 'Jane Smith', 'email' => 'jane.smith@company.com'],
            ['name' => 'Ahmad Wijaya', 'email' => 'ahmad.wijaya@company.com'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza@company.com'],
            ['name' => 'Budi Santoso', 'email' => 'budi.santoso@company.com'],
        ];

        foreach ($employees as $employee) {
            User::create([
                'name' => $employee['name'],
                'email' => $employee['email'],
                'password' => Hash::make('123'),
                'role' => 'user',
            ]);
        }
    }
}
