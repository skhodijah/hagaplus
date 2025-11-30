@php
    $pendingSubscriptionRequests = \DB::table('subscription_requests')->where('payment_status', 'pending')->whereNotNull('payment_proof')->count();
    $newInstansis = \DB::table('instansis')->where('created_at', '>=', now()->subDays(7))->count();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HagaPlus - Super Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/8c8ccf764d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Alpine.js x-cloak CSS -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="flex h-screen overflow-hidden">
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-40 flex-shrink-0 w-72 lg:w-80 bg-white dark:bg-gray-800 shadow-xl transition-colors duration-300 sidebar-transition sidebar-closed lg:transform-none">
            <div class="flex items-center h-16 px-6 border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('images/Haga.png') }}" alt="Haga+" class="w-8 h-8">
                    <span class="text-xl font-semibold italic text-gray-900 dark:text-white">Haga+</span>
                </a>
                <button id="sidebar-close" class="ml-auto lg:hidden p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded" aria-label="Close sidebar">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto" x-data="{ 
                openMenu: '{{ request()->routeIs('superadmin.instansi.*') ? 'instansi' : (request()->routeIs('superadmin.subscriptions.*') || request()->routeIs('superadmin.payment-methods.*') ? 'subscriptions' : (request()->routeIs('superadmin.packages.*') ? 'packages' : (request()->routeIs('superadmin.analytics.*') || request()->routeIs('superadmin.reports.*') ? 'reports' : ''))) }}'
            }">
                <!-- Dashboard -->
                <x-layout.sidebar-link :href="route('superadmin.dashboard')" icon="fa-solid fa-gauge" label="Dashboard" :active="request()->routeIs('superadmin.dashboard')" />

                <!-- Instansi Management -->
                <div class="space-y-1 pt-2">
                    <button @click="openMenu = openMenu === 'instansi' ? '' : 'instansi'" 
                            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 group
                                   {{ request()->routeIs('superadmin.instansi.*') 
                                      ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' 
                                      : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-200' }}">
                        <div class="flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg mr-2 transition-colors
                                       {{ request()->routeIs('superadmin.instansi.*') 
                                          ? 'bg-blue-100 text-blue-600 dark:bg-blue-800 dark:text-blue-300' 
                                          : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400 group-hover:bg-white dark:group-hover:bg-gray-700 group-hover:shadow-sm' }}">
                                <i class="fa-solid fa-building text-xs"></i>
                            </span>
                            <span>Instansi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($newInstansis > 0)
                                <span class="inline-flex items-center justify-center w-2 h-2 bg-red-500 rounded-full"></span>
                            @endif
                            <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 opacity-50" :class="openMenu === 'instansi' ? 'rotate-180' : ''"></i>
                        </div>
                    </button>
                    <div x-show="openMenu === 'instansi'" x-collapse class="pl-11 pr-3 space-y-1">
                        <a href="{{ route('superadmin.instansi.index') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200 flex justify-between items-center
                                  {{ request()->routeIs('superadmin.instansi.index') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <span>All Instansi</span>
                            @if($newInstansis > 0)
                                <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-red-100 bg-red-500 rounded-full">{{ $newInstansis }}</span>
                            @endif
                        </a>
                        <a href="{{ route('superadmin.instansi.monitoring') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                                  {{ request()->routeIs('superadmin.instansi.monitoring') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            Usage Monitoring
                        </a>
                    </div>
                </div>

                <!-- User Management -->
                <x-layout.sidebar-link :href="route('superadmin.users.index')" icon="fa-solid fa-users" label="User Management" :active="request()->routeIs('superadmin.users.*')" />

                <!-- Subscription Management -->
                <div class="space-y-1 pt-2">
                    <button @click="openMenu = openMenu === 'subscriptions' ? '' : 'subscriptions'" 
                            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 group
                                   {{ request()->routeIs('superadmin.subscriptions.*') || request()->routeIs('superadmin.payment-methods.*') 
                                      ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' 
                                      : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-200' }}">
                        <div class="flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg mr-2 transition-colors
                                       {{ request()->routeIs('superadmin.subscriptions.*') || request()->routeIs('superadmin.payment-methods.*') 
                                          ? 'bg-blue-100 text-blue-600 dark:bg-blue-800 dark:text-blue-300' 
                                          : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400 group-hover:bg-white dark:group-hover:bg-gray-700 group-hover:shadow-sm' }}">
                                <i class="fa-solid fa-receipt text-xs"></i>
                            </span>
                            <span>Subscriptions</span>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($pendingSubscriptionRequests > 0)
                                <span class="inline-flex items-center justify-center w-2 h-2 bg-red-500 rounded-full"></span>
                            @endif
                            <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 opacity-50" :class="openMenu === 'subscriptions' ? 'rotate-180' : ''"></i>
                        </div>
                    </button>
                    <div x-show="openMenu === 'subscriptions'" x-collapse class="pl-11 pr-3 space-y-1">
                        <a href="{{ route('superadmin.subscriptions.index') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                                  {{ request()->routeIs('superadmin.subscriptions.index') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            All Subscriptions
                        </a>
                        <a href="{{ route('superadmin.subscriptions.analytics') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                                  {{ request()->routeIs('superadmin.subscriptions.analytics') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            Analytics
                        </a>
                        <a href="{{ route('superadmin.subscriptions.subscription-requests') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200 flex justify-between items-center
                                  {{ request()->routeIs('superadmin.subscriptions.subscription-requests') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <span>Transaction Requests</span>
                            @if($pendingSubscriptionRequests > 0)
                                <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-red-100 bg-red-500 rounded-full">{{ $pendingSubscriptionRequests }}</span>
                            @endif
                        </a>
                        <a href="{{ route('superadmin.payment-methods.index') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                                  {{ request()->routeIs('superadmin.payment-methods.*') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            Payment Methods
                        </a>
                    </div>
                </div>

                <!-- Package Management -->
                <div class="space-y-1 pt-2">
                    <button @click="openMenu = openMenu === 'packages' ? '' : 'packages'" 
                            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 group
                                   {{ request()->routeIs('superadmin.packages.*') 
                                      ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' 
                                      : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-200' }}">
                        <div class="flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg mr-2 transition-colors
                                       {{ request()->routeIs('superadmin.packages.*') 
                                          ? 'bg-blue-100 text-blue-600 dark:bg-blue-800 dark:text-blue-300' 
                                          : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400 group-hover:bg-white dark:group-hover:bg-gray-700 group-hover:shadow-sm' }}">
                                <i class="fa-solid fa-box text-xs"></i>
                            </span>
                            <span>Packages</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 opacity-50" :class="openMenu === 'packages' ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openMenu === 'packages'" x-collapse class="pl-11 pr-3 space-y-1">
                        <a href="{{ route('superadmin.packages.index') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                                  {{ request()->routeIs('superadmin.packages.index') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            Manage Packages
                        </a>
                    </div>
                </div>

                <!-- Reports & Analytics -->
                <div class="space-y-1 pt-2">
                    <button @click="openMenu = openMenu === 'reports' ? '' : 'reports'" 
                            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 group
                                   {{ request()->routeIs('superadmin.analytics.*') || request()->routeIs('superadmin.reports.*') || request()->routeIs('superadmin.financial.*')
                                      ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' 
                                      : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-200' }}">
                        <div class="flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg mr-2 transition-colors
                                       {{ request()->routeIs('superadmin.analytics.*') || request()->routeIs('superadmin.reports.*') || request()->routeIs('superadmin.financial.*')
                                          ? 'bg-blue-100 text-blue-600 dark:bg-blue-800 dark:text-blue-300' 
                                          : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400 group-hover:bg-white dark:group-hover:bg-gray-700 group-hover:shadow-sm' }}">
                                <i class="fa-solid fa-chart-line text-xs"></i>
                            </span>
                            <span>Reports</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 opacity-50" :class="openMenu === 'reports' ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openMenu === 'reports'" x-collapse class="pl-11 pr-3 space-y-1">
                        <a href="{{ route('superadmin.analytics.index') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                                  {{ request()->routeIs('superadmin.analytics.index') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            Analytics Dashboard
                        </a>
                        <a href="{{ route('superadmin.financial.index') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                                  {{ request()->routeIs('superadmin.financial.index') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            Financial Reports
                        </a>
                        <a href="{{ route('superadmin.reports.activities') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                                  {{ request()->routeIs('superadmin.reports.activities') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            Recent Activities
                        </a>
                    </div>
                </div>
            </nav>

            <!-- System Settings -->
            <div class="px-3 py-4 border-t border-gray-200 dark:border-gray-700">
                @php
                    $settingVal = \App\Models\SystemSetting::where('key', 'email_verification_enabled')->value('value');
                    $emailVerificationEnabled = $settingVal !== 'false'; // Default to true if not set
                @endphp
                <div class="flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800">
                    <span class="flex items-center" title="Toggle Email Verification Requirement">
                        <i class="fa-solid fa-shield-halved mr-2 w-5 text-center"></i>
                        <span>Verifikasi Email</span>
                    </span>
                    <button 
                        x-data="{ enabled: {{ $emailVerificationEnabled ? 'true' : 'false' }} }"
                        @click="
                            enabled = !enabled;
                            fetch('{{ route('superadmin.settings.toggle-email-verification') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ enabled: enabled })
                            }).then(res => res.json()).then(data => {
                                if(data.success) {
                                    // Optional: Show toast
                                } else {
                                    enabled = !enabled; // Revert on failure
                                }
                            }).catch(() => {
                                enabled = !enabled; // Revert on error
                            });
                        "
                        :class="enabled ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-600'"
                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        <span
                            aria-hidden="true"
                            :class="enabled ? 'translate-x-5' : 'translate-x-0'"
                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                        ></span>
                    </button>
                </div>
            </div>

            <!-- User Profile Section -->
            <div class="border-t border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ Auth::user()->initials() }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Super Admin</p>
                        </div>
                    </div>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute bottom-full right-0 mb-2 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 py-1 z-50">
                            <a href="{{ route('superadmin.settings.profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 flex items-center">
                                <i class="fa-solid fa-user-gear mr-2"></i>
                                Profile Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 flex items-center">
                                    <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 z-10">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <button id="sidebar-toggle" class="lg:hidden p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" aria-label="Toggle sidebar">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div class="flex-1"></div>
                    <div class="flex items-center space-x-4">
                        <!-- Dark Mode Toggle -->
                        <button id="theme-toggle" class="p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fa-solid fa-moon dark:hidden"></i>
                            <i class="fa-solid fa-sun hidden dark:inline"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Fancybox JS -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
</body>
</html>
