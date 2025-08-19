<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Display the messages page with conversations.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // Get all chats where the user is involved
        $chats = Chat::where('user1_id', $user->id)
            ->orWhere('user2_id', $user->id)
            ->with(['userRequest.travelerAnnouncement', 'userRequest.senderAnnouncement', 'userRequest.initiator', 'userRequest.announcementOwner', 'messages' => function($query) {
                $query->orderBy('sent_at', 'asc');
            }])
            ->get()
            ->map(function ($chat) use ($user) {
                // Get the other user in the chat
                $otherUserId = $chat->user1_id === $user->id ? $chat->user2_id : $chat->user1_id;
                $otherUser = $chat->user1_id === $user->id ? $chat->user2 : $chat->user1;
                
                // Get the latest message
                $latestMessage = $chat->messages->last();
                
                // Determine announcement type and title
                $announcement = $chat->userRequest->travelerAnnouncement ?? $chat->userRequest->senderAnnouncement;
                $announcementType = $chat->userRequest->travelerAnnouncement ? 'travel' : 'delivery';
                
                if ($announcementType === 'travel') {
                    $title = "Voyage {$announcement->departure_city} → {$announcement->arrival_city}";
                } else {
                    $title = "Livraison {$announcement->pickup_city} → {$announcement->delivery_city}";
                }
                
                return (object) [
                    'id' => $chat->id,
                    'other_user' => $otherUser,
                    'announcement_title' => $title,
                    'announcement_type' => $announcementType,
                    'latest_message' => $latestMessage,
                    'unread_count' => $chat->messages->where('sender_id', '!=', $user->id)->where('read_at', null)->count(),
                    'userRequest' => $chat->userRequest,
                    'messages' => $chat->messages,
                    // Fallback timestamp when there is no latest message yet
                    'created_at' => $chat->created_at,
                ];
            })
            ->sortByDesc(function ($chat) {
                return $chat->latest_message ? $chat->latest_message->sent_at : $chat->created_at;
            });

        return view('messages.index', compact('chats'));
    }

    /**
     * Show a specific conversation.
     */
    public function show($chatId): View
    {
        $user = Auth::user();
        $chat = Chat::where('id', $chatId)
            ->where(function($query) use ($user) {
                $query->where('user1_id', $user->id)
                      ->orWhere('user2_id', $user->id);
            })
            ->with(['userRequest.travelerAnnouncement', 'userRequest.senderAnnouncement', 'userRequest.initiator', 'userRequest.announcementOwner', 'messages.sender'])
            ->firstOrFail();

        // Mark messages as read
        $chat->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('read_at', null)
            ->update(['read_at' => now()]);

        // Get the other user
        $otherUser = $chat->user1_id === $user->id ? $chat->user2 : $chat->user1;
        
        // Get announcement details
        $announcement = $chat->userRequest->travelerAnnouncement ?? $chat->userRequest->senderAnnouncement;
        $announcementType = $chat->userRequest->travelerAnnouncement ? 'travel' : 'delivery';

        return view('messages.show', compact('chat', 'otherUser', 'announcement', 'announcementType'));
    }

    /**
     * Send a message in a chat.
     */
    public function store(Request $request, $chatId)
    {
        $request->validate([
            'message' => 'required|string|max:10000',
        ]);

        $user = Auth::user();
        $chat = Chat::where('id', $chatId)
            ->where(function($query) use ($user) {
                $query->where('user1_id', $user->id)
                      ->orWhere('user2_id', $user->id);
            })
            ->firstOrFail();

        $message = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $user->id,
            'message' => $request->message,
            'sent_at' => now(),
        ]);

        return back()->with('success', 'Message envoyé !');
    }

    /**
     * Create a new chat for a request.
     */
    public function createChat($requestId)
    {
        $user = Auth::user();
        $userRequest = UserRequest::findOrFail($requestId);

        // Check if user is involved in this request
        if ($user->id !== $userRequest->initiator_user_id && $user->id !== $userRequest->announcement_owner_id) {
            abort(403);
        }

        // Check if chat already exists
        $existingChat = Chat::where('request_id', $requestId)->first();
        if ($existingChat) {
            return redirect()->route('messages.show', $existingChat->id);
        }

        // Create new chat
        $chat = Chat::create([
            'request_id' => $requestId,
            'user1_id' => $userRequest->initiator_user_id,
            'user2_id' => $userRequest->announcement_owner_id,
        ]);

        return redirect()->route('messages.show', $chat->id);
    }
}
