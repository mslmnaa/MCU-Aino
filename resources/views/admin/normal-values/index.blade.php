@extends('layouts.admin')

@section('page-title', 'Normal Values Management')
@section('page-subtitle', 'Manage reference ranges for medical test parameters')

@section('content')


@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
    {{ session('error') }}
</div>
@endif

<form method="POST" action="{{ route('admin.normal-values.update') }}" id="normal-values-form">
    @csrf
    @method('PUT')

    @php
        $examinations = [
            'pemeriksaan_vital' => [
                'name' => 'Pemeriksaan Vital',
                'color' => 'primary',
                'fields' => [
                    'berat_badan' => ['label' => 'Berat Badan', 'unit' => 'kg'],
                    'tinggi_badan' => ['label' => 'Tinggi Badan', 'unit' => 'cm'],
                    'lingkar_perut' => ['label' => 'Lingkar Perut', 'unit' => 'cm'],
                    'bmi' => ['label' => 'Indeks Massa Tubuh (BMI)', 'unit' => '-']
                ]
            ],
            'tanda_vital' => [
                'name' => 'Tanda Vital',
                'color' => 'secondary',
                'fields' => [
                    'tekanan_darah' => ['label' => 'Tekanan Darah', 'unit' => 'mmHg'],
                    'nadi' => ['label' => 'Denyut Nadi', 'unit' => 'x/menit'],
                    'pernapasan' => ['label' => 'Frekuensi Napas', 'unit' => 'x/menit'],
                    'suhu_tubuh' => ['label' => 'Suhu Tubuh', 'unit' => '°C']
                ]
            ],
            'hematologi' => [
                'name' => 'Hematologi',
                'color' => 'cream',
                'fields' => [
                    'hemoglobin' => ['label' => 'Hemoglobin', 'unit' => 'g/dL'],
                    'erytrosit' => ['label' => 'Sel Darah Merah', 'unit' => 'juta/μL'],
                    'hematokrit' => ['label' => 'Hematokrit', 'unit' => '%'],
                    'mcv' => ['label' => 'MCV', 'unit' => 'fL'],
                    'mch' => ['label' => 'MCH', 'unit' => 'pg'],
                    'mchc' => ['label' => 'MCHC', 'unit' => 'g/dL'],
                    'rdw' => ['label' => 'RDW', 'unit' => '%'],
                    'leukosit' => ['label' => 'Sel Darah Putih', 'unit' => '/μL'],
                    'eosinofil' => ['label' => 'Eosinofil', 'unit' => '%'],
                    'basofil' => ['label' => 'Basofil', 'unit' => '%'],
                    'neutrofil_batang' => ['label' => 'Neutrofil Batang', 'unit' => '%'],
                    'neutrofil_segmen' => ['label' => 'Neutrofil Segmen', 'unit' => '%'],
                    'limfosit' => ['label' => 'Limfosit', 'unit' => '%'],
                    'monosit' => ['label' => 'Monosit', 'unit' => '%'],
                    'trombosit' => ['label' => 'Trombosit', 'unit' => '/μL'],
                    'laju_endap_darah' => ['label' => 'Laju Endap Darah', 'unit' => 'mm/jam']
                ]
            ],
            'fungsi_liver' => [
                'name' => 'Fungsi Hati',
                'color' => 'primary',
                'fields' => [
                    'sgot' => ['label' => 'SGOT', 'unit' => 'U/L'],
                    'sgpt' => ['label' => 'SGPT', 'unit' => 'U/L']
                ]
            ],
            'profil_lemak' => [
                'name' => 'Profil Lemak',
                'color' => 'secondary',
                'fields' => [
                    'cholesterol' => ['label' => 'Kolesterol Total', 'unit' => 'mg/dL'],
                    'trigliserida' => ['label' => 'Trigliserida', 'unit' => 'mg/dL'],
                    'hdl_cholesterol' => ['label' => 'Kolesterol HDL', 'unit' => 'mg/dL'],
                    'ldl_cholesterol' => ['label' => 'Kolesterol LDL', 'unit' => 'mg/dL']
                ]
            ],
            'fungsi_ginjal' => [
                'name' => 'Fungsi Ginjal',
                'color' => 'cream',
                'fields' => [
                    'ureum' => ['label' => 'Ureum', 'unit' => 'mg/dL'],
                    'creatinin' => ['label' => 'Kreatinin', 'unit' => 'mg/dL'],
                    'asam_urat' => ['label' => 'Asam Urat', 'unit' => 'mg/dL']
                ]
            ],
            'glukosa_darah' => [
                'name' => 'Gula Darah',
                'color' => 'primary',
                'fields' => [
                    'glukosa_puasa' => ['label' => 'Glukosa Puasa', 'unit' => 'mg/dL'],
                    'glukosa_2jam_pp' => ['label' => 'Glukosa 2 Jam PP', 'unit' => 'mg/dL'],
                    'hba1c' => ['label' => 'HbA1c', 'unit' => '%']
                ]
            ]
        ];
    @endphp

    <!-- Main Normal Values Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="bg-primary-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Normal Reference Values Configuration</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-neutral-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200 bg-neutral-100" style="min-width: 180px;">Jenis Pemeriksaan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200" style="min-width: 150px;">Parameter</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200" style="min-width: 200px;">Nilai Normal</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200" style="min-width: 80px;">Satuan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider" style="min-width: 150px;">Contoh Format</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @foreach($examinations as $examType => $examInfo)
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
                                $currentValue = '';
                                if (isset($normalValues[$examType][$fieldName])) {
                                    $currentValue = $normalValues[$examType][$fieldName];
                                }
                            @endphp

                            <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                                <input type="text"
                                       name="{{ $examType }}[{{ $fieldName }}]"
                                       value="{{ $currentValue }}"
                                       class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-{{ $examInfo['color'] }}-500 focus:border-{{ $examInfo['color'] }}-500 text-center"
                                       placeholder="Masukkan nilai normal">
                            </td>

                            <td class="px-4 py-3 text-sm text-neutral-600 text-center border-r border-neutral-200">
                                {{ $fieldInfo['unit'] }}
                            </td>

                            <td class="px-4 py-3 text-sm text-neutral-500">
                                @switch($fieldName)
                                    @case('hemoglobin')
                                        12-16 g/dL
                                        @break
                                    @case('tekanan_darah')
                                        120/80 mmHg
                                        @break
                                    @case('bmi')
                                        18.5-24.9
                                        @break
                                    @case('cholesterol')
                                        < 200 mg/dL
                                        @break
                                    @case('glukosa_puasa')
                                        70-100 mg/dL
                                        @break
                                    @case('sgot')
                                        10-40 U/L
                                        @break
                                    @case('ureum')
                                        10-50 mg/dL
                                        @break
                                    @case('nadi')
                                        60-100 x/menit
                                        @break
                                    @case('berat_badan')
                                        40-100 kg
                                        @break
                                    @case('leukosit')
                                        4.000-11.000/μL
                                        @break
                                    @default
                                        Range nilai normal
                                @endswitch
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Section -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Normal Values Guidelines</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Gunakan format range untuk nilai normal (contoh: "70-100 mg/dL")</li>
                        <li>Gunakan tanda < atau > untuk batas maksimal/minimal (contoh: "< 200 mg/dL")</li>
                        <li>Untuk nilai kategorikal, gunakan deskripsi (contoh: "Normal", "Negatif")</li>
                        <li>Nilai normal akan ditampilkan sebagai referensi di halaman edit dan show</li>
                        <li>Kosongkan field jika parameter tidak memiliki nilai normal standar</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="flex justify-end space-x-4">
        <a href="{{ route('patients.index') }}"
           class="px-6 py-2 text-neutral-600 bg-neutral-100 hover:bg-neutral-200 rounded-lg font-medium transition-colors">
            Cancel
        </a>
        <button type="submit"
                class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors">
            Save Normal Values
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
    min-width: 150px;
}

