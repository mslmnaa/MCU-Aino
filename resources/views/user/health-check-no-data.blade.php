@extends('layouts.user')

@section('page-title', 'My Health Results')
@section('page-subtitle', 'Medical Check-Up Records')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- No Patient Data -->
    <div class="bg-white border border-neutral-200 rounded-lg p-12 text-center">
        <div class="w-20 h-20 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </div>
        <h3 class="text-2xl font-semibold text-neutral-800 mb-3">No Medical Records Found</h3>
        <p class="text-neutral-600 mb-6 max-w-md mx-auto">
            We couldn't find any medical records associated with your account. This might be because:
        </p>

        <div class="bg-neutral-50 rounded-lg p-6 text-left max-w-lg mx-auto mb-6">
            <ul class="space-y-2 text-sm text-neutral-700">
                <li class="flex items-start">
                    <span class="text-neutral-400 mr-2">•</span>
                    Your medical records haven't been uploaded to the system yet
                </li>
                <li class="flex items-start">
                    <span class="text-neutral-400 mr-2">•</span>
                    Your account isn't linked to a patient record
                </li>
                <li class="flex items-start">
                    <span class="text-neutral-400 mr-2">•</span>
                    There might be a technical issue with your account setup
                </li>
            </ul>
        </div>

        <div class="space-y-3">
            <p class="text-sm text-neutral-600">
                Please contact the administrator or HR department for assistance.
            </p>

            <div class="flex justify-center space-x-4">
                <button onclick="window.location.reload()" class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 text-sm font-medium rounded-lg transition-colors">
                    Refresh Page
                </button>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection