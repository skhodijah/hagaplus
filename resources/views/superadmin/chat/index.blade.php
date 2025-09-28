<x-superadmin-layout>
<div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Customer Service Chat</h2>
            <button onclick="showStartChatModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-2"></i> Start New Chat
            </button>
        </div>
    </div>

    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($chats as $chat)
            <a href="{{ route('superadmin.chat.show', $chat->id) }}"
               class="block p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $chat->other_participant ? $chat->other_participant->name : 'Unknown User' }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $chat->updated_at->diffForHumans() }}
                            </p>
                        </div>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300 truncate">
                            @if($chat->lastMessage)
                                <span class="font-medium">{{ $chat->lastMessage->sender->name }}:</span>
                                {{ Str::limit($chat->lastMessage->message, 50) }}
                            @else
                                No messages yet
                            @endif
                        </p>
                        @if($chat->other_participant && $chat->other_participant->instansi)
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ $chat->other_participant->instansi->nama_instansi }}
                            </p>
                        @endif
                    </div>
                    @if($chat->messages()->where('sender_id', '!=', auth()->id())->whereNull('read_at')->exists())
                        <div class="ml-4 flex-shrink-0">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                New
                            </span>
                        </div>
                    @endif
                </div>
            </a>
        @empty
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                <i class="fas fa-comments text-4xl mb-3 opacity-30"></i>
                <p class="text-lg font-medium">No chats yet</p>
                <p class="text-sm">Start a conversation with an admin to get help.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Start Chat Modal -->
<div id="startChatModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Start New Chat</h3>
                <button onclick="hideStartChatModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <form action="{{ route('superadmin.chat.start') }}" method="POST" class="p-6">
                @csrf
                <div class="mb-6">
                    <label for="admin_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        <i class="fas fa-user mr-2"></i>Select Admin
                    </label>
                    <select name="admin_id" id="admin_id" required
                            class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors">
                        <option value="">Choose an admin...</option>
                        @foreach(\App\Models\Core\User::where('role', 'admin')->get() as $admin)
                            <option value="{{ $admin->id }}">
                                {{ $admin->name }} 
                                @if($admin->instansi)
                                    <span class="text-gray-500">({{ $admin->instansi->nama_instansi }})</span>
                                @else
                                    <span class="text-gray-500">(No Instansi)</span>
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @if(\App\Models\Core\User::where('role', 'admin')->count() === 0)
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-info-circle mr-1"></i>
                            No admins available at the moment.
                        </p>
                    @endif
                </div>
                
                <!-- Modal Footer -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" onclick="hideStartChatModal()"
                            class="px-6 py-2.5 bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition-colors font-medium">
                        Cancel
                    </button>
                    <button type="submit" 
                            @if(\App\Models\Core\User::where('role', 'admin')->count() === 0) disabled @endif
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-comments mr-2"></i>Start Chat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showStartChatModal() {
    const modal = document.getElementById('startChatModal');
    const modalContent = document.getElementById('modalContent');
    
    // Show modal
    modal.classList.remove('hidden');
    
    // Trigger animation after a small delay
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
    
    // Focus on select element
    setTimeout(() => {
        document.getElementById('admin_id').focus();
    }, 300);
}

function hideStartChatModal() {
    const modal = document.getElementById('startChatModal');
    const modalContent = document.getElementById('modalContent');
    
    // Start hide animation
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    // Hide modal after animation
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('startChatModal');
    const modalContent = document.getElementById('modalContent');
    
    if (event.target === modal) {
        hideStartChatModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('startChatModal');
        if (!modal.classList.contains('hidden')) {
            hideStartChatModal();
        }
    }
});
</script>
</x-superadmin-layout>