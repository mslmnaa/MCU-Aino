@extends('layouts.admin')

@section('page-title', 'Create New User')
@section('page-subtitle', 'Add a new employee to the system')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-primary-700">Create New User</h1>
            <p class="text-neutral-600 mt-1">Register a new employee with complete profile information</p>
        </div>
        <a href="{{ route('patients.index') }}"
           class="bg-neutral-500 hover:bg-neutral-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
            Back to Employees
        </a>
    </div>
</div>

@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('users.store') }}" id="create-user-form">
    @csrf

    <!-- Account Information Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="bg-green-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Account Information</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200 bg-neutral-100" style="min-width: 200px;">Field</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200" style="min-width: 300px;">Value</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider" style="min-width: 200px;">Requirements</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    <tr class="hover:bg-green-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-green-50 border-r border-neutral-200">
                            Email Address
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-center"
                                   placeholder="user@company.com"
                                   required>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Must be unique, used for login
                        </td>
                    </tr>

                    <tr class="hover:bg-blue-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-blue-50 border-r border-neutral-200">
                            Password
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <input type="password"
                                   name="password"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center"
                                   placeholder="Enter secure password"
                                   required>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Minimum 8 characters
                        </td>
                    </tr>

                    <tr class="hover:bg-indigo-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-indigo-50 border-r border-neutral-200">
                            Confirm Password
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <input type="password"
                                   name="password_confirmation"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-center"
                                   placeholder="Confirm password"
                                   required>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Must match password
                        </td>
                    </tr>

                    <tr class="hover:bg-purple-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-purple-50 border-r border-neutral-200">
                            User Role
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <select name="role"
                                    class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-center"
                                    required>
                                <option value="">Select Role</option>
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Employee</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                            </select>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Employee or Administrator
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Personal Information Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="bg-primary-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Personal Information</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200 bg-neutral-100" style="min-width: 200px;">Field</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200" style="min-width: 300px;">Value</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider" style="min-width: 200px;">Description</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    <tr class="hover:bg-primary-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-primary-50 border-r border-neutral-200">
                            Full Name
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-center"
                                   placeholder="Enter full name"
                                   required>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Employee's complete name
                        </td>
                    </tr>

                    <tr class="hover:bg-secondary-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-secondary-50 border-r border-neutral-200">
                            Gender
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <select name="jenis_kelamin"
                                    class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 text-center"
                                    required>
                                <option value="">Select Gender</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Biological gender
                        </td>
                    </tr>

                    <tr class="hover:bg-cream-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-cream-50 border-r border-neutral-200">
                            Birth Date
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <input type="date"
                                   name="tanggal_lahir"
                                   value="{{ old('tanggal_lahir') }}"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-cream-500 focus:border-cream-500 text-center"
                                   required>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Date of birth
                        </td>
                    </tr>

                    <tr class="hover:bg-primary-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-primary-50 border-r border-neutral-200">
                            Age
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <input type="number"
                                   name="umur"
                                   value="{{ old('umur') }}"
                                   min="1"
                                   max="120"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-center"
                                   placeholder="Age"
                                   required>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Current age in years
                        </td>
                    </tr>

                    <tr class="hover:bg-secondary-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-secondary-50 border-r border-neutral-200">
                            Department
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <input type="text"
                                   name="departemen"
                                   value="{{ old('departemen') }}"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 text-center"
                                   placeholder="Department"
                                   required>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Work department
                        </td>
                    </tr>

                    <tr class="hover:bg-cream-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-cream-50 border-r border-neutral-200">
                            Position
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <input type="text"
                                   name="jabatan"
                                   value="{{ old('jabatan') }}"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-cream-500 focus:border-cream-500 text-center"
                                   placeholder="Job Position"
                                   required>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Job title/position
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Lifestyle Habits Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="bg-secondary-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Lifestyle & Health Habits</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200 bg-neutral-100" style="min-width: 200px;">Habit</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200" style="min-width: 150px;">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider" style="min-width: 300px;">Description</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    <tr class="hover:bg-secondary-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-secondary-50 border-r border-neutral-200">
                            Smoking
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <label class="inline-flex items-center">
                                <input type="checkbox"
                                       name="merokok"
                                       value="1"
                                       {{ old('merokok') ? 'checked' : '' }}
                                       class="form-checkbox h-5 w-5 text-secondary-600 rounded focus:ring-secondary-500 border-gray-300">
                                <span class="ml-2 text-sm">Yes, smokes</span>
                            </label>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Regular smoking habit
                        </td>
                    </tr>

                    <tr class="hover:bg-cream-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-cream-50 border-r border-neutral-200">
                            Alcohol Consumption
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <label class="inline-flex items-center">
                                <input type="checkbox"
                                       name="minum_alkohol"
                                       value="1"
                                       {{ old('minum_alkohol') ? 'checked' : '' }}
                                       class="form-checkbox h-5 w-5 text-cream-600 rounded focus:ring-cream-500 border-gray-300">
                                <span class="ml-2 text-sm">Yes, drinks alcohol</span>
                            </label>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Regular alcohol consumption
                        </td>
                    </tr>

                    <tr class="hover:bg-primary-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-primary-50 border-r border-neutral-200">
                            Exercise
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <label class="inline-flex items-center">
                                <input type="checkbox"
                                       name="olahraga"
                                       value="1"
                                       {{ old('olahraga') ? 'checked' : '' }}
                                       class="form-checkbox h-5 w-5 text-primary-600 rounded focus:ring-primary-500 border-gray-300">
                                <span class="ml-2 text-sm">Yes, exercises regularly</span>
                            </label>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Regular physical exercise
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="flex justify-end space-x-4">
        <a href="{{ route('patients.index') }}"
           class="px-6 py-2 text-neutral-600 bg-neutral-100 hover:bg-neutral-200 rounded-lg font-medium transition-colors">
            Cancel
        </a>
        <button type="submit"
                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
            Create User
        </button>
    </div>
