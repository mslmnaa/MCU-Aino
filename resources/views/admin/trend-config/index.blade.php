@extends('layouts.admin')

@section('page-title', 'Configure Trend')
@section('page-subtitle', $patient->name . ' • ID: ' . $patient->share_id)

@section('content')
<div class="mb-6">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('patients.index') }}" class="inline-flex items-center text-sm font-medium text-neutral-700 hover:text-primary-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Patients
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('patients.show', $patient->id) }}" class="ml-1 text-sm font-medium text-neutral-700 hover:text-primary-600 md:ml-2">
                        {{ $patient->name }}
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-neutral-500 md:ml-2">Configure Trend</span>
                </div>
            </li>
        </ol>
    </nav>
</div>

<!-- Patient Info Card -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex items-center space-x-4">
        <div class="flex-shrink-0">
            @if($patient->profile_photo || $patient->user?->profile_photo)
                <img src="{{ asset('storage/' . ($patient->profile_photo ?? $patient->user->profile_photo)) }}"
                     alt="{{ $patient->name }}"
                     class="w-16 h-16 rounded-full object-cover border-2 border-primary-300">
            @else
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center border-2 border-primary-300">
                    <span class="text-white text-lg font-bold">
                        {{ strtoupper(substr($patient->name, 0, 2)) }}
                    </span>
                </div>
            @endif
        </div>
        <div class="flex-grow">
            <h3 class="text-xl font-bold text-neutral-800">{{ $patient->name }}</h3>
            <div class="flex space-x-4 mt-1 text-sm text-neutral-600">
                <span>{{ $patient->jabatan }}</span>
                <span>•</span>
                <span>{{ $patient->departemen }}</span>
                <span>•</span>
                <span>{{ $patient->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                <span>•</span>
                <span>{{ $patient->umur }} tahun</span>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h4 class="text-lg font-semibold text-neutral-800 mb-4">Quick Actions</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Copy from Patient -->
        <div class="border border-neutral-200 rounded-lg p-4">
            <label class="block text-sm font-medium text-neutral-700 mb-2">Copy from Patient</label>
            <select id="copyFromPatient" class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 mb-2">
                <option value="">Choose a patient...</option>
                @foreach($patientsWithConfig as $p)
                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->share_id }})</option>
                @endforeach
            </select>
            <button onclick="copyFromPatient()" class="w-full bg-neutral-600 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                Copy Configuration
            </button>
        </div>

        <!-- Bulk Action -->
        <div class="border border-neutral-200 rounded-lg p-4">
            <label class="block text-sm font-medium text-neutral-700 mb-2">Bulk Set All Parameters</label>
            <div class="grid grid-cols-2 gap-2 mb-2">
                <select id="bulkAbove" class="px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                    <option value="red">🔴 Red (Above)</option>
                    <option value="green">🟢 Green (Above)</option>
                </select>
                <select id="bulkBelow" class="px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                    <option value="red">🔴 Red (Below)</option>
                    <option value="green">🟢 Green (Below)</option>
                </select>
            </div>
            <button onclick="bulkSet()" class="w-full bg-neutral-600 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                Apply to All
            </button>
        </div>
    </div>
    <p class="text-xs text-neutral-500 mt-3">💡 Quick tip: Copy configuration from similar patients or use bulk set to configure all parameters at once</p>
</div>

<!-- Search & Filter -->
<div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <div class="flex items-center space-x-4">
        <div class="flex-1">
            <input type="text" id="searchParameter" placeholder="Search parameter..."
                   class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                   onkeyup="filterParameters()">
        </div>
        <div>
            <select id="filterExamType" class="px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500" onchange="filterParameters()">
                <option value="">All Exam Types</option>
                <option value="pemeriksaan-vital">Pemeriksaan Vital</option>
                <option value="tanda-vital">Tanda Vital</option>
                <option value="hematologi">Hematologi</option>
                <option value="analisis-urine">Analisis Urine</option>
                <option value="fungsi-hati">Fungsi Hati</option>
                <option value="profil-lemak">Profil Lemak</option>
                <option value="fungsi-ginjal">Fungsi Ginjal</option>
                <option value="gula-darah">Gula Darah</option>
                <option value="penanda-tumor">Penanda Tumor</option>
            </select>
        </div>
    </div>
</div>

