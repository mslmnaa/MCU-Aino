@extends('layouts.admin')

@section('page-title', 'Patient Record')
@section('page-subtitle', $patient->name . ' • ID: ' . $patient->share_id)

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div class="text-sm text-neutral-500">
            Patient Information • {{ $patient->departemen }} • {{ $patient->jabatan }}
        </div>
        <div class="flex space-x-3">
            <button onclick="exportSelectedYears()" class="bg-red-500 hover:bg-red-600 text-neutral-50 px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Export PDF
            </button>
            <a href="{{ route('patients.index') }}" class="bg-primary-500 hover:bg-primary-600 text-neutral-50 px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                Back to Patients
            </a>
        </div>
    </div>
</div>

<!-- Patient Header Card -->
<div class="bg-primary-700 text-white rounded-xl p-8 mb-8 shadow-lg">
    <div class="flex items-center space-x-8">
        <div class="flex-shrink-0">
            @if($patient->profile_photo || $patient->user?->profile_photo)
                <img src="{{ asset('storage/' . ($patient->profile_photo ?? $patient->user->profile_photo)) }}"
                     alt="{{ $patient->name }}"
                     class="w-28 h-28 rounded-full object-cover border-4 border-primary-300">
            @else
                <div class="w-28 h-28 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center border-4 border-primary-300">
                    <span class="text-white text-2xl font-bold">
                        {{ strtoupper(substr($patient->name, 0, 2)) }}
                    </span>
                </div>
            @endif
        </div>

        <div class="flex-grow">
            <div class="mb-4">
                <h3 class="text-4xl font-bold text-white">{{ strtoupper($patient->name) }}</h3>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-primary-200 text-sm">Position</p>
                    <p class="text-white font-bold text-lg">{{ $patient->jabatan }}</p>
                </div>
                <div>
                    <p class="text-primary-200 text-sm">Department</p>
                    <p class="text-white font-bold text-lg">{{ $patient->departemen }}</p>
                </div>
                <div>
                    <p class="text-primary-200 text-sm">Gender</p>
                    <p class="text-white font-bold text-lg">{{ $patient->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                </div>
                <div>
                    <p class="text-primary-200 text-sm">Age</p>
                    <p class="text-white font-bold text-lg">{{ $patient->umur }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Year Selection Controls -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-neutral-800">Medical Check-Up Comparison</h3>
            <p class="text-sm text-neutral-600 mt-1">Select years to compare medical examination results</p>
        </div>
        <div id="selected-count" class="text-sm font-medium text-primary-600">
            <span id="count-text">1 year selected</span>
        </div>
    </div>
    
    <div class="flex flex-wrap gap-2">
        @foreach($availableYears as $year)
            <button onclick="toggleYear({{ $year }})"
                    class="year-toggle-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 border-2
                           {{ $loop->last ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-neutral-700 border-neutral-300 hover:border-primary-300' }}"
                    data-year="{{ $year }}"
                    data-active="{{ $loop->last ? 'true' : 'false' }}">
                <span>{{ $year }}</span>
                <svg class="inline-block w-4 h-4 ml-1 {{ $loop->last ? '' : 'hidden' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </button>
        @endforeach
    </div>
    
    <div class="mt-3 text-xs text-neutral-500">
        <span>💡 Tip: Select multiple years to compare side by side. Maximum 5 years recommended for best viewing experience.</span>
    </div>
</div>

<!-- Unified Summary & Detail Section -->
<div class="bg-white rounded-lg shadow-md mb-6" id="unified-section" x-data="{ activeTab: 'detail' }">
    <div class="border-b border-neutral-200 px-6 py-4" id="unified-header">
        <!-- Header will be populated by JavaScript -->
    </div>

    <!-- Tab Navigation -->
    <div class="border-b border-neutral-200" id="tab-navigation" style="display: none;">
        <div class="px-6">
            <nav class="flex space-x-8" aria-label="Tabs">
                <button @click="activeTab = 'detail'"
                        :class="activeTab === 'detail' ? 'border-primary-500 text-primary-600' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Detail Hasil
                </button>
                <button @click="activeTab = 'chart'"
                        :class="activeTab === 'chart' ? 'border-primary-500 text-primary-600' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Grafik Hasil
                </button>
            </nav>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="p-6">
        <!-- Detail Hasil Tab Content -->
        <div x-show="activeTab === 'detail'" id="unified-content">
            <!-- Content will be populated by JavaScript -->
        </div>

        <!-- Grafik Hasil Tab Content -->
        <div x-show="activeTab === 'chart'" id="chart-content">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="charts-container">
                <!-- Charts will be generated by JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Main Comparison Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
    <div class="bg-primary-600 px-6 py-4">
        <h3 class="text-lg font-semibold text-white">Complete Medical Examination Results</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full" id="comparison-table">
            <thead class="bg-neutral-50 sticky top-0 z-10">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200 bg-neutral-100" style="min-width: 180px;">Jenis Pemeriksaan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200" style="min-width: 150px;">Parameter</th>
                    
                    @foreach($availableYears as $year)
                    <th class="year-column year-col-{{ $year }} px-4 py-3 text-center text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200 {{ !$loop->last ? 'hidden' : '' }}" style="min-width: 100px;">
                        {{ $year }}
                    </th>
                    @endforeach
                    
                    <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200" style="min-width: 80px;">Satuan</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200" style="min-width: 80px;">Trend</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider" style="min-width: 150px;">Nilai Normal</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                @php
                    function cleanValue($value, $unit = '') {
                        if ($value === null || $value === 'T/A') return '-';
                        
                        $units_to_remove = [' kg', ' cm', ' mmHg', ' x/menit', ' °C', ' g/dL', ' juta/μL', 
                                          '%', ' fL', ' pg', '/μL', ' mm/jam', ' mg/dL', ' U/L', ' ng/mL', ' million/μL'];
                        
                        $clean = $value;
                        foreach($units_to_remove as $u) {
                            $clean = str_replace($u, '', $clean);
                        }
                        
                        return trim($clean);
                    }

                    $examinations = [
                        'pemeriksaanVital' => [
                            'name' => 'Pemeriksaan Vital',
                            'color' => 'primary',
                            'fields' => [
                                'berat_badan' => ['label' => 'Berat Badan', 'unit' => 'kg', 'normal' => '40-100 kg'],
                                'tinggi_badan' => ['label' => 'Tinggi Badan', 'unit' => 'cm', 'normal' => '150-200 cm'],
                                'lingkar_perut' => ['label' => 'Lingkar Perut', 'unit' => 'cm', 'normal' => '< 90 cm (P), < 80 cm (W)'],
                                'bmi' => ['label' => 'Indeks Massa Tubuh (BMI)', 'unit' => '-', 'normal' => '18.5-24.9'],
                                'klasifikasi_tekanan_darah' => ['label' => 'Klasifikasi Tekanan Darah', 'unit' => '-', 'normal' => 'Normal'],
                                'klasifikasi_od' => ['label' => 'Klasifikasi OD', 'unit' => '-', 'normal' => 'Normal'],
                                'klasifikasi_os' => ['label' => 'Klasifikasi OS', 'unit' => '-', 'normal' => 'Normal'],
                                'persepsi_warna' => ['label' => 'Persepsi Warna', 'unit' => '-', 'normal' => 'Normal']
                            ]
                        ],
                        'tandaVital' => [
                            'name' => 'Tanda Vital',
                            'color' => 'secondary',
                            'fields' => [
                                'tekanan_darah' => ['label' => 'Tekanan Darah', 'unit' => 'mmHg', 'normal' => '120/80 mmHg'],
                                'nadi' => ['label' => 'Denyut Nadi', 'unit' => 'x/menit', 'normal' => '60-100 x/menit'],
                                'pernapasan' => ['label' => 'Frekuensi Napas', 'unit' => 'x/menit', 'normal' => '12-20 x/menit'],
                                'suhu_tubuh' => ['label' => 'Suhu Tubuh', 'unit' => '°C', 'normal' => '36.0-37.5°C']
                            ]
                        ],
                        'labHematologi' => [
                            'name' => 'Hematologi',
                            'color' => 'cream',
                            'fields' => [
                                'hemoglobin' => ['label' => 'Hemoglobin', 'unit' => 'g/dL', 'normal' => '12-16 g/dL'],
                                'erytrosit' => ['label' => 'Sel Darah Merah', 'unit' => 'juta/μL', 'normal' => '4.2-5.4 juta/μL'],
                                'hematokrit' => ['label' => 'Hematokrit', 'unit' => '%', 'normal' => '37-48%'],
                                'mcv' => ['label' => 'MCV', 'unit' => 'fL', 'normal' => '82-98 fL'],
                                'mch' => ['label' => 'MCH', 'unit' => 'pg', 'normal' => '27-31 pg'],
                                'mchc' => ['label' => 'MCHC', 'unit' => 'g/dL', 'normal' => '32-36 g/dL'],
                                'rdw' => ['label' => 'RDW', 'unit' => '%', 'normal' => '11.5-14.5%'],
                                'leukosit' => ['label' => 'Sel Darah Putih', 'unit' => '/μL', 'normal' => '4.000-11.000/μL'],
                                'eosinofil' => ['label' => 'Eosinofil', 'unit' => '%', 'normal' => '1-3%'],
                                'basofil' => ['label' => 'Basofil', 'unit' => '%', 'normal' => '0-1%'],
                                'neutrofil_batang' => ['label' => 'Neutrofil Batang', 'unit' => '%', 'normal' => '2-6%'],
                                'neutrofil_segmen' => ['label' => 'Neutrofil Segmen', 'unit' => '%', 'normal' => '50-70%'],
                                'limfosit' => ['label' => 'Limfosit', 'unit' => '%', 'normal' => '20-40%'],
                                'monosit' => ['label' => 'Monosit', 'unit' => '%', 'normal' => '2-8%'],
                                'trombosit' => ['label' => 'Trombosit', 'unit' => '/μL', 'normal' => '150.000-450.000/μL'],
                                'laju_endap_darah' => ['label' => 'Laju Endap Darah', 'unit' => 'mm/jam', 'normal' => '0-15 mm/jam']
                            ]
                        ],
                        'labUrine' => [
                            'name' => 'Analisis Urine',
                            'color' => 'neutral',
                            'fields' => [
                                'warna' => ['label' => 'Warna', 'unit' => '-', 'normal' => 'Kuning'],
                                'kejernihan' => ['label' => 'Kejernihan', 'unit' => '-', 'normal' => 'Jernih'],
                                'bj' => ['label' => 'Berat Jenis', 'unit' => '-', 'normal' => '1.010-1.025'],
                                'ph' => ['label' => 'pH', 'unit' => '-', 'normal' => '5.0-8.0'],
                                'protein' => ['label' => 'Protein', 'unit' => '-', 'normal' => 'Negatif'],
                                'glukosa' => ['label' => 'Glukosa', 'unit' => '-', 'normal' => 'Negatif'],
                                'keton' => ['label' => 'Keton', 'unit' => '-', 'normal' => 'Negatif'],
                                'bilirubin' => ['label' => 'Bilirubin', 'unit' => '-', 'normal' => 'Negatif'],
                                'urobilinogen' => ['label' => 'Urobilinogen', 'unit' => '-', 'normal' => 'Normal'],
                                'nitrit' => ['label' => 'Nitrit', 'unit' => '-', 'normal' => 'Negatif'],
                                'darah' => ['label' => 'Darah', 'unit' => '-', 'normal' => 'Negatif'],
                                'lekosit_esterase' => ['label' => 'Lekosit Esterase', 'unit' => '-', 'normal' => 'Negatif'],
                                'eritrosit_sedimen' => ['label' => 'Sedimen Eritrosit', 'unit' => '/LPB', 'normal' => '0-2/LPB'],
                                'lekosit_sedimen' => ['label' => 'Sedimen Leukosit', 'unit' => '/LPB', 'normal' => '0-5/LPB'],
                                'epitel_sedimen' => ['label' => 'Sedimen Epitel', 'unit' => '/LPB', 'normal' => 'Positif 1'],
                                'silinder_sedimen' => ['label' => 'Sedimen Silinder', 'unit' => '/LPK', 'normal' => 'Negatif'],
                                'kristal_sedimen' => ['label' => 'Sedimen Kristal', 'unit' => '/LPK', 'normal' => 'Negatif'],
                                'lain_lain_sedimen' => ['label' => 'Sedimen Lain-lain', 'unit' => '-', 'normal' => 'Negatif']
                            ]
                        ],
                        'labFungsiLiver' => [
                            'name' => 'Fungsi Hati',
                            'color' => 'primary',
                            'fields' => [
                                'sgot' => ['label' => 'SGOT', 'unit' => 'U/L', 'normal' => '10-40 U/L'],
                                'sgpt' => ['label' => 'SGPT', 'unit' => 'U/L', 'normal' => '7-56 U/L']
                            ]
                        ],
                        'labProfilLemak' => [
                            'name' => 'Profil Lemak',
                            'color' => 'secondary',
                            'fields' => [
                                'cholesterol' => ['label' => 'Kolesterol Total', 'unit' => 'mg/dL', 'normal' => '< 200 mg/dL'],
                                'trigliserida' => ['label' => 'Trigliserida', 'unit' => 'mg/dL', 'normal' => '< 150 mg/dL'],
                                'hdl_cholesterol' => ['label' => 'Kolesterol HDL', 'unit' => 'mg/dL', 'normal' => '> 40 mg/dL (P), > 50 mg/dL (W)'],
                                'ldl_cholesterol' => ['label' => 'Kolesterol LDL', 'unit' => 'mg/dL', 'normal' => '< 100 mg/dL']
                            ]
                        ],
                        'labFungsiGinjal' => [
                            'name' => 'Fungsi Ginjal',
                            'color' => 'cream',
                            'fields' => [
                                'ureum' => ['label' => 'Ureum', 'unit' => 'mg/dL', 'normal' => '10-50 mg/dL'],
                                'creatinin' => ['label' => 'Kreatinin', 'unit' => 'mg/dL', 'normal' => '0.6-1.2 mg/dL'],
                                'asam_urat' => ['label' => 'Asam Urat', 'unit' => 'mg/dL', 'normal' => '3.5-7.2 mg/dL']
                            ]
                        ],
                        'labGlukosaDarah' => [
                            'name' => 'Gula Darah',
                            'color' => 'primary',
                            'fields' => [
                                'glukosa_puasa' => ['label' => 'Glukosa Puasa', 'unit' => 'mg/dL', 'normal' => '70-100 mg/dL'],
                                'glukosa_2jam_pp' => ['label' => 'Glukosa 2 Jam PP', 'unit' => 'mg/dL', 'normal' => '< 140 mg/dL'],
                                'hba1c' => ['label' => 'HbA1c', 'unit' => '%', 'normal' => '< 5.7%']
                            ]
                        ],
                        'labPenandaTumor' => [
                            'name' => 'Penanda Tumor',
                            'color' => 'secondary',
                            'fields' => [
                                'hbsag' => ['label' => 'HBsAg', 'unit' => '-', 'normal' => 'Non Reaktif'],
                                'cea' => ['label' => 'CEA', 'unit' => 'ng/mL', 'normal' => '< 5.0 ng/mL']
                            ]
                        ]
                    ];
                @endphp

                @foreach($examinations as $examType => $examInfo)
                    @php
                        $hasData = false;
                        foreach($patient->orders as $order) {
                            if($order->$examType) {
                                $hasData = true;
                                break;
                            }
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
                            
                            @foreach($availableYears as $year)
                                @php
                                    $yearOrder = $patient->orders->firstWhere(function($order) use ($year) {
                                        return $order->tgl_order->year == $year;
                                    });
                                    
                                    $value = '-';
                                    if($yearOrder && $yearOrder->$examType && isset($yearOrder->$examType->$fieldName)) {
                                        $value = cleanValue($yearOrder->$examType->$fieldName);
                                    }
                                @endphp
                            <td class="year-column year-col-{{ $year }} px-4 py-3 text-sm font-semibold text-center border-r border-neutral-200 {{ !$loop->last ? 'hidden' : '' }}">
                                <span class="text-{{ $value == '-' ? 'neutral-400' : $examInfo['color'] . '-700' }}">
                                    {{ $value }}
                                </span>
                            </td>
                            @endforeach
                            
                            <td class="px-4 py-3 text-sm text-neutral-600 text-center border-r border-neutral-200">
                                {{ $fieldInfo['unit'] }}
                            </td>

                            <td class="trend-cell px-4 py-3 text-sm text-center border-r border-neutral-200" data-field="{{ $fieldName }}" data-exam="{{ $examType }}">
                                <span class="trend-indicator">-</span>
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


<style>
/* Ensure horizontal scroll on mobile */
.overflow-x-auto {
    -webkit-overflow-scrolling: touch;
}

/* Active year button style */
.year-toggle-btn[data-active="true"] {
    background-color: rgb(37 99 235);
    color: white;
    border-color: rgb(37 99 235);
}

.year-toggle-btn[data-active="false"] {
    background-color: white;
    color: rgb(55 65 81);
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

/* Column width consistency */
.year-column {
    min-width: 100px;
    max-width: 120px;
}

/* Trend indicators styling */
.trend-indicator {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: help;
}

.trend-up {
    color: #ef4444; /* red-500 */
}

.trend-down {
    color: #10b981; /* green-500 */
}

.trend-neutral {
    color: #9ca3af; /* neutral-400 */
}

/* Hover effect for trend indicators */
.trend-cell:hover .trend-indicator {
    transform: scale(1.1);
    transition: transform 0.2s ease;
}
</style>

<script>
let activeYears = [{{ $availableYears->max() }}];
const maxYears = 5;

// Patient orders data for summary
const patientOrders = @json($patient->orders);

function toggleYear(year) {
    const btn = document.querySelector(`[data-year="${year}"]`);
    const isActive = btn.dataset.active === 'true';
    const checkIcon = btn.querySelector('svg');
    
    if (isActive) {
        // Try to deactivate - only if other years are active
        if (activeYears.length > 1) {
            activeYears = activeYears.filter(y => y !== year);
            btn.dataset.active = 'false';
            btn.classList.remove('bg-primary-600', 'text-white', 'border-primary-600');
            btn.classList.add('bg-white', 'text-neutral-700', 'border-neutral-300');
            checkIcon.classList.add('hidden');
            
            // Hide column
            document.querySelectorAll(`.year-col-${year}`).forEach(col => {
                col.classList.add('hidden');
            });
        } else {
            // Can't deactivate the only active year
            showNotification('At least one year must be selected', 'warning');
        }
    } else {
        // Try to activate
        if (activeYears.length >= maxYears) {
            showNotification(`Maximum ${maxYears} years can be compared at once`, 'warning');
            return;
        }
        
        activeYears.push(year);
        activeYears.sort((a, b) => b - a); // Sort descending
        
        btn.dataset.active = 'true';
        btn.classList.add('bg-primary-600', 'text-white', 'border-primary-600');
        btn.classList.remove('bg-white', 'text-neutral-700', 'border-neutral-300');
        checkIcon.classList.remove('hidden');
        
        // Show column
        document.querySelectorAll(`.year-col-${year}`).forEach(col => {
            col.classList.remove('hidden');
        });
    }
    
    updateDisplay();
}

function updateDisplay() {
    // Update count
    const countText = document.getElementById('count-text');
    countText.textContent = activeYears.length === 1
        ? '1 year selected'
        : `${activeYears.length} years selected`;

    // Update unified section
    updateUnifiedSection();

    // Update trend indicators
    updateTrendIndicators();
}

function updateUnifiedSection() {
    const unifiedHeader = document.getElementById('unified-header');
    const unifiedContent = document.getElementById('unified-content');

    if (activeYears.length === 0) {
        unifiedHeader.innerHTML = '<h3 class="text-lg font-semibold text-neutral-800">Rangkuman & Detail MCU</h3>';
        unifiedContent.innerHTML = getEmptyState();
        hideTabNavigation();
        return;
    }

    // Get the first selected year (primary year for summary)
    const primaryYear = activeYears[0];
    const orderForYear = patientOrders.find(order => {
        const orderYear = new Date(order.tgl_order).getFullYear();
        return orderYear === primaryYear;
    });

    if (!orderForYear) {
        unifiedHeader.innerHTML = '<h3 class="text-lg font-semibold text-neutral-800">Rangkuman & Detail MCU</h3>';
        unifiedContent.innerHTML = getEmptyState();
        hideTabNavigation();
        return;
    }

    // Generate header with edit button
    const orderDate = new Date(orderForYear.tgl_order).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });

    unifiedHeader.innerHTML = `
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-neutral-800">Rangkuman & Detail MCU - ${primaryYear}</h3>
                <p class="text-sm text-neutral-600 mt-1">${orderDate} • Lab No: ${orderForYear.no_lab} • ${orderForYear.cabang || 'Lab Utama'}</p>
            </div>
            <a href="{{ route('medical-records.patient', $patient->id) }}"
               class="bg-neutral-600 hover:bg-neutral-700 text-white px-4 py-2 text-sm font-medium rounded-lg transition-colors">
                Edit Medical Records
            </a>
        </div>
    `;

    unifiedContent.innerHTML = generateUnifiedHTML(orderForYear);

    // Show tab navigation and generate charts
    showTabNavigation();
    generateHealthCharts();
}

function getEmptyState() {
    return `
        <div class="text-center py-8">
            <div class="text-neutral-400 mb-2">
                <svg class="w-12 h-12 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <p class="text-neutral-600">Belum ada data MCU yang tersedia untuk pasien ini</p>
        </div>
    `;
}

function generateUnifiedHTML(order) {
    const labResults = getLabResults(order);
    const nonLabResults = getNonLabResults(order);

    let html = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Hasil Laboratorium -->
            <div class="bg-neutral-50 rounded-lg p-5 border border-neutral-200">
                <div class="flex items-center mb-4">
                    <div class="w-3 h-3 bg-neutral-400 rounded-full mr-2"></div>
                    <h5 class="font-semibold text-neutral-700">Hasil Laboratorium</h5>
                </div>

                <div class="space-y-3 text-sm">
    `;

    if (labResults.length > 0) {
        labResults.forEach(result => {
            html += `
                <div class="flex justify-between items-center">
                    <span class="text-neutral-600">${result.param}</span>
                    <div class="flex items-center space-x-2">
                        <span class="font-medium text-neutral-800">${result.value}</span>
                        <span class="px-2 py-1 text-xs rounded-full border ${result.status === 'Normal' ? 'bg-neutral-100 text-neutral-600 border-neutral-300' : 'bg-neutral-200 text-neutral-700 border-neutral-400'}">
                            ${result.status}
                        </span>
                    </div>
                </div>
            `;
        });
    } else {
        html += '<p class="text-neutral-500">Data laboratorium belum tersedia</p>';
    }

    html += `
                </div>
            </div>

            <!-- Hasil Non-Laboratorium -->
            <div class="bg-neutral-50 rounded-lg p-5 border border-neutral-200">
                <div class="flex items-center mb-4">
                    <div class="w-3 h-3 bg-neutral-400 rounded-full mr-2"></div>
                    <h5 class="font-semibold text-neutral-700">Hasil Non-Laboratorium</h5>
                </div>

                <div class="space-y-3 text-sm">
    `;

    if (nonLabResults.length > 0) {
        nonLabResults.forEach(result => {
            html += `
                <div class="flex justify-between items-center">
                    <span class="text-neutral-600">${result.param}</span>
                    <div class="flex items-center space-x-2">
                        <span class="font-medium text-neutral-800">${result.value}</span>
                        <span class="px-2 py-1 text-xs rounded-full border ${result.status === 'Normal' ? 'bg-neutral-100 text-neutral-600 border-neutral-300' : 'bg-neutral-200 text-neutral-700 border-neutral-400'}">
                            ${result.status}
                        </span>
                    </div>
                </div>
            `;
        });
    } else {
        html += '<p class="text-neutral-500">Data pemeriksaan belum tersedia</p>';
    }

    html += `
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
    `;

    // Add general physical examination summary
    if (order.pemeriksaan_vital && order.pemeriksaan_vital.pemeriksaan_fisik_umum) {
        html += `
                <div class="flex flex-col space-y-2">
                    <span class="text-neutral-600 font-medium">Pemeriksaan Fisik Umum:</span>
                    <div class="bg-white rounded-lg p-3 border border-neutral-200">
                        <p class="text-neutral-800 whitespace-pre-line">${order.pemeriksaan_vital.pemeriksaan_fisik_umum}</p>
                    </div>
                </div>
        `;
    } else {
        html += '<p class="text-neutral-500">Data pemeriksaan fisik umum belum tersedia</p>';
    }

    html += `
            </div>
        </div>
    `;

    // Add detailed information if available
    const hasDetailedInfo = order.pemeriksaan_vital &&
        (order.pemeriksaan_vital.kesimpulan_fisik ||
         order.pemeriksaan_vital.rekomendasi ||
         order.pemeriksaan_vital.saran);

    if (hasDetailedInfo) {
        html += '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">';

        if (order.pemeriksaan_vital.kesimpulan_fisik) {
            html += `
                <div class="bg-neutral-50 rounded-lg p-4 border border-neutral-200">
                    <h4 class="font-medium text-neutral-700 mb-2">Kesimpulan Pemeriksaan</h4>
                    <p class="text-sm text-neutral-600">${order.pemeriksaan_vital.kesimpulan_fisik}</p>
                </div>
            `;
        }

        if (order.pemeriksaan_vital.rekomendasi) {
            html += `
                <div class="bg-neutral-50 rounded-lg p-4 border border-neutral-200">
                    <h4 class="font-medium text-neutral-700 mb-2">Rekomendasi</h4>
                    <p class="text-sm text-neutral-600 whitespace-pre-line">${order.pemeriksaan_vital.rekomendasi}</p>
                </div>
            `;
        }

        if (order.pemeriksaan_vital.saran) {
            html += `
                <div class="bg-neutral-50 rounded-lg p-4 border border-neutral-200">
                    <h4 class="font-medium text-neutral-700 mb-2">Saran</h4>
                    <p class="text-sm text-neutral-600 whitespace-pre-line">${order.pemeriksaan_vital.saran}</p>
                </div>
            `;
        }

        html += '</div>';
    }

    return html;
}

function cleanValue(value, unit = '') {
    if (value === null || value === 'T/A') return '-';

    const units_to_remove = [' kg', ' cm', ' mmHg', ' x/menit', ' °C', ' g/dL', ' juta/μL',
                          '%', ' fL', ' pg', '/μL', ' mm/jam', ' mg/dL', ' U/L', ' ng/mL', ' million/μL'];

    let clean = value;
    units_to_remove.forEach(u => {
        clean = clean.replace(u, '');
    });

    return clean.trim();
}

function getLabResults(order) {
    const results = [];

    // Hematologi Lengkap
    if (order.lab_hematologi && order.lab_hematologi.kesimpulan_hematologi) {
        const status = order.lab_hematologi.kesimpulan_hematologi.toLowerCase().includes('normal') ? 'Normal' : 'Abnormal';
        results.push({param: 'Hematologi Lengkap', value: order.lab_hematologi.kesimpulan_hematologi, status: status});
    }

    // Urine Lengkap
    if (order.lab_urine && order.lab_urine.kesimpulan_urine) {
        const status = order.lab_urine.kesimpulan_urine.toLowerCase().includes('normal') ? 'Normal' : 'Abnormal';
        results.push({param: 'Urine Lengkap', value: order.lab_urine.kesimpulan_urine, status: status});
    }

    // Fungsi Hati
    if (order.lab_fungsi_liver && order.lab_fungsi_liver.kesimpulan_fungsi_hati) {
        const status = order.lab_fungsi_liver.kesimpulan_fungsi_hati.toLowerCase().includes('normal') ? 'Normal' : 'Abnormal';
        results.push({param: 'Fungsi Hati', value: order.lab_fungsi_liver.kesimpulan_fungsi_hati, status: status});
    }

    // Profil Lemak
    if (order.lab_profil_lemak && order.lab_profil_lemak.kesimpulan_profil_lemak) {
        const status = order.lab_profil_lemak.kesimpulan_profil_lemak.toLowerCase().includes('normal') ? 'Normal' : 'Abnormal';
        results.push({param: 'Profil Lemak', value: order.lab_profil_lemak.kesimpulan_profil_lemak, status: status});
    }

    // Fungsi Ginjal
    if (order.lab_fungsi_ginjal && order.lab_fungsi_ginjal.kesimpulan_fungsi_ginjal) {
        const status = order.lab_fungsi_ginjal.kesimpulan_fungsi_ginjal.toLowerCase().includes('normal') ? 'Normal' : 'Abnormal';
        results.push({param: 'Fungsi Ginjal', value: order.lab_fungsi_ginjal.kesimpulan_fungsi_ginjal, status: status});
    }

    // Glukosa Darah & HbA1c
    if (order.lab_glukosa_darah && order.lab_glukosa_darah.kesimpulan_glukosa) {
        const status = order.lab_glukosa_darah.kesimpulan_glukosa.toLowerCase().includes('normal') ? 'Normal' : 'Abnormal';
        results.push({param: 'Glukosa Darah & HbA1c', value: order.lab_glukosa_darah.kesimpulan_glukosa, status: status});
    }

    // Penanda Tumor
    if (order.lab_penanda_tumor && order.lab_penanda_tumor.kesimpulan_penanda_tumor) {
        const status = order.lab_penanda_tumor.kesimpulan_penanda_tumor.toLowerCase().includes('normal') ? 'Normal' : 'Abnormal';
        results.push({param: 'Penanda Tumor', value: order.lab_penanda_tumor.kesimpulan_penanda_tumor, status: status});
    }

    return results;
}

function getNonLabResults(order) {
    const results = [];

    // Radiologi - ECG saja
    if (order.radiologi) {
        const ecg = order.radiologi.ecg;
        const kesimpulanEcg = order.radiologi.kesimpulan_ecg;

        if (kesimpulanEcg && kesimpulanEcg !== 'T/A') {
            const kesimpulanStatus = (kesimpulanEcg.toLowerCase().includes('normal')) ? 'Normal' : 'Abnormal';
            results.push({param: 'Kesimpulan EKG', value: kesimpulanEcg, status: kesimpulanStatus});
        }
    }

    return results;
}

function updateTrendIndicators() {
    // Reset all trend indicators
    document.querySelectorAll('.trend-indicator').forEach(indicator => {
        indicator.innerHTML = '-';
        indicator.className = 'trend-indicator';
    });

    // Only calculate trends if we have multiple years selected
    if (activeYears.length < 2) {
        return;
    }

    // Sort years for proper comparison (newest to oldest)
    const sortedYears = [...activeYears].sort((a, b) => b - a);

    // Calculate trends for each parameter
    document.querySelectorAll('.trend-cell').forEach(cell => {
        const fieldName = cell.dataset.field;
        const examType = cell.dataset.exam;

        const trend = calculateTrend(fieldName, examType, sortedYears);
        const indicator = cell.querySelector('.trend-indicator');

        if (trend.direction === 'up') {
            const colorClass = trend.color === 'red' ? 'text-red-500' : 'text-green-500';
            indicator.innerHTML = `
                <div class="trend-arrow-up ${colorClass}">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            `;
            indicator.className = `trend-indicator trend-up ${colorClass}`;
            indicator.title = trend.tooltip;
        } else if (trend.direction === 'down') {
            const colorClass = trend.color === 'red' ? 'text-red-500' : 'text-green-500';
            indicator.innerHTML = `
                <div class="trend-arrow-down ${colorClass}">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            `;
            indicator.className = `trend-indicator trend-down ${colorClass}`;
            indicator.title = trend.tooltip;
        } else if (trend.direction === 'neutral') {
            indicator.innerHTML = '-';
            indicator.className = 'trend-indicator trend-neutral';
            indicator.title = trend.tooltip;
        }
    });
}

function calculateTrend(fieldName, examType, sortedYears) {
    if (sortedYears.length < 2) {
        return { direction: 'none', tooltip: 'Perlu minimal 2 tahun untuk perbandingan' };
    }

    // Get the two most recent years
    const newestYear = sortedYears[0];
    const previousYear = sortedYears[1];

    // Find orders for these years
    const newestOrder = patientOrders.find(order => {
        const orderYear = new Date(order.tgl_order).getFullYear();
        return orderYear === newestYear;
    });

    const previousOrder = patientOrders.find(order => {
        const orderYear = new Date(order.tgl_order).getFullYear();
        return orderYear === previousYear;
    });

    if (!newestOrder || !previousOrder) {
        return { direction: 'none', tooltip: 'Data tidak lengkap untuk perbandingan' };
    }

    // Get values from both years
    const newestValue = getFieldValue(newestOrder, examType, fieldName);
    const previousValue = getFieldValue(previousOrder, examType, fieldName);

    if (newestValue === null || previousValue === null ||
        newestValue === 'T/A' || previousValue === 'T/A' ||
        newestValue === '-' || previousValue === '-') {
        return { direction: 'none', tooltip: 'Data tidak tersedia untuk perbandingan' };
    }

    // For non-numeric values (like classifications), compare directly
    if (isNaN(parseFloat(newestValue)) || isNaN(parseFloat(previousValue))) {
        if (newestValue === previousValue) {
            return {
                direction: 'neutral',
                tooltip: `${previousYear}: ${previousValue} → ${newestYear}: ${newestValue} (Tidak berubah)`
            };
        } else {
            return {
                direction: 'neutral',
                tooltip: `${previousYear}: ${previousValue} → ${newestYear}: ${newestValue} (Berubah)`
            };
        }
    }

    // For numeric values, calculate percentage change
    const numNewest = parseFloat(newestValue.toString().replace(/[^0-9.-]/g, ''));
    const numPrevious = parseFloat(previousValue.toString().replace(/[^0-9.-]/g, ''));

    if (isNaN(numNewest) || isNaN(numPrevious) || numPrevious === 0) {
        return { direction: 'none', tooltip: 'Tidak dapat menghitung trend' };
    }

    // Check if values are exactly the same (no change)
    if (numNewest === numPrevious) {
        return {
            direction: 'neutral',
            tooltip: `${previousYear}: ${previousValue} → ${newestYear}: ${newestValue} (Tidak berubah)`
        };
    }

    const percentChange = ((numNewest - numPrevious) / numPrevious) * 100;
    const tooltip = `${previousYear}: ${previousValue} → ${newestYear}: ${newestValue} (${percentChange > 0 ? '+' : ''}${percentChange.toFixed(1)}%)`;

    // Always show trend direction, no threshold

    // For parameters where higher is generally worse (like cholesterol, blood pressure)
    const worseWhenHigher = [
        'cholesterol', 'trigliserida', 'ldl_cholesterol', 'glukosa_puasa', 'glukosa_2jam_pp',
        'hba1c', 'ureum', 'creatinin', 'asam_urat', 'sgot', 'sgpt', 'berat_badan', 'bmi'
    ];

    // For parameters where lower is generally worse (like hemoglobin, HDL)
    const worseWhenLower = [
        'hemoglobin', 'hdl_cholesterol', 'tinggi_badan'
    ];

    if (worseWhenHigher.includes(fieldName)) {
        // Red for increase (bad), Green for decrease (good)
        return {
            direction: percentChange > 0 ? 'up' : 'down',
            color: percentChange > 0 ? 'red' : 'green',
            tooltip: tooltip + (percentChange > 0 ? ' ⚠️ Meningkat' : ' ✅ Menurun')
        };
    } else if (worseWhenLower.includes(fieldName)) {
        // Green for increase (good), Red for decrease (bad)
        return {
            direction: percentChange > 0 ? 'up' : 'down',
            color: percentChange > 0 ? 'green' : 'red',
            tooltip: tooltip + (percentChange > 0 ? ' ✅ Meningkat' : ' ⚠️ Menurun')
        };
    } else {
        // For neutral parameters, use green/red based on direction
        return {
            direction: percentChange > 0 ? 'up' : 'down',
            color: percentChange > 0 ? 'green' : 'red',
            tooltip: tooltip
        };
    }
}

function getFieldValue(order, examType, fieldName) {
    try {
        // Handle different exam types
        let examData;
        switch(examType) {
            case 'pemeriksaanVital':
                examData = order.pemeriksaan_vital;
                break;
            case 'tandaVital':
                examData = order.tanda_vital;
                break;
            case 'labHematologi':
                examData = order.lab_hematologi;
                break;
            case 'labUrine':
                examData = order.lab_urine;
                break;
            case 'labFungsiLiver':
                examData = order.lab_fungsi_liver;
                break;
            case 'labProfilLemak':
                examData = order.lab_profil_lemak;
                break;
            case 'labFungsiGinjal':
                examData = order.lab_fungsi_ginjal;
                break;
            case 'labGlukosaDarah':
                examData = order.lab_glukosa_darah;
                break;
            case 'labPenandaTumor':
                examData = order.lab_penanda_tumor;
                break;
            default:
                return null;
        }

        if (!examData || !examData[fieldName]) {
            return null;
        }

        return examData[fieldName];
    } catch (error) {
        console.error('Error getting field value:', error);
        return null;
    }
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'warning' ? 'bg-amber-500 text-white' : 'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);

    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function exportSelectedYears() {
    // Get currently active years
    const yearsParam = activeYears.join(',');
    const exportUrl = `{{ route('patients.export', $patient->id) }}?years=${yearsParam}`;

    // Show notification about which years are being exported
    const yearText = activeYears.length === 1
        ? `tahun ${activeYears[0]}`
        : `tahun ${activeYears.join(', ')}`;
    showNotification(`Mengekspor data MCU untuk ${yearText}...`, 'info');

    // Open export URL in new window to trigger download
    window.open(exportUrl, '_blank');
}

// Chart management
let healthCharts = {};

function showTabNavigation() {
    const tabNav = document.getElementById('tab-navigation');
    if (tabNav) {
        tabNav.style.display = 'block';
    }
}

function hideTabNavigation() {
    const tabNav = document.getElementById('tab-navigation');
    if (tabNav) {
        tabNav.style.display = 'none';
    }
}

function generateHealthCharts() {
    const chartsContainer = document.getElementById('charts-container');
    if (!chartsContainer) return;

    // Clear existing charts
    Object.values(healthCharts).forEach(chart => {
        if (chart) chart.destroy();
    });
    healthCharts = {};
    chartsContainer.innerHTML = '';

    // Get all available years from patient orders
    const allYears = [...new Set(patientOrders.map(order => new Date(order.tgl_order).getFullYear()))].sort();

    if (allYears.length < 2) {
        chartsContainer.innerHTML = `
            <div class="col-span-2 text-center py-12 text-neutral-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-neutral-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-lg font-medium">Minimal 2 tahun data diperlukan</p>
                <p class="text-sm mt-2">Grafik trend memerlukan data dari minimal 2 tahun untuk perbandingan</p>
            </div>
        `;
        return;
    }

    // Define examination types and their chart configurations with numeric parameters
    const examTypes = [
        {
            id: 'hematologi',
            title: 'Hematologi - Hemoglobin',
            dataKey: 'lab_hematologi',
            parameter: 'hemoglobin',
            unit: 'g/dL',
            normalRange: { min: 12, max: 16 },
            color: {
                line: 'rgba(34, 197, 94, 0.8)',
                normal: 'rgba(156, 163, 175, 0.2)',
                point: 'rgba(34, 197, 94, 1)'
            }
        },
        {
            id: 'cholesterol',
            title: 'Profil Lemak - Kolesterol Total',
            dataKey: 'lab_profil_lemak',
            parameter: 'cholesterol',
            unit: 'mg/dL',
            normalRange: { min: 0, max: 200 },
            color: {
                line: 'rgba(245, 158, 11, 0.8)',
                normal: 'rgba(156, 163, 175, 0.2)',
                point: 'rgba(245, 158, 11, 1)'
            }
        },
        {
            id: 'glucose',
            title: 'Glukosa Darah - Puasa',
            dataKey: 'lab_glukosa_darah',
            parameter: 'glukosa_puasa',
            unit: 'mg/dL',
            normalRange: { min: 70, max: 100 },
            color: {
                line: 'rgba(16, 185, 129, 0.8)',
                normal: 'rgba(156, 163, 175, 0.2)',
                point: 'rgba(16, 185, 129, 1)'
            }
        },
        {
            id: 'liver_sgot',
            title: 'Fungsi Hati - SGOT',
            dataKey: 'lab_fungsi_liver',
            parameter: 'sgot',
            unit: 'U/L',
            normalRange: { min: 10, max: 40 },
            color: {
                line: 'rgba(168, 85, 247, 0.8)',
                normal: 'rgba(156, 163, 175, 0.2)',
                point: 'rgba(168, 85, 247, 1)'
            }
        },
        {
            id: 'liver_sgpt',
            title: 'Fungsi Hati - SGPT',
            dataKey: 'lab_fungsi_liver',
            parameter: 'sgpt',
            unit: 'U/L',
            normalRange: { min: 7, max: 56 },
            color: {
                line: 'rgba(99, 102, 241, 0.8)',
                normal: 'rgba(156, 163, 175, 0.2)',
                point: 'rgba(99, 102, 241, 1)'
            }
        },
        {
            id: 'kidney_ureum',
            title: 'Fungsi Ginjal - Ureum',
            dataKey: 'lab_fungsi_ginjal',
            parameter: 'ureum',
            unit: 'mg/dL',
            normalRange: { min: 10, max: 50 },
            color: {
                line: 'rgba(14, 165, 233, 0.8)',
                normal: 'rgba(156, 163, 175, 0.2)',
                point: 'rgba(14, 165, 233, 1)'
            }
        },
        {
            id: 'kidney_creatinin',
            title: 'Fungsi Ginjal - Kreatinin',
            dataKey: 'lab_fungsi_ginjal',
            parameter: 'creatinin',
            unit: 'mg/dL',
            normalRange: { min: 0.6, max: 1.2 },
            color: {
                line: 'rgba(6, 182, 212, 0.8)',
                normal: 'rgba(156, 163, 175, 0.2)',
                point: 'rgba(6, 182, 212, 1)'
            }
        },
        {
            id: 'lipid_hdl',
            title: 'Profil Lemak - HDL Kolesterol',
            dataKey: 'lab_profil_lemak',
            parameter: 'hdl_cholesterol',
            unit: 'mg/dL',
            normalRange: { min: 40, max: 100 },
            color: {
                line: 'rgba(217, 119, 6, 0.8)',
                normal: 'rgba(156, 163, 175, 0.2)',
                point: 'rgba(217, 119, 6, 1)'
            }
        }
    ];

    // Generate charts for each examination type
    examTypes.forEach(examType => {
        const chartData = prepareChartData(examType, allYears);
        if (chartData.datasets[0].data.some(value => value !== null)) {
            createChart(examType, chartData, chartsContainer);
        }
    });
}

function prepareChartData(examType, years) {
    const data = [];
    const rawValues = [];

    years.forEach(year => {
        const orderForYear = patientOrders.find(order => {
            return new Date(order.tgl_order).getFullYear() === year;
        });

        if (orderForYear && orderForYear[examType.dataKey] && orderForYear[examType.dataKey][examType.parameter]) {
            const rawValue = orderForYear[examType.dataKey][examType.parameter];
            const numericValue = parseFloat(rawValue.toString().replace(/[^0-9.-]/g, ''));

            if (!isNaN(numericValue)) {
                data.push(numericValue);
                rawValues.push(`${numericValue} ${examType.unit}`);
            } else {
                data.push(null);
                rawValues.push('Data tidak valid');
            }
        } else {
            data.push(null);
            rawValues.push('Data tidak tersedia');
        }
    });

    // Create normal range dataset
    const normalRangeData = years.map(() => examType.normalRange.max);
    const normalRangeMin = years.map(() => examType.normalRange.min);

    return {
        labels: years,
        datasets: [
            // Normal range area (background)
            {
                label: 'Normal Range',
                data: normalRangeData,
                backgroundColor: examType.color.normal,
                borderColor: 'transparent',
                fill: '+1',
                order: 2,
                pointRadius: 0,
                pointHoverRadius: 0,
                tension: 0
            },
            // Normal range minimum (invisible line for fill)
            {
                label: 'Normal Range Min',
                data: normalRangeMin,
                backgroundColor: 'transparent',
                borderColor: 'transparent',
                fill: false,
                order: 3,
                pointRadius: 0,
                pointHoverRadius: 0,
                tension: 0
            },
            // Actual values line
            {
                label: examType.title,
                data: data,
                borderColor: examType.color.line,
                backgroundColor: examType.color.point,
                borderWidth: 3,
                pointBackgroundColor: data.map(value => {
                    if (value === null) return 'rgba(156, 163, 175, 0.5)';
                    const isNormal = value >= examType.normalRange.min && value <= examType.normalRange.max;
                    return isNormal ? examType.color.point : 'rgba(239, 68, 68, 1)';
                }),
                pointBorderColor: data.map(value => {
                    if (value === null) return 'rgba(156, 163, 175, 0.8)';
                    const isNormal = value >= examType.normalRange.min && value <= examType.normalRange.max;
                    return isNormal ? examType.color.point : 'rgba(239, 68, 68, 1)';
                }),
                pointRadius: 8,
                pointHoverRadius: 10,
                fill: false,
                tension: 0.4,
                order: 1,
                rawValues: rawValues
            }
        ]
    };
}

function createChart(examType, chartData, container) {
    // Create chart container
    const chartWrapper = document.createElement('div');
    chartWrapper.className = 'bg-white p-4 rounded-lg border border-neutral-200';
    chartWrapper.innerHTML = `
        <h4 class="text-lg font-semibold text-neutral-800 mb-2">${examType.title}</h4>
        <div class="flex items-center space-x-4 text-sm text-neutral-600 mb-4">
            <div class="flex items-center">
                <div class="w-3 h-3 bg-gray-300 opacity-50 rounded mr-2"></div>
                <span>Normal Range: ${examType.normalRange.min} - ${examType.normalRange.max} ${examType.unit}</span>
            </div>
        </div>
        <div class="relative h-64">
            <canvas id="chart-${examType.id}"></canvas>
        </div>
    `;
    container.appendChild(chartWrapper);

    // Calculate dynamic Y-axis range
    const actualData = chartData.datasets[2].data.filter(value => value !== null);
    const minValue = Math.min(...actualData, examType.normalRange.min);
    const maxValue = Math.max(...actualData, examType.normalRange.max);
    const padding = (maxValue - minValue) * 0.1;
    const yMin = Math.max(0, minValue - padding);
    const yMax = maxValue + padding;

    // Create chart
    const ctx = document.getElementById(`chart-${examType.id}`).getContext('2d');
    healthCharts[examType.id] = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    min: yMin,
                    max: yMax,
                    ticks: {
                        callback: function(value) {
                            return `${value} ${examType.unit}`;
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                    },
                    title: {
                        display: true,
                        text: `${examType.parameter.toUpperCase()} (${examType.unit})`
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                    },
                    title: {
                        display: true,
                        text: 'Tahun'
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        filter: function(legendItem, chartData) {
                            // Only show legend for actual data and normal range
                            return legendItem.datasetIndex === 0 || legendItem.datasetIndex === 2;
                        },
                        generateLabels: function(chart) {
                            const datasets = chart.data.datasets;
                            return [
                                {
                                    text: 'Normal Range',
                                    fillStyle: examType.color.normal,
                                    strokeStyle: 'transparent',
                                    lineWidth: 0
                                },
                                {
                                    text: 'Actual Values',
                                    fillStyle: examType.color.point,
                                    strokeStyle: examType.color.line,
                                    lineWidth: 3
                                }
                            ];
                        }
                    }
                },
                tooltip: {
                    filter: function(tooltipItem) {
                        // Only show tooltip for actual values (dataset index 2)
                        return tooltipItem.datasetIndex === 2;
                    },
                    callbacks: {
                        title: function(context) {
                            return `${examType.title} - ${context[0].label}`;
                        },
                        label: function(context) {
                            const rawValues = context.dataset.rawValues;
                            const rawValue = rawValues[context.dataIndex];
                            const numericValue = context.parsed.y;

                            if (numericValue === null || rawValue === 'Data tidak tersedia') {
                                return 'Data tidak tersedia';
                            }

                            const isNormal = numericValue >= examType.normalRange.min && numericValue <= examType.normalRange.max;
                            const status = isNormal ? '✅ Normal' : '⚠️ Abnormal';

                            return [
                                `Value: ${rawValue}`,
                                `Status: ${status}`,
                                `Normal Range: ${examType.normalRange.min} - ${examType.normalRange.max} ${examType.unit}`
                            ];
                        }
                    },
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(0, 0, 0, 0.1)',
                    borderWidth: 1
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    updateDisplay();
});
</script>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection