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
            'password' => Hash::make('123'),
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
    ['name' => 'Muhammad Salman Alfarisi', 'email' => 'user@a.com'],
    ['name' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza@company.com'],
    ['name' => 'Budi Santoso', 'email' => 'budi.santoso@company.com'],
    ['name' => 'Dewi Lestari', 'email' => 'dewi.lestari@company.com'],
    ['name' => 'Rizky Pratama', 'email' => 'rizky.pratama@company.com'],
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
