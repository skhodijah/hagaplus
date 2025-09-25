@props(['notifications' => null])

<!-- Notification Modal -->
<div id="notification-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" style="z-index: 9999;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-bell text-blue-500 text-xl"></i>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Notifikasi</h3>
                    @if($notifications && $notifications->count() > 0)
                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $notifications->count() }}</span>
                    @endif
                </div>
                <button id="close-notification-modal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-96">
                @if($notifications && $notifications->count() > 0)
                    <div class="space-y-4">
                        @foreach($notifications as $notification)
                            <div class="flex items-start space-x-4 p-4 rounded-lg border {{ $notification->is_read ? 'bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-600' : 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-700' }}">
                                <!-- Notification Icon -->
                                <div class="flex-shrink-0">
                                    @if($notification->type === 'success')
                                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                            <i class="fa-solid fa-check text-green-600 dark:text-green-400"></i>
                                        </div>
                                    @elseif($notification->type === 'warning')
                                        <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                                            <i class="fa-solid fa-exclamation-triangle text-yellow-600 dark:text-yellow-400"></i>
                                        </div>
                                    @elseif($notification->type === 'error')
                                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                                            <i class="fa-solid fa-times text-red-600 dark:text-red-400"></i>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                            <i class="fa-solid fa-info text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Notification Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                                                {{ $notification->title }}
                                            </h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">
                                                {{ $notification->message }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                            </p>
                                        </div>
                                        @if(!$notification->is_read)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                                                Baru
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-bell-slash text-gray-400 dark:text-gray-500 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada notifikasi</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada notifikasi yang tersedia saat ini.</p>
                    </div>
                @endif
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-between p-6 border-t border-gray-200 dark:border-gray-700">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    @if($notifications && $notifications->count() > 0)
                        Menampilkan {{ $notifications->count() }} notifikasi
                    @endif
                </div>
                <div class="flex space-x-3">
                    @if($notifications && $notifications->where('is_read', false)->count() > 0)
                        <button id="mark-all-read" class="px-4 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                            Tandai Semua Dibaca
                        </button>
                    @endif
                    <button id="close-notification-modal-footer" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors text-sm font-medium">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('notification-modal');
    const closeButtons = document.querySelectorAll('#close-notification-modal, #close-notification-modal-footer');
    const markAllReadButton = document.getElementById('mark-all-read');

    // Close modal function
    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Open modal function
    function openModal() {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Close modal with buttons
    closeButtons.forEach(button => {
        button.addEventListener('click', closeModal);
    });

    // Mark all as read
    if (markAllReadButton) {
        markAllReadButton.addEventListener('click', function() {
            // You can add AJAX call here to mark notifications as read
            alert('Fitur tandai semua dibaca akan diimplementasikan');
        });
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Make openModal function globally available
    window.openNotificationModal = openModal;
    window.closeNotificationModal = closeModal;
});
</script>