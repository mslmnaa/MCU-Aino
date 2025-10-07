@extends('layouts.admin')

@section('page-title', '')
@section('page-subtitle', '')

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
                    <span class="ml-1 text-sm font-medium text-neutral-500 md:ml-2">Employees</span>
                </div>
            </li>
        </ol>
    </nav>
</div>

<!-- Header with Action Buttons -->
<div class="mb-6">

</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
    {{ session('error') }}
</div>
@endif

<div class="bg-white border border-neutral-200 rounded-xl shadow-lg overflow-hidden">
    <!-- Mobile View -->
    <div class="block lg:hidden">
        @foreach($patients as $patient)
        <div class="border-b border-neutral-200 p-4">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        @if($patient->profile_photo || $patient->user?->profile_photo)
                            <img src="{{ asset('storage/' . ($patient->profile_photo ?? $patient->user->profile_photo)) }}"
                                 alt="{{ $patient->name }}"
                                 class="h-12 w-12 rounded-full object-cover border-2 border-primary-300 shadow-sm">
                        @else
                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center border-2 border-primary-300 shadow-sm">
                                <span class="text-white text-sm font-bold">
                                    {{ strtoupper(substr($patient->name, 0, 2)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="text-base font-medium text-neutral-900">{{ $patient->name }}</div>
                        <div class="text-sm text-primary-600">{{ $patient->jabatan }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                <div>
                    <span class="text-neutral-500">Department:</span>
                    <p class="font-medium text-neutral-700">{{ $patient->departemen }}</p>
                </div>
                <div>
                    <span class="text-neutral-500">Account:</span>
                    <p class="text-neutral-700">{{ $patient->user?->email ?? 'No account' }}</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                <!-- View Medical Records -->
                <a href="{{ route('patients.show', $patient) }}"
                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-primary-600 bg-primary-50 hover:bg-primary-100 transition-colors border border-primary-200"
                   title="View Medical Records">
                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Medical
                </a>

                @if($patient->user)
                <!-- Edit Employee (Combined) -->
                <a href="{{ route('users.edit', $patient->user->id) }}"
                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors border border-blue-200"
                   title="Edit Employee">
                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>

                <!-- Delete User -->
                <form method="POST" action="{{ route('users.destroy', $patient->user->id) }}" class="inline-block delete-form" data-user-name="{{ $patient->name }}">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-red-600 bg-red-50 hover:bg-red-100 transition-colors border border-red-200 delete-btn"
                            title="Delete Employee">
                        <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete
                    </button>
                </form>
                @else
                <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-neutral-500 bg-neutral-100 rounded-md">
                    No account linked
                </span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Desktop Table View -->
    <div class="hidden lg:block overflow-x-auto">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider">Employee Info</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider">Department</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider">Account</th>
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
                   
                    <td class="px-6 py-4 text-sm text-neutral-700">{{ $patient->departemen }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-neutral-900">{{ $patient->user?->email ?? 'No account' }}</div>
                        <div class="text-sm text-neutral-500">
                            {{-- <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $patient->user?->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $patient->user ? ucfirst($patient->user->role) : 'No Role' }}
                            </span> --}}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <!-- View Medical Records -->
                            <a href="{{ route('patients.show', $patient) }}"
                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-primary-600 bg-primary-50 hover:bg-primary-100 transition-colors border border-primary-200"
                               title="View Medical Records">
                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Medical
                            </a>

                            @if($patient->user)
                            <!-- Edit Employee (Combined) -->
                            <a href="{{ route('users.edit', $patient->user->id) }}"
                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors border border-blue-200"
                               title="Edit Employee">
                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>

                            <!-- Delete User -->
                            <form method="POST" action="{{ route('users.destroy', $patient->user->id) }}" class="inline-block delete-form" data-user-name="{{ $patient->name }}">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-red-600 bg-red-50 hover:bg-red-100 transition-colors border border-red-200 delete-btn"
                                        title="Delete Employee">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </button>
                            </form>
                            @else
                            <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-neutral-500 bg-neutral-100 rounded-md">
                                No account linked
                            </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination for both mobile and desktop -->
    <div class="px-4 lg:px-6 py-4 bg-neutral-50 border-t border-neutral-200">
        {{ $patients->links() }}
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.93L13.732 4.242a2 2 0 00-3.464 0L3.34 16.07c-.77 1.263.192 2.93 1.732 2.93z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Delete Employee</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete employee <span class="font-semibold text-gray-900" id="employeeName"></span>? This action cannot be undone and will permanently remove all associated data.
                </p>
            </div>
            <div class="flex justify-center gap-3 mt-4">
                <button id="cancelDelete"
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                    Cancel
                </button>
                <button id="confirmDelete"
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                    Delete Employee
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Employee management functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Employee management page loaded successfully');

    // Delete modal functionality
    const deleteModal = document.getElementById('deleteModal');
    const employeeNameSpan = document.getElementById('employeeName');
    const cancelDeleteBtn = document.getElementById('cancelDelete');
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    const deleteButtons = document.querySelectorAll('.delete-btn');
    let currentForm = null;

    deleteButtons.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-form');
            const employeeName = form.dataset.userName;

            currentForm = form;
            employeeNameSpan.textContent = employeeName;
            deleteModal.classList.remove('hidden');
        });
    });

    cancelDeleteBtn.addEventListener('click', function() {
        deleteModal.classList.add('hidden');
        currentForm = null;
    });

    confirmDeleteBtn.addEventListener('click', function() {
        if (currentForm) {
            currentForm.submit();
        }
    });

    // Close modal when clicking outside
    deleteModal.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            deleteModal.classList.add('hidden');
            currentForm = null;
        }
    });

    // Close modal with escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
            deleteModal.classList.add('hidden');
            currentForm = null;
        }
    });
});
</script>
@endsection