<x-admin-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Manage and view all your notifications</p>
            </div>
            <div class="flex items-center space-x-3">
                <button id="mark-all-read" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <i class="fa-solid fa-check-double mr-2"></i>
                    Mark All Read
                </button>
                <button id="delete-all" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <i class="fa-solid fa-trash-alt mr-2"></i>
                    Delete All
                </button>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($notifications as $notification)
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ !$notification->is_read ? 'bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-l-4 border-l-blue-500' : '' }}" id="notification-{{ $notification->id }}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4 flex-1">
                                <!-- Notification Icon -->
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $notification->type_badge_class }}">
                                        <i class="fas {{ $notification->icon }} text-white text-lg"></i>
                                    </div>
                                </div>

                                <!-- Notification Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                                {{ $notification->title }}
                                            </h3>
                                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-3">
                                                {{ $notification->message }}
                                            </p>
                                            <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                                <span class="flex items-center">
                                                    <i class="fa-solid fa-clock mr-1"></i>
                                                    {{ $notification->created_at->format('d M Y, H:i') }}
                                                </span>
                                                <span class="flex items-center">
                                                    <i class="fa-solid fa-tag mr-1"></i>
                                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $notification->type_badge_class }}">
                                                        {{ ucfirst($notification->type) }}
                                                    </span>
                                                </span>
                                                @if(!$notification->is_read)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                                        <i class="fa-solid fa-circle text-xs mr-1"></i>
                                                        New
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex items-center space-x-2 ml-4">
                                            @if(!$notification->is_read)
                                                <button class="mark-as-read inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors duration-200" data-id="{{ $notification->id }}">
                                                    <i class="fa-solid fa-check mr-1"></i>
                                                    Mark Read
                                                </button>
                                            @else
                                                <button class="mark-as-unread inline-flex items-center px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded transition-colors duration-200" data-id="{{ $notification->id }}">
                                                    <i class="fa-solid fa-undo mr-1"></i>
                                                    Mark Unread
                                                </button>
                                            @endif
                                            <button class="delete-notification inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded transition-colors duration-200" data-id="{{ $notification->id }}">
                                                <i class="fa-solid fa-trash-alt mr-1"></i>
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-bell-slash text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No notifications</h3>
                        <p class="text-gray-500 dark:text-gray-400">You're all caught up! No new notifications at the moment.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($notifications->hasPages())
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Mark as read
            document.querySelectorAll('.mark-as-read').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    markAsRead(id);
                });
            });

            // Mark as unread
            document.querySelectorAll('.mark-as-unread').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    markAsUnread(id);
                });
            });

            // Delete notification
            document.querySelectorAll('.delete-notification').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    if (confirm('Are you sure you want to delete this notification?')) {
                        deleteNotification(id);
                    }
                });
            });

            // Mark all as read
            document.getElementById('mark-all-read')?.addEventListener('click', function() {
                if (confirm('Are you sure you want to mark all notifications as read?')) {
                    markAllAsRead();
                }
            });

            // Delete all notifications
            document.getElementById('delete-all')?.addEventListener('click', function() {
                if (confirm('Are you sure you want to delete all notifications? This action cannot be undone.')) {
                    deleteAllNotifications();
                }
            });

            async function markAsRead(id) {
                try {
                    const response = await fetch(`/admin/notifications/${id}/read`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });

                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Failed to mark notification as read.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while marking the notification as read.');
                }
            }

            async function markAsUnread(id) {
                try {
                    const response = await fetch(`/admin/notifications/${id}/unread`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });

                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Failed to mark notification as unread.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while marking the notification as unread.');
                }
            }

            async function deleteNotification(id) {
                try {
                    const response = await fetch(`/admin/notifications/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });

                    if (response.ok) {
                        document.getElementById(`notification-${id}`).remove();
                    } else {
                        alert('Failed to delete notification.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the notification.');
                }
            }

            async function markAllAsRead() {
                try {
                    const response = await fetch('/admin/notifications/mark-all-read', {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });

                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Failed to mark all notifications as read.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while marking all notifications as read.');
                }
            }

            async function deleteAllNotifications() {
                try {
                    const response = await fetch('/admin/notifications', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });

                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Failed to delete all notifications.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while deleting all notifications.');
                }
            }
        });
    </script>
</x-admin-layout>