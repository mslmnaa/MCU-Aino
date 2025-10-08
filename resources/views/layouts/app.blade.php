<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Aino - Medical Check-Up System' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-cream-50 min-h-screen font-sans">
    @guest
    <!-- Login Page Layout -->
    <main>
        @yield('content')
    </main>
    @endguest

    @auth
    <div class="flex min-h-screen">
        <!-- Mobile Menu Button -->
        <button id="mobile-menu-button" class="fixed top-4 left-4 z-50 lg:hidden bg-primary-500 text-white p-2 rounded-lg shadow-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Mobile Overlay -->
        <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="fixed lg:relative w-64 bg-primary-500 flex flex-col h-full z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
            <!-- Logo/Brand -->
            <div class="px-6 py-8 border-b border-primary-400">
                <x-logo variant="light" size="md" />
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 px-4 py-6">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center px-4 py-3 text-primary-100 hover:text-neutral-50 hover:bg-primary-400 border-l-4 border-transparent hover:border-secondary-300 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-primary-400 text-neutral-50 border-secondary-300' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 5l4-3 4 3"></path>
                            </svg>
                            Dashboard
                        </a>
                    </li>

                    @if(auth()->user()->role === 'admin')
                        <li>
                            <a href="{{ route('patients.index') }}"
                               class="flex items-center px-4 py-3 text-primary-100 hover:text-neutral-50 hover:bg-primary-400 border-l-4 border-transparent hover:border-secondary-300 transition-all duration-200 {{ request()->routeIs('patients.*') ? 'bg-primary-400 text-neutral-50 border-secondary-300' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Patient Records
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.health-records') }}"
                               class="flex items-center px-4 py-3 text-primary-100 hover:text-neutral-50 hover:bg-primary-400 border-l-4 border-transparent hover:border-secondary-300 transition-all duration-200 {{ request()->routeIs('admin.health-records') ? 'bg-primary-400 text-neutral-50 border-secondary-300' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Health Records
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('my-health') }}"
                               class="flex items-center px-4 py-3 text-primary-100 hover:text-neutral-50 hover:bg-primary-400 border-l-4 border-transparent hover:border-secondary-300 transition-all duration-200 {{ request()->routeIs('my-health') ? 'bg-primary-400 text-neutral-50 border-secondary-300' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                My Health Results
                            </a>
                        </li>
                    @endif

                    <!-- Health Comparison - Available to all authenticated users -->
                    <li>
                        <a href="{{ route('health-comparison.index') }}"
                           class="flex items-center px-4 py-3 text-primary-100 hover:text-neutral-50 hover:bg-primary-400 border-l-4 border-transparent hover:border-secondary-300 transition-all duration-200 {{ request()->routeIs('health-comparison.*') ? 'bg-primary-400 text-neutral-50 border-secondary-300' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Health Comparison
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- User Info & Logout -->
            <div class="px-4 py-6 border-t border-primary-400">
                <div class="flex items-center mb-4">
                    <div class="relative">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                 alt="Profile Photo"
                                 class="w-10 h-10 rounded-full object-cover border-2 border-secondary-300">
                        @else
                            <div class="w-10 h-10 bg-secondary-500 rounded-full flex items-center justify-center text-neutral-50 font-semibold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                        <!-- Profile Photo Upload Button -->
                        <button onclick="document.getElementById('profile-photo-input').click()"
                                class="absolute -bottom-1 -right-1 w-4 h-4 bg-cream-300 rounded-full border border-neutral-50 flex items-center justify-center hover:bg-cream-400 transition-colors">
                            <svg class="w-2 h-2 text-neutral-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-neutral-50">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-primary-200">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>

                <!-- Hidden Profile Photo Upload Form -->
                <form id="profile-photo-form" action="{{ route('profile.upload-photo') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" id="profile-photo-input" name="profile_photo" accept="image/*" onchange="document.getElementById('profile-photo-form').submit()">
                </form>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-2 text-primary-100 hover:text-neutral-50 hover:bg-primary-400 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 bg-neutral-50 lg:ml-0">
            <!-- Page Header -->
            <header class="bg-neutral-50 border-b border-cream-200 px-4 lg:px-8 py-6">
                <div class="flex items-center justify-between ml-0 lg:ml-0">
                    <div>
                
                        <p class="text-sm text-neutral-500 mt-1">
                            @yield('page-subtitle', 'PT Aino Medical Check-Up Management System')
                        </p>
                    </div>
                    <div class="text-sm text-neutral-500">
                        {{ now()->format('l, F j, Y') }}
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="px-4 lg:px-8 py-6">
                @yield('content')
            </div>
        </main>
    </div>  
    @endauth

    @vite('resources/js/app.js')

    <!-- Mobile Menu JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');

            if (mobileMenuButton && sidebar && overlay) {
                function toggleMobileMenu() {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                }

                mobileMenuButton.addEventListener('click', toggleMobileMenu);
                overlay.addEventListener('click', toggleMobileMenu);

                // Close menu when clicking a link
                const sidebarLinks = sidebar.querySelectorAll('a');
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth < 1024) {
                            toggleMobileMenu();
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>