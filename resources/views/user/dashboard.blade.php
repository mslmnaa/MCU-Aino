@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary-700">User Dashboard</h1>
        <p class="text-accent-600 mt-2">Welcome back, {{ auth()->user()->name }}</p>
    </div>

    <!-- Health Check Access Card -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
        <div class="text-center">
            <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-semibold text-primary-700 mb-2">Check Your Health Results</h2>
            <p class="text-accent-600 mb-6">Enter your Share ID to view your medical check-up results</p>

            <form action="{{ route('health-check', ':shareId') }}" method="GET" class="max-w-md mx-auto" onsubmit="updateAction(event)">
                <div class="flex">
                    <input type="text" name="shareId" placeholder="Enter your Share ID (e.g., MCU001)"
                        class="flex-1 px-4 py-3 border border-accent-300 rounded-l-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        required>
                    <button type="submit" class="px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-r-lg transition-colors">
                        Check
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sample IDs -->
    <div class="bg-accent-50 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-accent-700 mb-4">Sample Share IDs for Testing</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h4 class="font-medium text-primary-600">John Doe</h4>
                <p class="text-sm text-accent-500">IT Department</p>
                <p class="text-primary-700 font-mono mt-2">MCU001</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h4 class="font-medium text-primary-600">Jane Smith</h4>
                <p class="text-sm text-accent-500">HR Department</p>
                <p class="text-primary-700 font-mono mt-2">MCU002</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h4 class="font-medium text-primary-600">Ahmad Wijaya</h4>
                <p class="text-sm text-accent-500">Finance Department</p>
                <p class="text-primary-700 font-mono mt-2">MCU003</p>
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="w-12 h-12 bg-secondary-100 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.818-4.954A9.953 9.953 0 0121 12a9.953 9.953 0 01-2.182 6.954l-8.735-8.735 8.735-8.735z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-secondary-700 mb-2">Complete Health Report</h3>
            <p class="text-accent-600">View detailed lab results, vital signs, and medical recommendations</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-green-700 mb-2">Secure Access</h3>
            <p class="text-accent-600">Your health data is protected and only accessible with your unique Share ID</p>
        </div>
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