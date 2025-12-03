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
        :root {
            --shamrock: #049460;
            --emerald: #10C874;
            --light-green: #89EB81;
            --lime-cream: #D5FF88;
            --black: #000000;
        }
        
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
        
        /* Hide scrollbar but keep functionality */
        .hide-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;  /* Chrome, Safari and Opera */
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-40 flex-shrink-0 w-72 lg:w-80 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transition-colors duration-300 sidebar-transition sidebar-closed lg:transform-none flex flex-col shadow-lg">
            <!-- Logo Header -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('employee.dashboard') }}" class="flex items-center space-x-3 group">
                    @if(Auth::user()->instansi && Auth::user()->instansi->logo)
                        <img src="{{ asset('storage/' . Auth::user()->instansi->logo) }}" alt="{{ Auth::user()->instansi->nama_instansi }}" class="w-10 h-10 object-contain group-hover:scale-105 transition-all duration-200">
                    @else
                        <img src="{{ asset('images/Haga.png') }}" alt="Haga+" class="w-10 h-10 object-contain group-hover:scale-105 transition-all duration-200">
                    @endif
                    <span class="text-xl font-bold bg-gradient-to-r from-[#049460] to-[#10C874] bg-clip-text text-transparent">{{ Auth::user()->instansi->abbreviated_name ?? 'Haga+' }}</span>
                </a>
                <button id="sidebar-close" class="lg:hidden p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200" aria-label="Close sidebar">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto hide-scrollbar">
                <div class="mb-6">
                    <p class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Menu Utama</p>
                    
                    <x-layout.sidebar-link 
                        :href="route('employee.dashboard')" 
                        icon="fa-solid fa-house" 
                        label="Dashboard" 
                        :active="request()->routeIs('employee.dashboard')" 
                    />

                    <x-layout.sidebar-link 
                        :href="route('employee.attendance.index')" 
                        icon="fa-solid fa-calendar-check" 
                        label="Kehadiran" 
                        :active="request()->routeIs('employee.attendance.*')" 
                    />

                    <x-layout.sidebar-link 
                        :href="route('employee.leaves.index')" 
                        icon="fa-solid fa-umbrella-beach" 
                        label="Pengajuan Cuti" 
                        :active="request()->routeIs('employee.leaves.*')" 
                    />

                    <x-layout.sidebar-link 
                        :href="route('employee.reimbursements.index')" 
                        icon="fa-solid fa-receipt" 
                        label="Reimbursement" 
                        :active="request()->routeIs('employee.reimbursements.*')" 
                    />

                    <x-layout.sidebar-link 
                        :href="route('employee.payroll.index')" 
                        icon="fa-solid fa-wallet" 
                        label="Gaji" 
                        :active="request()->routeIs('employee.payroll.*')" 
                    />

                    <x-layout.sidebar-link 
                        :href="route('employee.tax-forms.index')" 
                        icon="fa-solid fa-file-invoice-dollar" 
                        label="SPT Tahunan" 
                        :active="request()->routeIs('employee.tax-forms.*')" 
                    />
                </div>

                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <p class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Akun</p>
                    
                    @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                        <x-layout.sidebar-link 
                            :href="route('admin.dashboard')" 
                            icon="fa-solid fa-user-shield" 
                            label="Admin Dashboard" 
                            :active="false" 
                        />
                    @endif
                    
                    <x-layout.sidebar-link 
                        :href="route('employee.profile')" 
                        icon="fa-solid fa-user-circle" 
                        label="Profil Saya" 
                        :active="request()->routeIs('employee.profile')" 
                    />
                </div>
            </nav>

            <!-- User Info Footer -->
            <div class="mt-auto p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
                <div class="flex items-center space-x-3 px-3 py-2.5 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-[#10C874]/50 dark:hover:border-[#049460]/50 transition-all duration-200">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-lg overflow-hidden border-2 border-[#10C874]/30 dark:border-[#049460]/30">
                            @if(Auth::user()->employee && Auth::user()->employee->profile_picture)
                                <img src="{{ asset('storage/' . Auth::user()->employee->profile_picture) }}"
                                    alt="{{ Auth::user()->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=049460&background=D5FF88"
                                    alt="{{ Auth::user()->name }}"
                                    class="w-full h-full object-cover">
                            @endif
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            {{ ucfirst(Auth::user()->role) }}
                        </p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                        @csrf
                        <button type="submit" 
                            class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors duration-200"
                            title="Logout">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white dark:bg-gray-800 shadow-sm transition-colors duration-300 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between px-4 sm:px-6 h-16">
                    <div class="flex items-center space-x-3">
                        <button id="sidebar-toggle" class="lg:hidden p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" aria-label="Open sidebar">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                        <img src="{{ asset('images/Haga.png') }}" alt="Haga+" class="w-8 h-8 lg:hidden">
                    </div>

                    <!-- Right side - Theme Toggle, Location, Camera, Notifications -->
                    <div class="flex items-center space-x-2">
                        <!-- Theme Toggle -->
                        <button data-theme-toggle
                            class="p-2.5 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                            title="Toggle Theme">
                            <span class="block dark:hidden"><i class="fa-solid fa-moon text-lg"></i></span>
                            <span class="hidden dark:block"><i class="fa-solid fa-sun text-lg"></i></span>
                        </button>

                        <!-- Location Status -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" id="location-status" 
                                class="flex items-center gap-1.5 px-2.5 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                                title="Location Status">
                                <i id="location-icon" class="fa-solid fa-location-dot text-lg text-gray-400"></i>
                                <div class="hidden sm:flex flex-col items-start">
                                    <span id="location-status-text" class="text-xs font-medium">Checking...</span>
                                    <span id="location-distance" class="text-[10px] text-gray-500 dark:text-gray-400"></span>
                                </div>
                            </button>
                            
                            <!-- Location Dropdown -->
                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute right-0 mt-2 w-72 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-3 z-50">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between pb-2 border-b border-gray-200 dark:border-gray-700">
                                        <h3 class="font-semibold text-sm">Location Status</h3>
                                        <span id="location-badge" class="px-2 py-0.5 text-xs rounded-full bg-gray-200 dark:bg-gray-700">Inactive</span>
                                    </div>
                                    <div class="text-xs space-y-1">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 dark:text-gray-400">Latitude:</span>
                                            <span id="current-lat" class="font-mono">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 dark:text-gray-400">Longitude:</span>
                                            <span id="current-lng" class="font-mono">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 dark:text-gray-400">Distance:</span>
                                            <span id="current-distance" class="font-semibold">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 dark:text-gray-400">Accuracy:</span>
                                            <span id="location-accuracy" class="font-mono">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Camera Status -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" id="camera-toggle" 
                                class="flex items-center gap-1.5 px-2.5 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                                title="Camera">
                                <i id="camera-icon" class="fa-solid fa-camera text-lg text-gray-400"></i>
                                <span id="camera-status-text" class="hidden sm:block text-xs font-medium">Inactive</span>
                            </button>
                            
                            <!-- Camera Dropdown -->
                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-3 z-50">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between pb-2 border-b border-gray-200 dark:border-gray-700">
                                        <h3 class="font-semibold text-sm">Camera Status</h3>
                                        <span id="camera-badge" class="px-2 py-0.5 text-xs rounded-full bg-gray-200 dark:bg-gray-700">Inactive</span>
                                    </div>
                                    <div class="text-xs space-y-1">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 dark:text-gray-400">Permission:</span>
                                            <span id="camera-permission">Not granted</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 dark:text-gray-400">Devices:</span>
                                            <span id="camera-devices">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900">
                @php
                    $currentEmployee = \App\Models\Admin\Employee::where('user_id', Auth::id())->first();
                    $isProfileComplete = $currentEmployee ? $currentEmployee->isProfileComplete() : false;
                @endphp

                @if(!$isProfileComplete)
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border-b border-yellow-200 dark:border-yellow-800 px-4 py-3">
                        <div class="max-w-7xl mx-auto flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-triangle-exclamation text-yellow-600 dark:text-yellow-400"></i>
                                <p class="text-sm font-medium text-yellow-700 dark:text-yellow-400">
                                    Profil Anda belum lengkap. Silakan lengkapi data diri Anda untuk dapat melakukan absensi.
                                </p>
                            </div>
                            <a href="{{ route('employee.profile') }}" class="text-sm font-semibold text-yellow-700 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-300 underline whitespace-nowrap ml-4">
                                Lengkapi Sekarang
                            </a>
                        </div>
                    </div>
                @endif

                @php
                    $subscriptionService = new \App\Services\SubscriptionService(Auth::user()->instansi);
                    $subscriptionStatus = $subscriptionService->getSubscriptionStatus();
                @endphp

                @if(in_array($subscriptionStatus, ['expired', 'suspended']))
                    <div class="bg-red-50 dark:bg-red-900/20 border-b border-red-200 dark:border-red-800 px-4 py-3">
                        <div class="max-w-7xl mx-auto flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                                <p class="text-sm font-medium text-red-700 dark:text-red-400">
                                    Layanan tidak tersedia. Paket berlangganan instansi telah berakhir. Silakan hubungi Admin untuk perpanjangan.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mx-auto px-4 sm:px-6 py-6 sm:py-8 max-w-7xl">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
    
    <script>
        // Branch data for distance calculation
        const branchData = @json($branchData ?? ['latitude' => null, 'longitude' => null, 'radius' => 100]);

        // Real-time Location Tracking
        let locationWatchId = null;
        let cameraStream = null;

        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371e3; // Earth's radius in meters
            const φ1 = lat1 * Math.PI / 180;
            const φ2 = lat2 * Math.PI / 180;
            const Δφ = (lat2 - lat1) * Math.PI / 180;
            const Δλ = (lon2 - lon1) * Math.PI / 180;

            const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            return R * c; // Distance in meters
        }

        function updateLocationUI(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const accuracy = position.coords.accuracy;

            // Update coordinates
            document.getElementById('current-lat').textContent = lat.toFixed(6);
            document.getElementById('current-lng').textContent = lng.toFixed(6);
            document.getElementById('location-accuracy').textContent = accuracy.toFixed(0) + 'm';

            // Calculate distance to office
            if (branchData.latitude && branchData.longitude) {
                const distance = calculateDistance(lat, lng, branchData.latitude, branchData.longitude);
                const distanceText = distance < 1000 ? 
                    Math.round(distance) + 'm' : 
                    (distance / 1000).toFixed(2) + 'km';
                
                document.getElementById('current-distance').textContent = distanceText;
                document.getElementById('location-distance').textContent = distanceText;

                // Update status text
                const isInRange = distance <= branchData.radius;
                document.getElementById('location-status-text').textContent = isInRange ? 'In Range' : 'Out of Range';
                
                // Update icon and badge color
                const icon = document.getElementById('location-icon');
                const badge = document.getElementById('location-badge');
                
                if (isInRange) {
                    icon.className = 'fa-solid fa-location-dot text-lg text-green-500';
                    badge.textContent = 'Active';
                    badge.className = 'px-2 py-0.5 text-xs rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400';
                } else {
                    icon.className = 'fa-solid fa-location-dot text-lg text-yellow-500';
                    badge.textContent = 'Out of Range';
                    badge.className = 'px-2 py-0.5 text-xs rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400';
                }
            } else {
                document.getElementById('current-distance').textContent = 'No office location';
                document.getElementById('location-status-text').textContent = 'Active';
                document.getElementById('location-icon').className = 'fa-solid fa-location-dot text-lg text-green-500';
                
                const badge = document.getElementById('location-badge');
                badge.textContent = 'Active';
                badge.className = 'px-2 py-0.5 text-xs rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400';
            }
        }

        function updateLocationError(error) {
            document.getElementById('location-status-text').textContent = 'Inactive';
            document.getElementById('location-distance').textContent = '';
            document.getElementById('location-icon').className = 'fa-solid fa-location-dot text-lg text-red-500';
            
            const badge = document.getElementById('location-badge');
            badge.textContent = 'Denied';
            badge.className = 'px-2 py-0.5 text-xs rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400';

            document.getElementById('current-lat').textContent = 'Permission denied';
            document.getElementById('current-lng').textContent = 'Permission denied';
            document.getElementById('current-distance').textContent = '-';
            document.getElementById('location-accuracy').textContent = '-';
        }

        function startLocationTracking() {
            if ('geolocation' in navigator) {
                locationWatchId = navigator.geolocation.watchPosition(
                    updateLocationUI,
                    updateLocationError,
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                updateLocationError({ code: 0, message: 'Geolocation not supported' });
            }
        }

        // Camera Status Tracking
        async function checkCameraStatus() {
            try {
                // Check camera permission
                const permissionStatus = await navigator.permissions.query({ name: 'camera' });
                
                const updateCameraUI = (status) => {
                    const icon = document.getElementById('camera-icon');
                    const statusText = document.getElementById('camera-status-text');
                    const badge = document.getElementById('camera-badge');
                    const permission = document.getElementById('camera-permission');

                    if (status === 'granted') {
                        icon.className = 'fa-solid fa-camera text-lg text-green-500';
                        statusText.textContent = 'Active';
                        badge.textContent = 'Active';
                        badge.className = 'px-2 py-0.5 text-xs rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400';
                        permission.textContent = 'Granted';
                    } else if (status === 'denied') {
                        icon.className = 'fa-solid fa-camera text-lg text-red-500';
                        statusText.textContent = 'Denied';
                        badge.textContent = 'Denied';
                        badge.className = 'px-2 py-0.5 text-xs rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400';
                        permission.textContent = 'Denied';
                    } else {
                        icon.className = 'fa-solid fa-camera text-lg text-gray-400';
                        statusText.textContent = 'Inactive';
                        badge.textContent = 'Inactive';
                        badge.className = 'px-2 py-0.5 text-xs rounded-full bg-gray-200 dark:bg-gray-700';
                        permission.textContent = 'Not granted';
                    }
                };

                updateCameraUI(permissionStatus.state);

                // Listen for permission changes
                permissionStatus.onchange = () => {
                    updateCameraUI(permissionStatus.state);
                };

                // Get camera devices count
                if (permissionStatus.state === 'granted') {
                    const devices = await navigator.mediaDevices.enumerateDevices();
                    const cameras = devices.filter(device => device.kind === 'videoinput');
                    document.getElementById('camera-devices').textContent = cameras.length;
                } else {
                    document.getElementById('camera-devices').textContent = '0';
                }
            } catch (error) {
                console.log('Camera permission check not supported:', error);
                // Fallback for browsers that don't support permission API
                document.getElementById('camera-permission').textContent = 'Unknown';
            }
        }

        // Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            // Start tracking
            startLocationTracking();
            checkCameraStatus();

            // Update camera status every 5 seconds
            setInterval(checkCameraStatus, 5000);

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