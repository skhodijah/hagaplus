@php
    $adminSettings = \App\Models\Admin\Setting::where('instansi_id', Auth::user()->instansi_id ?? 1)
        ->whereIn('key', ['logo_path', 'company_name_display'])
        ->pluck('value', 'key')
        ->toArray();

    $logoPath = $adminSettings['logo_path'] ?? '';
    $companyName = $adminSettings['company_name_display'] ?? 'Haga+';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HagaPlus - Employee</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/8c8ccf764d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Alpine.js x-cloak CSS -->
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="flex h-screen overflow-hidden">
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-40 flex-shrink-0 w-72 lg:w-80 bg-white dark:bg-gray-800 shadow-xl transition-colors duration-300 sidebar-transition sidebar-closed lg:transform-none">
            <div class="flex items-center h-16 px-6 border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('employee.dashboard') }}" class="flex items-center space-x-3">
                    @if($logoPath)
                        <img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $companyName }}" class="w-8 h-8">
                    @else
                        <img src="{{ asset('images/Haga.png') }}" alt="Haga+" class="w-8 h-8">
                    @endif
                    <span class="text-xl font-semibold italic text-gray-900 dark:text-white">{{ $companyName }}</span>
                </a>
                <button id="sidebar-close" class="ml-auto lg:hidden p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded" aria-label="Close sidebar">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <x-layout.sidebar-link :href="route('employee.dashboard')" icon="fa-solid fa-gauge" label="Dashboard" :active="request()->routeIs('employee.dashboard')" />

                <x-layout.sidebar-link :href="route('employee.attendance.index')" icon="fa-solid fa-calendar-check" label="Absensi" :active="request()->routeIs('employee.attendance.*')" />

                <x-layout.sidebar-link :href="route('employee.leaves.index')" icon="fa-solid fa-calendar-times" label="Pengajuan Cuti" :active="request()->routeIs('employee.leaves.*')" />

                <x-layout.sidebar-link :href="route('employee.payroll.index')" icon="fa-solid fa-money-bill-wave" label="Payroll" :active="request()->routeIs('employee.payroll.*')" />
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white dark:bg-gray-800 transition-colors duration-300">
                <div class="flex items-center justify-between px-4 sm:px-6 h-16">
                    <div class="flex items-center space-x-3">
                        <button id="sidebar-toggle" class="lg:hidden p-2 rounded text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Open sidebar">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                        @if($logoPath)
                            <img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $companyName }}" class="w-8 h-8 lg:hidden">
                        @else
                            <img src="{{ asset('images/Haga.png') }}" alt="Haga+" class="w-8 h-8 lg:hidden">
                        @endif

                        <div class="hidden sm:block">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                                </div>
                                <input type="search" placeholder="Search or type command..." class="block w-full sm:w-80 pl-10 pr-3 py-2 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--color-haga-2)] focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <button data-theme-toggle class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" title="Toggle Theme">
                            <span class="block dark:hidden"><i class="fa-solid fa-moon"></i></span>
                            <span class="hidden dark:block"><i class="fa-solid fa-sun"></i></span>
                        </button>

                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }"
                             x-init="
                                open = false;
                                window.addEventListener('beforeunload', () => open = false);
                             ">
                            <button @click="open = !open" class="inline-flex p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" title="Profile Settings">
                                <i class="fa-solid fa-user"></i>
                            </button>

                            <!-- Profile Dropdown Menu -->
                            <div x-show="open"
                                 x-cloak
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95">

                                <!-- Profile Info -->
                                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                            <i class="fa-solid fa-user text-white"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Menu Items -->
                                <div class="py-1">
                                    <a href="#" onclick="alert('Profile settings coming soon!')" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                                        <i class="fa-solid fa-user-cog mr-3 text-gray-400"></i>
                                        Profile Settings
                                    </a>
                                    <a href="#" onclick="alert('Help & Support feature coming soon!')" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                                        <i class="fa-solid fa-question-circle mr-3 text-gray-400"></i>
                                        Help & Support
                                    </a>
                                </div>

                                <!-- Logout -->
                                <div class="border-t border-gray-200 dark:border-gray-700 py-1">
                                    <a href="#" data-logout class="flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                        <i class="fa-solid fa-sign-out-alt mr-3"></i>
                                        Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Logout Form -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900">
                <div class="mx-auto px-4 sm:px-6 py-6 sm:py-8 max-w-7xl">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')

    <script>
        // Theme toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.querySelector('[data-theme-toggle]');
            const html = document.documentElement;

            // Check for saved theme preference or default to light mode
            const currentTheme = localStorage.getItem('theme') || 'light';
            html.classList.toggle('dark', currentTheme === 'dark');

            themeToggle.addEventListener('click', function() {
                const isDark = html.classList.toggle('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            });

            // Sidebar toggle functionality
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarClose = document.getElementById('sidebar-close');

            function toggleSidebar() {
                sidebar.classList.toggle('sidebar-closed');
                sidebarOverlay.classList.toggle('hidden');
            }

            sidebarToggle.addEventListener('click', toggleSidebar);
            sidebarClose.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', toggleSidebar);

            // Logout functionality
            const logoutLinks = document.querySelectorAll('[data-logout]');
            logoutLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('logout-form').submit();
                });
            });
        });
    </script>
</body>
</html>


