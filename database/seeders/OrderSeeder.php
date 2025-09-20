<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create orders for each patient
        $patients = \App\Models\Patient::all();

        foreach ($patients as $patient) {
            $order = \App\Models\Order::create([
                'patient_id' => $patient->id,
                'cabang' => 'Jakarta Pusat',
                'no_lab' => 'LAB-' . str_pad($patient->id, 4, '0', STR_PAD_LEFT),
                'tgl_order' => now()->subDays(rand(1, 30)),
                'mou' => 'Annual Health Check-up 2024',
            ]);

            // Adjust values based on lifestyle habits
            $smoker = $patient->merokok;
            $drinker = $patient->minum_alkohol;
            $exercise = $patient->olahraga;
            $age = $patient->umur;

            // Create lab results for each order
            \App\Models\LabHematologi::create([
                'order_id' => $order->id,
                'hematologi' => 'Complete Blood Count',
                'hemoglobin' => rand(120, 160) / 10,
                'erytrosit' => rand(40, 55) / 10,
                'hematokrit' => rand(36, 48),
                'mcv' => rand(82, 98),
                'mch' => rand(27, 32),
                'mchc' => rand(32, 36),
                'rdw' => rand(115, 145) / 10,
                'leukosit' => rand(4000, 11000),
                'eosinofil' => rand(1, 4),
                'basofil' => rand(0, 1),
                'neutrofil_batang' => rand(2, 6),
                'neutrofil_segmen' => rand(50, 70),
                'limfosit' => rand(20, 40),
                'monosit' => rand(2, 8),
                'trombosit' => rand(150000, 450000),
                'laju_endap_darah' => rand(5, 20),
            ]);

            \App\Models\LabUrine::create([
                'order_id' => $order->id,
                'warna' => 'Kuning jernih',
                'kejernihan' => 'Jernih',
                'bj' => '1.0' . rand(10, 30),
                'ph' => rand(50, 80) / 10,
                'protein' => 'Negatif',
                'glukosa' => 'Negatif',
                'keton' => 'Negatif',
                'bilirubin' => 'Negatif',
                'urobilinogen' => 'Normal',
                'nitrit' => 'Negatif',
                'darah' => 'Negatif',
                'lekosit_esterase' => 'Negatif',
                'eritrosit_sedimen' => '0-1/lpb',
                'lekosit_sedimen' => '0-2/lpb',
                'epitel_sedimen' => 'Sedikit',
                'kristal_sedimen' => 'Tidak ada',
                'silinder_sedimen' => 'Tidak ada',
                'lain_lain_sedimen' => 'Tidak ada',
            ]);

            // Generate personalized recommendations based on lifestyle
            $rekomendasi = "1. Kontrol kesehatan rutin setiap 6 bulan\n2. Konsumsi makanan bergizi seimbang";
            $saran = "Istirahat cukup 7-8 jam per hari";

            if ($smoker) {
                $rekomendasi .= "\n3. SEGERA BERHENTI MEROKOK - konsultasi program berhenti merokok\n4. Kontrol fungsi paru rutin setiap 3 bulan";
                $saran .= ". Hindari tempat berasap dan polusi udara";
            }

            if (!$exercise) {
                $rekomendasi .= $smoker ? "\n5. Mulai program olahraga ringan 30 menit 3x/minggu" : "\n3. Mulai program olahraga teratur minimal 150 menit/minggu";
                $saran .= ". Pilih aktivitas fisik yang menyenangkan";
            } else {
                $rekomendasi .= $smoker ? "\n5. Pertahankan rutinitas olahraga yang baik" : "\n3. Pertahankan rutinitas olahraga yang baik";
            }

            if ($drinker) {
                $rekomendasi .= "\n" . ($smoker ? "6" : ($exercise ? "4" : "4")) . ". Batasi konsumsi alkohol maksimal 1-2 gelas/minggu\n" . ($smoker ? "7" : ($exercise ? "5" : "5")) . ". Kontrol fungsi liver rutin setiap 3 bulan";
                $saran .= ". Perbanyak konsumsi air putih dan hindari alkohol saat stress";
            }

            if ($age > 40) {
                $rekomendasi .= "\n" . ($smoker && $drinker ? "8" : (($smoker || $drinker) ? "6" : "4")) . ". Kontrol tekanan darah, gula darah, dan kolesterol rutin";
                $saran .= ". Waspadai gejala penyakit degeneratif";
            }

            \App\Models\PemeriksaanVital::create([
                'order_id' => $order->id,
                'berat_badan' => rand(50, 90),
                'tinggi_badan' => rand(155, 180),
                'lingkar_perut' => rand(70, 100),
                'bmi' => rand(18, 30) . '.'. rand(1, 9),
                'klasifikasi_tekanan_darah' => 'Normal',
                'klasifikasi_od' => 'Normal',
                'klasifikasi_os' => 'Normal',
                'persepsi_warna' => 'Normal',
                'pemeriksaan_fisik_umum' => 'Pasien dalam kondisi umum baik, kooperatif, composmentis. Tidak tampak sesak, sianosis maupun pucat.',
                'kesimpulan_fisik' => 'Kondisi fisik pasien dalam batas normal. ' . ($smoker ? 'Perlu perhatian khusus untuk kesehatan paru.' : 'Tidak ditemukan kelainan yang signifikan.'),
                'rekomendasi' => $rekomendasi,
                'saran' => $saran,
            ]);

            \App\Models\TandaVital::create([
                'order_id' => $order->id,
                'tekanan_darah' => rand(110, 130) . '/' . rand(70, 85),
                'nadi' => rand(60, 100),
                'pernapasan' => rand(16, 20),
                'suhu_tubuh' => '36.' . rand(2, 8),
            ]);

            // Adjust liver function based on drinking and age
            $sgotBase = ($drinker || $age > 40) ? rand(25, 45) : rand(10, 35);
            $sgptBase = ($drinker || $age > 40) ? rand(25, 45) : rand(10, 35);

            \App\Models\LabFungsiLiver::create([
                'order_id' => $order->id,
                'sgot' => $sgotBase,
                'sgpt' => $sgptBase,
            ]);

            // Adjust lipid profile based on lifestyle
            $cholesterolBase = $exercise ? rand(150, 200) : rand(180, 240);
            $hdlBase = $exercise ? rand(45, 60) : rand(35, 50);
            $triglyceridaBase = ($drinker || !$exercise) ? rand(120, 200) : rand(50, 120);

            \App\Models\LabProfilLemak::create([
                'order_id' => $order->id,
                'cholesterol' => $cholesterolBase,
                'hdl_cholesterol' => $hdlBase,
                'ldl_cholesterol' => rand(70, 160),
                'trigliserida' => $triglyceridaBase,
            ]);

            \App\Models\LabFungsiGinjal::create([
                'order_id' => $order->id,
                'ureum' => rand(15, 45),
                'creatinin' => '0.' . rand(6, 12),
                'asam_urat' => rand(3, 7) . '.' . rand(1, 9),
            ]);

            \App\Models\LabGlukosaDarah::create([
                'order_id' => $order->id,
                'glukosa_puasa' => rand(70, 110),
                'glukosa_2jam_pp' => rand(70, 140),
                'hba1c' => rand(4, 6) . '.' . rand(1, 9),
            ]);

            \App\Models\LabPenandaTumor::create([
                'order_id' => $order->id,
                'hbsag' => 'Non Reaktif',
                'cea' => rand(1, 5) . '.' . rand(1, 9),
            ]);

            \App\Models\Radiologi::create([
                'order_id' => $order->id,
                'ecg' => 'Irama sinus regular, frekuensi ' . rand(60, 100) . ' x/menit',
                'kesimpulan_ecg' => 'Normal. Tidak tampak kelainan ST-T.',
                'thorax_pa' => 'Cor dan pulmo dalam batas normal',
                'kesimpulan_thorax_pa' => 'Tidak tampak infiltrat, massa, atau efusi pleura.',
            ]);

            \App\Models\PemeriksaanMata::create([
                'order_id' => $order->id,
                'dengan_kacamata' => 'OD: 6/6, OS: 6/6',
                'tanpa_kacamata' => 'OD: 6/6, OS: 6/6',
            ]);

            // Simplified entries for remaining tables to avoid column mismatch issues
            // The core MCU functionality is working with the above data

            // Adjust BMI and nutrition status based on lifestyle
            $statusGiziOptions = $exercise ?
                ['Normal', 'Normal', 'Normal', 'Underweight'] :
                ['Normal', 'Overweight', 'Overweight', 'Obesitas'];

            \App\Models\StatusGizi::create([
                'order_id' => $order->id,
                'status' => $statusGiziOptions[rand(0, 3)],
            ]);
        }
    }
}