<!-- Configuration Cards -->
<div id="configContainer">
    @php
        $examCategories = [
            'Pemeriksaan Vital' => [
                'id' => 'pemeriksaan-vital',
                'color' => 'primary',
                'params' => [
                    ['name' => 'berat_badan', 'label' => 'Berat Badan', 'exam_type' => 'pemeriksaanVital'],
                    ['name' => 'tinggi_badan', 'label' => 'Tinggi Badan', 'exam_type' => 'pemeriksaanVital'],
                    ['name' => 'lingkar_perut', 'label' => 'Lingkar Perut', 'exam_type' => 'pemeriksaanVital'],
                    ['name' => 'bmi', 'label' => 'Indeks Massa Tubuh (BMI)', 'exam_type' => 'pemeriksaanVital'],
                    ['name' => 'klasifikasi_tekanan_darah', 'label' => 'Klasifikasi Tekanan Darah', 'exam_type' => 'pemeriksaanVital'],
                    ['name' => 'klasifikasi_od', 'label' => 'Klasifikasi OD', 'exam_type' => 'pemeriksaanVital'],
                    ['name' => 'klasifikasi_os', 'label' => 'Klasifikasi OS', 'exam_type' => 'pemeriksaanVital'],
                    ['name' => 'persepsi_warna', 'label' => 'Persepsi Warna', 'exam_type' => 'pemeriksaanVital'],
                ]
            ],
            'Tanda Vital' => [
                'id' => 'tanda-vital',
                'color' => 'secondary',
                'params' => [
                    ['name' => 'tekanan_darah', 'label' => 'Tekanan Darah', 'exam_type' => 'tandaVital'],
                    ['name' => 'nadi', 'label' => 'Denyut Nadi', 'exam_type' => 'tandaVital'],
                    ['name' => 'pernapasan', 'label' => 'Frekuensi Napas', 'exam_type' => 'tandaVital'],
                    ['name' => 'suhu_tubuh', 'label' => 'Suhu Tubuh', 'exam_type' => 'tandaVital'],
                ]
            ],
            'Hematologi' => [
                'id' => 'hematologi',
                'color' => 'cream',
                'params' => [
                    ['name' => 'hemoglobin', 'label' => 'Hemoglobin', 'exam_type' => 'labHematologi'],
                    ['name' => 'erytrosit', 'label' => 'Sel Darah Merah', 'exam_type' => 'labHematologi'],
                    ['name' => 'hematokrit', 'label' => 'Hematokrit', 'exam_type' => 'labHematologi'],
                    ['name' => 'mcv', 'label' => 'MCV', 'exam_type' => 'labHematologi'],
                    ['name' => 'mch', 'label' => 'MCH', 'exam_type' => 'labHematologi'],
                    ['name' => 'mchc', 'label' => 'MCHC', 'exam_type' => 'labHematologi'],
                    ['name' => 'rdw', 'label' => 'RDW', 'exam_type' => 'labHematologi'],
                    ['name' => 'leukosit', 'label' => 'Sel Darah Putih', 'exam_type' => 'labHematologi'],
                    ['name' => 'eosinofil', 'label' => 'Eosinofil', 'exam_type' => 'labHematologi'],
                    ['name' => 'basofil', 'label' => 'Basofil', 'exam_type' => 'labHematologi'],
                    ['name' => 'neutrofil_batang', 'label' => 'Neutrofil Batang', 'exam_type' => 'labHematologi'],
                    ['name' => 'neutrofil_segmen', 'label' => 'Neutrofil Segmen', 'exam_type' => 'labHematologi'],
                    ['name' => 'limfosit', 'label' => 'Limfosit', 'exam_type' => 'labHematologi'],
                    ['name' => 'monosit', 'label' => 'Monosit', 'exam_type' => 'labHematologi'],
                    ['name' => 'trombosit', 'label' => 'Trombosit', 'exam_type' => 'labHematologi'],
                    ['name' => 'laju_endap_darah', 'label' => 'Laju Endap Darah', 'exam_type' => 'labHematologi'],
                ]
            ],
            'Analisis Urine' => [
                'id' => 'analisis-urine',
                'color' => 'neutral',
                'params' => [
                    ['name' => 'warna', 'label' => 'Warna', 'exam_type' => 'labUrine'],
                    ['name' => 'kejernihan', 'label' => 'Kejernihan', 'exam_type' => 'labUrine'],
                    ['name' => 'bj', 'label' => 'Berat Jenis', 'exam_type' => 'labUrine'],
                    ['name' => 'ph', 'label' => 'pH', 'exam_type' => 'labUrine'],
                    ['name' => 'protein', 'label' => 'Protein', 'exam_type' => 'labUrine'],
                    ['name' => 'glukosa', 'label' => 'Glukosa', 'exam_type' => 'labUrine'],
                    ['name' => 'keton', 'label' => 'Keton', 'exam_type' => 'labUrine'],
                    ['name' => 'bilirubin', 'label' => 'Bilirubin', 'exam_type' => 'labUrine'],
                    ['name' => 'urobilinogen', 'label' => 'Urobilinogen', 'exam_type' => 'labUrine'],
                    ['name' => 'nitrit', 'label' => 'Nitrit', 'exam_type' => 'labUrine'],
                    ['name' => 'darah', 'label' => 'Darah', 'exam_type' => 'labUrine'],
                    ['name' => 'lekosit_esterase', 'label' => 'Lekosit Esterase', 'exam_type' => 'labUrine'],
                    ['name' => 'eritrosit_sedimen', 'label' => 'Sedimen Eritrosit', 'exam_type' => 'labUrine'],
                    ['name' => 'lekosit_sedimen', 'label' => 'Sedimen Leukosit', 'exam_type' => 'labUrine'],
                    ['name' => 'epitel_sedimen', 'label' => 'Sedimen Epitel', 'exam_type' => 'labUrine'],
                    ['name' => 'silinder_sedimen', 'label' => 'Sedimen Silinder', 'exam_type' => 'labUrine'],
                    ['name' => 'kristal_sedimen', 'label' => 'Sedimen Kristal', 'exam_type' => 'labUrine'],
                    ['name' => 'lain_lain_sedimen', 'label' => 'Sedimen Lain-lain', 'exam_type' => 'labUrine'],
                ]
            ],
            'Fungsi Hati' => [
                'id' => 'fungsi-hati',
                'color' => 'primary',
                'params' => [
                    ['name' => 'sgot', 'label' => 'SGOT', 'exam_type' => 'labFungsiLiver'],
                    ['name' => 'sgpt', 'label' => 'SGPT', 'exam_type' => 'labFungsiLiver'],
                ]
            ],
            'Profil Lemak' => [
                'id' => 'profil-lemak',
                'color' => 'secondary',
                'params' => [
                    ['name' => 'cholesterol', 'label' => 'Kolesterol Total', 'exam_type' => 'labProfilLemak'],
                    ['name' => 'trigliserida', 'label' => 'Trigliserida', 'exam_type' => 'labProfilLemak'],
                    ['name' => 'hdl_cholesterol', 'label' => 'Kolesterol HDL', 'exam_type' => 'labProfilLemak'],
                    ['name' => 'ldl_cholesterol', 'label' => 'Kolesterol LDL', 'exam_type' => 'labProfilLemak'],
                ]
            ],
            'Fungsi Ginjal' => [
                'id' => 'fungsi-ginjal',
                'color' => 'cream',
                'params' => [
                    ['name' => 'ureum', 'label' => 'Ureum', 'exam_type' => 'labFungsiGinjal'],
                    ['name' => 'creatinin', 'label' => 'Kreatinin', 'exam_type' => 'labFungsiGinjal'],
                    ['name' => 'asam_urat', 'label' => 'Asam Urat', 'exam_type' => 'labFungsiGinjal'],
                ]
            ],
            'Gula Darah' => [
                'id' => 'gula-darah',
                'color' => 'primary',
                'params' => [
                    ['name' => 'glukosa_puasa', 'label' => 'Glukosa Puasa', 'exam_type' => 'labGlukosaDarah'],
                    ['name' => 'glukosa_2jam_pp', 'label' => 'Glukosa 2 Jam PP', 'exam_type' => 'labGlukosaDarah'],
                    ['name' => 'hba1c', 'label' => 'HbA1c', 'exam_type' => 'labGlukosaDarah'],
                ]
            ],
            'Penanda Tumor' => [
                'id' => 'penanda-tumor',
                'color' => 'secondary',
                'params' => [
                    ['name' => 'hbsag', 'label' => 'HBsAg', 'exam_type' => 'labPenandaTumor'],
                    ['name' => 'cea', 'label' => 'CEA', 'exam_type' => 'labPenandaTumor'],
                ]
            ],
        ];
    @endphp

    @foreach($examCategories as $categoryName => $category)
        <div class="bg-white rounded-lg shadow-md mb-6 exam-category" data-category-id="{{ $category['id'] }}">
            <div class="bg-{{ $category['color'] }}-600 px-6 py-4 rounded-t-lg">
                <h3 class="text-lg font-semibold text-white">{{ $categoryName }}</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($category['params'] as $param)
                        @php
                            $existingConfig = $configs->firstWhere('parameter_name', $param['name']);
                            $aboveValue = $existingConfig?->trend_above_normal ?? 'red';
                            $belowValue = $existingConfig?->trend_below_normal ?? 'red';
                        @endphp
                        <div class="border border-neutral-200 rounded-lg p-4 parameter-item"
                             data-param-name="{{ strtolower($param['label']) }}"
                             data-category="{{ $category['id'] }}">
                            <div class="mb-3">
                                <p class="font-medium text-neutral-800">{{ $param['label'] }}</p>
                                <p class="text-xs text-neutral-500 mt-1">{{ $param['exam_type'] }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs text-neutral-600 mb-1">Above Normal</label>
                                    <select class="config-select w-full px-2 py-1 border border-neutral-300 rounded text-sm"
                                            data-param="{{ $param['name'] }}"
                                            data-exam="{{ $param['exam_type'] }}"
                                            data-type="above">
                                        <option value="red" {{ $aboveValue === 'red' ? 'selected' : '' }}>🔴 Red</option>
                                        <option value="green" {{ $aboveValue === 'green' ? 'selected' : '' }}>🟢 Green</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs text-neutral-600 mb-1">Below Normal</label>
                                    <select class="config-select w-full px-2 py-1 border border-neutral-300 rounded text-sm"
                                            data-param="{{ $param['name'] }}"
                                            data-exam="{{ $param['exam_type'] }}"
                                            data-type="below">
                                        <option value="red" {{ $belowValue === 'red' ? 'selected' : '' }}>🔴 Red</option>
                                        <option value="green" {{ $belowValue === 'green' ? 'selected' : '' }}>🟢 Green</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Action Buttons -->
<div class="bg-white rounded-lg shadow-md p-6 sticky bottom-0 z-10">
    <div class="flex justify-between items-center">
        <a href="{{ route('patients.show', $patient->id) }}" class="px-6 py-2 bg-neutral-200 hover:bg-neutral-300 text-neutral-700 rounded-lg font-medium transition-colors">
            Cancel
        </a>
        <button onclick="saveConfig()" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors">
            Save Configuration
        </button>
    </div>
</div>

<script>
const patientId = {{ $patient->id }};

// Filter function
function filterParameters() {
    const searchTerm = document.getElementById('searchParameter').value.toLowerCase();
    const examTypeFilter = document.getElementById('filterExamType').value;

    const categories = document.querySelectorAll('.exam-category');

    categories.forEach(category => {
        const categoryId = category.dataset.categoryId;
        const parameters = category.querySelectorAll('.parameter-item');
        let hasVisibleParam = false;

        parameters.forEach(param => {
            const paramName = param.dataset.paramName;
            const paramCategory = param.dataset.category;

            const matchesSearch = paramName.includes(searchTerm);
            const matchesCategory = !examTypeFilter || paramCategory === examTypeFilter;

            if (matchesSearch && matchesCategory) {
                param.style.display = 'block';
                hasVisibleParam = true;
            } else {
                param.style.display = 'none';
            }
        });

        // Hide category if no visible parameters
        category.style.display = hasVisibleParam ? 'block' : 'none';
    });
}

// Copy from patient
async function copyFromPatient() {
    const sourcePatientId = document.getElementById('copyFromPatient').value;

    if (!sourcePatientId) {
        alert('Please select a patient');
        return;
    }

    try {
        const response = await fetch(`/patients/${patientId}/trend-config/copy`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ source_patient_id: sourcePatientId })
        });

        const result = await response.json();

        if (result.success) {
            alert(result.message);
            location.reload();
        } else {
            alert('Failed: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to copy configuration');
    }
}

