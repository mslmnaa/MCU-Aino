<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MultiYearOrderSeeder extends Seeder
{
    public function run(): void
    {
        $patients = \App\Models\Patient::all();
        $years = [2021, 2022, 2023, 2024, 2025];

        foreach ($patients as $patient) {
            foreach ($years as $year) {
                $orderDate = Carbon::create($year, rand(1, 12), rand(1, 28));

                $order = \App\Models\Order::create([
                    'patient_id' => $patient->id,
                    'cabang' => 'Jakarta Pusat',
                    'no_lab' => 'LAB-' . $year . '-' . str_pad($patient->id, 4, '0', STR_PAD_LEFT),
                    'tgl_order' => $orderDate,
                    'mou' => "Annual Health Check-up $year",
                ]);

                $this->createLabResults($order, $patient, $year);
            }
        }
    }

    private function createLabResults($order, $patient, $year)
    {
        $smoker = $patient->merokok;
        $drinker = $patient->minum_alkohol;
        $exercise = $patient->olahraga;
        $age = $patient->umur + ($year - 2025);

        // Simulate health deterioration/improvement over years
        $yearFactor = ($year - 2021) / 4; // 0 to 1 progression
        $agingFactor = 1 + ($yearFactor * 0.1); // Slight aging effect

        // Simulate lifestyle improvements for some patients
        $improvementFactor = 1;
        if ($patient->id % 2 == 0 && $year >= 2023) {
            $improvementFactor = 0.9; // 10% improvement for even ID patients from 2023
        }

        // Hematologi with year variations
        $hemoglobinValue = $this->varyValue(13.5, $smoker ? -1 : 0, $yearFactor, $improvementFactor);
        \App\Models\LabHematologi::create([
            'order_id' => $order->id,
            'hematologi' => 'Complete Blood Count',
            'hemoglobin' => $hemoglobinValue,
            'erytrosit' => $this->varyValue(4.5, $smoker ? -0.2 : 0, $yearFactor, $improvementFactor),
            'hematokrit' => $this->varyValue(42, $smoker ? -2 : 0, $yearFactor, $improvementFactor),
            'mcv' => rand(82, 98),
            'mch' => rand(27, 32),
            'mchc' => rand(32, 36),
            'rdw' => rand(115, 145) / 10,
            'leukosit' => $this->varyValue(7500, $smoker ? 1000 : 0, $yearFactor, $improvementFactor),
            'eosinofil' => rand(1, 4),
            'basofil' => rand(0, 1),
            'neutrofil_batang' => rand(2, 6),
            'neutrofil_segmen' => rand(50, 70),
            'limfosit' => rand(20, 40),
            'monosit' => rand(2, 8),
            'trombosit' => rand(150000, 450000),
            'laju_endap_darah' => $this->varyValue(10, $age > 40 ? 5 : 0, $yearFactor, $improvementFactor),
            'kesimpulan_hematologi' => ($hemoglobinValue >= 12 && $hemoglobinValue <= 16) ? 'Dalam batas normal' : 'Perlu perhatian khusus',
        ]);

        // Urine Analysis
        $proteinValue = rand(1, 100) <= 5 ? 'Trace' : 'Negatif';
        \App\Models\LabUrine::create([
            'order_id' => $order->id,
            'warna' => 'Kuning jernih',
            'kejernihan' => 'Jernih',
            'bj' => '1.0' . rand(10, 30),
            'ph' => rand(50, 80) / 10,
            'protein' => $proteinValue,
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
            'kesimpulan_urine' => ($proteinValue === 'Negatif') ? 'Dalam batas normal' : 'Terdapat protein dalam urine, perlu pemantauan',
        ]);

        // Liver Function with alcohol/age progression
        $sgotBase = $drinker ? 35 : 25;
        $sgptBase = $drinker ? 35 : 25;

        $sgotValue = $this->varyValue($sgotBase, ($drinker ? 10 : 0) + ($age > 40 ? 5 : 0), $yearFactor, $improvementFactor);
        $sgptValue = $this->varyValue($sgptBase, ($drinker ? 10 : 0) + ($age > 40 ? 5 : 0), $yearFactor, $improvementFactor);
        \App\Models\LabFungsiLiver::create([
            'order_id' => $order->id,
            'sgot' => $sgotValue,
            'sgpt' => $sgptValue,
            'kesimpulan_fungsi_hati' => ($sgotValue <= 40 && $sgptValue <= 40) ? 'Dalam batas normal' : 'Fungsi hati perlu evaluasi lebih lanjut',
        ]);

        // Lipid Profile with lifestyle impact
        $cholesterolBase = $exercise ? 180 : 200;
        $hdlBase = $exercise ? 55 : 40;
        $ldlBase = $exercise ? 100 : 130;
        $triglyceridaBase = ($drinker || !$exercise) ? 150 : 90;

        $cholesterolValue = $this->varyValue($cholesterolBase, !$exercise ? 20 : 0, $yearFactor, $improvementFactor);
        $hdlValue = $this->varyValue($hdlBase, $exercise ? 5 : -5, -$yearFactor, $improvementFactor);
        $ldlValue = $this->varyValue($ldlBase, !$exercise ? 15 : 0, $yearFactor, $improvementFactor);
        \App\Models\LabProfilLemak::create([
            'order_id' => $order->id,
            'cholesterol' => $cholesterolValue,
            'hdl_cholesterol' => $hdlValue,
            'ldl_cholesterol' => $ldlValue,
            'trigliserida' => $this->varyValue($triglyceridaBase, ($drinker || !$exercise) ? 30 : 0, $yearFactor, $improvementFactor),
            'kesimpulan_profil_lemak' => ($cholesterolValue < 200 && $hdlValue >= 40 && $ldlValue < 130) ? 'Dalam batas normal' : 'Perlu perhatian terhadap profil lipid',
        ]);

        // Kidney Function
        $ureumValue = $this->varyValue(30, $age > 40 ? 10 : 0, $yearFactor, $improvementFactor);
        $creatininValue = number_format($this->varyValue(0.9, $age > 40 ? 0.2 : 0, $yearFactor, $improvementFactor), 1);
        \App\Models\LabFungsiGinjal::create([
            'order_id' => $order->id,
            'ureum' => $ureumValue,
            'creatinin' => $creatininValue,
            'asam_urat' => number_format($this->varyValue(5, ($drinker ? 1 : 0) + (!$exercise ? 0.5 : 0), $yearFactor, $improvementFactor), 1),
            'kesimpulan_fungsi_ginjal' => ($ureumValue <= 50 && $creatininValue <= 1.2) ? 'Dalam batas normal' : 'Fungsi ginjal perlu evaluasi',
        ]);

        // Blood Glucose
        $glucoseBase = !$exercise ? 95 : 85;
        $glucose2hBase = !$exercise ? 120 : 100;
        $hba1cBase = !$exercise ? 5.5 : 5.0;

        $glukosaPuasaValue = $this->varyValue($glucoseBase, !$exercise ? 10 : 0, $yearFactor, $improvementFactor);
        $hba1cValue = number_format($this->varyValue($hba1cBase, !$exercise ? 0.3 : 0, $yearFactor, $improvementFactor), 1);
        \App\Models\LabGlukosaDarah::create([
            'order_id' => $order->id,
            'glukosa_puasa' => $glukosaPuasaValue,
            'glukosa_2jam_pp' => $this->varyValue($glucose2hBase, !$exercise ? 15 : 0, $yearFactor, $improvementFactor),
            'hba1c' => $hba1cValue,
            'kesimpulan_glukosa' => ($glukosaPuasaValue <= 100 && $hba1cValue < 5.7) ? 'Dalam batas normal' : 'Perlu monitoring kadar glukosa',
        ]);

        // Tumor Markers
        $ceaValue = number_format(rand(10, 40) / 10, 1);
        \App\Models\LabPenandaTumor::create([
            'order_id' => $order->id,
            'hbsag' => 'Non Reaktif',
            'cea' => $ceaValue,
            'kesimpulan_penanda_tumor' => ($ceaValue <= 5.0) ? 'Dalam batas normal' : 'Penanda tumor perlu evaluasi lebih lanjut',
        ]);

        // Vital Signs with age progression
        $systolicBase = 120;
        $diastolicBase = 80;
        $bmiBase = $exercise ? 22 : 25;

        \App\Models\TandaVital::create([
            'order_id' => $order->id,
            'tekanan_darah' =>
                $this->varyValue($systolicBase, $age > 40 ? 10 : 0, $yearFactor, $improvementFactor) . '/' .
                $this->varyValue($diastolicBase, $age > 40 ? 5 : 0, $yearFactor, $improvementFactor),
            'nadi' => rand(60, 100),
            'pernapasan' => rand(16, 20),
            'suhu_tubuh' => '36.' . rand(2, 8),
        ]);

        // Physical Examination
        $rekomendasi = $this->generateRecommendations($patient, $year, $age);

        \App\Models\PemeriksaanVital::create([
            'order_id' => $order->id,
            'berat_badan' => $this->varyValue(70, !$exercise ? 5 : 0, $yearFactor * 0.5, $improvementFactor),
            'tinggi_badan' => 170,
            'lingkar_perut' => $this->varyValue(85, !$exercise ? 10 : 0, $yearFactor, $improvementFactor),
            'bmi' => number_format($this->varyValue($bmiBase, !$exercise ? 2 : 0, $yearFactor, $improvementFactor), 1),
            'klasifikasi_tekanan_darah' => $this->getBloodPressureClass($age, $yearFactor),
            'klasifikasi_od' => 'Normal',
            'klasifikasi_os' => 'Normal',
            'persepsi_warna' => 'Normal',
            'pemeriksaan_fisik_umum' => 'Pasien dalam kondisi umum baik, kooperatif, composmentis.',
            'kesimpulan_fisik' => $this->getPhysicalConclusion($patient, $year),
            'rekomendasi' => $rekomendasi['rekomendasi'],
            'saran' => $rekomendasi['saran'],
        ]);

        // Radiologi
        \App\Models\Radiologi::create([
            'order_id' => $order->id,
            'ecg' => 'Irama sinus regular, frekuensi ' . rand(60, 100) . ' x/menit',
            'kesimpulan_ecg' => $this->getECGConclusion($patient, $age),
            'thorax_pa' => $this->getThoraxResult($patient, $age),
            'kesimpulan_thorax_pa' => $this->getThoraxConclusion($patient, $age),
        ]);

        // Eye Examination
        \App\Models\PemeriksaanMata::create([
            'order_id' => $order->id,
            'dengan_kacamata' => $age > 45 ? 'OD: 6/9, OS: 6/9' : 'OD: 6/6, OS: 6/6',
            'tanpa_kacamata' => $age > 45 ? 'OD: 6/12, OS: 6/12' : 'OD: 6/6, OS: 6/6',
        ]);

        // Nutritional Status
        $statusOptions = $exercise ?
            ['Normal', 'Normal', 'Normal', 'Underweight'] :
            ['Normal', 'Overweight', 'Overweight', 'Obesitas'];

        \App\Models\StatusGizi::create([
            'order_id' => $order->id,
            'status' => $statusOptions[rand(0, 3)],
        ]);
    }

    private function varyValue($base, $riskFactor, $yearFactor, $improvementFactor)
    {
        $randomVariation = rand(-5, 5);
        $value = $base + $riskFactor + ($yearFactor * $riskFactor * 0.5) + $randomVariation;
        return max(0, round($value * $improvementFactor));
    }

    private function generateRecommendations($patient, $year, $age)
    {
        $rekomendasi = "1. Kontrol kesehatan rutin setiap 6 bulan\n2. Konsumsi makanan bergizi seimbang";
        $saran = "Istirahat cukup 7-8 jam per hari";

        if ($patient->merokok) {
            if ($year >= 2023 && $patient->id % 2 == 0) {
                $rekomendasi .= "\n3. Lanjutkan program berhenti merokok yang sudah dimulai";
                $saran .= ". Pertahankan komitmen bebas rokok";
            } else {
                $rekomendasi .= "\n3. SEGERA BERHENTI MEROKOK - konsultasi program berhenti merokok";
                $saran .= ". Hindari tempat berasap dan polusi udara";
            }
        }

        if (!$patient->olahraga) {
            if ($year >= 2023 && $patient->id % 2 == 0) {
                $rekomendasi .= "\n4. Pertahankan rutinitas olahraga yang baru dimulai";
                $saran .= ". Tingkatkan intensitas olahraga secara bertahap";
            } else {
                $rekomendasi .= "\n4. Mulai program olahraga teratur minimal 150 menit/minggu";
                $saran .= ". Pilih aktivitas fisik yang menyenangkan";
            }
        }

        if ($patient->minum_alkohol) {
            $rekomendasi .= "\n5. Batasi konsumsi alkohol maksimal 1-2 gelas/minggu";
            $saran .= ". Perbanyak konsumsi air putih";
        }

        if ($age > 40) {
            $rekomendasi .= "\n6. Kontrol tekanan darah, gula darah, dan kolesterol rutin";
            $saran .= ". Waspadai gejala penyakit degeneratif";
        }

        return [
            'rekomendasi' => $rekomendasi,
            'saran' => $saran
        ];
    }

    private function getBloodPressureClass($age, $yearFactor)
    {
        if ($age > 45 && $yearFactor > 0.5) {
            return rand(1, 10) <= 3 ? 'Pre-hipertensi' : 'Normal';
        }
        return 'Normal';
    }

    private function getPhysicalConclusion($patient, $year)
    {
        $base = 'Kondisi fisik pasien dalam batas normal.';

        if ($patient->merokok && $year < 2023) {
            $base .= ' Perlu perhatian khusus untuk kesehatan paru.';
        } elseif ($patient->merokok && $year >= 2023 && $patient->id % 2 == 0) {
            $base .= ' Terdapat perbaikan kondisi paru setelah berhenti merokok.';
        }

        return $base;
    }

    private function getECGConclusion($patient, $age)
    {
        if ($age > 45 && ($patient->merokok || !$patient->olahraga)) {
            return rand(1, 10) <= 2 ? 'Sinus takikardia ringan. Perlu evaluasi lebih lanjut.' : 'Normal. Tidak tampak kelainan ST-T.';
        }
        return 'Normal. Tidak tampak kelainan ST-T.';
    }

    private function getThoraxResult($patient, $age)
    {
        if ($patient->merokok && $age > 40) {
            return rand(1, 10) <= 3 ? 'Cor normal, pulmo tampak corakan bronkovaskuler meningkat' : 'Cor dan pulmo dalam batas normal';
        }
        return 'Cor dan pulmo dalam batas normal';
    }

    private function getThoraxConclusion($patient, $age)
    {
        if ($patient->merokok && $age > 40) {
            return rand(1, 10) <= 3 ? 'Bronkitis kronik ringan. Disarankan berhenti merokok.' : 'Tidak tampak infiltrat, massa, atau efusi pleura.';
        }
        return 'Tidak tampak infiltrat, massa, atau efusi pleura.';
    }
}