</form>

<style>
/* Same styling as edit-profile */
.overflow-x-auto {
    -webkit-overflow-scrolling: touch;
}

thead th {
    position: sticky;
    top: 0;
    z-index: 10;
}

tbody tr:nth-child(even) {
    background-color: rgb(249 250 251);
}

input:focus, select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-checkbox:checked {
    background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='m13.854 3.646-7.5 7.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6 10.293l7.146-7.147a.5.5 0 0 1 .708.708z'/%3e%3c/svg%3e");
}
</style>

<script>
// Auto-calculate age based on birth date
document.addEventListener('DOMContentLoaded', function() {
    const birthDateInput = document.querySelector('input[name="tanggal_lahir"]');
    const ageInput = document.querySelector('input[name="umur"]');

    function calculateAge() {
        if (birthDateInput.value) {
            const birthDate = new Date(birthDateInput.value);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            if (age >= 0 && age <= 120) {
                ageInput.value = age;
            }
        }
    }

    if (birthDateInput) {
        birthDateInput.addEventListener('change', calculateAge);
    }
});

// Form validation
document.getElementById('create-user-form').addEventListener('submit', function(e) {
    const password = document.querySelector('input[name="password"]').value;
    const confirmPassword = document.querySelector('input[name="password_confirmation"]').value;

    if (password !== confirmPassword) {
        alert('Password and confirmation do not match.');
        e.preventDefault();
        return;
    }

    if (password.length < 8) {
        alert('Password must be at least 8 characters long.');
        e.preventDefault();
        return;
    }

    const requiredFields = this.querySelectorAll('input[required], select[required]');
    let hasError = false;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('border-red-500');
            hasError = true;
        } else {
            field.classList.remove('border-red-500');
        }
    });

    if (hasError) {
        e.preventDefault();
        alert('Please fill in all required fields.');
    }
});
</script>
@endsection