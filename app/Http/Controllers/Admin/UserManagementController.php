<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\Patient;

class UserManagementController extends Controller
{
    public function editProfile($userId)
    {
        $user = User::with('patient')->findOrFail($userId);

        return view('admin.employees.edit-profile', compact('user'));
    }

    public function edit($userId)
    {
        $user = User::with('patient')->findOrFail($userId);

        return view('admin.employees.edit', compact('user'));
    }

    public function updateProfile(Request $request, $userId)
    {
        $user = User::with('patient')->findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'umur' => 'required|integer|min:1|max:120',
            'departemen' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'merokok' => 'boolean',
            'minum_alkohol' => 'boolean',
            'olahraga' => 'boolean',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Update user data
        $user->update([
            'name' => $request->name,
        ]);

        // Update patient data
        $patientData = [
            'name' => $request->name,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'umur' => $request->umur,
            'departemen' => $request->departemen,
            'jabatan' => $request->jabatan,
            'merokok' => $request->has('merokok'),
            'minum_alkohol' => $request->has('minum_alkohol'),
            'olahraga' => $request->has('olahraga'),
        ];

        if ($user->patient) {
            $user->patient->update($patientData);
        } else {
            // Create patient record if doesn't exist
            $patientData['user_id'] = $user->id;
            $patientData['share_id'] = 'EMP' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
            Patient::create($patientData);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully'
            ]);
        }

        return redirect()
            ->route('patients.index')
            ->with('success', 'Profile updated successfully');
    }

    public function update(Request $request, $userId)
    {
        $user = User::with('patient')->findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'umur' => 'required|integer|min:1|max:120',
            'departemen' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'merokok' => 'boolean',
            'minum_alkohol' => 'boolean',
            'olahraga' => 'boolean',
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'current_password' => 'required_with:password',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Verify current password if changing password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['current_password' => ['Current password is incorrect']]
                    ], 422);
                }
                return back()->withErrors(['current_password' => 'Current password is incorrect'])->withInput();
            }
        }

        // Update user data
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Update patient data
        $patientData = [
            'name' => $request->name,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'umur' => $request->umur,
            'departemen' => $request->departemen,
            'jabatan' => $request->jabatan,
            'merokok' => $request->has('merokok'),
            'minum_alkohol' => $request->has('minum_alkohol'),
            'olahraga' => $request->has('olahraga'),
        ];

        if ($user->patient) {
            $user->patient->update($patientData);
        } else {
            // Create patient record if doesn't exist
            $patientData['user_id'] = $user->id;
            $patientData['share_id'] = 'EMP' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
            Patient::create($patientData);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User information updated successfully'
            ]);
        }

        return redirect()
            ->route('patients.index')
            ->with('success', 'User information updated successfully');
    }

    public function editCredentials($userId)
    {
        $user = User::findOrFail($userId);

        return view('admin.employees.edit-credentials', compact('user'));
    }

    public function updateCredentials(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'current_password' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['current_password' => ['Current password is incorrect']]
                ], 422);
            }
            return back()->withErrors(['current_password' => 'Current password is incorrect'])->withInput();
        }

        $updateData = [
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Credentials updated successfully'
            ]);
        }

        return redirect()
            ->route('patients.index')
            ->with('success', 'Credentials updated successfully');
    }

    public function createUser()
    {
        return view('admin.employees.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:admin,user',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'umur' => 'required|integer|min:1|max:120',
            'departemen' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'merokok' => 'boolean',
            'minum_alkohol' => 'boolean',
            'olahraga' => 'boolean',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Create patient record
        Patient::create([
            'user_id' => $user->id,
            'share_id' => 'EMP' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
            'name' => $request->name,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'umur' => $request->umur,
            'departemen' => $request->departemen,
            'jabatan' => $request->jabatan,
            'merokok' => $request->has('merokok'),
            'minum_alkohol' => $request->has('minum_alkohol'),
            'olahraga' => $request->has('olahraga'),
        ]);

        return redirect()
            ->route('patients.index')
            ->with('success', 'User created successfully');
    }

    public function deleteUser($userId)
    {
        $user = User::with('patient')->findOrFail($userId);

        // Prevent deleting admin user if it's the only admin
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()
                    ->route('patients.index')
                    ->with('error', 'Cannot delete the last admin user');
            }
        }

        // Delete patient record first (if exists)
        if ($user->patient) {
            $user->patient->delete();
        }

        // Delete user
        $user->delete();

        return redirect()
            ->route('patients.index')
            ->with('success', 'User deleted successfully');
    }
}