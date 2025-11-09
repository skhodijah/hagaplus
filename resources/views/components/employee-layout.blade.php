<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Employee</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/8c8ccf764d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Alpine.js x-cloak CSS -->
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        .sidebar-closed {
            transform: translateX(-100%);
        }
        @media (min-width: 1024px) {
            .sidebar-closed {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-40 flex-shrink-0 w-72 lg:w-80 bg-white dark:bg-gray-800 shadow-xl transition-colors duration-300 sidebar-transition sidebar-closed lg:transform-none flex flex-col">
            <div class="flex items-center h-16 px-6 border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('employee.dashboard') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('images/Haga.png') }}" alt="Haga+" class="w-8 h-8">
                    <span class="text-xl font-semibold italic text-gray-900 dark:text-white">Haga+</span>
                </a>
                <button id="sidebar-close" class="ml-auto lg:hidden p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded" aria-label="Close sidebar">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <x-layout.sidebar-link :href="route('employee.dashboard')" icon="fa-solid fa-gauge" label="Dashboard" :active="request()->routeIs('employee.dashboard')" />

                <x-layout.sidebar-link :href="route('employee.attendance.index')" icon="fa-solid fa-calendar-check" label="Attendance" :active="request()->routeIs('employee.attendance.*')" />

                <x-layout.sidebar-link :href="route('employee.payroll.index')" icon="fa-solid fa-money-bill-wave" label="Payroll" :active="request()->routeIs('employee.payroll.*')" />
            </nav>

            <!-- User Info Footer -->
            <div class="mt-auto p-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3 px-4 py-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                            <i class="fa-solid fa-user text-gray-600 dark:text-gray-300"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            {{ ucfirst(Auth::user()->role) }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white dark:bg-gray-800 transition-colors duration-300">
                <div class="flex items-center justify-between px-4 sm:px-6 h-16">
                    <div class="flex items-center space-x-3">
                        <button id="sidebar-toggle" class="lg:hidden p-2 rounded text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Open sidebar">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                        <img src="{{ asset('images/Haga.png') }}" alt="Haga+" class="w-8 h-8 lg:hidden">
                    </div>

                    <!-- Right side - User menu with logout -->
                    <div class="flex items-center space-x-3">
                        <button data-theme-toggle
                            class="p-2 rounded-lg text-gray-500 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                            title="Toggle Theme">
                            <span class="block dark:hidden"><i class="fa-solid fa-moon"></i></span>
                            <span class="hidden dark:block"><i class="fa-solid fa-sun"></i></span>
                        </button>
                        <div class="hidden md:flex items-center space-x-2">
                            <span class="text-sm text-gray-700 dark:text-white">{{ Auth::user()->name }}</span>
                            <span class="text-xs text-gray-900 bg-gray-200 px-2 py-1 rounded">{{ ucfirst(Auth::user()->role) }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="hidden sm:block bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                {{ __('Logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900">
                <div class="mx-auto px-4 sm:px-6 py-6 sm:py-8 max-w-7xl">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
    
    <script>
        // Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarClose = document.getElementById('sidebar-close');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            function openSidebar() {
                sidebar.classList.remove('sidebar-closed');
                sidebarOverlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.add('sidebar-closed');
                sidebarOverlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', openSidebar);
            }

            if (sidebarClose) {
                sidebarClose.addEventListener('click', closeSidebar);
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }

            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !sidebar.classList.contains('sidebar-closed')) {
                    closeSidebar();
                }
            });
        });
    </script>
</body>

</html>