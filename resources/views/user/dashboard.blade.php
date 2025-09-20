@extends('layouts.user')

@section('page-title', 'Employee Dashboard')
@section('page-subtitle', 'Access your medical check-up results and health information')

@section('content')
<!-- Main Health Check Access Section -->
<div class="bg-neutral-50 border border-cream-200 p-8 mb-8">
    <div class="text-center">
        <div class="w-16 h-16 bg-primary-100 border border-primary-200 flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-serif font-semibold text-primary-700 mb-2">Access Your Health Results</h2>
        <p class="text-neutral-600 mb-8">Enter your medical check-up ID to view detailed health examination results</p>

        <form action="{{ route('health-check', ':shareId') }}" method="GET" class="max-w-md mx-auto" onsubmit="updateAction(event)">
            <div class="flex">
                <input type="text" name="shareId" placeholder="Enter your MCU ID (e.g., MCU001)"
                    class="flex-1 px-4 py-3 border border-cream-300 focus:border-primary-500 focus:outline-none text-neutral-900"
                    required>
                <button type="submit" class="px-8 py-3 bg-primary-500 hover:bg-primary-600 text-neutral-50 font-medium transition-colors duration-200">
                    View Results
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Sample Test IDs -->
<div class="bg-cream-50 border border-cream-200 p-6 mb-8">
    <h3 class="text-lg font-serif font-semibold text-primary-700 mb-4">Sample MCU IDs for Testing</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-neutral-50 border border-cream-200 p-4">
            <h4 class="font-medium text-neutral-900">John Doe</h4>
            <p class="text-sm text-neutral-500">IT Department</p>
            <p class="text-primary-600 font-mono font-medium mt-2">MCU001</p>
        </div>
        <div class="bg-neutral-50 border border-cream-200 p-4">
            <h4 class="font-medium text-neutral-900">Jane Smith</h4>
            <p class="text-sm text-neutral-500">HR Department</p>
            <p class="text-primary-600 font-mono font-medium mt-2">MCU002</p>
        </div>
        <div class="bg-neutral-50 border border-cream-200 p-4">
            <h4 class="font-medium text-neutral-900">Ahmad Wijaya</h4>
            <p class="text-sm text-neutral-500">Finance Department</p>
            <p class="text-primary-600 font-mono font-medium mt-2">MCU003</p>
        </div>
    </div>
</div>

<!-- System Features -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-neutral-50 border border-cream-200 p-6">
        <div class="w-12 h-12 bg-secondary-100 border border-secondary-200 flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-serif font-semibold text-secondary-700 mb-2">Comprehensive Health Report</h3>
        <p class="text-neutral-600 mb-4">Access complete medical examination results including:</p>
        <ul class="text-sm text-neutral-600 space-y-1">
            <li>• Blood tests (Hematology, Liver, Kidney, Lipid Profile)</li>
            <li>• Urine analysis and blood sugar tests</li>
            <li>• Radiology (ECG, Chest X-Ray)</li>
            <li>• Physical exams (Eye, Dental, Vital Signs)</li>
            <li>• Tumor markers and nutritional status</li>
        </ul>
    </div>

    <div class="bg-neutral-50 border border-cream-200 p-6">
        <div class="w-12 h-12 bg-cream-200 border border-cream-300 flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-cream-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-serif font-semibold text-cream-700 mb-2">Secure Data Protection</h3>
        <p class="text-neutral-600">Your personal health information is protected with enterprise-level security and accessed only with your unique ID</p>
    </div>
</div>

<script>
function updateAction(event) {
    const shareId = event.target.shareId.value;
    const form = event.target;
    form.action = form.action.replace(':shareId', shareId);
}
</script>
@endsection