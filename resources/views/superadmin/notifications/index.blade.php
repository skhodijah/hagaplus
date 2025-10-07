<x-superadmin-layout>
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Stay updated with system activities and requests</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button onclick="markAllAsRead()"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-check-double mr-2"></i>
                        Mark All Read
                    </button>
                    <button onclick="deleteAllNotifications()"
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Clear All
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Active Filters Summary -->
        @if(request()->hasAny(['type', 'status', 'date_from', 'date_to', 'search']))
            <div class="px-6 py-3 bg-blue-50 dark:bg-blue-900/20 border-b border-blue-200 dark:border-blue-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm text-blue-800 dark:text-blue-200">
                        <i class="fas fa-filter"></i>
                        <span class="font-medium">Active Filters:</span>
                        <div class="flex flex-wrap gap-2">
                            @if(request('type'))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                                    Type: {{ ucfirst(str_replace('_', ' ', request('type'))) }}
                                    <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="ml-1 text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-times text-xs"></i>
                                    </a>
                                </span>
                            @endif
                            @if(request('status'))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                                    Status: {{ ucfirst(request('status')) }}
                                    <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="ml-1 text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-times text-xs"></i>
                                    </a>
                                </span>
                            @endif
                            @if(request('date_from') || request('date_to'))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                                    Date: {{ request('date_from') ?: '...' }} to {{ request('date_to') ?: '...' }}
                                    <a href="{{ request()->fullUrlWithQuery(['date_from' => null, 'date_to' => null]) }}" class="ml-1 text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-times text-xs"></i>
                                    </a>
                                </span>
                            @endif
                            @if(request('search'))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                                    Search: "{{ request('search') }}"
                                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-1 text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-times text-xs"></i>
                                    </a>
                                </span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('superadmin.notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                        Clear All
                    </a>
                </div>
            </div>
        @endif

        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-tag mr-1"></i>Type
                    </label>
                    <select name="type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                        <option value="">All Types</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-eye mr-1"></i>Status
                    </label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                        <option value="">All Status</option>
                        <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i>From Date
                    </label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i>To Date
                    </label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                </div>

                <!-- Search and Actions -->
                <div class="flex flex-col gap-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-search mr-1"></i>Search
                    </label>
                    <div class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search notifications..."
                               class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('superadmin.notifications.index') }}"
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="space-y-4">
        @forelse($notifications as $notification)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow duration-200 {{ !$notification->is_read ? 'ring-2 ring-blue-500/20 border-blue-300 dark:border-blue-600' : '' }}"
                 id="notification-{{ $notification->id }}">

                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <!-- Notification Icon -->
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center
                                @if($notification->type === 'subscription_request') bg-gradient-to-br from-blue-500 to-blue-600 text-white
                                @elseif($notification->type === 'success') bg-gradient-to-br from-green-500 to-green-600 text-white
                                @elseif($notification->type === 'error') bg-gradient-to-br from-red-500 to-red-600 text-white
                                @elseif($notification->type === 'warning') bg-gradient-to-br from-yellow-500 to-yellow-600 text-white
                                @else bg-gradient-to-br from-gray-500 to-gray-600 text-white @endif">
                                <i class="fas {{ $notification->icon }} text-lg"></i>
                            </div>
                        </div>

                        <!-- Notification Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $notification->title }}
                                        </h3>
                                        @if(!$notification->is_read)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                New
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-gray-700 dark:text-gray-300 mb-3 leading-relaxed">
                                        {{ $notification->message }}
                                    </p>

                                    <!-- Action Button for Subscription Requests -->
                                    @if($notification->type === 'subscription_request')
                                        <div class="mb-3">
                                            <a href="{{ route('superadmin.subscriptions.subscription-requests') }}"
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                <i class="fas fa-eye mr-2"></i>
                                                View Requests
                                            </a>
                                        </div>
                                    @endif

                                    <!-- Metadata -->
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center gap-4">
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-clock text-xs"></i>
                                                <time datetime="{{ $notification->created_at->toIso8601String() }}"
                                                      title="{{ $notification->created_at->format('M j, Y g:i A') }}">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </time>
                                            </span>

                                            @if($notification->type)
                                                <span class="flex items-center gap-1">
                                                    <i class="fas fa-tag text-xs"></i>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($notification->type === 'subscription_request') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                                        @elseif($notification->type === 'success') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                                        @elseif($notification->type === 'error') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                                        @elseif($notification->type === 'warning') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300 @endif">
                                                        {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                                                    </span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-2 mt-4 sm:mt-0">
                                    @if(!$notification->is_read)
                                        <button onclick="markAsRead('{{ $notification->id }}')"
                                                class="inline-flex items-center px-3 py-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-200"
                                                title="Mark as read">
                                            <i class="fas fa-check mr-1"></i>
                                            <span class="hidden sm:inline">Mark Read</span>
                                        </button>
                                    @endif

                                    <button onclick="deleteNotification('{{ $notification->id }}')"
                                            class="inline-flex items-center px-3 py-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200"
                                            title="Delete notification">
                                        <i class="fas fa-trash mr-1"></i>
                                        <span class="hidden sm:inline">Delete</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <i class="fas fa-bell-slash text-3xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No notifications yet</h3>
                <p class="text-gray-600 dark:text-gray-400">You'll see important updates and requests here when they arrive.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 px-6 py-4">
            {{ $notifications->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<script>
    function markAsRead(notificationId) {
        fetch(`/superadmin/notifications/${notificationId}/read`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
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
                const notificationElement = document.getElementById(`notification-${notificationId}`);
                if (notificationElement) {
                    // Remove the blue ring and border styling for read notifications
                    notificationElement.classList.remove('ring-2', 'ring-blue-500/20', 'border-blue-300', 'dark:border-blue-600');
                    // Hide the "New" badge
                    const newBadge = notificationElement.querySelector('.bg-blue-100, .dark\\:bg-blue-900\\/30');
                    if (newBadge) {
                        newBadge.style.display = 'none';
                    }
                    // Hide the mark as read button
                    const markAsReadBtn = notificationElement.querySelector(`button[onclick*="markAsRead('${notificationId}')"]`);
                    if (markAsReadBtn) {
                        markAsReadBtn.style.display = 'none';
                    }
                }
                updateUnreadCount();
            } else {
                showToast('Failed to mark notification as read', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error marking notification as read', 'error');
        });
    }

    function markAllAsRead() {
        if (!confirm('Are you sure you want to mark all notifications as read?')) {
            return;
        }

        fetch('/superadmin/notifications/mark-all-read', {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
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
                document.querySelectorAll('[id^="notification-"]').forEach(el => {
                    // Remove styling for read notifications
                    el.classList.remove('ring-2', 'ring-blue-500/20', 'border-blue-300', 'dark:border-blue-600');
                    // Hide "New" badges
                    const newBadges = el.querySelectorAll('.bg-blue-100, .dark\\:bg-blue-900\\/30');
                    newBadges.forEach(badge => badge.style.display = 'none');
                    // Hide mark as read buttons
                    const markAsReadBtns = el.querySelectorAll('button[onclick*="markAsRead"]');
                    markAsReadBtns.forEach(btn => btn.style.display = 'none');
                });
                updateUnreadCount();
                showToast('All notifications marked as read', 'success');
            } else {
                showToast('Failed to mark all notifications as read', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error marking all notifications as read', 'error');
        });
    }

    function deleteNotification(notificationId) {
        if (!confirm('Are you sure you want to delete this notification?')) {
            return;
        }

        fetch(`/superadmin/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
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
                const notificationElement = document.getElementById(`notification-${notificationId}`);
                if (notificationElement) {
                    notificationElement.remove();
                    updateUnreadCount();
                    showToast('Notification deleted successfully', 'success');
                }
            } else {
                showToast('Failed to delete notification', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error deleting notification', 'error');
        });
    }

    function deleteAllNotifications() {
        if (!confirm('Are you sure you want to delete all notifications? This action cannot be undone.')) {
            return;
        }

        fetch('/superadmin/notifications', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
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
                window.location.reload();
            } else {
                showToast('Failed to delete all notifications', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error deleting all notifications', 'error');
        });
    }

    function updateUnreadCount() {
        fetch('/superadmin/notifications/count')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const countElement = document.querySelector('[data-notification-count]');
                if (countElement) {
                    if (data.count > 0) {
                        countElement.textContent = data.count;
                        countElement.classList.remove('hidden');
                    } else {
                        countElement.classList.add('hidden');
                    }
                }
            })
            .catch(error => {
                console.error('Error updating unread count:', error);
            });
    }

    function showToast(message, type = 'info') {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;

        // Set colors based on type
        if (type === 'success') {
            toast.classList.add('bg-green-500', 'text-white');
        } else if (type === 'error') {
            toast.classList.add('bg-red-500', 'text-white');
        } else {
            toast.classList.add('bg-blue-500', 'text-white');
        }

        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 100);

        // Remove after 3 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
</script>
</x-superadmin-layout>
