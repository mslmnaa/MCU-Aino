@extends('layouts.admin')

@section('page-title', 'Edit Employee')
@section('page-subtitle', $user->name . ' • ' . ucfirst($user->role))

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-primary-700">Edit Employee Information</h1>
            <p class="text-neutral-600 mt-1">Update employee personal information and security settings</p>
        </div>
        <a href="{{ route('patients.index') }}"
           class="bg-neutral-500 hover:bg-neutral-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
            Back to Employees
        </a>
    </div>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('users.update', $user->id) }}" id="user-form">
    @csrf
    @method('PUT')

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
                        <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200" style="min-width: 300px;">Current Value</th>
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
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-center"
                                   placeholder="Enter full name"
                                   required>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Employee's complete name as registered
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
                                <option value="L" {{ old('jenis_kelamin', $user->patient->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $user->patient->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Employee's biological gender
                        </td>
                    </tr>

                    <tr class="hover:bg-cream-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-cream-50 border-r border-neutral-200">
                            Birth Date
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <input type="date"
                                   name="tanggal_lahir"
                                   value="{{ old('tanggal_lahir', $user->patient->tanggal_lahir?->format('Y-m-d') ?? '') }}"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-cream-500 focus:border-cream-500 text-center"
                                   required>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Date of birth (YYYY-MM-DD)
                        </td>
                    </tr>

                    <tr class="hover:bg-primary-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-primary-50 border-r border-neutral-200">
                            Age
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <input type="number"
                                   name="umur"
                                   value="{{ old('umur', $user->patient->umur ?? '') }}"
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
                                   value="{{ old('departemen', $user->patient->departemen ?? '') }}"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 text-center"
                                   placeholder="Department"
                                   required>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Employee's work department
                        </td>
                    </tr>

                    <tr class="hover:bg-cream-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-cream-50 border-r border-neutral-200">
                            Position
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <input type="text"
                                   name="jabatan"
                                   value="{{ old('jabatan', $user->patient->jabatan ?? '') }}"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-cream-500 focus:border-cream-500 text-center"
                                   placeholder="Job Position"
                                   required>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Employee's job title/position
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Security Information Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="bg-red-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Account Security Settings</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200 bg-neutral-100" style="min-width: 200px;">Security Field</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-700 uppercase tracking-wider border-r border-neutral-200" style="min-width: 350px;">New Value</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider" style="min-width: 250px;">Security Notes</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    <tr class="hover:bg-red-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-red-50 border-r border-neutral-200">
                            Email Address
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <input type="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-center"
                                   placeholder="user@company.com"
                                   required>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Used for login and system notifications
                        </td>
                    </tr>

                    <tr class="hover:bg-orange-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-orange-50 border-r border-neutral-200">
                            Current Password
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <input type="password"
                                   name="current_password"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-center"
                                   placeholder="Required if changing password">
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Only required when changing password
                        </td>
                    </tr>

                    <tr class="hover:bg-yellow-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-yellow-50 border-r border-neutral-200">
                            New Password
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <input type="password"
                                   name="password"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-center"
                                   placeholder="Leave empty to keep current">
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Minimum 8 characters. Leave empty to keep current
                        </td>
                    </tr>

                    <tr class="hover:bg-green-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 bg-green-50 border-r border-neutral-200">
                            Confirm New Password
                        </td>
                        <td class="px-4 py-3 text-sm text-center border-r border-neutral-200">
                            <input type="password"
                                   name="password_confirmation"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-center"
                                   placeholder="Confirm new password">
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Must match the new password exactly
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
                                       {{ old('merokok', $user->patient->merokok ?? false) ? 'checked' : '' }}
                                       class="form-checkbox h-5 w-5 text-secondary-600 rounded focus:ring-secondary-500 border-gray-300">
                                <span class="ml-2 text-sm">Yes, I smoke</span>
                            </label>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Regular smoking habit (cigarettes, tobacco, etc.)
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
                                       {{ old('minum_alkohol', $user->patient->minum_alkohol ?? false) ? 'checked' : '' }}
                                       class="form-checkbox h-5 w-5 text-cream-600 rounded focus:ring-cream-500 border-gray-300">
                                <span class="ml-2 text-sm">Yes, I drink alcohol</span>
                            </label>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Regular alcohol consumption habit
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
                                       {{ old('olahraga', $user->patient->olahraga ?? false) ? 'checked' : '' }}
                                       class="form-checkbox h-5 w-5 text-primary-600 rounded focus:ring-primary-500 border-gray-300">
                                <span class="ml-2 text-sm">Yes, I exercise regularly</span>
                            </label>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            Regular physical exercise/sports activity
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
                class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors">
            Update Employee Information
        </button>
    </div>
</form>

<style>
/* Ensure horizontal scroll on mobile */
.overflow-x-auto {
    -webkit-overflow-scrolling: touch;
}

/* Table sticky header */
thead th {
    position: sticky;
    top: 0;
    z-index: 10;
}

/* Zebra striping for better readability */
tbody tr:nth-child(even) {
    background-color: rgb(249 250 251);
}

/* Focus states for better UX */
input:focus, select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Checkbox styling */
.form-checkbox:checked {
    background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='m13.854 3.646-7.5 7.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6 10.293l7.146-7.147a.5.5 0 0 1 .708.708z'/%3e%3c/svg%3e");
}

/* Password strength indicator */
.password-strength {
    height: 4px;
    border-radius: 2px;
    margin-top: 4px;
    transition: all 0.3s ease;
}

.password-weak {
    background-color: #ef4444;
    width: 33%;
}

.password-medium {
    background-color: #f59e0b;
    width: 66%;
}

.password-strong {
    background-color: #10b981;
    width: 100%;
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

    // Password strength indicator
    const passwordInput = document.querySelector('input[name="password"]');
    const confirmPasswordInput = document.querySelector('input[name="password_confirmation"]');
    const currentPasswordInput = document.querySelector('input[name="current_password"]');

    function checkPasswordStrength(password) {
        let strength = 0;

        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;

        return strength;
    }

    function updatePasswordStrength() {
        const password = passwordInput.value;
        let strengthIndicator = passwordInput.parentNode.querySelector('.password-strength');

        if (!strengthIndicator && password.length > 0) {
            strengthIndicator = document.createElement('div');
            strengthIndicator.className = 'password-strength';
            passwordInput.parentNode.appendChild(strengthIndicator);
        }

        if (password.length === 0) {
            if (strengthIndicator) {
                strengthIndicator.remove();
            }
            return;
        }

        const strength = checkPasswordStrength(password);

        if (strength <= 2) {
            strengthIndicator.className = 'password-strength password-weak';
        } else if (strength <= 4) {
            strengthIndicator.className = 'password-strength password-medium';
        } else {
            strengthIndicator.className = 'password-strength password-strong';
        }
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', updatePasswordStrength);
    }

    // Form validation
    document.getElementById('user-form').addEventListener('submit', function(e) {
        const requiredFields = this.querySelectorAll('input[required], select[required]');
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const currentPassword = currentPasswordInput.value;

        let hasError = false;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                hasError = true;
            } else {
                field.classList.remove('border-red-500');
            }
        });

        // Check password requirements if password is being changed
        if (password) {
            if (!currentPassword) {
                alert('Current password is required when changing password.');
                e.preventDefault();
                return;
            }

            if (password !== confirmPassword) {
                alert('New password and confirmation do not match.');
                e.preventDefault();
                return;
            }

            if (password.length < 8) {
                alert('New password must be at least 8 characters long.');
                e.preventDefault();
                return;
            }
        }

        if (hasError) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
});
</script>
@endsection