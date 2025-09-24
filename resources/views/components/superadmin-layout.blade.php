<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HagaPlus - Super Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/8c8ccf764d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-Sl1fL0x2y5m0mXQmZs7q8w9mK3Yk8wVqf9VQf8lYp4mDk8Qxg3m6Jrj0D7n6o2o1g7l5xj5m1n9m0z2yXb7aYw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    @php
        $isInstansiActive = request()->routeIs('superadmin.instansi.*') || request()->routeIs('superadmin.subscriptions.*');
        $isPackagesActive = request()->routeIs('superadmin.packages.*');
        $isFinancialActive = request()->routeIs('superadmin.financial.*');
        $isReportsActive = request()->routeIs('superadmin.analytics.*') || request()->routeIs('superadmin.reports.*');
        $isSystemActive = request()->routeIs('superadmin.system.*');
    @endphp
    <div class="flex h-screen overflow-hidden">
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-40 flex-shrink-0 w-72 lg:w-80 bg-white dark:bg-gray-800 shadow-xl transition-colors duration-300 sidebar-transition sidebar-closed lg:transform-none">
            <div class="flex items-center h-16 px-6 border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('images/Haga.png') }}" alt="Haga+" class="w-8 h-8">
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">Haga+</span>
                </a>
                <button id="sidebar-close" class="ml-auto lg:hidden p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded" aria-label="Close sidebar">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <x-layout.sidebar-link :href="route('superadmin.dashboard')" icon="fa-solid fa-gauge" label="Dashboard" :active="request()->routeIs('superadmin.dashboard')" />

                <x-layout.sidebar-accordion icon="fa-solid fa-building" label="Instansi Management" :open="$isInstansiActive" target="menu-instansi">
                    <x-layout.sidebar-subitem :href="route('superadmin.instansi.index')" label="All Instansi" :active="request()->routeIs('superadmin.instansi.*')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.subscriptions.index')" label="Subscription Status" :active="request()->routeIs('superadmin.subscriptions.*')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.instansi.monitoring')" label="Usage Monitoring" :active="request()->routeIs('superadmin.instansi.monitoring')" />
                    <x-layout.sidebar-subitem href="#" label="Support Requests" />
                </x-layout.sidebar-accordion>

                <x-layout.sidebar-accordion icon="fa-solid fa-box-open" label="Package Management" :open="$isPackagesActive" target="menu-packages">
                    <x-layout.sidebar-subitem :href="route('superadmin.packages.index')" label="Manage Packages" :active="request()->routeIs('superadmin.packages.*')" />
                    <x-layout.sidebar-subitem href="#" label="Feature Configuration" />
                    <x-layout.sidebar-subitem href="#" label="Pricing Settings" />
                </x-layout.sidebar-accordion>

                <x-layout.sidebar-accordion icon="fa-solid fa-sack-dollar" label="Financial" :open="$isFinancialActive" target="menu-financial">
                    <x-layout.sidebar-subitem :href="route('superadmin.financial.index')" label="Revenue Overview" :active="request()->routeIs('superadmin.financial.index')" />
                    <x-layout.sidebar-subitem href="#" label="Payment Tracking" />
                    <x-layout.sidebar-subitem href="#" label="Invoice Management" />
                    <x-layout.sidebar-subitem href="#" label="Financial Reports" />
                </x-layout.sidebar-accordion>

                <x-layout.sidebar-accordion icon="fa-solid fa-chart-line" label="Reports" :open="$isReportsActive" target="menu-reports">
                    <x-layout.sidebar-subitem :href="route('superadmin.analytics.index')" label="Analytics Dashboard" :active="request()->routeIs('superadmin.analytics.index')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.reports.activities')" label="Recent Activities" :active="request()->routeIs('superadmin.reports.activities')" />
                    <x-layout.sidebar-subitem href="#" label="Usage Statistics" />
                    <x-layout.sidebar-subitem href="#" label="Performance Reports" />
                    <x-layout.sidebar-subitem href="#" label="Export Data" />
                </x-layout.sidebar-accordion>

                <x-layout.sidebar-accordion icon="fa-solid fa-gear" label="System" :open="$isSystemActive" target="menu-system">
                    <x-layout.sidebar-subitem :href="route('superadmin.system.health')" label="System Health" :active="request()->routeIs('superadmin.system.health')" />
                    <x-layout.sidebar-subitem href="#" label="User Logs" />
                    <x-layout.sidebar-subitem href="#" label="Settings" />
                    <a href="#" data-logout class="flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                </x-layout.sidebar-accordion>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white dark:bg-gray-800  transition-colors duration-300">
                <div class="flex items-center justify-between px-4 sm:px-6 h-16">
                    <div class="flex items-center space-x-3">
                        <!-- logo small screen -->
                         
                        <button id="sidebar-toggle" class="lg:hidden p-2 rounded text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Open sidebar">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                        <img src="{{ asset('images/Haga.png') }}" alt="Haga+" class="w-8 h-8 lg:hidden">

                        <!-- logo large screen -->
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

                        <div class="relative">
                            <button data-notification-btn class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 relative" title="Notifications">
                                <i class="fa-solid fa-bell"></i>
                                <span class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                            </button>
                            <div data-notification-panel class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50">
                                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Notifications</h3>
                                        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                </div>
                                <div class="p-4 space-y-3 text-sm text-gray-700 dark:text-gray-300">
                                    <div class="flex items-start space-x-3"><i class="fa-solid fa-circle-info text-haga-2 mt-1"></i><div>Subscription renewal pending for 2 instansi</div></div>
                                    <div class="flex items-start space-x-3"><i class="fa-solid fa-wrench text-amber-500 mt-1"></i><div>System maintenance scheduled tonight</div></div>
                                    <div class="flex items-start space-x-3"><i class="fa-solid fa-envelope text-green-500 mt-1"></i><div>New support request received</div></div>
                                </div>
                                <div class="p-4"><button class="w-full text-center text-sm text-haga-2 hover:opacity-80 transition-colors duration-200">View All Notifications</button></div>
                            </div>
                        </div>

                        <a href="#" class="hidden sm:inline-flex p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" title="Recent Activities"><i class="fa-solid fa-clock"></i></a>
                        <a href="#" class="hidden sm:inline-flex p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" title="Profile Settings"><i class="fa-solid fa-user"></i></a>
                    </div>
                </div>
            </header>

            @include('partials.breadcrumbs')

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900">
                <div class="mx-auto px-4 sm:px-6 py-6 sm:py-8 max-w-7xl">
                    
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>