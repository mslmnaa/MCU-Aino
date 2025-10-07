<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <title>@yield('title', 'Aino - Medical Check-Up System')</title> --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        /* Prevent layout shift on page load by setting default states */
        .admin-layout {
            visibility: hidden;
        }

        .admin-layout.loaded {
            visibility: visible;
        }

        /* Default sidebar state for desktop */
        @media (min-width: 768px) {
            .sidebar-default {
                width: 16rem; /* w-64 */
                transform: translateX(0);
            }

            .main-content-default {
                margin-left: 16rem; /* ml-64 */
            }
        }

        /* Default sidebar state for mobile */
        @media (max-width: 767px) {
            .sidebar-default {
                width: 16rem; /* w-64 */
                transform: translateX(-100%);
            }

            .main-content-default {
                margin-left: 0;
            }
        }

        /* Sidebar content default states */
        @media (min-width: 768px) {
            /* Show full content by default on desktop */
            .logo-full {
                display: block;
            }

            .logo-icon {
                display: none;
            }

            .nav-text {
                display: inline;
                opacity: 1;
            }

            .user-info {
                display: block;
                opacity: 1;
            }
        }

        @media (max-width: 767px) {
            /* Hide text content by default on mobile */
            .logo-full {
                display: none;
            }

            .logo-icon {
                display: flex;
            }

            .nav-text {
                display: none;
            }

            .user-info {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-cream-50 min-h-screen font-sans">
    @guest
    <!-- Login Page Layout -->
    <main>
        @yield('content')
    </main>
    @endguest

    @auth
    <div class="flex min-h-screen admin-layout"
         x-data="{
             sidebarOpen: window.innerWidth >= 768,
             init() {
                 // Handle window resize
                 window.addEventListener('resize', () => {
                     if (window.innerWidth >= 768) {
                         this.sidebarOpen = true;
                     } else {
                         this.sidebarOpen = false;
                     }
                 });
             }
         }"
         x-init="$nextTick(() => { $el.classList.add('loaded') })">
        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen && window.innerWidth < 768"
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-neutral-900 bg-opacity-50 z-40 md:hidden"></div>

        <!-- Sidebar Navigation -->
        <aside class="fixed left-0 top-0 h-screen bg-white border-r border-neutral-200 shadow-sm flex flex-col z-50 transition-all duration-300 sidebar-default"
               :class="{
                   'w-64': sidebarOpen,
                   'w-16': !sidebarOpen && window.innerWidth >= 768,
                   '-translate-x-full': !sidebarOpen && window.innerWidth < 768,
                   'translate-x-0': sidebarOpen || window.innerWidth >= 768
               }">
            <!-- Logo/Brand -->
            <div class="px-6 py-8 border-b border-neutral-200 flex items-center justify-between">
                <div x-show="sidebarOpen" class="logo-full">
                    <x-logo variant="sidebar" size="md" />
                </div>
                <div x-show="!sidebarOpen" class="w-full flex justify-center logo-icon">
                    <x-logo variant="sidebar" size="md" :showText="false" />
                </div>
                <!-- Toggle Button -->
                <button @click="sidebarOpen = !sidebarOpen"
                        class="text-neutral-600 hover:bg-neutral-100 p-2 rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 transition-transform duration-200" :class="{'rotate-180': !sidebarOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 px-4 py-6">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center py-3 text-bold-600 hover:text-primary-700 hover:bg-primary-50 border-l-4 border-transparent hover:border-primary-300 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-700 border-primary-300' : '' }}"
                           :class="sidebarOpen ? 'px-4' : 'px-2 justify-center'"
                           :title="!sidebarOpen ? 'Dashboard' : ''">
                            <svg class="w-5 h-5" :class="sidebarOpen ? 'mr-3' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 5l4-3 4 3"></path>
                            </svg>
                            <span x-show="sidebarOpen" class="nav-text">Dashboard</span>
                        </a>
                    </li>

                    @if(auth()->user()->role === 'admin')
                        <li>
                            <a href="{{ route('patients.index') }}"
                               class="flex items-center py-3 text-neutral-600 hover:text-primary-700 hover:bg-primary-50 border-l-4 border-transparent hover:border-primary-300 transition-all duration-200 {{ request()->routeIs('patients.*') || request()->routeIs('medical-records.*') ? 'bg-primary-50 text-primary-700 border-primary-300' : '' }}"
                               :class="sidebarOpen ? 'px-4' : 'px-2 justify-center'"
                               :title="!sidebarOpen ? 'Employee Management' : ''">
                                <svg class="w-5 h-5" :class="sidebarOpen ? 'mr-3' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span x-show="sidebarOpen" class="nav-text">Employees</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.normal-values') }}"
                               class="flex items-center py-3 text-neutral-600 hover:text-primary-700 hover:bg-primary-50 border-l-4 border-transparent hover:border-primary-300 transition-all duration-200 {{ request()->routeIs('admin.normal-values*') ? 'bg-primary-50 text-primary-700 border-primary-300' : '' }}"
                               :class="sidebarOpen ? 'px-4' : 'px-2 justify-center'"
                               :title="!sidebarOpen ? 'Normal Values' : ''">
                                <svg class="w-5 h-5" :class="sidebarOpen ? 'mr-3' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span x-show="sidebarOpen" class="nav-text">Normal Values</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.import') }}"
                               class="flex items-center py-3 text-neutral-600 hover:text-primary-700 hover:bg-primary-50 border-l-4 border-transparent hover:border-primary-300 transition-all duration-200 {{ request()->routeIs('admin.import*') ? 'bg-primary-50 text-primary-700 border-primary-300' : '' }}"
                               :class="sidebarOpen ? 'px-4' : 'px-2 justify-center'"
                               :title="!sidebarOpen ? 'Import Data MCU' : ''">
                                <svg class="w-5 h-5" :class="sidebarOpen ? 'mr-3' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                                <span x-show="sidebarOpen" class="nav-text">Import Data</span>
                            </a>
                        </li>

                    @else
                        <li>
                            <a href="{{ route('my-health') }}"
                               class="flex items-center py-3 text-neutral-600 hover:text-primary-700 hover:bg-primary-50 border-l-4 border-transparent hover:border-primary-300 transition-all duration-200 {{ request()->routeIs('my-health') ? 'bg-primary-50 text-primary-700 border-primary-300' : '' }}"
                               :class="sidebarOpen ? 'px-4' : 'px-2 justify-center'"
                               :title="!sidebarOpen ? 'My Health Results' : ''">
                                <svg class="w-5 h-5" :class="sidebarOpen ? 'mr-3' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span x-show="sidebarOpen" class="nav-text">My Health Results</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>

            <!-- User Info & Logout -->
            <div class="px-4 py-6 border-t border-neutral-200">
                <div class="mb-4" :class="sidebarOpen ? 'flex items-center' : 'flex flex-col items-center space-y-3'">
                    <div class="relative">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                 alt="Profile Photo"
                                 class="w-10 h-10 rounded-full object-cover border-2 border-secondary-300">
                        @else
                            <div class="w-10 h-10 bg-primary-500 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                        <!-- Profile Photo Upload Button -->
                        <button onclick="document.getElementById('profile-photo-input').click()"
                                class="absolute -bottom-1 -right-1 w-4 h-4 bg-neutral-200 rounded-full border border-white flex items-center justify-center hover:bg-neutral-300 transition-colors">
                            <svg class="w-2 h-2 text-neutral-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                    </div>
                    <div x-show="sidebarOpen" class="ml-3 user-info">
                        <p class="text-sm font-medium text-neutral-700">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-neutral-500">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>

                <!-- Hidden Profile Photo Upload Form -->
                <form id="profile-photo-form" action="{{ route('profile.upload-photo') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" id="profile-photo-input" name="profile_photo" accept="image/*" onchange="document.getElementById('profile-photo-form').submit()">
                </form>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center py-2 text-neutral-600 hover:text-red-600 hover:bg-red-50 transition-colors duration-200"
                            :class="sidebarOpen ? 'px-4' : 'px-2 justify-center'"
                            :title="!sidebarOpen ? 'Sign Out' : ''">
                        <svg class="w-4 h-4" :class="sidebarOpen ? 'mr-3' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span x-show="sidebarOpen" class="nav-text">Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 bg-neutral-50 transition-all duration-300 main-content-default"
              :class="{
                  'ml-64': sidebarOpen && window.innerWidth >= 768,
                  'ml-16': !sidebarOpen && window.innerWidth >= 768,
                  'ml-0': window.innerWidth < 768
              }">
            <!-- Mobile Menu Button (Floating) -->
            <div class="md:hidden fixed top-4 left-4 z-30">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="text-primary-600 hover:text-primary-700 bg-white rounded-lg p-2 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Page Content -->
            <div class="px-8 py-6">
                @yield('content')
            </div>
        </main>
    </div>
    @endauth

    @vite('resources/js/app.js')
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>