<x-superadmin-layout>
<div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden h-96 flex flex-col">
    <!-- Chat Header -->
    <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ $chat->other_participant ? $chat->other_participant->name : 'Unknown User' }}
                    </h3>
                    @if($chat->other_participant && $chat->other_participant->instansi)
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $chat->other_participant->instansi->nama_instansi }}
                        </p>
                    @endif
                </div>
            </div>
            <a href="{{ route('superadmin.chat.index') }}"
               class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </div>

    <!-- Messages -->
    <div id="messages" class="flex-1 overflow-y-auto p-4 space-y-4">
        <!-- Messages will be loaded here -->
    </div>

    <!-- Message Input -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
        <form id="messageForm" class="flex space-x-3">
            <input type="text" id="messageInput"
                   class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                   placeholder="Type your message..."
                   required>
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<script>
let chatId = {{ $chat->id }};
let currentUserId = {{ auth()->id() }};

document.addEventListener('DOMContentLoaded', function() {
    loadMessages();
    setInterval(loadMessages, 3000); // Refresh every 3 seconds

    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');

    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        sendMessage();
    });

    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
});

function loadMessages() {
    fetch(`/superadmin/chat/${chatId}/messages`)
        .then(response => response.json())
        .then(data => {
            const messagesContainer = document.getElementById('messages');
            messagesContainer.innerHTML = '';

            data.messages.forEach(message => {
                const messageElement = createMessageElement(message);
                messagesContainer.appendChild(messageElement);
            });

            // Scroll to bottom
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        });
}

function createMessageElement(message) {
    const isOwnMessage = message.sender_id === currentUserId;
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex ${isOwnMessage ? 'justify-end' : 'justify-start'}`;

    messageDiv.innerHTML = `
        <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${
            isOwnMessage
                ? 'bg-blue-600 text-white'
                : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white'
        }">
            <p class="text-sm">${message.message}</p>
            <p class="text-xs mt-1 opacity-70">
                ${new Date(message.created_at).toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                })}
            </p>
        </div>
    `;

    return messageDiv;
}

function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();

    if (!message) return;

    fetch(`/superadmin/chat/${chatId}/messages`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        messageInput.value = '';
        loadMessages(); // Reload messages to show the new one
    })
    .catch(error => {
        console.error('Error sending message:', error);
    });
}
</script>
</x-superadmin-layout>