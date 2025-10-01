<x-superadmin-layout>
<div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Notifications</h2>
            <div class="flex space-x-4">
                <button onclick="markAllAsRead()" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-check-double mr-2"></i> Mark All as Read
                </button>
                <button onclick="deleteAllNotifications()" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-trash-alt mr-2"></i> Clear All
                </button>
            </div>
        </div>
    </div>

    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($notifications as $notification)
            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ !$notification->is_read ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}"
                 id="notification-{{ $notification->id }}">
                <div class="flex items-start">
                    <div class="flex-shrink-0 pt-0.5">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center {{ $notification->type_badge_class }} dark:bg-opacity-20">
                            <i class="fas {{ $notification->icon }}"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $notification->title }}
                            </p>
                            <div class="flex space-x-2">
                                @if(!$notification->is_read)
                                    <button onclick="markAsRead('{{ $notification->id }}')" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Mark as read">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                                <button onclick="deleteNotification('{{ $notification->id }}')" class="text-xs text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            {{ $notification->message }}
                        </p>
                        @if($notification->type === 'subscription_request' && $notification->action_url)
                            <div class="mt-2">
                                <a href="{{ $notification->action_url }}" class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-full hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors">
                                    <i class="fas fa-external-link-alt mr-1"></i>
                                    View Payment History
                                </a>
                            </div>
                        @endif
                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                            <time datetime="{{ $notification->created_at->toIso8601String() }}" title="{{ $notification->created_at->format('M j, Y g:i A') }}">
                                {{ $notification->created_at->diffForHumans() }}
                            </time>
                            @if($notification->type)
                                <span class="mx-2">•</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $notification->type_badge_class }} dark:bg-opacity-20">
                                    {{ ucfirst($notification->type) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                <i class="fas fa-bell-slash text-4xl mb-3 opacity-30"></i>
                <p>No notifications found.</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $notifications->links() }}
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
                    notificationElement.classList.remove('bg-blue-50', 'dark:bg-blue-900/20');
                    // Hide the mark as read button
                    const markAsReadBtn = notificationElement.querySelector(`button[onclick*="markAsRead('${notificationId}')"]`);
                    if (markAsReadBtn) {
                        markAsReadBtn.style.display = 'none';
                    }
                }
                updateUnreadCount();
            } else {
                alert('Failed to mark notification as read');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error marking notification as read');
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
                    el.classList.remove('bg-blue-50', 'dark:bg-blue-900/20');
                    // Hide all mark as read buttons
                    const markAsReadBtns = el.querySelectorAll('button[onclick*="markAsRead"]');
                    markAsReadBtns.forEach(btn => btn.style.display = 'none');
                });
                updateUnreadCount();
            } else {
                alert('Failed to mark all notifications as read');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error marking all notifications as read');
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
                }
            } else {
                alert('Failed to delete notification');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting notification');
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
                alert('Failed to delete all notifications');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting all notifications');
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
</script>
</x-superadmin-layout>
