@extends('layouts.admin')


@section('content')
<!-- Statistics Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8">
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-4 lg:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-neutral-600 uppercase tracking-wide">Total Employee</p>
                <p class="text-2xl lg:text-3xl font-semibold text-primary-700 mt-2">{{ $totalPatients }}</p>
                <p class="text-sm text-neutral-500 mt-1">Registered employees</p>
            </div>
            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-4 lg:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-neutral-600 uppercase tracking-wide">Health Records</p>
                <p class="text-2xl lg:text-3xl font-semibold text-secondary-700 mt-2">{{ $totalOrders }}</p>
                <p class="text-sm text-neutral-500 mt-1">Complete MCU examinations</p>
            </div>
            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-secondary-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 712-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="mt-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-primary-700">Recent Medical Check-ups</h3>
        <p class="text-sm text-neutral-500 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
            </svg>
            Click any row to view medical records
        </p>
    </div>
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
        <!-- Desktop Table Header -->
        <div class="hidden lg:block px-6 py-4 bg-neutral-50 border-b border-neutral-200">
            <div class="grid grid-cols-4 gap-4 text-sm font-semibold text-neutral-700 uppercase tracking-wide">
                <div>Patient Name</div>
                <div>Department</div>
                <div>Lab Number</div>
                <div>Date</div>
            </div>
        </div>
        <div class="divide-y divide-neutral-200">
            @foreach($recentOrders as $order)
            <a href="{{ route('patients.show', $order->patient->id) }}"
               class="block px-4 lg:px-6 py-4 hover:bg-primary-50 transition-colors duration-150 cursor-pointer group"
               title="View {{ $order->patient->name }}'s medical records ({{ $order->tgl_order->format('Y') }})">
                <!-- Mobile Layout -->
                <div class="lg:hidden">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                @if($order->patient->profile_photo || $order->patient->user?->profile_photo)
                                    <img src="{{ asset('storage/' . ($order->patient->profile_photo ?? $order->patient->user->profile_photo)) }}"
                                         alt="{{ $order->patient->name }}"
                                         class="h-10 w-10 rounded-full object-cover border-2 border-primary-200 group-hover:border-primary-400 transition-colors">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center border-2 border-primary-200 group-hover:border-primary-400 transition-colors group-hover:shadow-md">
                                        <span class="text-white text-xs font-bold">
                                            {{ strtoupper(substr($order->patient->name, 0, 2)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-neutral-900 group-hover:text-primary-700 transition-colors">{{ $order->patient->name }}</p>
                                <p class="text-sm text-neutral-500">{{ $order->patient->jabatan }}</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-neutral-400 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-neutral-500">Department:</span>
                            <p class="font-medium text-neutral-700">{{ $order->patient->departemen }}</p>
                        </div>
                        <div>
                            <span class="text-neutral-500">Lab Number:</span>
                            <p class="font-mono text-primary-600">{{ $order->no_lab }}</p>
                        </div>
                        <div class="col-span-2">
                            <span class="text-neutral-500">Date:</span>
                            <p class="text-neutral-700">{{ $order->tgl_order->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Desktop Layout -->
                <div class="hidden lg:grid grid-cols-4 gap-4 items-center">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            @if($order->patient->profile_photo || $order->patient->user?->profile_photo)
                                <img src="{{ asset('storage/' . ($order->patient->profile_photo ?? $order->patient->user->profile_photo)) }}"
                                     alt="{{ $order->patient->name }}"
                                     class="h-10 w-10 rounded-full object-cover border-2 border-primary-200 group-hover:border-primary-400 transition-colors">
                            @else
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center border-2 border-primary-200 group-hover:border-primary-400 transition-colors group-hover:shadow-md">
                                    <span class="text-white text-xs font-bold">
                                        {{ strtoupper(substr($order->patient->name, 0, 2)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="font-medium text-neutral-900 group-hover:text-primary-700 transition-colors">{{ $order->patient->name }}</p>
                            <p class="text-sm text-neutral-500">{{ $order->patient->jabatan }}</p>
                        </div>
                    </div>
                    <div class="text-sm text-neutral-600 group-hover:text-neutral-700 transition-colors">{{ $order->patient->departemen }}</div>
                    <div class="text-sm font-mono text-primary-600 group-hover:text-primary-700 transition-colors">{{ $order->no_lab }}</div>
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-neutral-500 group-hover:text-neutral-600 transition-colors">{{ $order->tgl_order->format('M d, Y') }}</div>
                        <svg class="w-4 h-4 text-neutral-400 group-hover:text-primary-600 transition-colors ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </a>
            @endforeach

            @if($recentOrders->isEmpty())
            <div class="px-4 lg:px-6 py-8 text-center">
                <div class="text-neutral-500">
                    <svg class="w-12 h-12 mx-auto mb-4 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-sm">No recent medical check-ups found</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection