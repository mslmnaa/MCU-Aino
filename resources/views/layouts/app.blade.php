<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'MCU System' }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-accent-50 min-h-screen">
    <!-- Header -->
    @auth
    <nav class="bg-white shadow-lg border-b-4 border-primary-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-primary-500">MCU System</h1>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <a href="{{ route('dashboard') }}" class="text-primary-700 hover:text-secondary-500 px-3 py-2 rounded-md text-sm font-medium transition-colors">Dashboard</a>
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('patients.index') }}" class="text-primary-700 hover:text-secondary-500 px-3 py-2 rounded-md text-sm font-medium transition-colors">Patients</a>
                                <a href="{{ route('admin.health-records') }}" class="text-primary-700 hover:text-secondary-500 px-3 py-2 rounded-md text-sm font-medium transition-colors">Health Records</a>
                            @else
                                <a href="{{ route('my-health') }}" class="text-primary-700 hover:text-secondary-500 px-3 py-2 rounded-md text-sm font-medium transition-colors">My Health</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-primary-600">{{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Main Content -->
    <main class="py-6">
        @yield('content')
    </main>

    @vite('resources/js/app.js')
</body>
</html>