// Bulk set
function bulkSet() {
    const aboveValue = document.getElementById('bulkAbove').value;
    const belowValue = document.getElementById('bulkBelow').value;

    if (!confirm(`Set all parameters to:\n- Above Normal: ${aboveValue === 'red' ? 'Red 🔴' : 'Green 🟢'}\n- Below Normal: ${belowValue === 'red' ? 'Red 🔴' : 'Green 🟢'}`)) {
        return;
    }

    document.querySelectorAll('.config-select').forEach(select => {
        if (select.dataset.type === 'above') {
            select.value = aboveValue;
        } else {
            select.value = belowValue;
        }
    });

    alert('Bulk action applied! Don\'t forget to save.');
}

// Save configuration
async function saveConfig() {
    const selects = document.querySelectorAll('.config-select');
    const configMap = {};

    selects.forEach(select => {
        const paramName = select.dataset.param;
        const examType = select.dataset.exam;
        const type = select.dataset.type;
        const value = select.value;

        if (!configMap[paramName]) {
            configMap[paramName] = {
                parameter_name: paramName,
                exam_type: examType,
                trend_above_normal: 'red',
                trend_below_normal: 'red',
                notes: null
            };
        }

        if (type === 'above') {
            configMap[paramName].trend_above_normal = value;
        } else {
            configMap[paramName].trend_below_normal = value;
        }
    });

    const configs = Object.values(configMap);

    try {
        const response = await fetch(`/patients/${patientId}/trend-config`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ configs })
        });

        const result = await response.json();

        if (result.success) {
            alert('Configuration saved successfully!');
            window.location.href = '{{ route("patients.show", $patient->id) }}';
        } else {
            alert('Failed to save configuration');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to save configuration');
    }
}
</script>
@endsection
