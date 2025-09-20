@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary-700">Patient Management</h1>
        <p class="text-accent-600 mt-2">Manage all patients and their medical records</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-accent-200">
            <h2 class="text-xl font-semibold text-primary-700">All Patients</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-accent-200">
                <thead class="bg-accent-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-accent-500 uppercase tracking-wider">Patient Info</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-accent-500 uppercase tracking-wider">Share ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-accent-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-accent-500 uppercase tracking-wider">Age</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-accent-500 uppercase tracking-wider">MCU Records</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-accent-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-accent-200">
                    @foreach($patients as $patient)
                    <tr class="hover:bg-accent-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-accent-900">{{ $patient->name }}</div>
                                <div class="text-sm text-accent-500">{{ $patient->jabatan }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                {{ $patient->share_id }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-accent-900">{{ $patient->departemen }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-accent-900">{{ $patient->umur }} years</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-accent-500">
                            {{ $patient->orders->count() }} records
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('patients.show', $patient) }}" class="text-secondary-600 hover:text-secondary-900 transition-colors">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-accent-200">
            {{ $patients->links() }}
        </div>
    </div>
</div>
@endsection