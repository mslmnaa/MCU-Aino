<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get user IDs for linking
        $johnUser = \App\Models\User::where('email', 'user@a.com')->first();
        $janeUser = \App\Models\User::where('email', 'siti.nurhaliza@company.com')->first();
        $ahmadUser = \App\Models\User::where('email', 'budi.santoso@company.com')->first();
        $sitiUser = \App\Models\User::where('email', 'dewi.lestari@company.com')->first();
        $budiUser = \App\Models\User::where('email', 'rizky.pratama@company.com')->first();

        $patients = [
            [
                'user_id' => $johnUser->id,
                'share_id' => 'MCU001',
                'name' => 'Salman Alfarisi',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1985-03-15',
                'umur' => 39,
                'departemen' => 'IT',
                'jabatan' => 'Web Developer',
                'riwayat_kebiasaan_hidup' => 'Sering begadang, jarang olahraga',
                'merokok' => true,
                'minum_alkohol' => false,
                'olahraga' => false,
            ],
            [
                'user_id' => $janeUser->id,
                'share_id' => 'MCU002',
                'name' => 'Jane Smith',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1990-07-22',
                'umur' => 34,
                'departemen' => 'HR',
                'jabatan' => 'HR Manager',
                'riwayat_kebiasaan_hidup' => 'Rutin olahraga, pola makan sehat',
                'merokok' => false,
                'minum_alkohol' => false,
                'olahraga' => true,
            ],
            [
                'user_id' => $ahmadUser->id,
                'share_id' => 'MCU003',
                'name' => 'Ahmad Wijaya',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1982-11-08',
                'umur' => 42,
                'departemen' => 'Finance',
                'jabatan' => 'Finance Manager',
                'riwayat_kebiasaan_hidup' => 'Stress tinggi, sering lembur',
                'merokok' => true,
                'minum_alkohol' => true,
                'olahraga' => false,
            ],
            [
                'user_id' => $sitiUser->id,
                'share_id' => 'MCU004',
                'name' => 'Siti Nurhaliza',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1988-05-12',
                'umur' => 36,
                'departemen' => 'Marketing',
                'jabatan' => 'Marketing Specialist',
                'riwayat_kebiasaan_hidup' => 'Aktif, suka traveling',
                'merokok' => false,
                'minum_alkohol' => false,
                'olahraga' => true,
            ],
            [
                'user_id' => $budiUser->id,
                'share_id' => 'MCU005',
                'name' => 'Budi Santoso',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1975-12-03',
                'umur' => 49,
                'departemen' => 'Operations',
                'jabatan' => 'Operations Manager',
                'riwayat_kebiasaan_hidup' => 'Gaya hidup sedentari, kurang tidur',
                'merokok' => true,
                'minum_alkohol' => true,
                'olahraga' => false,
            ],
        ];

        foreach ($patients as $patient) {
            \App\Models\Patient::create($patient);
        }
    }
}
