<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'PT Aino - Medical Check-Up System' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')

    @if(isset($pdfMode) && $pdfMode)
    <style>
        /* Hide navigation and interactive elements for PDF */
        header, .no-pdf { display: none !important; }

        /* Make layout full width for PDF */
        body { background: white !important; margin: 0; padding: 20px; font-family: 'Inter', sans-serif; }
        .max-w-6xl { max-width: none !important; margin: 0 !important; }

        /* Override container styles for PDF */
        .bg-cream-50 { background-color: white !important; }

        /* Ensure good printing */
        @media print {
            * { -webkit-print-color-adjust: exact !important; color-adjust: exact !important; }
        }

        /* Override some interactive elements */
        button, a[href]:not([href^="mailto"]):not([href^="tel"]) {
            cursor: default !important;
            pointer-events: none !important;
        }
    </style>
    @endif
</head>
<body class="bg-cream-50 min-h-screen font-sans">
    @guest
    <!-- Login Page Layout -->
    <main>
        @yield('content')
    </main>
    @endguest

    @auth
    <!-- Top Navigation Header -->
    <header class="bg-primary-500 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo/Brand -->
                <div class="flex items-center">
                    <x-logo variant="light" size="sm" :showText="false" />
                    <div class="ml-3">
                        <h1 class="text-xl font-serif font-bold text-neutral-50">PT Aino</h1>
                        <p class="text-primary-100 text-sm hidden sm:block">Medical Check-Up System</p>
                    </div>
                </div>

                <!-- Navigation Menu & User Profile -->
                <div class="flex items-center space-x-6">
                    <!-- Navigation Links -->
                    <nav class="hidden md:flex space-x-6">
                        
                       
                    </nav>

                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 text-primary-100 hover:text-neutral-50 transition-colors duration-200">
                            <div class="relative">
                                @if(auth()->user()->profile_photo)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                         alt="Profile Photo"
                                         class="w-8 h-8 rounded-full object-cover border-2 border-secondary-300">
                                @else
                                    <div class="w-8 h-8 bg-secondary-500 rounded-full flex items-center justify-center text-neutral-50 font-semibold text-sm">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-medium text-neutral-50">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-primary-200">{{ ucfirst(auth()->user()->role) }}</p>
                            </div>
                            <svg class="w-4 h-4" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" x-transition
                             class="absolute right-0 mt-2 w-64 bg-neutral-50 rounded-md shadow-lg border border-cream-200 z-50">
                            <div class="p-4 border-b border-cream-200">
                                <div class="flex items-center space-x-3">
                                    <div class="relative">
                                        @if(auth()->user()->profile_photo)
                                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                                 alt="Profile Photo"
                                                 class="w-12 h-12 rounded-full object-cover border-2 border-secondary-300">
                                        @else
                                            <div class="w-12 h-12 bg-secondary-500 rounded-full flex items-center justify-center text-neutral-50 font-semibold">
                                                {{ substr(auth()->user()->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <!-- Upload Photo Button -->
                                        <button onclick="document.getElementById('profile-photo-input').click()"
                                                class="absolute -bottom-1 -right-1 w-5 h-5 bg-cream-300 rounded-full border border-neutral-50 flex items-center justify-center hover:bg-cream-400 transition-colors">
                                            <svg class="w-3 h-3 text-neutral-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div>
                                        <p class="font-medium text-neutral-900">{{ auth()->user()->name }}</p>
                                        <p class="text-sm text-neutral-500">{{ auth()->user()->email }}</p>
                                        <p class="text-xs text-primary-600 font-medium">{{ ucfirst(auth()->user()->role) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Navigation -->
                            <div class="md:hidden border-b border-cream-200">
                                <a href="{{ route('dashboard') }}"
                                   class="block px-4 py-3 text-neutral-700 hover:bg-cream-100 transition-colors {{ request()->routeIs('dashboard') ? 'bg-cream-100 text-primary-600 font-medium' : '' }}">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 5l4-3 4 3"></path>
                                    </svg>
                                    Dashboard
                                </a>
                                <a href="{{ route('my-health') }}"
                                   class="block px-4 py-3 text-neutral-700 hover:bg-cream-100 transition-colors {{ request()->routeIs('my-health') ? 'bg-cream-100 text-primary-600 font-medium' : '' }}">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    My Health Results
                                </a>
                            </div>

                            <!-- Logout -->
                            <div class="p-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-3 py-2 text-neutral-700 hover:bg-red-50 hover:text-red-600 rounded transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden Profile Photo Upload Form -->
        <form id="profile-photo-form" action="{{ route('profile.upload-photo') }}" method="POST" enctype="multipart/form-data" class="hidden">
            @csrf
            <input type="file" id="profile-photo-input" name="profile_photo" accept="image/*" onchange="document.getElementById('profile-photo-form').submit()">
        </form>
    </header>

    <!-- Page Header -->
    <div class="bg-neutral-50 border-b border-cream-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-sans font-semibold text-primary-700">
                        @yield('page-title', 'Dashboard')
                    </h2>
                    <p class="text-sm text-neutral-500 mt-1">
                        @yield('page-subtitle', 'PT Aino Medical Check-Up Management System')
                    </p>
                </div>
                <div class="text-sm text-neutral-500 hidden sm:block">
                    {{ now()->format('l, F j, Y') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
    @endauth

    @vite('resources/js/app.js')
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>