@extends('layouts.app')

@section('title', 'Messages - CarryWay')

@section('content')
<div class="min-h-full">
    <!-- Dashboard Header -->
    <div class="bg-white shadow-sm border-b border-gray-200 mb-6">
        <div class="px-6 py-4">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Messages</h1>
                    <p class="text-gray-600 text-sm lg:text-base">Communiquez avec les autres utilisateurs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
            {{ session('error') }}
        </div>
    @endif

    <!-- Messages Interface -->
    <div class="bg-white shadow rounded-lg">
        <div class="flex h-[600px]">
            <!-- Conversations List -->
            <div class="w-1/3 border-r border-gray-200 bg-gray-50">
                <div class="p-4 border-b border-gray-200 bg-white">
                    <h3 class="text-lg font-medium text-gray-900">Conversations</h3>
                </div>
                <div class="overflow-y-auto h-full">
                    @forelse($chats as $chat)
                        <div class="p-4 border-b border-gray-100 hover:bg-white cursor-pointer transition-all duration-200 conversation-item bg-white" 
                             data-chat-id="{{ $chat->id }}"
                             data-other-user="{{ $chat->other_user->name }}"
                             data-announcement-title="{{ $chat->announcement_title }}"
                             data-show-url="{{ route('messages.show', $chat->id) }}"
                             data-post-url="{{ route('messages.store', $chat->id) }}">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-sm">
                                        <span class="text-white font-semibold text-sm">{{ substr($chat->other_user->name, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-semibold text-gray-900 truncate">
                                            <a href="{{ route('profiles.show', $chat->other_user->id) }}" 
                                               class="inline-block px-2 py-1 rounded hover:text-blue-600 hover:bg-blue-50 transition-all duration-200 cursor-pointer border border-transparent hover:border-blue-200">
                                                {{ $chat->other_user->name }}
                                            </a>
                                        </p>
                                        @if($chat->latest_message)
                                            <span class="text-xs text-gray-400">{{ $chat->latest_message->sent_at->diffForHumans() }}</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-blue-600 font-medium truncate mb-1">{{ $chat->announcement_title }}</p>
                                    @if($chat->latest_message)
                                        <p class="text-sm text-gray-600 truncate">{{ Str::limit($chat->latest_message->message, 40) }}</p>
                                    @else
                                        <p class="text-sm text-gray-500 italic">Aucun message</p>
                                    @endif
                                </div>
                                @if($chat->unread_count > 0)
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-500 text-white text-xs font-bold rounded-full">
                                            {{ $chat->unread_count }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="mx-auto h-16 w-16 text-gray-300">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune conversation</h3>
                            <p class="mt-1 text-sm text-gray-500">Créez une demande pour commencer à discuter !</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Message Content -->
            <div class="flex-1 flex flex-col bg-white" id="message-content">
                <!-- Message Header -->
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white" id="message-header">
                    <div class="flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Sélectionnez une conversation</h3>
                            <p class="text-sm text-gray-500">Choisissez une conversation pour commencer à discuter</p>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <div class="flex-1 overflow-y-auto p-6 flex items-center justify-center bg-gray-50" id="messages-area">
                    <div class="text-center text-gray-500">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-900 mb-1">Aucune conversation sélectionnée</p>
                        <p class="text-sm text-gray-500">Sélectionnez une conversation dans la liste pour voir les messages</p>
                    </div>
                </div>

                <!-- Message Input -->
                <div class="p-4 border-t border-gray-200 bg-white" id="message-input">
                    <div class="flex items-center space-x-3 opacity-50">
                        <div class="flex-1">
                            <input type="text" placeholder="Tapez votre message..." 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   disabled>
                        </div>
                        <button class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-gray-400 cursor-not-allowed" disabled>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Envoyer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const conversationItems = document.querySelectorAll('.conversation-item');
    const messageHeader = document.getElementById('message-header');
    const messagesArea = document.getElementById('messages-area');
    const messageInput = document.getElementById('message-input');
    
    // Store chat data for easy access
    const chatData = {};
    @foreach($chats as $chat)
        chatData['{{ $chat->id }}'] = {
            otherUser: '{{ $chat->other_user->name }}',
            announcementTitle: '{{ $chat->announcement_title }}',
            messages: @json($chat->messages ?? [])
        };
    @endforeach

    conversationItems.forEach(item => {
        item.addEventListener('click', function() {
            const chatId = this.dataset.chatId;
            const chat = chatData[chatId];
            
            if (!chat) return;
            
            // Update header with expand button
            const showUrl = this.dataset.showUrl;
            const postUrl = this.dataset.postUrl;
            messageHeader.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-sm">
                            <span class="text-white font-semibold text-sm">${chat.otherUser.substring(0, 2)}</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">${chat.otherUser}</h3>
                            <p class="text-sm text-gray-500">${chat.announcementTitle}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="${showUrl}" class="inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-lg text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                            </svg>
                            Développer
                        </a>
                    </div>
                </div>
            `;
            
            // Update messages area with professional design
            if (chat.messages && chat.messages.length > 0) {
                let messagesHtml = '';
                chat.messages.forEach(message => {
                    const isOwn = message.sender_id === '{{ auth()->id() }}';
                    const messageClass = isOwn ? 'justify-end' : '';
                    const bubbleClass = isOwn ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-gray-900 border border-gray-200 shadow-sm';
                    const time = new Date(message.sent_at).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
                    
                    messagesHtml += `
                        <div class="flex items-start space-x-3 ${messageClass} mb-4">
                            ${!isOwn ? `<div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-sm">
                                <span class="text-white text-xs font-semibold">${chat.otherUser.substring(0, 2)}</span>
                            </div>` : ''}
                            <div class="flex-1 ${isOwn ? 'text-right' : ''} max-w-xs">
                                <div class="inline-block ${bubbleClass} rounded-2xl px-4 py-3">
                                    <div class="text-sm leading-relaxed" style="direction: ltr; text-align: left;">${message.message.replace(/\n/g, '<br>')}</div>
                                </div>
                                <p class="text-xs text-gray-400 mt-2 ${isOwn ? 'text-right' : ''}">${time}</p>
                            </div>
                            ${isOwn ? `<div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-sm">
                                <span class="text-white text-xs font-semibold">Moi</span>
                            </div>` : ''}
                        </div>
                    `;
                });
                messagesArea.innerHTML = `<div class="space-y-2">${messagesHtml}</div>`;
                messagesArea.classList.remove('bg-gray-50', 'flex', 'items-center', 'justify-center');
                messagesArea.classList.add('bg-white');
            } else {
                messagesArea.innerHTML = `
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-900 mb-1">Aucun message</p>
                        <p class="text-sm text-gray-500">Commencez la conversation en envoyant le premier message !</p>
                    </div>
                `;
                messagesArea.classList.remove('bg-gray-50', 'flex', 'items-center', 'justify-center');
                messagesArea.classList.add('bg-white');
            }
            
            // Update input area with working form
            messageInput.innerHTML = `
                <form method="POST" action="${postUrl}" class="space-y-3">
                    @csrf
                    <div>
                        <textarea name="message" rows="3" placeholder="Tapez votre message..." 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-y"
                                  maxlength="10000" required></textarea>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400">Appuyez sur Entrée pour une nouvelle ligne</span>
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Envoyer
                        </button>
                    </div>
                </form>
            `;
            
            // Highlight selected conversation
            conversationItems.forEach(item => {
                item.classList.remove('bg-blue-50', 'border-l-4', 'border-l-blue-500');
                item.classList.add('bg-white');
            });
            this.classList.remove('bg-white');
            this.classList.add('bg-blue-50', 'border-l-4', 'border-l-blue-500');
        });
    });
    
    // Auto-select first conversation if available
    if (conversationItems.length > 0) {
        conversationItems[0].click();
    }
});
</script>
@endsection