@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-primary-700">{{ $patient->name }}</h1>
                <p class="text-accent-600 mt-2">Patient ID: {{ $patient->share_id }} • {{ $patient->departemen }}</p>
            </div>
            <a href="{{ route('patients.index') }}" class="bg-accent-500 hover:bg-accent-600 text-white px-4 py-2 rounded-lg transition-colors">
                Back to Patients
            </a>
        </div>
    </div>

    <!-- Patient Information -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-primary-700 mb-4">Patient Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="text-sm font-medium text-accent-500">Full Name</label>
                <p class="text-accent-900">{{ $patient->name }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-accent-500">Age</label>
                <p class="text-accent-900">{{ $patient->umur }} years</p>
            </div>
            <div>
                <label class="text-sm font-medium text-accent-500">Department</label>
                <p class="text-accent-900">{{ $patient->departemen }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-accent-500">Position</label>
                <p class="text-accent-900">{{ $patient->jabatan }}</p>
            </div>
        </div>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="text-sm font-medium text-accent-500">Smoking</label>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $patient->merokok ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                    {{ $patient->merokok ? 'Yes' : 'No' }}
                </span>
            </div>
            <div>
                <label class="text-sm font-medium text-accent-500">Alcohol</label>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $patient->minum_alkohol ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                    {{ $patient->minum_alkohol ? 'Yes' : 'No' }}
                </span>
            </div>
            <div>
                <label class="text-sm font-medium text-accent-500">Exercise</label>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $patient->olahraga ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $patient->olahraga ? 'Yes' : 'No' }}
                </span>
            </div>
        </div>
        <div class="mt-4">
            <label class="text-sm font-medium text-accent-500">Lifestyle History</label>
            <p class="text-accent-900">{{ $patient->riwayat_kebiasaan_hidup }}</p>
        </div>
    </div>

    <!-- MCU Records -->
    @foreach($patient->orders as $order)
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-primary-700">MCU Record - {{ $order->no_lab }}</h2>
            <span class="text-sm text-accent-500">{{ $order->tgl_order->format('d M Y') }}</span>
        </div>

        <!-- Lab Results Tabs -->
        <div class="border-b border-accent-200 mb-6">
            <nav class="-mb-px flex space-x-8 overflow-x-auto">
                <button onclick="showTab('hematologi-{{ $order->id }}')" class="whitespace-nowrap py-2 px-1 border-b-2 border-primary-500 font-medium text-sm text-primary-600 tab-button">
                    Hematologi
                </button>
                <button onclick="showTab('urine-{{ $order->id }}')" class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-accent-500 hover:text-accent-700 hover:border-accent-300 tab-button">
                    Urine
                </button>
                <button onclick="showTab('liver-{{ $order->id }}')" class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-accent-500 hover:text-accent-700 hover:border-accent-300 tab-button">
                    Liver
                </button>
                <button onclick="showTab('lipid-{{ $order->id }}')" class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-accent-500 hover:text-accent-700 hover:border-accent-300 tab-button">
                    Lipid
                </button>
                <button onclick="showTab('kidney-{{ $order->id }}')" class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-accent-500 hover:text-accent-700 hover:border-accent-300 tab-button">
                    Kidney
                </button>
                <button onclick="showTab('glucose-{{ $order->id }}')" class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-accent-500 hover:text-accent-700 hover:border-accent-300 tab-button">
                    Glucose
                </button>
                <button onclick="showTab('vital-{{ $order->id }}')" class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-accent-500 hover:text-accent-700 hover:border-accent-300 tab-button">
                    Vital Signs
                </button>
            </nav>
        </div>

        <!-- Lab Results Content -->
        @if($order->labHematologi)
        <div id="hematologi-{{ $order->id }}" class="tab-content">
            <h3 class="text-lg font-semibold text-primary-700 mb-3">Hematology Results</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                <div class="bg-accent-50 p-3 rounded-lg">
                    <span class="font-medium text-accent-600">Hemoglobin:</span>
                    <span class="text-accent-900">{{ $order->labHematologi->hemoglobin ?? 'N/A' }}</span>
                </div>
                <div class="bg-accent-50 p-3 rounded-lg">
                    <span class="font-medium text-accent-600">Leukosit:</span>
                    <span class="text-accent-900">{{ $order->labHematologi->leukosit ?? 'N/A' }}</span>
                </div>
                <div class="bg-accent-50 p-3 rounded-lg">
                    <span class="font-medium text-accent-600">Trombosit:</span>
                    <span class="text-accent-900">{{ $order->labHematologi->trombosit ?? 'N/A' }}</span>
                </div>
                <div class="bg-accent-50 p-3 rounded-lg">
                    <span class="font-medium text-accent-600">Hematokrit:</span>
                    <span class="text-accent-900">{{ $order->labHematologi->hematokrit ?? 'N/A' }}</span>
                </div>
                <div class="bg-accent-50 p-3 rounded-lg">
                    <span class="font-medium text-accent-600">Eritrosit:</span>
                    <span class="text-accent-900">{{ $order->labHematologi->erytrosit ?? 'N/A' }}</span>
                </div>
                <div class="bg-accent-50 p-3 rounded-lg">
                    <span class="font-medium text-accent-600">LED:</span>
                    <span class="text-accent-900">{{ $order->labHematologi->laju_endap_darah ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
        @endif

        @if($order->labUrine)
        <div id="urine-{{ $order->id }}" class="tab-content hidden">
            <h3 class="text-lg font-semibold text-primary-700 mb-3">Urine Analysis</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                <div class="bg-accent-50 p-3 rounded-lg">
                    <span class="font-medium text-accent-600">Color:</span>
                    <span class="text-accent-900">{{ $order->labUrine->warna ?? 'N/A' }}</span>
                </div>
                <div class="bg-accent-50 p-3 rounded-lg">
                    <span class="font-medium text-accent-600">Clarity:</span>
                    <span class="text-accent-900">{{ $order->labUrine->kejernihan ?? 'N/A' }}</span>
                </div>
                <div class="bg-accent-50 p-3 rounded-lg">
                    <span class="font-medium text-accent-600">pH:</span>
                    <span class="text-accent-900">{{ $order->labUrine->ph ?? 'N/A' }}</span>
                </div>
                <div class="bg-accent-50 p-3 rounded-lg">
                    <span class="font-medium text-accent-600">Protein:</span>
                    <span class="text-accent-900">{{ $order->labUrine->protein ?? 'N/A' }}</span>
                </div>
                <div class="bg-accent-50 p-3 rounded-lg">
                    <span class="font-medium text-accent-600">Glucose:</span>
                    <span class="text-accent-900">{{ $order->labUrine->glukosa ?? 'N/A' }}</span>
                </div>
                <div class="bg-accent-50 p-3 rounded-lg">
                    <span class="font-medium text-accent-600">Blood:</span>
                    <span class="text-accent-900">{{ $order->labUrine->darah ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
        @endif

        @if($order->pemeriksaanVital)
        <div id="vital-{{ $order->id }}" class="tab-content hidden">
            <h3 class="text-lg font-semibold text-primary-700 mb-3">Vital Signs & Physical Examination</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                <div class="bg-accent-50 p-3 rounded-lg">
                    <span class="font-medium text-accent-600">Weight:</span>
                    <span class="text-accent-900">{{ $order->pemeriksaanVital->berat_badan ?? 'N/A' }}</span>
                </div>
                <div class="bg-accent-50 p-3 rounded-lg">
                    <span class="font-medium text-accent-600">Height:</span>
                    <span class="text-accent-900">{{ $order->pemeriksaanVital->tinggi_badan ?? 'N/A' }}</span>
                </div>
                <div class="bg-accent-50 p-3 rounded-lg">
                    <span class="font-medium text-accent-600">BMI:</span>
                    <span class="text-accent-900">{{ $order->pemeriksaanVital->bmi ?? 'N/A' }}</span>
                </div>
            </div>
            @if($order->pemeriksaanVital->kesimpulan_fisik)
            <div class="mt-4 bg-accent-50 p-4 rounded-lg">
                <span class="font-medium text-accent-600">Physical Examination Conclusion:</span>
                <p class="text-accent-900 mt-1">{{ $order->pemeriksaanVital->kesimpulan_fisik }}</p>
            </div>
            @endif
            @if($order->pemeriksaanVital->rekomendasi)
            <div class="mt-4 bg-primary-50 p-4 rounded-lg">
                <span class="font-medium text-primary-600">Recommendations:</span>
                <p class="text-primary-900 mt-1">{{ $order->pemeriksaanVital->rekomendasi }}</p>
            </div>
            @endif
        </div>
        @endif

        <!-- Add other lab results here with similar structure -->
    </div>
    @endforeach
</div>

<script>
function showTab(tabId) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => content.classList.add('hidden'));

    // Remove active state from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('border-primary-500', 'text-primary-600');
        button.classList.add('border-transparent', 'text-accent-500');
    });

    // Show selected tab content
    document.getElementById(tabId).classList.remove('hidden');

    // Add active state to clicked button
    event.target.classList.remove('border-transparent', 'text-accent-500');
    event.target.classList.add('border-primary-500', 'text-primary-600');
}
</script>
@endsection