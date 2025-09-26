@extends('layouts.admin')

@section('page-title', 'Preview Import Data MCU')
@section('page-subtitle', 'Validasi dan konfirmasi data sebelum import')

@section('content')

@if(!isset($headers) || !isset($previewRows))
    <div class="max-w-4xl mx-auto">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <h3 class="font-bold">Data Preview Tidak Ditemukan</h3>
            <p>Silakan upload file kembali untuk melakukan preview.</p>
        </div>
        <a href="{{ route('admin.import') }}" class="bg-primary-600 text-white px-6 py-3 rounded-lg">
            Kembali ke Upload
        </a>
    </div>
@else
<div class="max-w-full mx-auto">
    <!-- Matching Summary -->
    @if(isset($matchingResults))
        @php
            $newCount = collect($matchingResults)->where('status', 'new')->count();
            $existingCount = collect($matchingResults)->where('status', 'existing')->count();
            $insufficientCount = collect($matchingResults)->where('status', 'insufficient_data')->count();
            $invalidDateCount = collect($matchingResults)->where('status', 'invalid_date')->count();
        @endphp
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">👥 Import Preview Summary</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $newCount }}</div>
                    <div class="text-sm text-green-700">New Accounts</div>
                    <div class="text-xs text-green-600 mt-1">Will create credentials</div>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $existingCount }}</div>
                    <div class="text-sm text-blue-700">Existing Accounts</div>
                    <div class="text-xs text-blue-600 mt-1">Will update data</div>
                </div>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-gray-600">{{ $insufficientCount }}</div>
                    <div class="text-sm text-gray-700">Incomplete Data</div>
                    <div class="text-xs text-gray-600 mt-1">Missing name/birth date</div>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $invalidDateCount }}</div>
                    <div class="text-sm text-red-700">Invalid Dates</div>
                    <div class="text-xs text-red-600 mt-1">Cannot parse birth date</div>
                </div>
            </div>
        </div>
    @endif

    <!-- Validation Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Valid Rows -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">Data Valid</p>
                    <p class="text-2xl font-bold text-green-900">{{ $validationResults['valid_rows'] }}</p>
                </div>
            </div>
        </div>

        <!-- Invalid Rows -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">Data Error</p>
                    <p class="text-2xl font-bold text-red-900">{{ $validationResults['invalid_rows'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Rows -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-800">Total Data</p>
                    <p class="text-2xl font-bold text-blue-900">{{ count($previewRows) }}</p>
                    <p class="text-xs text-blue-600">(Preview 10 baris pertama)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Start -->
    <form id="import-form" action="{{ route('admin.import.process') }}" method="POST">
        @csrf
        <input type="hidden" name="temp_file" value="{{ $tempFileName }}">

        <!-- Add column mapping as hidden fields -->
        @foreach($columnMapping as $field => $columnIndex)
            <input type="hidden" name="column_mapping[{{ $field }}]" value="{{ $columnIndex }}">
        @endforeach



    <!-- Preview Data Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Preview Data (10 baris pertama)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        @foreach($headers as $header)
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                        @endforeach
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Match Info</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($previewRows as $index => $row)
                    <tr class="{{ isset($validationResults['errors'][$index]) ? 'bg-red-50' : 'bg-green-50' }}">
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $index + 1 }}</td>
                        @foreach($headers as $headerIndex => $header)
                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{ $row[$headerIndex] ?? '-' }}
                        </td>
                        @endforeach
                        <td class="px-4 py-2 text-sm">
                            @if(isset($validationResults['errors'][$index]))
                                <div class="flex items-center text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-xs">Error</span>
                                </div>
                                <div class="mt-1">
                                    @foreach($validationResults['errors'][$index] as $error)
                                        <div class="text-xs text-red-600">• {{ $error }}</div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex items-center text-green-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-xs">Valid</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-sm">
                            @if(isset($matchingResults[$index]))
                                @php $matchInfo = $matchingResults[$index]; @endphp
                                @if($matchInfo['status'] === 'existing')
                                    <div class="flex items-center text-blue-600">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-xs font-medium">EXISTING</span>
                                    </div>
                                    <div class="text-xs text-blue-700 mt-1">
                                        {{ $matchInfo['patient']->email ?? 'No email' }}<br>
                                        <span class="text-blue-600">Will update data</span>
                                    </div>
                                @elseif($matchInfo['status'] === 'new')
                                    <div class="flex items-center text-green-600">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-xs font-medium">NEW</span>
                                    </div>
                                    <div class="text-xs text-green-700 mt-1">
                                        Will create account
                                    </div>
                                @elseif($matchInfo['status'] === 'insufficient_data')
                                    <div class="flex items-center text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-xs">INCOMPLETE</span>
                                    </div>
                                    <div class="text-xs text-gray-600 mt-1">
                                        Missing name/birth date
                                    </div>
                                @elseif($matchInfo['status'] === 'invalid_date')
                                    <div class="flex items-center text-red-500">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-xs">INVALID DATE</span>
                                    </div>
                                    <div class="text-xs text-red-600 mt-1">
                                        Cannot parse birth date
                                    </div>
                                @endif
                            @else
                                <div class="text-xs text-gray-400">
                                    No name/birth date mapping
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between items-center bg-white rounded-lg shadow-md p-6">
        <a href="{{ route('admin.import') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
            ← Kembali
        </a>

        <div class="flex space-x-4">
            @if($validationResults['invalid_rows'] > 0)
            <div class="text-sm text-red-600 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                Ada {{ $validationResults['invalid_rows'] }} data error. Hanya data valid yang akan diimport.
            </div>
            @endif

            @if($validationResults['valid_rows'] > 0)
            <button type="submit"
                    class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                </svg>
                Import {{ $validationResults['valid_rows'] }} Data Valid
            </button>
            @else
            <div class="text-sm text-gray-500 py-3">
                Tidak ada data valid untuk diimport
            </div>
            @endif
        </div>
    </div>
    </form>
</div>
@endif
@endsection