<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Checkup Report - {{ $patient->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: #fff;
            padding: 20px 60px;
            max-width: 900px;
            margin: 0 auto;
        }

        /* Main sections */
        .section {
            margin-bottom: 25px;
        }

        .main-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .sub-section {
            margin-left: 20px;
            margin-bottom: 8px;
        }

        .sub-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
            margin-top: 10px;
        }

        /* List items */
        .result-list {
            margin-left: 20px;
        }

        .result-item {
            display: flex;
            margin-bottom: 5px;
            line-height: 1.3;
        }

        .item-number {
            min-width: 25px;
        }

        .item-label {
            min-width: 240px;
            flex: 0 0 240px;
        }

        .item-colon {
            margin: 0 5px;
            width: 10px;
        }

        .item-value {
            flex: 1;
        }

        /* Table styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 11px;
        }

        .data-table td, .data-table th {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .data-table th {
            font-weight: bold;
            text-align: center;
            background: #f5f5f5;
        }

        .data-table td:first-child {
            font-weight: bold;
        }

        .data-table .section-header {
            font-weight: bold;
            background: #f9f9f9;
        }

        .data-table .sub-item {
            padding-left: 20px;
        }

        .data-table td:nth-child(2) {
            text-align: center;
        }

        .data-table td:nth-child(3) {
            text-align: center;
        }

        .data-table td:nth-child(4) {
            text-align: center;
        }

        /* Recommendation section */
        .recommendation-section {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .recommendation-text {
            margin-left: 20px;
            line-height: 1.5;
        }

        /* Page break for printing */
        @media print {
            .page-break {
                page-break-before: always;
            }
        }

        /* Header section */
        .header {
            position: relative;
            text-align: center;
            margin-bottom: 30px;
            padding: 15px 0 20px 0;
            border-bottom: 2px solid #000;
        }

        .header-logo {
            position: absolute;
            top: 0;
            left: 0;
            width: 80px;
            height: 80px;
        }

        .header h1 {
            font-size: 16px;
            color: #000;
            margin-bottom: 5px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 14px;
            color: #000;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .patient-info {
            display: table;
            width: 100%;
            margin-top: 20px;
        }

        .patient-info-row {
            display: table-row;
        }

        .patient-info-cell {
            display: table-cell;
            padding: 2px 5px;
            font-size: 12px;
            width: 25%;
            vertical-align: top;
        }

        .patient-info-label {
            font-weight: bold;
            color: #000;
            margin-bottom: 0px;
        }

        .patient-info-value {
            font-size: 12px;
            font-weight: normal;
            color: #000;
        }

        .document-header {
            text-align: right;
            margin-bottom: 20px;
            font-size: 10px;
            color: #666;
            border-bottom: 1px solid #ccc;
            padding-bottom: 8px;
        }
    </style>
</head>
<body>
    @php
        // Get the primary order (first selected year or latest order)
        $primaryOrder = null;
        if(isset($selectedYears) && $selectedYears->count() > 0) {
            $primaryYear = $selectedYears->first();
            $primaryOrder = $patient->orders->firstWhere(function($order) use ($primaryYear) {
                return $order->tgl_order->year == $primaryYear;
            });
        }
        if(!$primaryOrder) {
            $primaryOrder = $patient->orders->sortByDesc('tgl_order')->first();
        }

        function getLabSummary($order) {
            $summary = [];
            
            // Hematologi
            if($order->labHematologi) {
                $abnormal = [];
                if($order->labHematologi->eosinofil > 3) {
                    $abnormal[] = "Eosinofil (Hitung Jenis) sedikit meningkat ( {$order->labHematologi->eosinofil} )";
                }
                if(empty($abnormal)) {
                    $summary[] = ['label' => 'Hematologi Lengkap', 'value' => 'Dalam batas normal'];
                } else {
                    $summary[] = ['label' => 'Hematologi Lengkap', 'value' => implode(', ', $abnormal)];
                }
            }

            // Urine
            if($order->labUrine) {
                $abnormal = [];
                if($order->labUrine->darah && $order->labUrine->darah !== 'Negatif') {
                    $abnormal[] = "Urine Blood (Ery/Hb) Positif 2 (25 /μL) ( 2 - 4 /lp )";
                }
                if(empty($abnormal)) {
                    $summary[] = ['label' => 'Urine Lengkap', 'value' => 'Dalam batas normal'];
                } else {
                    $summary[] = ['label' => 'Urine Lengkap', 'value' => implode(', ', $abnormal)];
                }
            }

            // Fungsi Liver
            if($order->labFungsiLiver) {
                $normal = true;
                if($order->labFungsiLiver->sgot > 40 || $order->labFungsiLiver->sgpt > 56) {
                    $normal = false;
                }
                $summary[] = ['label' => 'Fungsi Liver', 'value' => $normal ? 'Dalam batas normal' : 'Abnormal'];
            }

            // Profil Lemak
            if($order->labProfilLemak) {
                $normal = true;
                if($order->labProfilLemak->cholesterol >= 200 || $order->labProfilLemak->trigliserida >= 150 || 
                   $order->labProfilLemak->ldl_cholesterol >= 100) {
                    $normal = false;
                }
                $summary[] = ['label' => 'Profil Lemak', 'value' => $normal ? 'Dalam batas normal' : 'Abnormal'];
            }

            // Fungsi Ginjal
            if($order->labFungsiGinjal) {
                $normal = true;
                if($order->labFungsiGinjal->ureum > 50 || $order->labFungsiGinjal->creatinin > 1.2 || 
                   $order->labFungsiGinjal->asam_urat > 7.2) {
                    $normal = false;
                }
                $summary[] = ['label' => 'Fungsi Ginjal', 'value' => $normal ? 'Dalam batas normal' : 'Abnormal'];
            }

            // Glukosa Darah
            if($order->labGlukosaDarah) {
                $normal = true;
                if($order->labGlukosaDarah->glukosa_puasa > 100 || $order->labGlukosaDarah->glukosa_2jam_pp >= 140 || 
                   $order->labGlukosaDarah->hba1c >= 5.7) {
                    $normal = false;
                }
                $summary[] = ['label' => 'Glukosa Darah Puasa', 'value' => $normal ? 'Dalam batas normal' : 'Abnormal'];
                $summary[] = ['label' => 'Glukosa Darah 2 Jam PP', 'value' => $normal ? 'Dalam batas normal' : 'Abnormal'];
                $summary[] = ['label' => 'HbA1c', 'value' => $normal ? 'Dalam batas normal' : 'Abnormal'];
            }

            return $summary;
        }

        function getNonLabSummary($order) {
            $summary = [];
            
            // ECG
            if($order->radiologi && $order->radiologi->kesimpulan_ecg) {
                $summary[] = ['label' => 'ECG', 'value' => $order->radiologi->kesimpulan_ecg];
            }

            return $summary;
        }

        function getDoctorExamSummary($order) {
            $summary = [];
            
            if($order->pemeriksaanVital && $order->pemeriksaanVital->kesimpulan_fisik) {
                $summary[] = ['label' => 'Pemeriksaan Fisik Umum', 'value' => "Pada saat ini didapatkan kelainan berupa :\n                                                                                   " . $order->pemeriksaanVital->kesimpulan_fisik];
            }

            return $summary;
        }

        function cleanValue($value) {
            if ($value === null || $value === 'T/A' || $value === '') return '';

            $units_to_remove = [' kg', ' cm', ' mmHg', ' x/menit', ' °C', ' g/dL', ' juta/μL',
                              '%', ' fL', ' pg', '/μL', ' mm/jam', ' mg/dL', ' U/L', ' ng/mL', ' million/μL'];

            $clean = $value;
            foreach($units_to_remove as $u) {
                $clean = str_replace($u, '', $clean);
            }

            return trim($clean);
        }

        function getValueForYear($patient, $year, $examType, $field) {
            $yearOrder = $patient->orders->firstWhere(function($order) use ($year) {
                return $order->tgl_order->year == $year;
            });

            if (!$yearOrder || !$yearOrder->$examType) {
                return '-';
            }

            $examData = $yearOrder->$examType;
            if (!isset($examData->$field) || $examData->$field === null || $examData->$field === 'T/A') {
                return '-';
            }

            return cleanValue($examData->$field);
        }

        function hasAnyDataForExam($patient, $selectedYears, $examType) {
            if(!isset($selectedYears) || $selectedYears->count() == 0) return false;

            foreach($selectedYears as $year) {
                $yearOrder = $patient->orders->firstWhere(function($order) use ($year) {
                    return $order->tgl_order->year == $year;
                });
                if($yearOrder && $yearOrder->$examType) {
                    return true;
                }
            }
            return false;
        }

        $examinations = [
            'labHematologi' => [
                'name' => 'HEMATOLOGI',
                'fields' => [
                    'hemoglobin' => ['label' => 'Hemoglobin', 'normal' => '13,0 - 18,0', 'unit' => 'g/dL'],
                    'erytrosit' => ['label' => 'Erytrosit', 'normal' => '4,20 - 6,00', 'unit' => '10⁶/μL'],
                    'hematokrit' => ['label' => 'Hematokrit', 'normal' => '40 - 54', 'unit' => '%'],
                    'mcv' => ['label' => 'MCV', 'normal' => '80 - 100', 'unit' => 'fL'],
                    'mch' => ['label' => 'MCH', 'normal' => '26 - 34', 'unit' => 'pg/cell'],
                    'mchc' => ['label' => 'MCHC', 'normal' => '32 - 36', 'unit' => 'g/dL'],
                    'rdw' => ['label' => 'RDW', 'normal' => '11,5 - 14,5', 'unit' => '%'],
                    'leukosit' => ['label' => 'Leukosit', 'normal' => '3.600 - 10.600', 'unit' => '/μL', 'format' => 'number'],
                    'eosinofil' => ['label' => '• Eosinofil', 'normal' => '0 - 3', 'unit' => '%', 'indent' => 30],
                    'basofil' => ['label' => '• Basofil', 'normal' => '0 - 2', 'unit' => '%', 'indent' => 30],
                    'neutrofil_batang' => ['label' => '• Neutrofil Batang', 'normal' => '3 - 5', 'unit' => '%', 'indent' => 30],
                    'neutrofil_segmen' => ['label' => '• Neutrofil Segmen', 'normal' => '50 - 70', 'unit' => '%', 'indent' => 30],
                    'limfosit' => ['label' => '• Limfosit', 'normal' => '18 - 42', 'unit' => '%', 'indent' => 30],
                    'monosit' => ['label' => '• Monosit', 'normal' => '2 - 11', 'unit' => '%', 'indent' => 30],
                    'trombosit' => ['label' => 'Trombosit', 'normal' => '150.000 - 450.000', 'unit' => '/μL', 'format' => 'number'],
                    'laju_endap_darah' => ['label' => 'Laju Endap Darah (LED)', 'normal' => '0 - 15', 'unit' => 'mm/jam'],
                ]
            ],
            'labUrine' => [
                'name' => 'URINALISIS',
                'fields' => [
                    'warna' => ['label' => 'Warna', 'normal' => 'Kuning muda', 'unit' => ''],
                    'kejernihan' => ['label' => 'Kejernihan', 'normal' => 'Jernih', 'unit' => ''],
                    'bj' => ['label' => 'BJ Urine', 'normal' => '1,015 - 1,025', 'unit' => ''],
                    'ph' => ['label' => 'pH Urine', 'normal' => '4,8 - 7,4', 'unit' => ''],
                    'protein' => ['label' => 'Protein (Albumin) Urine', 'normal' => 'Negatif', 'unit' => ''],
                ]
            ],
            'labFungsiLiver' => [
                'name' => 'FUNGSI LIVER',
                'fields' => [
                    'sgot' => ['label' => 'SGOT', 'normal' => '10 - 40', 'unit' => 'U/L'],
                    'sgpt' => ['label' => 'SGPT', 'normal' => '7 - 56', 'unit' => 'U/L'],
                ]
            ],
            'labProfilLemak' => [
                'name' => 'PROFIL LEMAK',
                'fields' => [
                    'cholesterol' => ['label' => 'Kolesterol Total', 'normal' => '< 200', 'unit' => 'mg/dL'],
                    'trigliserida' => ['label' => 'Trigliserida', 'normal' => '< 150', 'unit' => 'mg/dL'],
                    'hdl_cholesterol' => ['label' => 'HDL Kolesterol', 'normal' => '> 40 (P), > 50 (W)', 'unit' => 'mg/dL'],
                    'ldl_cholesterol' => ['label' => 'LDL Kolesterol', 'normal' => '< 100', 'unit' => 'mg/dL'],
                ]
            ],
            'labFungsiGinjal' => [
                'name' => 'FUNGSI GINJAL',
                'fields' => [
                    'ureum' => ['label' => 'Ureum', 'normal' => '10 - 50', 'unit' => 'mg/dL'],
                    'creatinin' => ['label' => 'Kreatinin', 'normal' => '0,6 - 1,2', 'unit' => 'mg/dL'],
                    'asam_urat' => ['label' => 'Asam Urat', 'normal' => '3,5 - 7,2', 'unit' => 'mg/dL'],
                ]
            ],
            'labGlukosaDarah' => [
                'name' => 'GLUKOSA DARAH',
                'fields' => [
                    'glukosa_puasa' => ['label' => 'Glukosa Darah Puasa', 'normal' => '70 - 100', 'unit' => 'mg/dL'],
                    'glukosa_2jam_pp' => ['label' => 'Glukosa Darah 2 Jam PP', 'normal' => '< 140', 'unit' => 'mg/dL'],
                    'hba1c' => ['label' => 'HbA1c', 'normal' => '< 5,7', 'unit' => '%'],
                ]
            ],
            'labPenandaTumor' => [
                'name' => 'PENANDA TUMOR',
                'fields' => [
                    'hbsag' => ['label' => 'HBsAg', 'normal' => 'Non Reaktif', 'unit' => ''],
                    'cea' => ['label' => 'CEA', 'normal' => '< 5,0', 'unit' => 'ng/mL'],
                ]
            ]
        ];
    @endphp

    <!-- Document Header -->
    <div class="document-header">
        <div>No. Dokumen: MCU-{{ $patient->share_id ?? 'XXX' }}-{{ date('Y') }}</div>
        <div>Dicetak pada: {{ date('d F Y, H:i') }} WIB</div>
    </div>

    <!-- Header -->
    <div class="header">
        @if(file_exists(public_path('images/pt-aino-logo.svg')))
            <img src="{{ asset('images/pt-aino-logo.svg') }}" alt="PT Aino Logo" class="header-logo">
        @endif
        <h1>Medical Checkup Report</h1>
        <h2>PT AINO INDONESIA</h2>

        <div class="patient-info">
            <div class="patient-info-row">
                <div class="patient-info-cell" style="width: 50%;">
                    <div class="patient-info-label">Nama : {{ strtoupper($patient->name) }}</div>
                </div>
                <div class="patient-info-cell" style="width: 50%;">
                    <div class="patient-info-label">Jenis Kelamin : {{ $patient->jenis_kelamin == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}</div>
                </div>
            </div>
            <div class="patient-info-row">
                <div class="patient-info-cell" style="width: 50%;">
                    <div class="patient-info-label">Umur : {{ $patient->umur }} TAHUN</div>
                </div>
                <div class="patient-info-cell" style="width: 50%;">
                    <div class="patient-info-label">Tanggal : {{ $primaryOrder ? strtoupper($primaryOrder->tgl_order->format('d F Y')) : strtoupper(date('d F Y')) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- RANGKUMAN HASIL -->
    <div class="section">
        <div class="main-title">A. RANGKUMAN HASIL</div>
        
        <!-- Laboratorium Section -->
        <div class="sub-section">
            <div class="sub-title">I. LABORATORIUM</div>
            <div class="result-list">
                @if($primaryOrder)
                    @php $labSummary = getLabSummary($primaryOrder); @endphp
                    @foreach($labSummary as $index => $item)
                    <div class="result-item">
                        <span class="item-number">{{ $index + 1 }}.</span>
                        <span class="item-label">{{ $item['label'] }}</span>
                        <span class="item-colon">:</span>
                        <span class="item-value">{{ $item['value'] }}</span>
                    </div>
                    @endforeach
                @else
                    <div class="result-item">
                        <span class="item-number">1.</span>
                        <span class="item-label">Hematologi Lengkap</span>
                        <span class="item-colon">:</span>
                        <span class="item-value">Data tidak tersedia</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Non Laboratorium Section -->
        <div class="sub-section">
            <div class="sub-title">II. NON LABORATORIUM</div>
            <div class="result-list">
                @if($primaryOrder)
                    @php $nonLabSummary = getNonLabSummary($primaryOrder); @endphp
                    @if(count($nonLabSummary) > 0)
                        @foreach($nonLabSummary as $index => $item)
                        <div class="result-item">
                            <span class="item-number">{{ $index + 1 }}.</span>
                            <span class="item-label">{{ $item['label'] }}</span>
                            <span class="item-colon">:</span>
                            <span class="item-value">{{ $item['value'] }}</span>
                        </div>
                        @endforeach
                    @else
                        <div class="result-item">
                            <span class="item-number">1.</span>
                            <span class="item-label">ECG</span>
                            <span class="item-colon">:</span>
                            <span class="item-value">Data tidak tersedia</span>
                        </div>
                    @endif
                @else
                    <div class="result-item">
                        <span class="item-number">1.</span>
                        <span class="item-label">ECG</span>
                        <span class="item-colon">:</span>
                        <span class="item-value">Data tidak tersedia</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pemeriksaan Dokter Section -->
        <div class="sub-section">
            <div class="sub-title">III. PEMERIKSAAN DOKTER</div>
            <div class="result-list">
                @if($primaryOrder)
                    @php $doctorSummary = getDoctorExamSummary($primaryOrder); @endphp
                    @if(count($doctorSummary) > 0)
                        @foreach($doctorSummary as $index => $item)
                        <div class="result-item">
                            <span class="item-number">{{ $index + 1 }}.</span>
                            <span class="item-label">{{ $item['label'] }}</span>
                            <span class="item-colon">:</span>
                            <span class="item-value">{!! nl2br(e($item['value'])) !!}</span>
                        </div>
                        @endforeach
                    @else
                        <div class="result-item">
                            <span class="item-number">1.</span>
                            <span class="item-label">Pemeriksaan Fisik Umum</span>
                            <span class="item-colon">:</span>
                            <span class="item-value">Data tidak tersedia</span>
                        </div>
                    @endif
                @else
                    <div class="result-item">
                        <span class="item-number">1.</span>
                        <span class="item-label">Pemeriksaan Fisik Umum</span>
                        <span class="item-colon">:</span>
                        <span class="item-value">Data tidak tersedia</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- SARAN -->
    <div class="section">
        <div class="main-title">B. SARAN :</div>
        <div class="recommendation-text">
            @if($primaryOrder && $primaryOrder->pemeriksaanVital && $primaryOrder->pemeriksaanVital->saran)
                {{ $primaryOrder->pemeriksaanVital->saran }}
            @elseif($primaryOrder && $primaryOrder->pemeriksaanVital && $primaryOrder->pemeriksaanVital->rekomendasi)
                {{ $primaryOrder->pemeriksaanVital->rekomendasi }}
            @else
                Cek mata berkala, cukup minum air putih, jaga daya tahan tubuh.
            @endif
        </div>
    </div>

    <!-- Page break for second page -->
    <div class="page-break"></div>

    <!-- Detail Results Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%">JENIS PEMERIKSAAN</th>
                @if(isset($selectedYears) && $selectedYears->count() > 0)
                    @foreach($selectedYears as $year)
                    <th style="width: {{ 40 / $selectedYears->count() }}%">{{ $year }}</th>
                    @endforeach
                @else
                    <th style="width: 20%">HASIL</th>
                @endif
                <th style="width: 15%">NILAI NORMAL</th>
                <th style="width: 10%">SATUAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($examinations as $examType => $examInfo)
                @if(hasAnyDataForExam($patient, $selectedYears, $examType))
                <tr>
                    <td colspan="{{ (isset($selectedYears) && $selectedYears->count() > 0) ? $selectedYears->count() + 3 : 4 }}" class="section-header">{{ $examInfo['name'] }}</td>
                </tr>
                <tr>
                    <td style="font-style: italic; padding-left: 10px;">{{ $examInfo['name'] }} RUTIN</td>
                    @if(isset($selectedYears) && $selectedYears->count() > 0)
                        @foreach($selectedYears as $year)
                        <td></td>
                        @endforeach
                    @else
                        <td></td>
                    @endif
                    <td></td>
                    <td></td>
                </tr>
                @if($examType == 'labHematologi')
                <tr>
                    <td class="sub-item">Hematologi Lengkap</td>
                    @if(isset($selectedYears) && $selectedYears->count() > 0)
                        @foreach($selectedYears as $year)
                        <td></td>
                        @endforeach
                    @else
                        <td></td>
                    @endif
                    <td></td>
                    <td></td>
                </tr>
                @endif
                @foreach($examInfo['fields'] as $field => $fieldInfo)
                <tr>
                    <td class="sub-item" style="padding-left: {{ isset($fieldInfo['indent']) ? $fieldInfo['indent'] : 30 }}px;">{{ $fieldInfo['label'] }}</td>
                    @if(isset($selectedYears) && $selectedYears->count() > 0)
                        @foreach($selectedYears as $year)
                            @php
                                $value = getValueForYear($patient, $year, $examType, $field);
                                if(isset($fieldInfo['format']) && $fieldInfo['format'] == 'number' && $value !== '-') {
                                    $value = number_format($value);
                                }
                            @endphp
                            <td>{{ $value }}</td>
                        @endforeach
                    @else
                        <td>{{ $primaryOrder && $primaryOrder->$examType ? cleanValue($primaryOrder->$examType->$field) : '-' }}</td>
                    @endif
                    <td>{{ $fieldInfo['normal'] }}</td>
                    <td>{{ $fieldInfo['unit'] }}</td>
                </tr>
                @if($field == 'leukosit')
                <tr>
                    <td class="sub-item">Hitung Jenis</td>
                    @if(isset($selectedYears) && $selectedYears->count() > 0)
                        @foreach($selectedYears as $year)
                        <td></td>
                        @endforeach
                    @else
                        <td></td>
                    @endif
                    <td></td>
                    <td></td>
                </tr>
                @endif
                @endforeach
                @endif
            @endforeach
        </tbody>
    </table>

    <p style="text-align: center; margin-top: 20px; font-size: 11px;">1 / 3</p>
</body>
</html>