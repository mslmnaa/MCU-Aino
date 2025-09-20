@extends('layouts.admin')

@section('page-title', 'Normal Values Management')
@section('page-subtitle', 'Manage reference ranges for medical test parameters')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-primary-700">Normal Values Management</h1>
        <div class="text-sm text-neutral-500">
            Configure reference ranges for all medical tests
        </div>
    </div>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

<form method="POST" action="{{ route('admin.normal-values.update') }}">
    @csrf
    @method('PUT')

    <!-- Hematologi -->
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm mb-6">
        <div class="bg-cream-50 px-6 py-4 border-b border-neutral-200">
            <h2 class="text-lg font-semibold text-neutral-800">Hematologi</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($normalValues['hematologi'] as $key => $value)
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                    </label>
                    <input type="text"
                           name="hematologi[{{ $key }}]"
                           value="{{ $value }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Fungsi Liver -->
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm mb-6">
        <div class="bg-primary-50 px-6 py-4 border-b border-neutral-200">
            <h2 class="text-lg font-semibold text-neutral-800">Fungsi Liver</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($normalValues['fungsi_liver'] as $key => $value)
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                        {{ strtoupper($key) }}
                    </label>
                    <input type="text"
                           name="fungsi_liver[{{ $key }}]"
                           value="{{ $value }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Fungsi Ginjal -->
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm mb-6">
        <div class="bg-cream-50 px-6 py-4 border-b border-neutral-200">
            <h2 class="text-lg font-semibold text-neutral-800">Fungsi Ginjal</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($normalValues['fungsi_ginjal'] as $key => $value)
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                    </label>
                    <input type="text"
                           name="fungsi_ginjal[{{ $key }}]"
                           value="{{ $value }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Gula Darah -->
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm mb-6">
        <div class="bg-primary-50 px-6 py-4 border-b border-neutral-200">
            <h2 class="text-lg font-semibold text-neutral-800">Gula Darah</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($normalValues['glukosa_darah'] as $key => $value)
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                        {{ ucfirst(str_replace(['_', 'pp'], [' ', 'PP'], $key)) }}
                    </label>
                    <input type="text"
                           name="glukosa_darah[{{ $key }}]"
                           value="{{ $value }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Profil Lemak -->
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm mb-6">
        <div class="bg-secondary-50 px-6 py-4 border-b border-neutral-200">
            <h2 class="text-lg font-semibold text-neutral-800">Profil Lemak</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($normalValues['profil_lemak'] as $key => $value)
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                        {{ ucfirst(str_replace(['_', 'cholesterol'], [' ', 'Cholesterol'], $key)) }}
                    </label>
                    <input type="text"
                           name="profil_lemak[{{ $key }}]"
                           value="{{ $value }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Tanda Vital -->
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm mb-6">
        <div class="bg-secondary-50 px-6 py-4 border-b border-neutral-200">
            <h2 class="text-lg font-semibold text-neutral-800">Tanda Vital</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($normalValues['tanda_vital'] as $key => $value)
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                    </label>
                    <input type="text"
                           name="tanda_vital[{{ $key }}]"
                           value="{{ $value }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Pemeriksaan Vital -->
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm mb-6">
        <div class="bg-primary-50 px-6 py-4 border-b border-neutral-200">
            <h2 class="text-lg font-semibold text-neutral-800">Pemeriksaan Vital</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($normalValues['pemeriksaan_vital'] as $key => $value)
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                    </label>
                    <input type="text"
                           name="pemeriksaan_vital[{{ $key }}]"
                           value="{{ $value }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Submit Button -->
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
@endsection