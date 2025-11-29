<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HagaPlus - Super Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Base Loader Styles */
        #page-loader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(249, 250, 251, 0.98) 100%);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.6s, transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 1rem;
            box-sizing: border-box;
        }
        
        .dark #page-loader {
            background: linear-gradient(135deg, rgba(17, 24, 39, 0.98) 0%, rgba(31, 41, 55, 0.98) 100%);
        }
        
        #page-loader.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: scale(0.95);
        }

        #page-loader.showing {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: scale(1);
        }
        
        /* Loader Container */
        .loader-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 320px;
            margin: 0 auto;
            padding: 2rem;
            text-align: center;
        }
        
        /* Logo Styles */
        .loader-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            position: relative;
            animation: float 3s ease-in-out infinite;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .loader-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        
        
        /* Text Styles */
        .loader-text {
            color: #4b5563;
            font-size: 1rem;
            font-weight: 500;
            margin: 1.5rem 0 0.5rem;
            position: relative;
            display: inline-block;
            min-height: 1.5rem;
            width: 100%;
        }
        
        .loader-text::after {
            content: '...';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            animation: dots 1.5s steps(4, end) infinite;
            width: auto;
            overflow: visible;
        }
        
        .dark .loader-text {
            color: #d1d5db;
        }
        
        /* Progress Bar */
        .progress-bar {
            width: 100%;
            height: 4px;
            background-color: #e5e7eb;
            border-radius: 2px;
            overflow: hidden;
            margin-top: 1.5rem;
        }
        
        .dark .progress-bar {
            background-color: #374151;
        }
        
        .progress {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #e1ffaa, #10c875);
            transition: width 0.3s ease;
            border-radius: 2px;
        }
        
        /* Animations */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        @keyframes dots {
            0% { opacity: 0.2; }
            50% { opacity: 1; }
            100% { opacity: 0.2; }
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/8c8ccf764d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Global loader management
        window.PageLoader = {
            loader: null,
            progressBar: null,
            loadingText: null,
            progress: 0,
            loadingInterval: null,
            activeRequests: 0,
            isPageLoading: true,

            init() {
                this.loader = document.getElementById('page-loader');
                this.progressBar = document.querySelector('.progress');
                this.loadingText = document.querySelector('.loader-text');

                // Show loader immediately for page refresh/reload
                this.show('Memuat Halaman...');

                this.setupEventListeners();
            },

            setupEventListeners() {
                // Handle navigation between pages
                document.addEventListener('click', (e) => {
                    // Find the closest anchor element
                    let target = e.target.closest('a');

                    // If no anchor element or it's an external link, return
                    if (!target ||
                        target.target === '_blank' ||
                        target.hostname !== window.location.hostname ||
                        target.getAttribute('href')?.startsWith('#') ||
                        target.getAttribute('data-dropdown-toggle') ||
                        target.getAttribute('data-drawer-target') ||
                        target.getAttribute('data-modal-target') ||
                        target.classList.contains('no-loader')) {
                        return;
                    }

                    this.show('Memuat Halaman...');
                    e.preventDefault();

                    // Navigate to the new page after a short delay
                    setTimeout(() => {
                        window.location.href = target.href;
                    }, 150);
                }, true);

                // Handle form submissions
                document.addEventListener('submit', (e) => {
                    const form = e.target;
                    if (form.classList.contains('no-loader')) return;

                    this.show('Memproses...');
                });

                // Handle page load completion
                window.addEventListener('load', () => {
                    // Small delay to ensure everything is rendered
                    setTimeout(() => {
                        window.PageLoader.isPageLoading = false;
                        if (window.PageLoader.activeRequests === 0) {
                            window.PageLoader.hide();
                        }
                    }, 100);
                });

                // Intercept XMLHttpRequest for AJAX requests
                const originalOpen = XMLHttpRequest.prototype.open;
                XMLHttpRequest.prototype.open = function(method, url) {
                    this.addEventListener('loadstart', () => {
                        window.PageLoader.activeRequests++;
                        if (!window.PageLoader.isPageLoading) {
                            window.PageLoader.show('Memuat Data...');
                        }
                    });

                    this.addEventListener('loadend', () => {
                        window.PageLoader.activeRequests--;
                        if (window.PageLoader.activeRequests === 0 && !window.PageLoader.isPageLoading) {
                            setTimeout(() => {
                                window.PageLoader.hide();
                            }, 200);
                        }
                    });

                    return originalOpen.apply(this, arguments);
                };

                // Intercept fetch requests
                const originalFetch = window.fetch;
                window.fetch = function(...args) {
                    window.PageLoader.activeRequests++;
                    if (!window.PageLoader.isPageLoading) {
                        window.PageLoader.show('Memuat Data...');
                    }

                    return originalFetch.apply(this, args).finally(() => {
                        window.PageLoader.activeRequests--;
                        if (window.PageLoader.activeRequests === 0 && !window.PageLoader.isPageLoading) {
                            setTimeout(() => {
                                window.PageLoader.hide();
                            }, 200);
                        }
                    });
                };
            },

            show(message = 'Memuat...') {
                if (!this.loader) return;

                // Clear any existing interval
                if (this.loadingInterval) {
                    clearInterval(this.loadingInterval);
                }

                // Prevent flickering by checking if already visible
                if (!this.loader.classList.contains('hidden')) {
                    // Just update the message if already visible
                    if (this.loadingText) {
                        this.loadingText.textContent = message;
                    }
                    return;
                }

                this.progress = 0;
                if (this.progressBar) {
                    this.progressBar.style.width = '0%';
                }
                if (this.loadingText) {
                    this.loadingText.textContent = message;
                }

                // Show loader with smooth transition
                this.loader.classList.remove('hidden');
                this.loader.classList.add('showing');

                // Start progress animation with smoother increments
                this.loadingInterval = setInterval(() => {
                    this.progress += Math.random() * 8 + 2; // Slower, more consistent progress
                    if (this.progress > 90) {
                        this.progress = 90; // Don't go to 100% until actually complete
                    }
                    if (this.progressBar) {
                        this.progressBar.style.width = this.progress + '%';
                    }
                }, 300); // Slower interval for smoother animation
            },

            hide() {
                if (!this.loader || this.loader.classList.contains('hidden')) return;

                // Clear the progress interval
                if (this.loadingInterval) {
                    clearInterval(this.loadingInterval);
                    this.loadingInterval = null;
                }

                // Complete the progress bar smoothly
                this.progress = 100;
                if (this.progressBar) {
                    this.progressBar.style.width = '100%';
                }
                if (this.loadingText) {
                    this.loadingText.textContent = 'Selesai!';
                }

                // Hide with longer delay for smooth transition
                setTimeout(() => {
                    if (this.loader && this.activeRequests === 0 && !this.isPageLoading) {
                        this.loader.classList.remove('showing');
                        this.loader.classList.add('hidden');
                    }
                }, 500); // Longer delay for smoother transition
            }
        };

        // Initialize loader when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            window.PageLoader.init();
        });
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <!-- Enhanced Page Loader -->
    <div id="page-loader">
        <div class="loader-container">
            <div class="loader-logo">
                <img src="{{ asset('favicon.svg') }}" alt="HagaPlus" style="width: 60px; height: 60px;">
            </div>
            <div class="loader-text">Memuat...</div>
            <div class="progress-bar">
                <div class="progress"></div>
            </div>
        </div>
    </div>
    @php
        $isInstansiActive = request()->routeIs('superadmin.instansi.*');
        $isSubscriptionsActive = request()->routeIs('superadmin.subscriptions.*') || request()->routeIs('superadmin.payment-methods.*');
        $isPackagesActive = request()->routeIs('superadmin.packages.*');
        $isFinancialActive = request()->routeIs('superadmin.financial.*');
        $isReportsActive = request()->routeIs('superadmin.analytics.*') || request()->routeIs('superadmin.reports.*');
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
                </x-layout.sidebar-accordion>

                <x-layout.sidebar-link :href="route('superadmin.users.index')" icon="fa-solid fa-users" label="User Management" :active="request()->routeIs('superadmin.users.*')" />

                <x-layout.sidebar-accordion icon="fa-solid fa-receipt" label="Subscription Management" :open="$isSubscriptionsActive" target="menu-subscriptions">
                    <x-layout.sidebar-subitem :href="route('superadmin.subscriptions.index')" label="All Subscriptions" :active="request()->routeIs('superadmin.subscriptions.index')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.subscriptions.analytics')" label="Subscription Analytics" :active="request()->routeIs('superadmin.subscriptions.analytics')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.subscriptions.subscription-requests')" label="Transaction Requests" :active="request()->routeIs('superadmin.subscriptions.subscription-requests')" :badge="\DB::table('subscription_requests')->where('payment_status', 'pending')->count()" />
                    <x-layout.sidebar-subitem :href="route('superadmin.payment-methods.index')" label="Payment Methods" :active="request()->routeIs('superadmin.payment-methods.*')" />
                </x-layout.sidebar-accordion>

                <x-layout.sidebar-accordion icon="fa-solid fa-box-open" label="Package Management" :open="$isPackagesActive" target="menu-packages">
                    <x-layout.sidebar-subitem :href="route('superadmin.packages.index')" label="Manage Packages" :active="request()->routeIs('superadmin.packages.index')" />
                </x-layout.sidebar-accordion>

                <x-layout.sidebar-accordion icon="fa-solid fa-chart-line" label="Reports" :open="$isReportsActive" target="menu-reports">
                    <x-layout.sidebar-subitem :href="route('superadmin.analytics.index')" label="Analytics Dashboard" :active="request()->routeIs('superadmin.analytics.index')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.reports.activities')" label="Recent Activities" :active="request()->routeIs('superadmin.reports.activities')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.reports.usage')" label="Usage Statistics" :active="request()->routeIs('superadmin.reports.usage')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.reports.performance')" label="Performance Reports" :active="request()->routeIs('superadmin.reports.performance')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.reports.export')" label="Export Data" :active="request()->routeIs('superadmin.reports.export')" />
                </x-layout.sidebar-accordion>

                <x-layout.sidebar-accordion icon="fa-solid fa-bell" label="Communication" :open="request()->routeIs('superadmin.notifications.*')" target="menu-communication">
                    <x-layout.sidebar-subitem :href="route('superadmin.notifications.index')" label="Notifications" :active="request()->routeIs('superadmin.notifications.index')" />
                    <x-layout.sidebar-subitem :href="route('superadmin.notifications.bulk')" label="Bulk Notifications" :active="request()->routeIs('superadmin.notifications.bulk')" />
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
                                        @if(Auth::user()->avatar)
                                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile Picture" class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                                        @else
                                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                                {{ Auth::user()->initials() }}
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Menu Items -->
                                <div class="py-1">
                                    <a href="{{ route('superadmin.settings.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <i class="fa-solid fa-user-cog mr-3 text-gray-400"></i>
                                        Profile & Account Settings
                                    </a>
                                    <a href="{{ route('superadmin.settings.notifications') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <i class="fa-solid fa-bell mr-3 text-gray-400"></i>
                                        Notification Settings
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
