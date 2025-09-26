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
            'email' => 'admin@ainosi.co.id',
            'password' => Hash::make('123'),
            'role' => 'admin',
        ]);

    

        // Employee user accounts
        $employees = [
    ['name' => 'Muhammad Salman Alfarisi', 'email' => 'user@a.com'],
    ['name' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza @ainosi.co.id'],
    ['name' => 'Budi Santoso', 'email' => 'budi.santoso@ainosi.co.id'],
    ['name' => 'Dewi Lestari', 'email' => 'dewi.lestari@ainosi.co.id'],
    ['name' => 'Rizky Pratama', 'email' => 'rizky.pratama@ainosi.co.id'],
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
