@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary-700">My Health</h1>
        <p class="text-accent-600 mt-2">Access your health information</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="text-center">
            <div class="w-20 h-20 bg-secondary-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-semibold text-primary-700 mb-4">Personal Health Records</h2>
            <p class="text-accent-600 mb-8">To access your health records, please enter your unique Share ID provided by your healthcare provider.</p>

            <form action="{{ route('health-check', ':shareId') }}" method="GET" class="max-w-md mx-auto mb-8" onsubmit="updateHealthAction(event)">
                <div class="flex">
                    <input type="text" name="shareId" placeholder="Enter your Share ID"
                        class="flex-1 px-4 py-3 border border-accent-300 rounded-l-lg focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500"
                        required>
                    <button type="submit" class="px-6 py-3 bg-secondary-500 hover:bg-secondary-600 text-white font-medium rounded-r-lg transition-colors">
                        Access
                    </button>
                </div>
            </form>

            <div class="text-left max-w-md mx-auto">
                <h3 class="font-semibold text-accent-700 mb-3">What you can access:</h3>
                <div class="space-y-2 text-sm text-accent-600">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Complete laboratory test results
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Vital signs and physical examination
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Medical recommendations and advice
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Historical health data
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="mt-6 bg-accent-50 border border-accent-200 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-accent-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-accent-800">Need Help?</h3>
                <div class="mt-2 text-sm text-accent-700">
                    <p>If you don't have your Share ID or need assistance, please contact your HR department or healthcare provider.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateHealthAction(event) {
    const shareId = event.target.shareId.value;
    const form = event.target;
    form.action = form.action.replace(':shareId', shareId);
}
</script>
@endsection