/* Focus states for better UX */
input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>

<script>
// Form validation
document.getElementById('normal-values-form').addEventListener('submit', function(e) {
    const inputs = this.querySelectorAll('input[type="text"]');
    let hasError = false;
    let emptyCount = 0;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            emptyCount++;
        }
    });

    // Warn if many fields are empty
    if (emptyCount > inputs.length / 2) {
        if (!confirm('Banyak field yang masih kosong. Apakah Anda yakin ingin menyimpan?')) {
            e.preventDefault();
            return;
        }
    }

    // Show success message preview
    if (!hasError) {
        // Optional: show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Saving...';
        }
    }
});

// Auto-format common patterns
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input[type="text"]');

    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            let value = this.value.trim();
            if (value) {
                // Auto-add common patterns
                const fieldName = this.name.split('[')[1].split(']')[0];

                // Format numeric ranges
                if (/^\d+\s*-\s*\d+$/.test(value)) {
                    this.value = value.replace(/\s*-\s*/, '-');
                }

                // Add units for specific fields if missing
                const fieldUnits = {
                    'hemoglobin': 'g/dL',
                    'cholesterol': 'mg/dL',
                    'sgot': 'U/L',
                    'sgpt': 'U/L',
                    'nadi': 'x/menit'
                };

                if (fieldUnits[fieldName] && !value.includes(fieldUnits[fieldName])) {
                    if (/^\d/.test(value)) { // Starts with number
                        this.value = value + ' ' + fieldUnits[fieldName];
                    }
                }
            }
        });
    });
});
</script>
@endsection