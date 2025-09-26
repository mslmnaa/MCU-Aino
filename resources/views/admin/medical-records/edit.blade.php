@extends('layouts.admin')

@section('page-title', 'Edit Medical Record')
@section('page-subtitle', $patient->name . ' • ID: ' . $patient->share_id)

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-primary-700">Edit Medical Record</h1>
            <p class="text-neutral-600 mt-1">{{ $order->tgl_order->format('d F Y') }} • Lab No: {{ $order->no_lab }} • {{ $order->cabang }}</p>
        </div>
        <a href="{{ route('patients.show', $patient->id) }}"
           class="bg-neutral-500 hover:bg-neutral-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
            Back to Patient
        </a>
    </div>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif


<form method="POST" action="{{ route('medical-records.update', [$patient->id, $order->id]) }}" id="medical-form">
    @csrf
    @method('PUT')

    @php
        $examinations = [
            'pemeriksaanVital' => [
                'name' => 'Pemeriksaan Vital',
                'color' => 'primary',
                'fields' => [
                    'berat_badan' => ['label' => 'Berat Badan', 'unit' => 'kg', 'normal' => '40-100 kg', 'type' => 'number'],
                    'tinggi_badan' => ['label' => 'Tinggi Badan', 'unit' => 'cm', 'normal' => '150-200 cm', 'type' => 'number'],
                    'lingkar_perut' => ['label' => 'Lingkar Perut', 'unit' => 'cm', 'normal' => '< 90 cm (P), < 80 cm (W)', 'type' => 'number'],
                    'bmi' => ['label' => 'Indeks Massa Tubuh (BMI)', 'unit' => '-', 'normal' => '18.5-24.9', 'type' => 'number', 'step' => '0.1'],
                    'klasifikasi_tekanan_darah' => ['label' => 'Klasifikasi Tekanan Darah', 'unit' => '-', 'normal' => 'Normal', 'type' => 'text'],
                    'klasifikasi_od' => ['label' => 'Klasifikasi OD', 'unit' => '-', 'normal' => 'Normal', 'type' => 'text'],
                    'klasifikasi_os' => ['label' => 'Klasifikasi OS', 'unit' => '-', 'normal' => 'Normal', 'type' => 'text'],
                    'persepsi_warna' => ['label' => 'Persepsi Warna', 'unit' => '-', 'normal' => 'Normal', 'type' => 'text']
                ]
            ],
            'tandaVital' => [
                'name' => 'Tanda Vital',
                'color' => 'secondary',
                'fields' => [
                    'tekanan_darah' => ['label' => 'Tekanan Darah', 'unit' => 'mmHg', 'normal' => '120/80 mmHg', 'type' => 'text'],
                    'nadi' => ['label' => 'Denyut Nadi', 'unit' => 'x/menit', 'normal' => '60-100 x/menit', 'type' => 'number'],
                    'pernapasan' => ['label' => 'Frekuensi Napas', 'unit' => 'x/menit', 'normal' => '12-20 x/menit', 'type' => 'number'],
                    'suhu_tubuh' => ['label' => 'Suhu Tubuh', 'unit' => '°C', 'normal' => '36.0-37.5°C', 'type' => 'number', 'step' => '0.1']
                ]
            ],
            'labHematologi' => [
                'name' => 'Hematologi',
                'color' => 'cream',
                'fields' => [
                    'hemoglobin' => ['label' => 'Hemoglobin', 'unit' => 'g/dL', 'normal' => '12-16 g/dL', 'type' => 'number', 'step' => '0.1'],
                    'erytrosit' => ['label' => 'Sel Darah Merah', 'unit' => 'juta/μL', 'normal' => '4.2-5.4 juta/μL', 'type' => 'number', 'step' => '0.1'],
                    'hematokrit' => ['label' => 'Hematokrit', 'unit' => '%', 'normal' => '37-48%', 'type' => 'number', 'step' => '0.1'],
                    'mcv' => ['label' => 'MCV', 'unit' => 'fL', 'normal' => '82-98 fL', 'type' => 'number', 'step' => '0.1'],
                    'mch' => ['label' => 'MCH', 'unit' => 'pg', 'normal' => '27-31 pg', 'type' => 'number', 'step' => '0.1'],
                    'mchc' => ['label' => 'MCHC', 'unit' => 'g/dL', 'normal' => '32-36 g/dL', 'type' => 'number', 'step' => '0.1'],
                    'rdw' => ['label' => 'RDW', 'unit' => '%', 'normal' => '11.5-14.5%', 'type' => 'number', 'step' => '0.1'],
                    'leukosit' => ['label' => 'Sel Darah Putih', 'unit' => '/μL', 'normal' => '4.000-11.000/μL', 'type' => 'number'],
                    'eosinofil' => ['label' => 'Eosinofil', 'unit' => '%', 'normal' => '1-3%', 'type' => 'number', 'step' => '0.1'],
                    'basofil' => ['label' => 'Basofil', 'unit' => '%', 'normal' => '0-1%', 'type' => 'number', 'step' => '0.1'],
                    'neutrofil_batang' => ['label' => 'Neutrofil Batang', 'unit' => '%', 'normal' => '2-6%', 'type' => 'number', 'step' => '0.1'],
                    'neutrofil_segmen' => ['label' => 'Neutrofil Segmen', 'unit' => '%', 'normal' => '50-70%', 'type' => 'number', 'step' => '0.1'],
                    'limfosit' => ['label' => 'Limfosit', 'unit' => '%', 'normal' => '20-40%', 'type' => 'number', 'step' => '0.1'],
                    'monosit' => ['label' => 'Monosit', 'unit' => '%', 'normal' => '2-8%', 'type' => 'number', 'step' => '0.1'],
                    'trombosit' => ['label' => 'Trombosit', 'unit' => '/μL', 'normal' => '150.000-450.000/μL', 'type' => 'number'],
                    'laju_endap_darah' => ['label' => 'Laju Endap Darah', 'unit' => 'mm/jam', 'normal' => '0-15 mm/jam', 'type' => 'number']
                ]
            ],
            'labUrine' => [
                'name' => 'Analisis Urine',
                'color' => 'neutral',
                'fields' => [
                    'warna' => ['label' => 'Warna', 'unit' => '-', 'normal' => 'Kuning', 'type' => 'text'],
                    'kejernihan' => ['label' => 'Kejernihan', 'unit' => '-', 'normal' => 'Jernih', 'type' => 'text'],
                    'bj' => ['label' => 'Berat Jenis', 'unit' => '-', 'normal' => '1.010-1.025', 'type' => 'number', 'step' => '0.001'],
                    'ph' => ['label' => 'pH', 'unit' => '-', 'normal' => '5.0-8.0', 'type' => 'number', 'step' => '0.1'],
                    'protein' => ['label' => 'Protein', 'unit' => '-', 'normal' => 'Negatif', 'type' => 'text'],
                    'glukosa' => ['label' => 'Glukosa', 'unit' => '-', 'normal' => 'Negatif', 'type' => 'text'],
                    'keton' => ['label' => 'Keton', 'unit' => '-', 'normal' => 'Negatif', 'type' => 'text'],
                    'bilirubin' => ['label' => 'Bilirubin', 'unit' => '-', 'normal' => 'Negatif', 'type' => 'text'],
                    'urobilinogen' => ['label' => 'Urobilinogen', 'unit' => '-', 'normal' => 'Normal', 'type' => 'text'],
                    'nitrit' => ['label' => 'Nitrit', 'unit' => '-', 'normal' => 'Negatif', 'type' => 'text'],
                    'darah' => ['label' => 'Darah', 'unit' => '-', 'normal' => 'Negatif', 'type' => 'text'],
                    'lekosit_esterase' => ['label' => 'Lekosit Esterase', 'unit' => '-', 'normal' => 'Negatif', 'type' => 'text']
                ]
            ],
            'labFungsiLiver' => [
                'name' => 'Fungsi Hati',
                'color' => 'primary',
                'fields' => [
                    'sgot' => ['label' => 'SGOT', 'unit' => 'U/L', 'normal' => '10-40 U/L', 'type' => 'number'],
                    'sgpt' => ['label' => 'SGPT', 'unit' => 'U/L', 'normal' => '7-56 U/L', 'type' => 'number']
                ]
            ],
            'labProfilLemak' => [
                'name' => 'Profil Lemak',
                'color' => 'secondary',
                'fields' => [
                    'cholesterol' => ['label' => 'Kolesterol Total', 'unit' => 'mg/dL', 'normal' => '< 200 mg/dL', 'type' => 'number'],
                    'trigliserida' => ['label' => 'Trigliserida', 'unit' => 'mg/dL', 'normal' => '< 150 mg/dL', 'type' => 'number'],
                    'hdl_cholesterol' => ['label' => 'Kolesterol HDL', 'unit' => 'mg/dL', 'normal' => '> 40 mg/dL (P), > 50 mg/dL (W)', 'type' => 'number'],
                    'ldl_cholesterol' => ['label' => 'Kolesterol LDL', 'unit' => 'mg/dL', 'normal' => '< 100 mg/dL', 'type' => 'number']
                ]
            ],
            'labFungsiGinjal' => [
                'name' => 'Fungsi Ginjal',
                'color' => 'cream',
                'fields' => [
                    'ureum' => ['label' => 'Ureum', 'unit' => 'mg/dL', 'normal' => '10-50 mg/dL', 'type' => 'number'],
                    'creatinin' => ['label' => 'Kreatinin', 'unit' => 'mg/dL', 'normal' => '0.6-1.2 mg/dL', 'type' => 'number', 'step' => '0.01'],
                    'asam_urat' => ['label' => 'Asam Urat', 'unit' => 'mg/dL', 'normal' => '3.5-7.2 mg/dL', 'type' => 'number', 'step' => '0.1']
                ]
            ],
            'labGlukosaDarah' => [
                'name' => 'Gula Darah',
                'color' => 'primary',
                'fields' => [
                    'glukosa_puasa' => ['label' => 'Glukosa Puasa', 'unit' => 'mg/dL', 'normal' => '70-100 mg/dL', 'type' => 'number'],
                    'glukosa_2jam_pp' => ['label' => 'Glukosa 2 Jam PP', 'unit' => 'mg/dL', 'normal' => '< 140 mg/dL', 'type' => 'number'],
                    'hba1c' => ['label' => 'HbA1c', 'unit' => '%', 'normal' => '< 5.7%', 'type' => 'number', 'step' => '0.1']
                ]
            ]
        ];

        function cleanValue($value, $unit = '') {
            if ($value === null || $value === 'T/A') return '';

            $units_to_remove = [' kg', ' cm', ' mmHg', ' x/menit', ' °C', ' g/dL', ' juta/μL',
                              '%', ' fL', ' pg', '/μL', ' mm/jam', ' mg/dL', ' U/L', ' ng/mL', ' million/μL'];

            $clean = $value;
            foreach($units_to_remove as $u) {
                $clean = str_replace($u, '', $clean);
            }

            return trim($clean);
        }
    @endphp

    <!-- Summary Section (Similar to User View) -->
    <div class="bg-white rounded-lg shadow-md mb-8" id="summary-section">
        <div class="bg-primary-600 px-6 py-4">
            <div>
                <h3 class="text-lg font-semibold text-white">Rangkuman MCU - {{ $order->tgl_order->format('Y') }}</h3>
                <p class="text-sm text-primary-100 mt-1">{{ $order->tgl_order->format('d F Y') }} • Lab No: {{ $order->no_lab }} • {{ $order->cabang ?? 'Lab Utama' }}</p>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Hasil Laboratorium -->
                <div class="bg-neutral-50 rounded-lg p-5 border border-neutral-200">
                    <div class="flex items-center mb-4">
                        <div class="w-3 h-3 bg-neutral-400 rounded-full mr-2"></div>
                        <h5 class="font-semibold text-neutral-700">Hasil Laboratorium</h5>
                    </div>

                    <div class="space-y-3 text-sm">
                        @php
                            $labResults = [];

                            // Hematologi
                            if($order->lab_hematologi && $order->lab_hematologi->kesimpulan_hematologi) {
                                $status = str_contains(strtolower($order->lab_hematologi->kesimpulan_hematologi), 'normal') ? 'Normal' : 'Abnormal';
                                $labResults[] = ['param' => 'Hematologi Lengkap', 'value' => $order->lab_hematologi->kesimpulan_hematologi, 'status' => $status];
                            }

                            // Urine
                            if($order->lab_urine && $order->lab_urine->kesimpulan_urine) {
                                $status = str_contains(strtolower($order->lab_urine->kesimpulan_urine), 'normal') ? 'Normal' : 'Abnormal';
                                $labResults[] = ['param' => 'Urine Lengkap', 'value' => $order->lab_urine->kesimpulan_urine, 'status' => $status];
                            }

                            // Fungsi Hati
                            if($order->lab_fungsi_liver && $order->lab_fungsi_liver->kesimpulan_fungsi_hati) {
                                $status = str_contains(strtolower($order->lab_fungsi_liver->kesimpulan_fungsi_hati), 'normal') ? 'Normal' : 'Abnormal';
                                $labResults[] = ['param' => 'Fungsi Hati', 'value' => $order->lab_fungsi_liver->kesimpulan_fungsi_hati, 'status' => $status];
                            }

                            // Profil Lemak
                            if($order->lab_profil_lemak && $order->lab_profil_lemak->kesimpulan_profil_lemak) {
                                $status = str_contains(strtolower($order->lab_profil_lemak->kesimpulan_profil_lemak), 'normal') ? 'Normal' : 'Abnormal';
                                $labResults[] = ['param' => 'Profil Lemak', 'value' => $order->lab_profil_lemak->kesimpulan_profil_lemak, 'status' => $status];
                            }

                            // Fungsi Ginjal
                            if($order->lab_fungsi_ginjal && $order->lab_fungsi_ginjal->kesimpulan_fungsi_ginjal) {
                                $status = str_contains(strtolower($order->lab_fungsi_ginjal->kesimpulan_fungsi_ginjal), 'normal') ? 'Normal' : 'Abnormal';
                                $labResults[] = ['param' => 'Fungsi Ginjal', 'value' => $order->lab_fungsi_ginjal->kesimpulan_fungsi_ginjal, 'status' => $status];
                            }

                            // Glukosa Darah
                            if($order->lab_glukosa_darah && $order->lab_glukosa_darah->kesimpulan_glukosa) {
                                $status = str_contains(strtolower($order->lab_glukosa_darah->kesimpulan_glukosa), 'normal') ? 'Normal' : 'Abnormal';
                                $labResults[] = ['param' => 'Glukosa Darah & HbA1c', 'value' => $order->lab_glukosa_darah->kesimpulan_glukosa, 'status' => $status];
                            }

                            // Penanda Tumor
                            if($order->lab_penanda_tumor && $order->lab_penanda_tumor->kesimpulan_penanda_tumor) {
                                $status = str_contains(strtolower($order->lab_penanda_tumor->kesimpulan_penanda_tumor), 'normal') ? 'Normal' : 'Abnormal';
                                $labResults[] = ['param' => 'Penanda Tumor', 'value' => $order->lab_penanda_tumor->kesimpulan_penanda_tumor, 'status' => $status];
                            }
                        @endphp

                        @if(count($labResults) > 0)
                            @foreach($labResults as $result)
                            <div class="flex justify-between items-center">
                                <span class="text-neutral-600">{{ $result['param'] }}</span>
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-neutral-800">{{ $result['value'] }}</span>
                                    <span class="px-2 py-1 text-xs rounded-full border {{ $result['status'] === 'Normal' ? 'bg-neutral-100 text-neutral-600 border-neutral-300' : 'bg-neutral-200 text-neutral-700 border-neutral-400' }}">
                                        {{ $result['status'] }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <p class="text-neutral-500">Data laboratorium belum tersedia</p>
                        @endif
                    </div>
                </div>

                <!-- Hasil Non-Laboratorium -->
                <div class="bg-neutral-50 rounded-lg p-5 border border-neutral-200">
                    <div class="flex items-center mb-4">
                        <div class="w-3 h-3 bg-neutral-400 rounded-full mr-2"></div>
                        <h5 class="font-semibold text-neutral-700">Hasil Non-Laboratorium</h5>
                    </div>

                    <div class="space-y-3 text-sm">
                        @php
                            $nonLabResults = [];

                            // EKG
                            if($order->radiologi && $order->radiologi->kesimpulan_ecg && $order->radiologi->kesimpulan_ecg !== 'T/A') {
                                $status = str_contains(strtolower($order->radiologi->kesimpulan_ecg), 'normal') ? 'Normal' : 'Abnormal';
                                $nonLabResults[] = ['param' => 'Kesimpulan EKG', 'value' => $order->radiologi->kesimpulan_ecg, 'status' => $status];
                            }
                        @endphp

                        @if(count($nonLabResults) > 0)
                            @foreach($nonLabResults as $result)
                            <div class="flex justify-between items-center">
                                <span class="text-neutral-600">{{ $result['param'] }}</span>
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-neutral-800">{{ $result['value'] }}</span>
                                    <span class="px-2 py-1 text-xs rounded-full border {{ $result['status'] === 'Normal' ? 'bg-neutral-100 text-neutral-600 border-neutral-300' : 'bg-neutral-200 text-neutral-700 border-neutral-400' }}">
                                        {{ $result['status'] }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <p class="text-neutral-500">Data pemeriksaan belum tersedia</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pemeriksaan Dokter -->
            <div class="bg-neutral-50 rounded-lg p-5 border border-neutral-200 mb-6">
                <div class="flex items-center mb-4">
                    <div class="w-3 h-3 bg-neutral-400 rounded-full mr-2"></div>
                    <h5 class="font-semibold text-neutral-700">Pemeriksaan Dokter</h5>
                </div>
                <div class="space-y-3 text-sm">
                    @if($order->pemeriksaan_vital && $order->pemeriksaan_vital->pemeriksaan_fisik_umum)
                        <div class="flex flex-col space-y-2">
                            <span class="text-neutral-600 font-medium">Pemeriksaan Fisik Umum:</span>
                            <div class="bg-white rounded-lg p-3 border border-neutral-200">
                                <p class="text-neutral-800 whitespace-pre-line">{{ $order->pemeriksaan_vital->pemeriksaan_fisik_umum }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-neutral-500">Data pemeriksaan fisik umum belum tersedia</p>
                    @endif
                </div>
            </div>

            @php
                $hasDetailedInfo = $order->pemeriksaan_vital &&
                    ($order->pemeriksaan_vital->kesimpulan_fisik ||
                     $order->pemeriksaan_vital->rekomendasi ||
                     $order->pemeriksaan_vital->saran);
            @endphp

            @if($hasDetailedInfo)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @if($order->pemeriksaan_vital->kesimpulan_fisik)
                <div class="bg-neutral-50 rounded-lg p-4 border border-neutral-200">
                    <h4 class="font-medium text-neutral-700 mb-2">Kesimpulan Pemeriksaan</h4>
                    <p class="text-sm text-neutral-600">{{ $order->pemeriksaan_vital->kesimpulan_fisik }}</p>
                </div>
                @endif

                @if($order->pemeriksaan_vital->rekomendasi)
                <div class="bg-neutral-50 rounded-lg p-4 border border-neutral-200">
                    <h4 class="font-medium text-neutral-700 mb-2">Rekomendasi</h4>
                    <p class="text-sm text-neutral-600 whitespace-pre-line">{{ $order->pemeriksaan_vital->rekomendasi }}</p>
                </div>
                @endif

                @if($order->pemeriksaan_vital->saran)
                <div class="bg-neutral-50 rounded-lg p-4 border border-neutral-200">
                    <h4 class="font-medium text-neutral-700 mb-2">Saran</h4>
                    <p class="text-sm text-neutral-600 whitespace-pre-line">{{ $order->pemeriksaan_vital->saran }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Main Comparison Table Format for Editing -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="bg-primary-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Edit Medical Examination Results</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-neutral-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200 bg-neutral-100" style="min-width: 180px;">Jenis Pemeriksaan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200" style="min-width: 150px;">Parameter</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200" style="min-width: 150px;">Nilai</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200" style="min-width: 80px;">Satuan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider" style="min-width: 150px;">Nilai Normal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @foreach($examinations as $examType => $examInfo)
                        @php
                            $hasData = false;
                            if($order->$examType) {
                                $hasData = true;
                            }
                        @endphp

                        @if($hasData)
                            @foreach($examInfo['fields'] as $fieldName => $fieldInfo)
                            <tr class="hover:bg-{{ $examInfo['color'] }}-50 transition-colors">
                                @if($loop->first)
                                <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-{{ $examInfo['color'] }}-50 border-r border-neutral-200"
                                    rowspan="{{ count($examInfo['fields']) }}">
                                    {{ $examInfo['name'] }}
                                </td>
                                @endif

                                <td class="px-4 py-3 text-sm text-neutral-700 border-r border-neutral-200">
                                    {{ $fieldInfo['label'] }}
                                </td>

                                @php
                                    $value = '';
                                    if($order->$examType && isset($order->$examType->$fieldName)) {
                                        $value = cleanValue($order->$examType->$fieldName);
                                    }

                                    $inputName = '';
                                    switch($examType) {
                                        case 'pemeriksaanVital':
                                            $inputName = 'pemeriksaan_vital[' . $fieldName . ']';
                                            break;
                                        case 'tandaVital':
                                            $inputName = 'tanda_vital[' . $fieldName . ']';
                                            break;
                                        case 'labHematologi':
                                            $inputName = 'hematologi[' . $fieldName . ']';
                                            break;
                                        case 'labUrine':
                                            $inputName = 'urine[' . $fieldName . ']';
                                            break;
                                        case 'labFungsiLiver':
                                            $inputName = 'fungsi_liver[' . $fieldName . ']';
                                            break;
                                        case 'labProfilLemak':
                                            $inputName = 'profil_lemak[' . $fieldName . ']';
                                            break;
                                        case 'labFungsiGinjal':
                                            $inputName = 'fungsi_ginjal[' . $fieldName . ']';
                                            break;
                                        case 'labGlukosaDarah':
                                            $inputName = 'glukosa_darah[' . $fieldName . ']';
                                            break;
                                    }
                                @endphp

                                <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                                    @if($fieldInfo['type'] == 'text')
                                        <input type="text"
                                               name="{{ $inputName }}"
                                               value="{{ $value }}"
                                               class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-{{ $examInfo['color'] }}-500 focus:border-{{ $examInfo['color'] }}-500 text-center"
                                               placeholder="-">
                                    @else
                                        <input type="number"
                                               name="{{ $inputName }}"
                                               value="{{ $value }}"
                                               @if(isset($fieldInfo['step'])) step="{{ $fieldInfo['step'] }}" @endif
                                               class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-{{ $examInfo['color'] }}-500 focus:border-{{ $examInfo['color'] }}-500 text-center"
                                               placeholder="-">
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-sm text-neutral-600 text-center border-r border-neutral-200">
                                    {{ $fieldInfo['unit'] }}
                                </td>

                                <td class="px-4 py-3 text-sm text-neutral-600">
                                    {{ $fieldInfo['normal'] }}
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Additional Fields Section -->
    @if($order->pemeriksaanVital && ($order->pemeriksaanVital->kesimpulan_fisik || $order->pemeriksaanVital->rekomendasi || $order->pemeriksaanVital->saran))
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="bg-primary-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Kesimpulan dan Rekomendasi</h3>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Kesimpulan Pemeriksaan Fisik</label>
                    <textarea name="pemeriksaan_vital[kesimpulan_fisik]"
                              rows="3"
                              class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                              placeholder="Masukkan kesimpulan pemeriksaan fisik...">{{ $order->pemeriksaanVital->kesimpulan_fisik ?? '' }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Rekomendasi</label>
                    <textarea name="pemeriksaan_vital[rekomendasi]"
                              rows="4"
                              class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                              placeholder="Masukkan rekomendasi kesehatan...">{{ $order->pemeriksaanVital->rekomendasi ?? '' }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Saran</label>
                    <textarea name="pemeriksaan_vital[saran]"
                              rows="3"
                              class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                              placeholder="Masukkan saran untuk pasien...">{{ $order->pemeriksaanVital->saran ?? '' }}</textarea>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Submit Buttons -->
    <div class="flex justify-end space-x-4">
        <a href="{{ route('patients.show', $patient->id) }}"
           class="px-6 py-2 text-neutral-600 bg-neutral-100 hover:bg-neutral-200 rounded-lg font-medium transition-colors">
            Cancel
        </a>
        <button type="submit"
                class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors">
            Update Medical Record
        </button>
    </div>
</form>

<style>
/* Ensure horizontal scroll on mobile */
.overflow-x-auto {
    -webkit-overflow-scrolling: touch;
}

/* Table sticky header */
thead th {
    position: sticky;
    top: 0;
    z-index: 10;
}

/* Zebra striping for better readability */
tbody tr:nth-child(even) {
    background-color: rgb(249 250 251);
}

/* Input field styling in table */
tbody input {
    min-width: 100px;
}

/* Focus states for better UX */
input:focus, textarea:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>

<script>
// Auto-calculate BMI when weight or height changes
document.addEventListener('DOMContentLoaded', function() {
    const weightInput = document.querySelector('input[name="pemeriksaan_vital[berat_badan]"]');
    const heightInput = document.querySelector('input[name="pemeriksaan_vital[tinggi_badan]"]');
    const bmiInput = document.querySelector('input[name="pemeriksaan_vital[bmi]"]');

    function calculateBMI() {
        if (weightInput && heightInput && bmiInput) {
            const weight = parseFloat(weightInput.value);
            const height = parseFloat(heightInput.value);

            if (weight > 0 && height > 0) {
                const heightInMeters = height / 100;
                const bmi = weight / (heightInMeters * heightInMeters);
                bmiInput.value = bmi.toFixed(1);
            }
        }
    }

    if (weightInput) weightInput.addEventListener('input', calculateBMI);
    if (heightInput) heightInput.addEventListener('input', calculateBMI);
});

// Form validation
document.getElementById('medical-form').addEventListener('submit', function(e) {
    const inputs = this.querySelectorAll('input[type="number"]');
    let hasError = false;

    inputs.forEach(input => {
        if (input.value && isNaN(parseFloat(input.value))) {
            input.classList.add('border-red-500');
            hasError = true;
        } else {
            input.classList.remove('border-red-500');
        }
    });

    if (hasError) {
        e.preventDefault();
        alert('Please check numeric values for errors.');
    }
});
</script>
@endsection