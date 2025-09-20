<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aino Medical System</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-primary-50 to-secondary-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <div class="flex items-center justify-center mb-4">
                <x-logo variant="default" size="lg" />
            </div>
        </div>

        <form method="POST" action="/login" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-accent-700 mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 border border-accent-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                    placeholder="Enter your email">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-accent-700 mb-2">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-3 border border-accent-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                    placeholder="Enter your password">
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-primary-500 hover:bg-primary-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 transform hover:scale-[1.02]">
                Login
            </button>
        </form>

        {{-- <div class="mt-8 p-4 bg-accent-50 rounded-lg">
            <p class="text-sm text-accent-600 font-medium mb-2">Demo Accounts:</p>
            <div class="space-y-1 text-xs text-accent-500">
                <p><strong>Admin:</strong> admin@mcu.com / password</p>
                <p><strong>User:</strong> john@example.com / password</p>
            </div>
        </div> --}}
    </div>
</body>
</html>