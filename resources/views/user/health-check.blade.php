@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-primary-700">Health Check Results</h1>
                <p class="text-accent-600 mt-2">{{ $patient->name }} • {{ $patient->share_id }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="bg-accent-500 hover:bg-accent-600 text-white px-4 py-2 rounded-lg transition-colors">
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Patient Summary -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-primary-700 mb-4">Patient Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-accent-50 rounded-lg">
                <div class="text-2xl font-bold text-primary-600">{{ $patient->umur }}</div>
                <div class="text-sm text-accent-500">Years Old</div>
            </div>
            <div class="text-center p-4 bg-accent-50 rounded-lg">
                <div class="text-lg font-semibold text-secondary-600">{{ $patient->departemen }}</div>
                <div class="text-sm text-accent-500">Department</div>
            </div>
            <div class="text-center p-4 bg-accent-50 rounded-lg">
                <div class="text-lg font-semibold text-secondary-600">{{ $patient->jabatan }}</div>
                <div class="text-sm text-accent-500">Position</div>
            </div>
            <div class="text-center p-4 bg-accent-50 rounded-lg">
                <div class="text-lg font-semibold text-green-600">{{ $patient->orders->count() }}</div>
                <div class="text-sm text-accent-500">MCU Records</div>
            </div>
        </div>
    </div>

    <!-- Health Records -->
    @foreach($patient->orders as $order)
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-semibold text-primary-700">Medical Check-Up Report</h2>
                <p class="text-accent-600">{{ $order->tgl_order->format('d F Y') }} • {{ $order->no_lab }} • {{ $order->cabang }}</p>
            </div>
            <span class="bg-primary-100 text-primary-800 px-3 py-1 rounded-full text-sm font-medium">
                Complete
            </span>
        </div>

        <!-- Health Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white p-4 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <div>
                        <div class="text-lg font-semibold">Overall Health</div>
                        <div class="text-sm opacity-90">Good Condition</div>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-secondary-500 to-secondary-600 text-white p-4 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <div>
                        <div class="text-lg font-semibold">Lab Results</div>
                        <div class="text-sm opacity-90">Within Normal Range</div>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <div class="text-lg font-semibold">Vital Signs</div>
                        <div class="text-sm opacity-90">Stable</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Results Tabs -->
        <div class="border-b border-accent-200 mb-6">
            <nav class="-mb-px flex space-x-8 overflow-x-auto">
                <button onclick="showUserTab('vital-{{ $order->id }}')" class="whitespace-nowrap py-2 px-1 border-b-2 border-primary-500 font-medium text-sm text-primary-600 user-tab-button">
                    Vital Signs
                </button>
                <button onclick="showUserTab('blood-{{ $order->id }}')" class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-accent-500 hover:text-accent-700 hover:border-accent-300 user-tab-button">
                    Blood Test
                </button>
                <button onclick="showUserTab('urine-{{ $order->id }}')" class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-accent-500 hover:text-accent-700 hover:border-accent-300 user-tab-button">
                    Urine Test
                </button>
                <button onclick="showUserTab('physical-{{ $order->id }}')" class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-accent-500 hover:text-accent-700 hover:border-accent-300 user-tab-button">
                    Physical Exam
                </button>
            </nav>
        </div>

        <!-- Vital Signs Tab -->
        @if($order->pemeriksaanVital)
        <div id="vital-{{ $order->id }}" class="user-tab-content">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-400">
                    <div class="text-blue-800 font-semibold">Weight</div>
                    <div class="text-2xl font-bold text-blue-900">{{ $order->pemeriksaanVital->berat_badan ?? 'N/A' }}</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-400">
                    <div class="text-green-800 font-semibold">Height</div>
                    <div class="text-2xl font-bold text-green-900">{{ $order->pemeriksaanVital->tinggi_badan ?? 'N/A' }}</div>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg border-l-4 border-purple-400">
                    <div class="text-purple-800 font-semibold">BMI</div>
                    <div class="text-2xl font-bold text-purple-900">{{ $order->pemeriksaanVital->bmi ?? 'N/A' }}</div>
                </div>
            </div>

            @if($order->tandaVital)
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-400">
                    <div class="text-red-800 font-semibold">Blood Pressure</div>
                    <div class="text-lg font-bold text-red-900">{{ $order->tandaVital->tekanan_darah ?? 'N/A' }}</div>
                </div>
                <div class="bg-orange-50 p-4 rounded-lg border-l-4 border-orange-400">
                    <div class="text-orange-800 font-semibold">Heart Rate</div>
                    <div class="text-lg font-bold text-orange-900">{{ $order->tandaVital->nadi ?? 'N/A' }}</div>
                </div>
                <div class="bg-teal-50 p-4 rounded-lg border-l-4 border-teal-400">
                    <div class="text-teal-800 font-semibold">Breathing</div>
                    <div class="text-lg font-bold text-teal-900">{{ $order->tandaVital->pernapasan ?? 'N/A' }}</div>
                </div>
                <div class="bg-indigo-50 p-4 rounded-lg border-l-4 border-indigo-400">
                    <div class="text-indigo-800 font-semibold">Temperature</div>
                    <div class="text-lg font-bold text-indigo-900">{{ $order->tandaVital->suhu_tubuh ?? 'N/A' }}</div>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Blood Test Tab -->
        @if($order->labHematologi)
        <div id="blood-{{ $order->id }}" class="user-tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white border border-accent-200 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-accent-700">Hemoglobin</span>
                        <span class="text-lg font-bold text-primary-600">{{ $order->labHematologi->hemoglobin ?? 'N/A' }}</span>
                    </div>
                    <div class="text-sm text-accent-500 mt-1">Normal: 12-16 g/dL</div>
                </div>
                <div class="bg-white border border-accent-200 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-accent-700">White Blood Cells</span>
                        <span class="text-lg font-bold text-primary-600">{{ $order->labHematologi->leukosit ?? 'N/A' }}</span>
                    </div>
                    <div class="text-sm text-accent-500 mt-1">Normal: 4,000-11,000/μL</div>
                </div>
                <div class="bg-white border border-accent-200 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-accent-700">Platelets</span>
                        <span class="text-lg font-bold text-primary-600">{{ $order->labHematologi->trombosit ?? 'N/A' }}</span>
                    </div>
                    <div class="text-sm text-accent-500 mt-1">Normal: 150,000-450,000/μL</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Physical Exam Tab -->
        @if($order->pemeriksaanVital && ($order->pemeriksaanVital->kesimpulan_fisik || $order->pemeriksaanVital->rekomendasi))
        <div id="physical-{{ $order->id }}" class="user-tab-content hidden">
            @if($order->pemeriksaanVital->kesimpulan_fisik)
            <div class="bg-accent-50 p-6 rounded-lg mb-4">
                <h4 class="font-semibold text-accent-700 mb-3">Physical Examination Summary</h4>
                <p class="text-accent-900 leading-relaxed">{{ $order->pemeriksaanVital->kesimpulan_fisik }}</p>
            </div>
            @endif

            @if($order->pemeriksaanVital->rekomendasi)
            <div class="bg-primary-50 p-6 rounded-lg">
                <h4 class="font-semibold text-primary-700 mb-3">Medical Recommendations</h4>
                <div class="text-primary-900 leading-relaxed whitespace-pre-line">{{ $order->pemeriksaanVital->rekomendasi }}</div>
            </div>
            @endif
        </div>
        @endif
    </div>
    @endforeach

    <!-- Health Tips -->
    <div class="bg-gradient-to-r from-secondary-500 to-secondary-600 rounded-xl shadow-lg p-6 text-white">
        <h3 class="text-xl font-semibold mb-4">Health Tips</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-start">
                <svg class="w-6 h-6 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <div class="font-medium">Regular Exercise</div>
                    <div class="text-sm opacity-90">Maintain at least 150 minutes of moderate activity per week</div>
                </div>
            </div>
            <div class="flex items-start">
                <svg class="w-6 h-6 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <div>
                    <div class="font-medium">Balanced Diet</div>
                    <div class="text-sm opacity-90">Include plenty of fruits, vegetables, and whole grains</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showUserTab(tabId) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.user-tab-content');
    tabContents.forEach(content => content.classList.add('hidden'));

    // Remove active state from all tab buttons
    const tabButtons = document.querySelectorAll('.user-tab-button');
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