@extends('layouts.admin')

@section('page-title', 'Import Data MCU')
@section('page-subtitle', 'Upload file Excel/CSV untuk import data medical check-up')

@section('content')
<!-- Breadcrumb -->
<div class="mb-6">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-neutral-700 hover:text-primary-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-neutral-500 md:ml-2">Import Data</span>
                </div>
            </li>
        </ol>
    </nav>
</div>

<div class="max-w-4xl mx-auto">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    {{-- <!-- Instructions Card -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-blue-800 mb-3">📋 Petunjuk Import Data MCU</h3>
        <div class="text-sm text-blue-700 space-y-2">
            <p><strong>Format File:</strong> Excel (.xlsx, .xls) atau CSV</p>
            <p><strong>Ukuran Maksimal:</strong> 10MB</p>
            <p><strong>Kolom yang Diperlukan:</strong></p>
            <ul class="list-disc list-inside ml-4 space-y-1">
                <li><strong>Nama</strong> (wajib) - Nama lengkap pasien</li>
                <li><strong>Jenis Kelamin</strong> - L/P atau Laki-laki/Perempuan</li>
                <li><strong>Umur</strong> - Angka dalam tahun</li>
                <li><strong>Departemen</strong> - Nama departemen</li>
                <li><strong>Jabatan</strong> - Posisi/jabatan</li>
                <li><strong>Tanggal MCU</strong> - Format: YYYY-MM-DD atau DD/MM/YYYY</li>
                <li><strong>No Lab</strong> - Nomor laboratorium (opsional)</li>
            </ul>
            <p><strong>Data Lab (opsional):</strong> Hemoglobin, Leukosit, Trombosit, SGOT, SGPT, Kolesterol, Glukosa Puasa, Berat Badan, Tinggi Badan, Tekanan Darah</p>
        </div>
    </div> --}}

    <!-- Template Download -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-medium text-yellow-800">📥 Download Template Excel</h4>
                <p class="text-sm text-yellow-700">Gunakan template ini untuk memastikan format data yang benar</p>
            </div>
            <a href="{{ asset('templates/sample-data.csv') }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Download Template
            </a>
        </div>
    </div>

    <!-- Upload Form -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-primary-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Upload File MCU Data</h3>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.import.preview') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- File Upload -->
                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih File Excel/CSV
                    </label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-400 transition-colors">
                        <input type="file"
                               id="file"
                               name="file"
                               accept=".xlsx,.xls,.csv"
                               required
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

                        <div class="space-y-2" id="upload-content">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="text-sm text-gray-600">
                                <span class="font-medium text-primary-600">Klik untuk upload</span> atau drag & drop file
                            </div>
                            <p class="text-xs text-gray-500">Excel (.xlsx, .xls) atau CSV (max. 10MB)</p>
                        </div>
                        <div class="space-y-2 hidden" id="file-selected-content">
                            <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-green-600">
                                <span class="font-medium">File berhasil dipilih!</span>
                            </div>
                            <p class="text-xs text-green-500" id="selected-file-details"></p>
                        </div>
                    </div>

                    <div id="file-info" class="mt-2 text-sm text-gray-600 hidden">
                        <span id="file-name"></span>
                        <span id="file-size" class="text-gray-500"></span>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center">
                    <button type="submit"
                            class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Preview Data
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
document.getElementById('file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');

    // Visual feedback elements
    const uploadContent = document.getElementById('upload-content');
    const fileSelectedContent = document.getElementById('file-selected-content');
    const selectedFileDetails = document.getElementById('selected-file-details');

    if (file) {
        // Calculate appropriate file size unit
        let fileSizeValue, fileSizeUnit;
        if (file.size >= 1024 * 1024) {
            fileSizeValue = (file.size / 1024 / 1024).toFixed(2);
            fileSizeUnit = ' MB';
        } else if (file.size >= 1024) {
            fileSizeValue = (file.size / 1024).toFixed(2);
            fileSizeUnit = ' KB';
        } else {
            fileSizeValue = file.size;
            fileSizeUnit = ' bytes';
        }

        const fileSizeText = fileSizeValue + fileSizeUnit;

        // Update traditional file info
        fileName.textContent = file.name;
        fileSize.textContent = ' (' + fileSizeText + ')';
        fileInfo.classList.remove('hidden');

        // Show visual feedback
        uploadContent.classList.add('hidden');
        fileSelectedContent.classList.remove('hidden');
        selectedFileDetails.textContent = file.name + ' (' + fileSizeText + ')';

        // Change border color to green to indicate success
        const dropZone = fileSelectedContent.closest('.relative');
        dropZone.classList.remove('border-gray-300', 'hover:border-primary-400');
        dropZone.classList.add('border-green-400', 'bg-green-50');

    } else {
        // Reset to original state
        fileInfo.classList.add('hidden');
        uploadContent.classList.remove('hidden');
        fileSelectedContent.classList.add('hidden');

        // Reset border color
        const dropZone = uploadContent.closest('.relative');
        dropZone.classList.remove('border-green-400', 'bg-green-50');
        dropZone.classList.add('border-gray-300', 'hover:border-primary-400');
    }
});

// Also add drag and drop visual feedback
const dropZone = document.querySelector('.relative.border-2.border-dashed');
if (dropZone) {
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-primary-500', 'bg-primary-50');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-primary-500', 'bg-primary-50');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-primary-500', 'bg-primary-50');
    });
}
</script>
@endsection