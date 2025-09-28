<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Core\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Show chat interface
     */
    public function index()
    {
        $chats = Chat::with(['participants', 'lastMessage'])
            ->whereHas('participants', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('superadmin.chat.index', compact('chats'));
    }

    /**
     * Get chat messages
     */
    public function show($chatId)
    {
        $chat = Chat::with(['participants', 'messages.sender'])
            ->findOrFail($chatId);

        // Check if user is participant
        if (!$chat->participants->contains('user_id', Auth::id())) {
            abort(403);
        }

        return view('superadmin.chat.show', compact('chat'));
    }

    /**
     * Get messages for a chat (AJAX)
     */
    public function getMessages($chatId)
    {
        $chat = Chat::findOrFail($chatId);

        if (!$chat->participants->contains('user_id', Auth::id())) {
            abort(403);
        }

        $messages = $chat->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['messages' => $messages]);
    }

    /**
     * Send message
     */
    public function sendMessage(Request $request, $chatId)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $chat = Chat::findOrFail($chatId);

        if (!$chat->participants->contains('user_id', Auth::id())) {
            abort(403);
        }

        $message = ChatMessage::create([
            'chat_id' => $chatId,
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Update chat updated_at
        $chat->touch();

        return response()->json(['message' => $message->load('sender')]);
    }

    /**
     * Start chat with admin
     */
    public function startChat(Request $request)
    {
        $request->validate([
            'admin_id' => 'required|exists:users,id'
        ]);

        $admin = User::where('id', $request->admin_id)->where('role', 'admin')->firstOrFail();

        // Check if chat already exists
        $existingChat = Chat::whereHas('participants', function ($query) use ($admin) {
            $query->where('user_id', Auth::id());
        })->whereHas('participants', function ($query) use ($admin) {
            $query->where('user_id', $admin->id);
        })->first();

        if ($existingChat) {
            return redirect()->route('superadmin.chat.show', $existingChat->id);
        }

        // Create new chat
        $chat = Chat::create([
            'type' => 'direct'
        ]);

        // Add participants using ChatParticipant model directly
        \App\Models\ChatParticipant::create([
            'chat_id' => $chat->id,
            'user_id' => Auth::id()
        ]);

        \App\Models\ChatParticipant::create([
            'chat_id' => $chat->id,
            'user_id' => $admin->id
        ]);

        return redirect()->route('superadmin.chat.show', $chat->id);
    }
}