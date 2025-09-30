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
    <title>HagaPlus - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/8c8ccf764d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-Sl1fL0x2y5m0XQmZs7q8w9mK3Yk8wVqf9VQf8lYp4mDk8Qxg3m6Jrj0D7n6o2o1g7l5xj5m1n9m0z2yXb7aYw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="flex h-screen overflow-hidden">
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-40 flex-shrink-0 w-72 lg:w-80 bg-white dark:bg-gray-800 shadow-xl transition-colors duration-300 sidebar-transition sidebar-closed lg:transform-none">
            <div class="flex items-center h-16 px-6 border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
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
                <x-layout.sidebar-link :href="route('admin.dashboard')" icon="fa-solid fa-gauge" label="Dashboard" :active="request()->routeIs('admin.dashboard')" />

                <x-layout.sidebar-link :href="route('admin.employees.index')" icon="fa-solid fa-users" label="Employees" :active="request()->routeIs('admin.employees.*')" />

                <x-layout.sidebar-link :href="route('admin.attendance.index')" icon="fa-solid fa-calendar-check" label="Attendance" :active="request()->routeIs('admin.attendance.*')" />

                <x-layout.sidebar-link :href="route('admin.payroll.index')" icon="fa-solid fa-money-bill-wave" label="Payroll" :active="request()->routeIs('admin.payroll.*')" />

                <x-layout.sidebar-link :href="route('admin.branches.index')" icon="fa-solid fa-building" label="Branches" :active="request()->routeIs('admin.branches.*')" />

                <x-layout.sidebar-link :href="route('admin.settings.index')" icon="fa-solid fa-gear" label="Settings" :active="request()->routeIs('admin.settings.*')" />

                <a href="#" data-logout class="flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
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
                        @if($logoPath)
                            <img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $companyName }}" class="w-8 h-8 lg:hidden">
                        @else
                            <img src="{{ asset('images/Haga.png') }}" alt="Haga+" class="w-8 h-8 lg:hidden">
                        @endif

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

                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false, notifications: [], unreadCount: 0 }" 
                             x-init="
                                fetchNotifications();
                                setInterval(fetchNotifications, 30000); // Refresh every 30 seconds
                                
                                function fetchNotifications() {
                                    fetch('{{ route('admin.notifications.index') }}')
                                        .then(response => response.json())
                                        .then(data => {
                                            notifications = data.notifications;
                                            unreadCount = data.unread_count;
                                        });
                                }
                                
                                function markAsRead(notificationId) {
                                    fetch(`/admin/notifications/${notificationId}/read`, {
                                        method: 'PUT',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                            'Content-Type': 'application/json'
                                        }
                                    }).then(() => {
                                        fetchNotifications();
                                    });
                                }
                                
                                function markAllAsRead() {
                                    fetch('/admin/notifications/mark-all-read', {
                                        method: 'PUT',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                            'Content-Type': 'application/json'
                                        }
                                    }).then(() => {
                                        fetchNotifications();
                                    });
                                }
                                
                                function deleteNotification(notificationId) {
                                    fetch(`/admin/notifications/${notificationId}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                            'Content-Type': 'application/json'
                                        }
                                    }).then(() => {
                                        fetchNotifications();
                                    });
                                }
                                
                                function getNotificationIcon(type) {
                                    const icons = {
                                        'success': 'fa-check-circle text-green-600 dark:text-green-400',
                                        'error': 'fa-exclamation-circle text-red-600 dark:text-red-400',
                                        'warning': 'fa-exclamation-triangle text-yellow-600 dark:text-yellow-400',
                                        'info': 'fa-info-circle text-blue-600 dark:text-blue-400',
                                        'attendance': 'fa-calendar-check text-blue-600 dark:text-blue-400',
                                        'leave': 'fa-calendar-times text-orange-600 dark:text-orange-400',
                                        'payroll': 'fa-money-bill-wave text-green-600 dark:text-green-400',
                                        'user': 'fa-user-plus text-purple-600 dark:text-purple-400'
                                    };
                                    return icons[type] || 'fa-bell text-gray-600 dark:text-gray-400';
                                }

                                function getNotificationBadge(type) {
                                    const badges = {
                                        'success': 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                        'error': 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                        'warning': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                        'info': 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                        'attendance': 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                        'leave': 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
                                        'payroll': 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                        'user': 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300'
                                    };
                                    return badges[type] || 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300';
                                }

                                function formatDate(dateString) {
                                    const date = new Date(dateString);
                                    const now = new Date();
                                    const diffInMinutes = Math.floor((now - date) / (1000 * 60));
                                    const diffInHours = Math.floor(diffInMinutes / 60);
                                    const diffInDays = Math.floor(diffInHours / 24);

                                    if (diffInMinutes < 1) return 'Just now';
                                    if (diffInMinutes < 60) return `${diffInMinutes}m ago`;
                                    if (diffInHours < 24) return `${diffInHours}h ago`;
                                    if (diffInDays === 1) return 'Yesterday';
                                    if (diffInDays < 7) return `${diffInDays}d ago`;

                                    return date.toLocaleDateString('id-ID', {
                                        day: 'numeric',
                                        month: 'short',
                                        year: 'numeric'
                                    });
                                }
                             ">
                            <button @click="open = !open" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 relative" title="Notifications">
                                <i class="fa-solid fa-bell"></i>
                                <span x-show="unreadCount > 0" class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center" x-text="unreadCount"></span>
                            </button>
                            
                            <!-- Notifications Dropdown -->
                            <div x-show="open" @click.away="open = false" 
                                 class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95">
                                
                                <!-- Header -->
                                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Notifications</h3>
                                        <button @click="markAllAsRead()" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            Mark all as read
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Notifications List -->
                                <div class="max-h-96 overflow-y-auto">
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200"
                                             :class="{ 'bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-l-4 border-l-blue-500': !notification.is_read }"
                                             @click="markAsRead(notification.id)">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0 mt-0.5">
                                                    <div class="w-10 h-10 rounded-full flex items-center justify-center" :class="getNotificationBadge(notification.type)">
                                                        <i :class="getNotificationIcon(notification.type)" class="text-sm"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-start justify-between">
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white" x-text="notification.title"></p>
                                                        <div class="flex items-center space-x-2 ml-2">
                                                            <span x-show="!notification.is_read" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                                                New
                                                            </span>
                                                            <button @click.stop="deleteNotification(notification.id)"
                                                                    class="text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors">
                                                                <i class="fa-solid fa-times text-xs"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1 leading-relaxed" x-text="notification.message"></p>
                                                    <div class="flex items-center justify-between mt-2">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="formatDate(notification.created_at)"></p>
                                                        <span class="text-xs font-medium capitalize px-2 py-1 rounded" :class="getNotificationBadge(notification.type)" x-text="notification.type"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <div x-show="notifications.length === 0" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <i class="fa-solid fa-bell-slash text-2xl mb-2"></i>
                                        <p>No notifications</p>
                                    </div>
                                </div>
                                
                                <!-- Footer -->
                                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                                    <a href="{{ route('admin.notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center">
                                        <i class="fa-solid fa-external-link-alt mr-2 text-xs"></i>
                                        View all notifications
                                    </a>
                                </div>
                            </div>
                        </div>

                        <a href="#" class="hidden sm:inline-flex p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" title="Recent Activities"><i class="fa-solid fa-clock"></i></a>
                        
                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="inline-flex p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" title="Profile Settings">
                                <i class="fa-solid fa-user"></i>
                            </button>
                            
                            <!-- Profile Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" 
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
                                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <i class="fa-solid fa-user-cog mr-3 text-gray-400"></i>
                                        Profile Settings
                                    </a>
                                    <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <i class="fa-solid fa-cog mr-3 text-gray-400"></i>
                                        Account Settings
                                    </a>
                                    <a href="{{ route('admin.settings.index') }}#notifications" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <i class="fa-solid fa-bell mr-3 text-gray-400"></i>
                                        Notification Settings
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