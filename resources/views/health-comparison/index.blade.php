@extends('layouts.app')

@section('title', 'Health Comparison')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Health Comparison Dashboard</h1>
        <p class="text-gray-600 mt-2">Compare medical checkup results across different years</p>
    </div>

    <!-- Selection Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form id="comparisonForm" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="patient_id" class="block text-sm font-medium text-gray-700">Select Patient</label>
                    <select name="patient_id" id="patient_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Choose a patient...</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ $selectedPatientId == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }} - {{ $patient->share_id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="mcu_records" class="block text-sm font-medium text-gray-700">Select MCU Records (minimum 2)</label>
                    <div id="mcu-records-container" class="mt-1 space-y-2 max-h-60 overflow-y-auto">
                        <!-- Debug info -->
                        <p class="text-xs text-red-500">DEBUG: availableMcuRecords count = {{ isset($availableMcuRecords) ? (is_countable($availableMcuRecords) ? $availableMcuRecords->count() : count($availableMcuRecords)) : 'NOT SET' }}</p>

                        @if($availableMcuRecords && $availableMcuRecords->count() > 0)
                            @foreach($availableMcuRecords as $record)
                                <label class="flex items-center relative group">
                                    <input type="checkbox" name="mcu_records[]" value="{{ $record['id'] }}"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 cursor-pointer">
                                        {{ $record['year'] }} - {{ $record['display_text'] }}
                                        @php
                                            $badgeColor = match($record['type']) {
                                                'pre-employment' => 'blue',
                                                'annual' => 'green',
                                                'recheck' => 'yellow',
                                                default => 'purple'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $badgeColor }}-100 text-{{ $badgeColor }}-800">
                                            {{ ucfirst(str_replace('-', ' ', $record['type'])) }}
                                        </span>
                                    </span>

                                    <!-- Tooltip -->
                                    <div class="absolute left-full ml-2 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-xs rounded-lg px-3 py-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                                        <div class="font-semibold">{{ $record['tooltip_info']['type'] }}</div>
                                        <div>Date: {{ $record['tooltip_info']['date'] }}</div>
                                        <div>Lab: {{ $record['tooltip_info']['lab'] }}</div>
                                        <div>Branch: {{ $record['tooltip_info']['branch'] }}</div>
                                        <!-- Arrow -->
                                        <div class="absolute right-full top-1/2 transform -translate-y-1/2 border-4 border-transparent border-r-gray-900"></div>
                                    </div>
                                </label>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500">Select a patient to see available MCU records</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Compare Results
                </button>
                @if($comparisonData)
                    <a href="{{ route('health-comparison.export', ['patient_id' => $selectedPatientId, 'years' => $selectedYears]) }}"
                       class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        Export PDF
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if($comparisonData)
        <!-- Health Scores Overview -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Health Score Trends</h2>
            <div class="grid grid-cols-{{ count($comparisonData['health_scores']) }} gap-4">
                @foreach($comparisonData['health_scores'] as $year => $score)
                    <div class="text-center">
                        <div class="text-3xl font-bold {{ $score >= 80 ? 'text-green-600' : ($score >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ number_format($score, 1) }}
                        </div>
                        <div class="text-sm text-gray-600">{{ $year }}</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="h-2 rounded-full {{ $score >= 80 ? 'bg-green-600' : ($score >= 60 ? 'bg-yellow-600' : 'bg-red-600') }}"
                                 style="width: {{ $score }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Alerts -->
        @if(count($comparisonData['alerts']) > 0)
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Health Alerts</h2>
                <div class="space-y-3">
                    @foreach($comparisonData['alerts'] as $alert)
                        <div class="flex items-center p-3 rounded-md {{ $alert['severity'] === 'high' ? 'bg-red-50 border border-red-200' : 'bg-yellow-50 border border-yellow-200' }}">
                            <div class="flex-shrink-0">
                                @if($alert['severity'] === 'high')
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium {{ $alert['severity'] === 'high' ? 'text-red-800' : 'text-yellow-800' }}">
                                    {{ $alert['message'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Lab Results Comparison -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Lab Results Comparison</h2>

            @foreach($comparisonData['lab_comparisons'] as $year => $labData)
                @if($loop->first)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parameter</th>
                                    @foreach($comparisonData['years'] as $compYear)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $compYear }}</th>
                                    @endforeach
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trend</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Hematologi -->
                                @if(isset($labData['lab_hematologi']))
                                    <tr class="bg-blue-50">
                                        <td colspan="{{ count($comparisonData['years']) + 2 }}" class="px-6 py-3 text-sm font-medium text-blue-900">
                                            Lab Hematologi
                                        </td>
                                    </tr>
                                    @foreach($labData['lab_hematologi'] as $param => $value)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $param)) }}</td>
                                            @foreach($comparisonData['years'] as $compYear)
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if(isset($comparisonData['lab_comparisons'][$compYear]['lab_hematologi'][$param]))
                                                        {{ $comparisonData['lab_comparisons'][$compYear]['lab_hematologi'][$param] ?? '-' }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if(isset($comparisonData['trends']["lab_hematologi.{$param}"]))
                                                    @php $trend = $comparisonData['trends']["lab_hematologi.{$param}"] @endphp
                                                    <span class="inline-flex items-center">
                                                        @if($trend['direction'] === 'up')
                                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75l3-3m0 0l3 3m-3-3v12.75"></path>
                                                            </svg>
                                                        @elseif($trend['direction'] === 'down')
                                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11.25l-3-3m0 0l-3 3m3-3v12.75"></path>
                                                            </svg>
                                                        @else
                                                            
                                                        @endif
                                                        <span class="ml-1 text-xs">{{ number_format($trend['percent_change'], 1) }}%</span>
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @break
                @endif
            @endforeach
        </div>

        <!-- Vital Signs Comparison -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Vital Signs Comparison</h2>

            @foreach($comparisonData['vital_comparisons'] as $year => $vitalData)
                @if($loop->first)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parameter</th>
                                    @foreach($comparisonData['years'] as $compYear)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $compYear }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if(isset($vitalData['tanda_vital']))
                                    <tr class="bg-green-50">
                                        <td colspan="{{ count($comparisonData['years']) + 1 }}" class="px-6 py-3 text-sm font-medium text-green-900">
                                            Tanda Vital
                                        </td>
                                    </tr>
                                    @foreach($vitalData['tanda_vital'] as $param => $value)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $param)) }}</td>
                                            @foreach($comparisonData['years'] as $compYear)
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if(isset($comparisonData['vital_comparisons'][$compYear]['tanda_vital'][$param]))
                                                        {{ $comparisonData['vital_comparisons'][$compYear]['tanda_vital'][$param] ?? '-' }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @break
                @endif
            @endforeach
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const patientSelect = document.getElementById('patient_id');
    const mcuRecordsContainer = document.getElementById('mcu-records-container');
    const comparisonForm = document.getElementById('comparisonForm');

    patientSelect.addEventListener('change', function() {
        if (this.value) {
            // Fetch available MCU records for selected patient
            fetch(`/health-comparison?patient_id=${this.value}`)
                .then(response => response.text())
                .then(html => {
                    // Parse the response to get the MCU records container
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newMcuContainer = doc.getElementById('mcu-records-container');
                    mcuRecordsContainer.innerHTML = newMcuContainer.innerHTML;
                });
        } else {
            mcuRecordsContainer.innerHTML = '<p class="text-sm text-gray-500">Select a patient to see available MCU records</p>';
        }
    });

    comparisonForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const checkedMcuRecords = formData.getAll('mcu_records[]');

        if (checkedMcuRecords.length < 2) {
            alert('Please select at least 2 MCU records for comparison');
            return;
        }

        // Submit the form normally with MCU records
        const params = new URLSearchParams();
        params.append('patient_id', formData.get('patient_id'));
        checkedMcuRecords.forEach(recordId => params.append('mcu_records[]', recordId));

        window.location.href = '/health-comparison?' + params.toString();
    });
});
</script>
@endsection