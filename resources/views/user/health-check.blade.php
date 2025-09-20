@extends('layouts.user')

@section('page-title', 'Medical Check-Up Report')
@section('page-subtitle', $patient->name . ' • ID: ' . $patient->share_id)

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div class="text-sm text-neutral-500">
            Patient Information • {{ $patient->departemen }} • {{ $patient->jabatan }}
        </div>
        <a href="{{ route('dashboard') }}" class="bg-primary-500 hover:bg-primary-600 text-neutral-50 px-6 py-2 font-medium transition-colors duration-200">
            Return to Dashboard
        </a>
    </div>
</div>

<!-- Patient Summary -->
<div class="bg-neutral-50 border border-cream-200 p-6 mb-8">
    <h2 class="text-lg font-serif font-semibold text-primary-700 mb-6">Ringkasan Karyawan</h2>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="text-center p-4 bg-cream-50 border border-cream-200">
            <div class="text-2xl font-serif font-bold text-primary-600">{{ $patient->umur }}</div>
            <div class="text-sm font-medium text-neutral-600 uppercase tracking-wide mt-1">Tahun</div>
        </div>
        <div class="text-center p-4 bg-cream-50 border border-cream-200">
            <div class="text-lg font-medium text-secondary-600">{{ $patient->departemen }}</div>
            <div class="text-sm font-medium text-neutral-600 uppercase tracking-wide mt-1">Departemen</div>
        </div>
        <div class="text-center p-4 bg-cream-50 border border-cream-200">
            <div class="text-lg font-medium text-secondary-600">{{ $patient->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
            <div class="text-sm font-medium text-neutral-600 uppercase tracking-wide mt-1">Jenis Kelamin</div>
        </div>
        <div class="text-center p-4 bg-cream-50 border border-cream-200">
            <div class="text-lg font-medium text-secondary-600">{{ $patient->jabatan }}</div>
            <div class="text-sm font-medium text-neutral-600 uppercase tracking-wide mt-1">Jabatan</div>
        </div>
        <div class="text-center p-4 bg-cream-50 border border-cream-200">
            <div class="text-lg font-serif font-bold text-primary-600">{{ $patient->orders->count() }}</div>
            <div class="text-sm font-medium text-neutral-600 uppercase tracking-wide mt-1">Rekam MCU</div>
        </div>
    </div>
</div>

<!-- Health Records -->
@foreach($patient->orders as $order)
<div class="bg-neutral-50 border border-cream-200 mb-8">
    <div class="border-b border-cream-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-serif font-semibold text-primary-700">Medical Check-Up Report</h2>
                <p class="text-neutral-600 mt-1">{{ $order->tgl_order->format('d F Y') }} • Lab No: {{ $order->no_lab }} • {{ $order->cabang }}</p>
            </div>
            <span class="bg-primary-100 text-primary-800 px-4 py-2 text-sm font-medium border border-primary-200">
                Complete
            </span>
        </div>
    </div>
    <div class="p-6">

        <!-- Health Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white p-4 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <div>
                        <div class="text-lg font-semibold">Kesehatan Umum</div>
                        <div class="text-sm opacity-90">Kondisi Baik</div>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-secondary-500 to-secondary-600 text-white p-4 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <div>
                        <div class="text-lg font-semibold">Hasil Laboratorium</div>
                        <div class="text-sm opacity-90">Dalam Batas Normal</div>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <div class="text-lg font-semibold">Tanda Vital</div>
                        <div class="text-sm opacity-90">Stabil</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medical Examination Results Table -->
        <div class="bg-white border border-accent-200 rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Hasil Pemeriksaan Medical Check-Up Lengkap</h3>
            </div>

            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 table-fixed">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Jenis Pemeriksaan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Parameter</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Hasil</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Nilai Normal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Vital Signs -->
                            @if($order->pemeriksaanVital)
                            <tr class="bg-blue-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" rowspan="9">Pemeriksaan Vital</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Berat Badan</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">{{ $order->pemeriksaanVital->berat_badan ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">40-100 kg</td>
                            </tr>
                            <tr class="bg-blue-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Tinggi Badan</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">{{ $order->pemeriksaanVital->tinggi_badan ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">150-200 cm</td>
                            </tr>
                            <tr class="bg-blue-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Lingkar Perut</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">{{ $order->pemeriksaanVital->lingkar_perut ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">< 90 cm (P), < 80 cm (W)</td>
                            </tr>
                            <tr class="bg-blue-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Indeks Massa Tubuh (BMI)</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">{{ $order->pemeriksaanVital->bmi ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">18.5-24.9</td>
                            </tr>
                            <tr class="bg-blue-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Klasifikasi Tekanan Darah</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">{{ $order->pemeriksaanVital->klasifikasi_tekanan_darah ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Normal</td>
                            </tr>
                            <tr class="bg-blue-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Klasifikasi OD</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">{{ $order->pemeriksaanVital->klasifikasi_od ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Normal</td>
                            </tr>
                            <tr class="bg-blue-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Klasifikasi OS</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">{{ $order->pemeriksaanVital->klasifikasi_os ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Normal</td>
                            </tr>
                            <tr class="bg-blue-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Persepsi Warna</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">{{ $order->pemeriksaanVital->persepsi_warna ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Normal</td>
                            </tr>
                            <tr class="bg-blue-50">
                                <td class="px-6 py-4 text-sm text-gray-900">Pemeriksaan Fisik Umum</td>
                                <td class="px-6 py-4 text-sm font-semibold text-blue-600">
                                    <div class="break-words">
                                        {{ $order->pemeriksaanVital->pemeriksaan_fisik_umum ?? 'T/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">-</td>
                            </tr>
                            @endif

                            <!-- Vital Signs - Additional -->
                            @if($order->tandaVital)
                            <tr class="bg-red-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" rowspan="4">Pemeriksaan Tanda Vital</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Tekanan Darah</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">{{ $order->tandaVital->tekanan_darah ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">120/80 mmHg</td>
                            </tr>
                            <tr class="bg-red-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Denyut Nadi</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">{{ $order->tandaVital->nadi ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">60-100 x/menit</td>
                            </tr>
                            <tr class="bg-red-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Frekuensi Napas</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">{{ $order->tandaVital->pernapasan ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">12-20 x/menit</td>
                            </tr>
                            <tr class="bg-red-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Suhu Tubuh</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">{{ $order->tandaVital->suhu_tubuh ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">36.0-37.5°C</td>
                            </tr>
                            @endif

                            <!-- Blood Test -->
                            @if($order->labHematologi)
                            <tr class="bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" rowspan="17">Tes Darah (Hematologi)</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Hematologi</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ $order->labHematologi->hematologi ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                            </tr>
                            <tr class="bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Hemoglobin</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ $order->labHematologi->hemoglobin ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">12-16 g/dL</td>
                            </tr>
                            <tr class="bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Sel Darah Merah (Erytrosit)</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ $order->labHematologi->erytrosit ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">4.2-5.4 juta/μL</td>
                            </tr>
                            <tr class="bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Hematokrit</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ $order->labHematologi->hematokrit ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">37-48%</td>
                            </tr>
                            <tr class="bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">MCV</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ $order->labHematologi->mcv ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">82-98 fL</td>
                            </tr>
                            <tr class="bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">MCH</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ $order->labHematologi->mch ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">27-31 pg</td>
                            </tr>
                            <tr class="bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">MCHC</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ $order->labHematologi->mchc ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">32-36 g/dL</td>
                            </tr>
                            <tr class="bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RDW</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ $order->labHematologi->rdw ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">11.5-14.5%</td>
                            </tr>
                            <tr class="bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Sel Darah Putih (Leukosit)</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ $order->labHematologi->leukosit ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">4.000-11.000/μL</td>
                            </tr>
                            <tr class="bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Eosinofil</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ $order->labHematologi->eosinofil ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1-3%</td>
                            </tr>
                            <tr class="bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Basofil</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ $order->labHematologi->basofil ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">0-1%</td>
                            </tr>
                            <tr class="bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Neutrofil Batang</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ $order->labHematologi->neutrofil_batang ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2-6%</td>
                            </tr>
                            <tr class="bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Neutrofil Segmen</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ $order->labHematologi->neutrofil_segmen ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">50-70%</td>
                            </tr>
                            <tr class="bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Limfosit</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ $order->labHematologi->limfosit ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">20-40%</td>
                            </tr>
                            <tr class="bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Monosit</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ $order->labHematologi->monosit ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2-8%</td>
                            </tr>
                            <tr class="bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Trombosit</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ $order->labHematologi->trombosit ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">150.000-450.000/μL</td>
                            </tr>
                            <tr class="bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Laju Endap Darah</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ $order->labHematologi->laju_endap_darah ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">0-15 mm/jam</td>
                            </tr>
                            @endif

                            <!-- Urine Test -->
                            @if($order->labUrine)
                            <tr class="bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" rowspan="18">Analisis Urine</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Warna</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $order->labUrine->warna ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Kuning</td>
                            </tr>
                            <tr class="bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Kejernihan</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $order->labUrine->kejernihan ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jernih</td>
                            </tr>
                            <tr class="bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Berat Jenis (BJ)</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $order->labUrine->bj ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1.010-1.025</td>
                            </tr>
                            <tr class="bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">pH</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $order->labUrine->ph ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">5.0-8.0</td>
                            </tr>
                            <tr class="bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Protein</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $order->labUrine->protein ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Negatif</td>
                            </tr>
                            <tr class="bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Glukosa</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $order->labUrine->glukosa ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Negatif</td>
                            </tr>
                            <tr class="bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Keton</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $order->labUrine->keton ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Negatif</td>
                            </tr>
                            <tr class="bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bilirubin</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $order->labUrine->bilirubin ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Negatif</td>
                            </tr>
                            <tr class="bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Urobilinogen</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $order->labUrine->urobilinogen ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Normal</td>
                            </tr>
                            <tr class="bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Nitrit</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $order->labUrine->nitrit ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Negatif</td>
                            </tr>
                            <tr class="bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Darah</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $order->labUrine->darah ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Negatif</td>
                            </tr>
                            <tr class="bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Lekosit Esterase</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $order->labUrine->lekosit_esterase ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Negatif</td>
                            </tr>
                            <tr class="bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Eritrosit Sedimen</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $order->labUrine->eritrosit_sedimen ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">0-3/LPB</td>
                            </tr>
                            <tr class="bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Lekosit Sedimen</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $order->labUrine->lekosit_sedimen ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">0-5/LPB</td>
                            </tr>
                            <tr class="bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Epitel Sedimen</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $order->labUrine->epitel_sedimen ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Positif 1-2</td>
                            </tr>
                            <tr class="bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Kristal Sedimen</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $order->labUrine->kristal_sedimen ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Negatif</td>
                            </tr>
                            <tr class="bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Silinder Sedimen</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $order->labUrine->silinder_sedimen ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Negatif</td>
                            </tr>
                            <tr class="bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Lain-lain Sedimen</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $order->labUrine->lain_lain_sedimen ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                            </tr>
                            @endif


                            <!-- Liver Function -->
                            @if($order->labFungsiLiver)
                            <tr class="bg-purple-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" rowspan="2">Fungsi Hati</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SGOT</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-purple-600">{{ $order->labFungsiLiver->sgot ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10-40 U/L</td>
                            </tr>
                            <tr class="bg-purple-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SGPT</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-purple-600">{{ $order->labFungsiLiver->sgpt ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">7-56 U/L</td>
                            </tr>
                            @endif

                            <!-- Lipid Profile -->
                            @if($order->labProfilLemak)
                            <tr class="bg-indigo-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" rowspan="4">Profil Lemak</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Kolesterol Total</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-600">{{ $order->labProfilLemak->cholesterol ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">< 200 mg/dL</td>
                            </tr>
                            <tr class="bg-indigo-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Trigliserida</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-600">{{ $order->labProfilLemak->trigliserida ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">< 150 mg/dL</td>
                            </tr>
                            <tr class="bg-indigo-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Kolesterol HDL</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-600">{{ $order->labProfilLemak->hdl_cholesterol ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">> 40 mg/dL (P), > 50 mg/dL (W)</td>
                            </tr>
                            <tr class="bg-indigo-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Kolesterol LDL</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-600">{{ $order->labProfilLemak->ldl_cholesterol ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">< 100 mg/dL</td>
                            </tr>
                            @endif

                            <!-- Kidney Function -->
                            @if($order->labFungsiGinjal)
                            <tr class="bg-teal-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" rowspan="3">Fungsi Ginjal</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Ureum</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-teal-600">{{ $order->labFungsiGinjal->ureum ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10-50 mg/dL</td>
                            </tr>
                            <tr class="bg-teal-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Kreatinin</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-teal-600">{{ $order->labFungsiGinjal->creatinin ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">0.6-1.2 mg/dL</td>
                            </tr>
                            <tr class="bg-teal-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Asam Urat</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-teal-600">{{ $order->labFungsiGinjal->asam_urat ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">3.5-7.2 mg/dL</td>
                            </tr>
                            @endif

                            <!-- Blood Sugar -->
                            @if($order->labGlukosaDarah)
                            <tr class="bg-pink-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" rowspan="3">Gula Darah</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Glukosa Puasa</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-pink-600">{{ $order->labGlukosaDarah->glukosa_puasa ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">70-100 mg/dL</td>
                            </tr>
                            <tr class="bg-pink-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Glukosa 2 Jam PP</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-pink-600">{{ $order->labGlukosaDarah->glukosa_2jam_pp ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">< 140 mg/dL</td>
                            </tr>
                            <tr class="bg-pink-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">HbA1c</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-pink-600">{{ $order->labGlukosaDarah->hba1c ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">< 5.7%</td>
                            </tr>
                            @endif

                            <!-- Tumor Markers -->
                            @if($order->labPenandaTumor)
                            <tr class="bg-orange-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" rowspan="2">Penanda Tumor</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">HBsAg</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-orange-600">{{ $order->labPenandaTumor->hbsag ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Non-Reaktif</td>
                            </tr>
                            <tr class="bg-orange-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">CEA</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-orange-600">{{ $order->labPenandaTumor->cea ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">< 3.0 ng/mL</td>
                            </tr>
                            @endif

                            <!-- Eye Examination -->
                            @if($order->pemeriksaanMata)
                            <tr class="bg-cyan-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" rowspan="2">Pemeriksaan Mata</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Dengan Kacamata</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-cyan-600">{{ $order->pemeriksaanMata->dengan_kacamata ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">6/6 atau 20/20</td>
                            </tr>
                            <tr class="bg-cyan-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Tanpa Kacamata</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-cyan-600">{{ $order->pemeriksaanMata->tanpa_kacamata ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">6/6 atau 20/20</td>
                            </tr>
                            @endif

                            <!-- Physical Test -->
                            @if($order->tesFisik)
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" rowspan="10">Tes Fisik</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Smell Test</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-600">{{ $order->tesFisik->smell_test ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Normal</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Low Back Pain</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-600">{{ $order->tesFisik->low_back_pain ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Negatif</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Laseque Test</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-600">{{ $order->tesFisik->laseque_test ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Negatif</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bragard Test</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-600">{{ $order->tesFisik->bragard_test ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Negatif</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Patrick Test</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-600">{{ $order->tesFisik->patrict_test ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Negatif</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Kontra Patrick</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-600">{{ $order->tesFisik->kontra_patrict ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Negatif</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Neer Sign</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-600">{{ $order->tesFisik->neer_sign ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Negatif</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Range of Motion</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-600">{{ $order->tesFisik->range_of_motion ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Normal</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Speed Test</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-600">{{ $order->tesFisik->speed_test ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Negatif</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Straight Leg Raised Test</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-600">{{ $order->tesFisik->straight_leg_raised_test ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Negatif</td>
                            </tr>
                            @endif

                            <!-- Riwayat Kebiasaan Hidup -->
                            @if($order->riwayatKebiasaanHidup)
                            <tr class="bg-emerald-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" rowspan="3">Riwayat Kebiasaan Hidup</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Merokok</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-emerald-600">{{ $order->riwayatKebiasaanHidup->merokok ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Tidak/Ya</td>
                            </tr>
                            <tr class="bg-emerald-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Minum Alkohol</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-emerald-600">{{ $order->riwayatKebiasaanHidup->minum_alkohol ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Tidak/Ya</td>
                            </tr>
                            <tr class="bg-emerald-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Olahraga</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-emerald-600">{{ $order->riwayatKebiasaanHidup->olahraga ?? 'T/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rutin/Tidak Rutin</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Additional Examinations -->
                @if($order->radiologi || $order->pemeriksaanGigi || $order->statusGizi || ($order->pemeriksaanVital && ($order->pemeriksaanVital->kesimpulan_fisik || $order->pemeriksaanVital->rekomendasi)))
                <div class="mt-8 space-y-6">
                    <!-- Radiology Results -->
                    @if($order->radiologi)
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Pemeriksaan Radiologi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white p-4 rounded border">
                                <h5 class="font-medium text-gray-700 mb-2">EKG (Elektrokardiogram)</h5>
                                <p class="text-sm text-gray-600 mb-2"><strong>Hasil:</strong> {{ $order->radiologi->ecg ?? 'T/A' }}</p>
                                <p class="text-sm text-gray-600"><strong>Kesimpulan:</strong> {{ $order->radiologi->kesimpulan_ecg ?? 'T/A' }}</p>
                            </div>
                            <div class="bg-white p-4 rounded border">
                                <h5 class="font-medium text-gray-700 mb-2">Rontgen Dada (Thorax PA)</h5>
                                <p class="text-sm text-gray-600 mb-2"><strong>Hasil:</strong> {{ $order->radiologi->thorax_pa ?? 'T/A' }}</p>
                                <p class="text-sm text-gray-600"><strong>Kesimpulan:</strong> {{ $order->radiologi->kesimpulan_thorax_pa ?? 'T/A' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Dental Examination -->
                    @if($order->pemeriksaanGigi)
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Pemeriksaan Gigi</h4>
                        <div class="bg-white p-4 rounded border">
                            <p class="text-gray-700">{{ $order->pemeriksaanGigi->kondisi_gigi ?? 'Data pemeriksaan gigi tidak tersedia' }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Nutrition Status -->
                    @if($order->statusGizi)
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Status Gizi</h4>
                        <div class="bg-white p-4 rounded border">
                            <p class="text-gray-700">{{ $order->statusGizi->status ?? 'Data status gizi tidak tersedia' }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Physical Examination Summary -->
                    @if($order->pemeriksaanVital && ($order->pemeriksaanVital->kesimpulan_fisik || $order->pemeriksaanVital->rekomendasi || $order->pemeriksaanVital->saran))
                    <div class="bg-yellow-50 p-6 rounded-lg">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pemeriksaan Fisik</h4>
                        @if($order->pemeriksaanVital->kesimpulan_fisik)
                        <div class="bg-white p-4 rounded border mb-4">
                            <h5 class="font-medium text-gray-700 mb-2">Kesimpulan Pemeriksaan Fisik</h5>
                            <p class="text-gray-700">{{ $order->pemeriksaanVital->kesimpulan_fisik }}</p>
                        </div>
                        @endif
                        @if($order->pemeriksaanVital->rekomendasi)
                        <div class="bg-white p-4 rounded border mb-4">
                            <h5 class="font-medium text-gray-700 mb-2">Rekomendasi Medis</h5>
                            <div class="text-gray-700 whitespace-pre-line">{{ $order->pemeriksaanVital->rekomendasi }}</div>
                        </div>
                        @endif
                        @if($order->pemeriksaanVital->saran)
                        <div class="bg-white p-4 rounded border">
                            <h5 class="font-medium text-gray-700 mb-2">Saran</h5>
                            <div class="text-gray-700 whitespace-pre-line">{{ $order->pemeriksaanVital->saran }}</div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    <!-- Health Tips -->
    <div class="bg-gradient-to-r from-secondary-500 to-secondary-600 rounded-xl shadow-lg p-6 text-white">
        <h3 class="text-xl font-semibold mb-4">Tips Kesehatan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-start">
                <svg class="w-6 h-6 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <div class="font-medium">Olahraga Teratur</div>
                    <div class="text-sm opacity-90">Lakukan minimal 150 menit aktivitas sedang per minggu</div>
                </div>
            </div>
            <div class="flex items-start">
                <svg class="w-6 h-6 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <div>
                    <div class="font-medium">Pola Makan Seimbang</div>
                    <div class="text-sm opacity-90">Konsumsi banyak buah, sayuran, dan biji-bijian utuh</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection