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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-Sl1fL0x2y5m0mXQmZs7q8w9mK3Yk8wVqf9VQf8lYp4mDk8Qxg3m6Jrj0D7n6o2o1g7l5xj5m1n9m0z2yXb7aYw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    @php
        $isInstansiActive = request()->routeIs('superadmin.instansi.*') || request()->routeIs('superadmin.support_requests.*');
        $isSubscriptionsActive = request()->routeIs('superadmin.subscriptions.*');
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
                    <x-layout.sidebar-subitem :href="route('superadmin.instansi.index')" label="All Instansi" :active="request()->routeIs('superadmin.instansi.index')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.instansi.monitoring')" label="Usage Monitoring" :active="request()->routeIs('superadmin.instansi.monitoring')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.support_requests.index')" label="Support Requests" :active="request()->routeIs('superadmin.support_requests.*')" />
                </x-layout.sidebar-accordion>

                <x-layout.sidebar-accordion icon="fa-solid fa-receipt" label="Subscription Management" :open="$isSubscriptionsActive" target="menu-subscriptions">
                    <x-layout.sidebar-subitem :href="route('superadmin.subscriptions.index')" label="All Subscriptions" :active="request()->routeIs('superadmin.subscriptions.index')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.subscriptions.analytics')" label="Subscription Analytics" :active="request()->routeIs('superadmin.subscriptions.analytics')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.subscriptions.payment-history')" label="Payment History" :active="request()->routeIs('superadmin.subscriptions.payment-history')" />
                </x-layout.sidebar-accordion>

                <x-layout.sidebar-accordion icon="fa-solid fa-box-open" label="Package Management" :open="$isPackagesActive" target="menu-packages">
                    <x-layout.sidebar-subitem :href="route('superadmin.packages.index')" label="Manage Packages" :active="request()->routeIs('superadmin.packages.index')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.packages.feature-configuration')" label="Feature Configuration" :active="request()->routeIs('superadmin.packages.feature-configuration')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.packages.pricing-settings')" label="Pricing Settings" :active="request()->routeIs('superadmin.packages.pricing-settings')" />
                </x-layout.sidebar-accordion>

                <x-layout.sidebar-accordion icon="fa-solid fa-chart-line" label="Reports" :open="$isReportsActive" target="menu-reports">
                    <x-layout.sidebar-subitem :href="route('superadmin.analytics.index')" label="Analytics Dashboard" :active="request()->routeIs('superadmin.analytics.index')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.reports.activities')" label="Recent Activities" :active="request()->routeIs('superadmin.reports.activities')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.reports.usage')" label="Usage Statistics" :active="request()->routeIs('superadmin.reports.usage')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.reports.performance')" label="Performance Reports" :active="request()->routeIs('superadmin.reports.performance')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.reports.export')" label="Export Data" :active="request()->routeIs('superadmin.reports.export')" />
                </x-layout.sidebar-accordion>

                <x-layout.sidebar-accordion icon="fa-solid fa-bell" label="Communication" :open="request()->routeIs('superadmin.notifications.*') || request()->routeIs('superadmin.chat.*')" target="menu-communication">
                    <x-layout.sidebar-subitem :href="route('superadmin.notifications.index')" label="Notifications" :active="request()->routeIs('superadmin.notifications.index')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.notifications.bulk')" label="Bulk Notifications" :active="request()->routeIs('superadmin.notifications.bulk')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.chat.index')" label="Customer Service Chat" :active="request()->routeIs('superadmin.chat.*')" />
                </x-layout.sidebar-accordion>

                <x-layout.sidebar-accordion icon="fa-solid fa-gear" label="System" :open="$isSystemActive" target="menu-system">
                    <x-layout.sidebar-subitem :href="route('superadmin.system.health')" label="System Health" :active="request()->routeIs('superadmin.system.health')" />
                    <x-layout.sidebar-subitem href="#" label="User Logs" />
                    <x-layout.sidebar-subitem href="#" label="Settings" />
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

                        <!-- Notifications -->
                        <div class="relative" x-data="{
                            open: false, 
                            notifications: [], 
                            unreadCount: 0,
                            fetchNotifications() {
                                fetch('{{ route('superadmin.notifications.index') }}?ajax=1')
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error('Network response was not ok');
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        this.notifications = data.notifications || [];
                                        this.unreadCount = data.unread_count || 0;
                                    })
                                    .catch(error => {
                                        console.error('Error fetching notifications:', error);
                                        this.notifications = [];
                                        this.unreadCount = 0;
                                    });
                            },
                            init() {
                                this.fetchNotifications();
                                // Refresh notifications every 30 seconds
                                setInterval(() => this.fetchNotifications(), 30000);
                                
                                // Listen for new-notification event
                                window.Echo.private(`App.Models.User.{{ Auth::id() }}`)
                                    .notification((notification) => {
                                        this.fetchNotifications();
                                    });
                            },
                            markAsRead(notificationId, index) {
                                // Mark as read locally first for immediate UI feedback
                                this.notifications[index].is_read = true;

                                fetch(`/superadmin/notifications/${notificationId}/read`, {
                                    method: 'PUT',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Network response was not ok');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        // Remove the notification from the list after a short delay
                                        setTimeout(() => {
                                            this.notifications.splice(index, 1);
                                            this.unreadCount = Math.max(0, this.unreadCount - 1);
                                        }, 500);
                                    } else {
                                        // Revert the local change if server failed
                                        this.notifications[index].is_read = false;
                                    }
                                })
                                .catch(error => {
                                    console.error('Error marking notification as read:', error);
                                    // Revert the local change on error
                                    this.notifications[index].is_read = false;
                                });
                            },
                            markAllAsRead() {
                                fetch('/superadmin/notifications/mark-all-read', {
                                    method: 'PUT',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Network response was not ok');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        // Clear all notifications from the dropdown since they're all read now
                                        this.notifications = [];
                                        this.unreadCount = 0;
                                    }
                                })
                                .catch(error => {
                                    console.error('Error marking all notifications as read:', error);
                                });
                            },
                            getNotificationIcon(type) {
                                const icons = {
                                    'success': 'fa-check-circle text-green-600 dark:text-green-400',
                                    'error': 'fa-times-circle text-red-600 dark:text-red-400',
                                    'warning': 'fa-exclamation-triangle text-yellow-600 dark:text-yellow-400',
                                    'info': 'fa-info-circle text-blue-600 dark:text-blue-400',
                                    'system': 'fa-cog text-purple-600 dark:text-purple-400',
                                    'user': 'fa-user-plus text-teal-600 dark:text-teal-400',
                                    'subscription': 'fa-credit-card text-indigo-600 dark:text-indigo-400',
                                    'support': 'fa-headset text-pink-600 dark:text-pink-400'
                                };
                                return icons[type] || 'fa-bell text-gray-600 dark:text-gray-400';
                            },
                            getNotificationBadge(type) {
                                const badges = {
                                    'success': 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                    'error': 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                    'warning': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                    'info': 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                    'system': 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
                                    'user': 'bg-teal-100 text-teal-800 dark:bg-teal-900/30 dark:text-teal-300',
                                    'subscription': 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300',
                                    'support': 'bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-300'
                                };
                                return badges[type] || 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300';
                            },
                            formatDate(dateString) {
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
                                
                                return date.toLocaleDateString('en-US', {
                                    day: 'numeric',
                                    month: 'short',
                                    year: 'numeric'
                                });
                            }
                        }" x-init="init()">
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
                                        <div class="flex items-center space-x-2">
                                            <button @click="markAllAsRead()" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" :disabled="unreadCount === 0">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                            <button @click="open = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Notifications List -->
                                <div class="max-h-96 overflow-y-auto">
                                    <template x-if="notifications.length > 0">
                                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                                            <template x-for="(notification, index) in notifications" :key="notification.id">
                                                <div class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"
                                                     :class="{ 'bg-green-50 dark:bg-green-900/20': notification.is_read }">
                                                    <div class="flex items-start">
                                                        <div class="flex-shrink-0 pt-0.5">
                                                            <div class="h-8 w-8 rounded-full flex items-center justify-center" :class="getNotificationBadge(notification.type)">
                                                                <i class="fas" :class="getNotificationIcon(notification.type).split(' ')"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ml-3 flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="notification.title || 'Notification'"></p>
                                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5" x-text="notification.message"></p>
                                                            <div class="mt-1 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                                <span x-text="formatDate(notification.created_at)"></span>
                                                                <span class="mx-1">•</span>
                                                                <span class="capitalize" x-text="notification.type || 'info'"></span>
                                                            </div>
                                                        </div>
                                                        <div class="ml-4 flex-shrink-0 flex">
                                                            <button @click="markAsRead(notification.id, index)"
                                                                    class="text-gray-400 hover:text-green-500 dark:hover:text-green-400 transition-colors"
                                                                    :class="{ 'text-green-500': notification.is_read }">
                                                                <i class="fas fa-check" :class="{ 'text-green-600': notification.is_read }"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    <template x-if="notifications.length === 0">
                                        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-bell-slash text-2xl mb-2 opacity-30"></i>
                                            <p class="text-sm">No unread notifications</p>
                                        </div>
                                    </template>
                                </div>

                                <!-- Footer -->
                                <div class="p-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                                    <a href="{{ route('superadmin.notifications.index') }}" class="block text-center text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">
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
                                    <a href="#" onclick="alert('Account Settings feature coming soon!')" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                                        <i class="fa-solid fa-cog mr-3 text-gray-400"></i>
                                        Account Settings
                                    </a>
                                    <a href="#" onclick="alert('Notification Settings feature coming soon!')" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer">
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
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
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
