@extends('layouts.admin')

@section('page-title', 'Edit Medical Record')
@section('page-subtitle', $patient->name . ' • ID: ' . $patient->share_id)

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-primary-700">Edit Medical Record</h1>
            <p class="text-neutral-600 mt-1">{{ $order->tgl_order->format('d F Y') }} • Lab No: {{ $order->no_lab }} • {{ $order->cabang }}</p>
        </div>
        <a href="{{ route('patients.show', $patient->id) }}"
           class="bg-neutral-500 hover:bg-neutral-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
            Back to Patient
        </a>
    </div>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

<form method="POST" action="{{ route('medical-records.update', [$patient->id, $order->id]) }}">
    @csrf
    @method('PUT')

    <!-- Tanda Vital -->
    @if($order->tandaVital)
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm mb-6">
        <div class="bg-secondary-50 px-6 py-4 border-b border-neutral-200">
            <h2 class="text-lg font-semibold text-neutral-800">Tanda Vital</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Tekanan Darah (mmHg)</label>
                    <input type="text"
                           name="tanda_vital[tekanan_darah]"
                           value="{{ $order->tandaVital->tekanan_darah }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500"
                           placeholder="120/80">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Denyut Nadi (x/menit)</label>
                    <input type="number"
                           name="tanda_vital[nadi]"
                           value="{{ str_replace(' x/menit', '', $order->tandaVital->nadi) }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500"
                           placeholder="80">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Frekuensi Napas (x/menit)</label>
                    <input type="number"
                           name="tanda_vital[pernapasan]"
                           value="{{ str_replace(' x/menit', '', $order->tandaVital->pernapasan) }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500"
                           placeholder="18">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Suhu Tubuh (°C)</label>
                    <input type="number"
                           step="0.1"
                           name="tanda_vital[suhu_tubuh]"
                           value="{{ str_replace(' °C', '', $order->tandaVital->suhu_tubuh) }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500"
                           placeholder="36.5">
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Fungsi Liver -->
    @if($order->labFungsiLiver)
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm mb-6">
        <div class="bg-primary-50 px-6 py-4 border-b border-neutral-200">
            <h2 class="text-lg font-semibold text-neutral-800">Fungsi Liver</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">SGOT (U/L)</label>
                    <input type="number"
                           name="fungsi_liver[sgot]"
                           value="{{ str_replace(' U/L', '', $order->labFungsiLiver->sgot) }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="25">
                    <p class="text-xs text-neutral-500 mt-1">Normal: 10-40 U/L</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">SGPT (U/L)</label>
                    <input type="number"
                           name="fungsi_liver[sgpt]"
                           value="{{ str_replace(' U/L', '', $order->labFungsiLiver->sgpt) }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="30">
                    <p class="text-xs text-neutral-500 mt-1">Normal: 7-56 U/L</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Fungsi Ginjal -->
    @if($order->labFungsiGinjal)
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm mb-6">
        <div class="bg-cream-50 px-6 py-4 border-b border-neutral-200">
            <h2 class="text-lg font-semibold text-neutral-800">Fungsi Ginjal</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Ureum (mg/dL)</label>
                    <input type="number"
                           name="fungsi_ginjal[ureum]"
                           value="{{ str_replace(' mg/dL', '', $order->labFungsiGinjal->ureum) }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-cream-500 focus:border-cream-500"
                           placeholder="30">
                    <p class="text-xs text-neutral-500 mt-1">Normal: 10-50 mg/dL</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Kreatinin (mg/dL)</label>
                    <input type="number"
                           step="0.01"
                           name="fungsi_ginjal[creatinin]"
                           value="{{ str_replace(' mg/dL', '', $order->labFungsiGinjal->creatinin) }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-cream-500 focus:border-cream-500"
                           placeholder="0.9">
                    <p class="text-xs text-neutral-500 mt-1">Normal: 0.6-1.2 mg/dL</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Asam Urat (mg/dL)</label>
                    <input type="number"
                           step="0.1"
                           name="fungsi_ginjal[asam_urat]"
                           value="{{ str_replace(' mg/dL', '', $order->labFungsiGinjal->asam_urat) }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-cream-500 focus:border-cream-500"
                           placeholder="5.5">
                    <p class="text-xs text-neutral-500 mt-1">Normal: 3.5-7.2 mg/dL</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Gula Darah -->
    @if($order->labGlukosaDarah)
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm mb-6">
        <div class="bg-primary-50 px-6 py-4 border-b border-neutral-200">
            <h2 class="text-lg font-semibold text-neutral-800">Gula Darah</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Glukosa Puasa (mg/dL)</label>
                    <input type="number"
                           name="glukosa_darah[glukosa_puasa]"
                           value="{{ str_replace(' mg/dL', '', $order->labGlukosaDarah->glukosa_puasa) }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="90">
                    <p class="text-xs text-neutral-500 mt-1">Normal: 70-100 mg/dL</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Glukosa 2 Jam PP (mg/dL)</label>
                    <input type="number"
                           name="glukosa_darah[glukosa_2jam_pp]"
                           value="{{ str_replace(' mg/dL', '', $order->labGlukosaDarah->glukosa_2jam_pp) }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="120">
                    <p class="text-xs text-neutral-500 mt-1">Normal: < 140 mg/dL</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">HbA1c (%)</label>
                    <input type="number"
                           step="0.1"
                           name="glukosa_darah[hba1c]"
                           value="{{ str_replace('%', '', $order->labGlukosaDarah->hba1c) }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="5.2">
                    <p class="text-xs text-neutral-500 mt-1">Normal: < 5.7%</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Pemeriksaan Vital -->
    @if($order->pemeriksaanVital)
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm mb-6">
        <div class="bg-primary-50 px-6 py-4 border-b border-neutral-200">
            <h2 class="text-lg font-semibold text-neutral-800">Pemeriksaan Vital</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Berat Badan (kg)</label>
                    <input type="number"
                           name="pemeriksaan_vital[berat_badan]"
                           value="{{ str_replace(' kg', '', $order->pemeriksaanVital->berat_badan) }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="70">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Tinggi Badan (cm)</label>
                    <input type="number"
                           name="pemeriksaan_vital[tinggi_badan]"
                           value="{{ str_replace(' cm', '', $order->pemeriksaanVital->tinggi_badan) }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="170">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Lingkar Perut (cm)</label>
                    <input type="number"
                           name="pemeriksaan_vital[lingkar_perut]"
                           value="{{ str_replace(' cm', '', $order->pemeriksaanVital->lingkar_perut) }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="85">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">BMI</label>
                    <input type="number"
                           step="0.1"
                           name="pemeriksaan_vital[bmi]"
                           value="{{ $order->pemeriksaanVital->bmi }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="24.2">
                </div>
            </div>

            <div class="mt-6">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Rekomendasi</label>
                        <textarea name="pemeriksaan_vital[rekomendasi]"
                                  rows="4"
                                  class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                  placeholder="Masukkan rekomendasi kesehatan...">{{ $order->pemeriksaanVital->rekomendasi }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Saran</label>
                        <textarea name="pemeriksaan_vital[saran]"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                  placeholder="Masukkan saran untuk pasien...">{{ $order->pemeriksaanVital->saran }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Submit Buttons -->
    <div class="flex justify-end space-x-4">
        <a href="{{ route('patients.show', $patient->id) }}"
           class="px-6 py-2 text-neutral-600 bg-neutral-100 hover:bg-neutral-200 rounded-lg font-medium transition-colors">
            Cancel
        </a>
        <button type="submit"
                class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors">
            Update Medical Record
        </button>
    </div>
</form>

<div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-yellow-800">Note</h3>
            <p class="text-sm text-yellow-700 mt-1">This is a simplified edit form showing key parameters. For complete lab results editing, additional sections can be added as needed.</p>
        </div>
    </div>
</div>
@endsection