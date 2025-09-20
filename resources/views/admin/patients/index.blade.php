@extends('layouts.admin')

@section('page-title', 'Employee Management')
@section('page-subtitle', 'Manage all patient information and medical check-up records')

@section('content')
<div class="mb-8">
    {{-- <div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-xl shadow-lg p-8 text-white">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold mb-2">Employee Management</h1>
                <p class="text-primary-100 text-lg">Manage all patient information and medical check-up records</p>
            </div>
        </div>
    </div> --}}
</div>

<div class="bg-white border border-neutral-200 rounded-xl shadow-lg overflow-hidden">
    {{-- <div class="px-6 py-5 bg-primary-600 flex items-center justify-between"> --}}
        {{-- <h2 class="text-xl font-semibold text-white">All Patients</h2> --}}
        {{-- Search or filter can go here --}}
        {{--
        <div class="relative">
            <input type="text" placeholder="Search patients..." class="pl-10 pr-4 py-2 rounded-lg bg-primary-500 text-white placeholder-primary-200 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-primary-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
        </div>
        --}}
    {{-- </div> --}}

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider">Patient Info</th>
                    {{-- <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider">Share ID</th> --}}
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider">Department</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                @foreach($patients as $patient)
                <tr class="hover:bg-neutral-50 transition-colors duration-150 ease-in-out">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-14 w-14">
                                @if($patient->profile_photo || $patient->user?->profile_photo)
                                    <img src="{{ asset('storage/' . ($patient->profile_photo ?? $patient->user->profile_photo)) }}"
                                         alt="{{ $patient->name }}"
                                         class="h-14 w-14 rounded-full object-cover border-2 border-primary-300 shadow-sm">
                                @else
                                    <div class="h-14 w-14 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center border-2 border-primary-300 shadow-sm">
                                        <span class="text-white text-lg font-bold">
                                            {{ strtoupper(substr($patient->name, 0, 2)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-base font-medium text-neutral-900">{{ $patient->name }}</div>
                                <div class="text-sm text-primary-600">{{ $patient->jabatan }}</div>
                            </div>
                        </div>
                    </td>
                    {{-- <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800 border border-primary-200">
                            {{ $patient->share_id }}
                        </span>
                    </td> --}}
                    <td class="px-6 py-4 text-sm text-neutral-700">{{ $patient->departemen }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('patients.show', $patient) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 ease-in-out">
                            <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                <path fill-rule="evenodd" d="M.661 10.334C2.083 4.678 7.218 2 12.5 2c5.282 0 10.417 2.678 11.839 8.334a1 1 0 010 .332C22.917 15.322 17.782 18 12.5 18c-5.282 0-10.417-2.678-11.839-8.334a1 1 0 010-.332zM12.5 4a6.5 6.5 0 100 13 6.5 6.5 0 000-13z" clip-rule="evenodd" />
                            </svg>
                            View Details
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 bg-neutral-50 border-t border-neutral-200">
        {{ $patients->links() }}
    </div>
</div>
@endsection