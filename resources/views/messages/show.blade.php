@extends('layouts.app')

@section('title', 'Conversation - CarryWay')

@section('content')
<div class="min-h-full" x-data="{ showPackageForm: false, showTravelerForm: false }" x-init="console.log('Alpine initialized, showPackageForm:', showPackageForm)">
    <!-- Dashboard Header -->
    <div class="bg-white shadow-sm border-b border-gray-200 mb-6">
        <div class="px-6 py-4">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('messages.index') }}" class="text-blue-600 hover:text-blue-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-sm">
                        <span class="text-white font-semibold text-sm">{{ substr($otherUser->name, 0, 2) }}</span>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $otherUser->name }}</h1>
                        <p class="text-gray-600 text-sm lg:text-base">{{ $announcementType === 'travel' ? 'Voyage' : 'Livraison' }} • {{ $announcementType === 'travel' ? "{$announcement->departure_city} → {$announcement->arrival_city}" : "{$announcement->pickup_city} → {$announcement->delivery_city}" }}</p>
                    </div>
                </div>
                
                <!-- Action Buttons - Vertically centered -->
                @if($chat->userRequest)
                    <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-3">
                        @php
                            $isOwner = auth()->id() === $chat->userRequest->announcement_owner_id;
                            $isRequester = auth()->id() === $chat->userRequest->initiator_user_id;
                        @endphp
                        
                        @if($announcementType === 'travel' && $isOwner && $chat->userRequest->status === 'pending')
                            @if(!$chat->userRequest->package_details_completed)
                                <!-- Owner: Request detailed form -->
                                <form method="POST" action="{{ route('requests.request-package-form') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="request_id" value="{{ $chat->userRequest->id }}">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-orange-300 text-sm font-medium rounded-md text-orange-700 bg-white hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Demander formulaire détaillé
                                    </button>
                                </form>
                            @else
                                <!-- Owner: Accept/Reject after form completion -->
                                <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2">
                                    <form method="POST" action="{{ route('requests.accept') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="request_id" value="{{ $chat->userRequest->id }}">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-green-300 text-sm font-medium rounded-md text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Accepter
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('requests.reject') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="request_id" value="{{ $chat->userRequest->id }}">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                            Refuser
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endif

                        @if($announcementType === 'delivery' && $isOwner && $chat->userRequest->status === 'pending')
                            @if(!$chat->userRequest->package_details_completed)
                                <!-- Owner: Request detailed traveler form -->
                                <form method="POST" action="{{ route('requests.request-traveler-form') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="request_id" value="{{ $chat->userRequest->id }}">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-orange-300 text-sm font-medium rounded-md text-orange-700 bg-white hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Demander formulaire détaillé
                                    </button>
                                </form>
                            @else
                                <!-- Owner: Accept/Reject after form completion -->
                                <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2">
                                    <form method="POST" action="{{ route('requests.accept') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="request_id" value="{{ $chat->userRequest->id }}">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-green-300 text-sm font-medium rounded-md text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Accepter
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('requests.reject') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="request_id" value="{{ $chat->userRequest->id }}">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                            Refuser
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endif
                        
                        @if($announcementType === 'travel' && $isRequester && $chat->userRequest->status === 'pending' && $chat->userRequest->form_requested && !$chat->userRequest->package_details_completed)
                            <!-- Requester: Fill detailed form -->
                            <button @click="showPackageForm = true" class="inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Remplir le formulaire détaillé
                            </button>
                        @endif

                        @if($announcementType === 'delivery' && $isRequester && $chat->userRequest->status === 'pending' && $chat->userRequest->form_requested && !$chat->userRequest->package_details_completed)
                            <!-- Requester: Fill detailed traveler form -->
                            <button @click="showTravelerForm = true" class="inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Remplir le formulaire détaillé
                            </button>
                        @endif
                        
                        @if($chat->userRequest->status === 'accepted')
                            <!-- Progress button for both users and both announcement types -->
                            <a href="{{ route('requests.progress', $chat->userRequest->id) }}" class="inline-flex items-center px-4 py-2 border border-purple-300 text-sm font-medium rounded-md text-purple-700 bg-white hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Voir le progrès
                            </a>
                        @endif
                    </div>
                @endif
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

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <!-- Messages -->
            <div class="flex-1 overflow-y-auto p-6 space-y-4" style="max-height: 60vh;">
                @forelse($chat->messages as $message)
                    <div class="flex items-start space-x-3 {{ $message->sender_id === auth()->id() ? 'justify-end' : '' }}">
                        @if($message->sender_id !== auth()->id())
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-sm">
                                <span class="text-white text-xs font-semibold">{{ substr($message->sender->name, 0, 2) }}</span>
                            </div>
                        @endif
                        
                        <div class="flex-1 {{ $message->sender_id === auth()->id() ? 'text-right' : '' }} max-w-xs">
                            <div class="inline-block {{ $message->sender_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-900' }} rounded-lg px-4 py-2 max-w-xs">
                                <div class="text-sm" style="direction: ltr; text-align: left;">{!! nl2br(e($message->message)) !!}</div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 {{ $message->sender_id === auth()->id() ? 'text-right' : '' }}">
                                {{ $message->sent_at->format('H:i') }}
                                @if($message->read_at && $message->sender_id === auth()->id())
                                    • Lu
                                @endif
                            </p>
                        </div>
                        
                        @if($message->sender_id === auth()->id())
                            <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-sm">
                                <span class="text-white text-xs font-semibold">Moi</span>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-900 mb-1">Aucun message</p>
                        <p class="text-sm text-gray-500">Commencez la conversation en envoyant le premier message !</p>
                    </div>
                @endforelse
            </div>

            <!-- Message Input -->
            <div class="p-4 border-t border-gray-200 bg-white">
                @if($announcementType === 'travel' && $chat->userRequest && auth()->id() === $chat->userRequest->initiator_user_id && $chat->userRequest->status === 'pending' && $chat->userRequest->form_requested && !$chat->userRequest->package_details_completed)
                    <!-- Blocked: Form requested but not completed -->
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-orange-800">Formulaire détaillé requis</h3>
                                <p class="text-sm text-orange-700 mt-1">Vous devez d'abord remplir le formulaire détaillé du colis avant de pouvoir continuer la conversation.</p>
                            </div>
                            <button @click="showPackageForm = true" class="inline-flex items-center px-4 py-2 border border-orange-300 text-sm font-medium rounded-md text-orange-700 bg-white hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Remplir le formulaire
                            </button>
                        </div>
                    </div>
                @elseif($announcementType === 'delivery' && $chat->userRequest && auth()->id() === $chat->userRequest->initiator_user_id && $chat->userRequest->status === 'pending' && $chat->userRequest->form_requested && !$chat->userRequest->package_details_completed)
                    <!-- Blocked: Traveler form requested but not completed -->
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-orange-800">Formulaire détaillé requis</h3>
                                <p class="text-sm text-orange-700 mt-1">Vous devez d'abord remplir le formulaire détaillé de voyage avant de pouvoir continuer la conversation.</p>
                            </div>
                            <button @click="showTravelerForm = true" class="inline-flex items-center px-4 py-2 border border-orange-300 text-sm font-medium rounded-md text-orange-700 bg-white hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Remplir le formulaire
                            </button>
                        </div>
                    </div>
                @else
                    <!-- Normal message input -->
                    <form method="POST" action="{{ route('messages.store', $chat->id) }}" class="space-y-3">
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
                @endif
            </div>
        </div>
    </div>

    <!-- Package Form Modal - Now inside the main Alpine.js scope -->
    @if($announcementType === 'travel' && $chat->userRequest && auth()->id() === $chat->userRequest->initiator_user_id)
    <div x-show="showPackageForm" 
         x-transition.opacity 
         class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4" 
         @keydown.window.escape="showPackageForm = false"
         style="display: none;">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden" @click.away="showPackageForm = false">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">Formulaire détaillé du colis</h3>
                            <p class="text-blue-100 text-lg mt-1">Remplissez tous les détails pour votre demande</p>
                        </div>
                    </div>
                    <button class="p-3 text-white hover:bg-white hover:bg-opacity-10 rounded-full transition-all duration-200 hover:scale-110" @click="showPackageForm = false">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('requests.submit-package-form') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8 max-h-[calc(90vh-200px)] overflow-y-auto">
                @csrf
                <input type="hidden" name="request_id" value="{{ $chat->userRequest->id }}">
                
                <!-- Basic Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Informations de base</h4>
                        <p class="text-gray-600">Décrivez votre colis en détail</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="weight" class="block text-sm font-semibold text-gray-700">Poids exact (kg) *</label>
                            <input type="number" name="weight" id="weight" step="0.1" min="0.1" max="100" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="Poids en kg">
                        </div>

                        <div class="space-y-2">
                            <label for="item_type" class="block text-sm font-semibold text-gray-700">Type d'objet *</label>
                            <select name="item_type" id="item_type" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <option value="">Sélectionnez le type d'objet</option>
                                <optgroup label="Électronique">
                                    <option value="ordinateur_portable">Ordinateur portable</option>
                                    <option value="telephone">Téléphone mobile</option>
                                    <option value="tablette">Tablette</option>
                                    <option value="accessoires_electroniques">Accessoires électroniques</option>
                                </optgroup>
                                <optgroup label="Vêtements & Textiles">
                                    <option value="vetements">Vêtements</option>
                                    <option value="chaussures">Chaussures</option>
                                    <option value="sacs">Sacs et bagages</option>
                                    <option value="tissus">Tissus et matériaux</option>
                                </optgroup>
                                <optgroup label="Documents & Livres">
                                    <option value="documents">Documents importants</option>
                                    <option value="livres">Livres et magazines</option>
                                    <option value="papeterie">Papeterie</option>
                                </optgroup>
                                <optgroup label="Alimentation">
                                    <option value="aliments_secs">Aliments secs</option>
                                    <option value="epices">Épices et condiments</option>
                                    <option value="the_cafe">Thé et café</option>
                                </optgroup>
                                <optgroup label="Autres">
                                    <option value="bijoux">Bijoux et accessoires</option>
                                    <option value="cosmetiques">Cosmétiques</option>
                                    <option value="medicaments">Médicaments</option>
                                    <option value="autre">Autre (précisez dans la description)</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="dimensions" class="block text-sm font-semibold text-gray-700">Dimensions (optionnel)</label>
                            <input type="text" name="dimensions" id="dimensions"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="Ex: 30x20x10 cm">
                        </div>

                        <div class="space-y-2">
                            <label for="breakable" class="block text-sm font-semibold text-gray-700">Fragilité *</label>
                            <select name="breakable" id="breakable" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <option value="">Sélectionnez le niveau de fragilité</option>
                                <option value="non_fragile">Non fragile - Transport standard</option>
                                <option value="fragile_legere">Fragile léger - Manipulation délicate</option>
                                <option value="fragile_elevee">Très fragile - Manipulation très délicate</option>
                                <option value="extremement_fragile">Extrêmement fragile - Transport spécialisé</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Description détaillée</h4>
                        <p class="text-gray-600">Décrivez votre colis en détail pour une meilleure identification</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-semibold text-gray-700">Description détaillée du colis *</label>
                        <textarea name="description" id="description" rows="4" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                  placeholder="Décrivez votre colis en détail, ses caractéristiques, matériaux, marque, modèle, etc..."></textarea>
                    </div>
                </div>

                <!-- Photos Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Photos du colis</h4>
                        <p class="text-gray-600">Ajoutez 1 à 3 photos pour une meilleure identification</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-data="{ 
                            selectedFiles: [],
                            previews: [],
                            maxFiles: 3,
                            addFile(event) {
                                const files = Array.from(event.target.files);
                                if (this.selectedFiles.length + files.length > this.maxFiles) {
                                    alert(`Vous ne pouvez sélectionner que ${this.maxFiles} photos maximum.`);
                                    return;
                                }
                                
                                files.forEach(file => {
                                    if (file.type.startsWith('image/')) {
                                        this.selectedFiles.push(file);
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            this.previews.push(e.target.result);
                                        };
                                        reader.readAsDataURL(file);
                                    }
                                });
                                
                                // Update hidden input
                                const dt = new DataTransfer();
                                this.selectedFiles.forEach(file => dt.items.add(file));
                                document.getElementById('pictures').files = dt.files;
                            },
                            removeFile(index) {
                                this.selectedFiles.splice(index, 1);
                                this.previews.splice(index, 1);
                                
                                // Update hidden input
                                const dt = new DataTransfer();
                                this.selectedFiles.forEach(file => dt.items.add(file));
                                document.getElementById('pictures').files = dt.files;
                            }
                        }">
                            <!-- File Input -->
                            <div class="col-span-full">
                                <input type="file" name="pictures[]" id="pictures" multiple accept="image/*" 
                                       @change="addFile($event)"
                                       class="hidden">
                                <label for="pictures" class="cursor-pointer">
                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 hover:bg-blue-50 transition-all duration-200">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="text-lg font-medium text-gray-700">Cliquez pour ajouter des photos</p>
                                        <p class="text-sm text-gray-500 mt-1">Formats: JPEG, PNG, JPG, GIF • Max: 5MB par image</p>
                                        <p class="text-sm text-blue-600 mt-2">Maximum 3 photos</p>
                                    </div>
                                </label>
                            </div>

                            <!-- Preview Images -->
                            <template x-for="(preview, index) in previews" :key="index">
                                <div class="relative group">
                                    <img :src="preview" class="w-full h-32 object-cover rounded-xl shadow-md" alt="Preview">
                                    <button type="button" @click="removeFile(index)" 
                                            class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>

                            <!-- Empty Slots -->
                            <template x-for="i in (maxFiles - previews.length)" :key="`empty-${i}`">
                                <div class="w-full h-32 bg-gray-100 rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">Photo vide</span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-4 pt-6 border-t border-gray-200">
                    <button type="button" @click="showPackageForm = false" 
                            class="flex-1 px-6 py-4 border-2 border-gray-300 text-lg font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="flex-1 px-6 py-4 border-2 border-transparent text-lg font-semibold rounded-xl text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Soumettre le formulaire
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Traveler Announcement Form Modal -->
    @if($announcementType === 'delivery' && $chat->userRequest && auth()->id() === $chat->userRequest->initiator_user_id)
    <div x-show="showTravelerForm" 
         x-transition.opacity 
         class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4" 
         @keydown.window.escape="showTravelerForm = false"
         style="display: none;">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden" @click.away="showTravelerForm = false">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 via-green-700 to-green-800 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">Formulaire détaillé de voyage</h3>
                            <p class="text-green-100 text-lg mt-1">Remplissez tous les détails pour votre voyage</p>
                        </div>
                    </div>
                    <button class="p-3 text-white hover:bg-white hover:bg-opacity-10 rounded-full transition-all duration-200 hover:scale-110" @click="showTravelerForm = false">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('requests.submit-traveler-form') }}" method="POST" class="p-8 space-y-8 max-h-[calc(90vh-200px)] overflow-y-auto">
                @csrf
                <input type="hidden" name="request_id" value="{{ $chat->userRequest->id }}">
                
                <!-- Basic Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Informations de base</h4>
                        <p class="text-gray-600">Détails du trajet et de la livraison</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Departure City -->
                        <div x-data="cityPicker({ fieldId: 'departure_city', placeholder: 'Ville de départ', initial: '' })" class="relative">
                            <label for="departure_city" class="block text-sm font-semibold text-gray-700 mb-2">Ville de départ *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <input x-model="query" @focus="open = true" @input="onInput()" @keydown.arrow-down.prevent="move(1)" @keydown.arrow-up.prevent="move(-1)" @keydown.enter.prevent="selectActive()" @click.away="open = false"
                                       type="text" name="departure_city" id="departure_city" :placeholder="placeholder"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" autocomplete="off" required>
                            </div>
                            <ul x-show="open && filtered.length" class="absolute z-50 mt-1 w-full max-h-60 overflow-auto bg-white border border-gray-200 rounded-lg shadow-lg">
                                <template x-for="(city, idx) in filtered" :key="city">
                                    <li @mouseenter="active = idx" @click="onItemClick(city)" @mousedown.prevent
                                        :class="idx === active ? 'bg-green-50 text-green-700 border-l-4 border-l-green-500' : 'hover:bg-gray-50'"
                                        class="px-4 py-3 cursor-pointer text-sm border-l-4 border-l-transparent transition-colors duration-150">
                                        <span x-text="city" class="font-medium"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>

                        <!-- Arrival City -->
                        <div x-data="cityPicker({ fieldId: 'arrival_city', placeholder: 'Ville d\'arrivée', initial: '' })" class="relative">
                            <label for="arrival_city" class="block text-sm font-semibold text-gray-700 mb-2">Ville d'arrivée *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </div>
                                <input x-model="query" @focus="open = true" @input="onInput()" @keydown.arrow-down.prevent="move(1)" @keydown.arrow-up.prevent="move(-1)" @keydown.enter.prevent="selectActive()" @click.away="open = false"
                                       type="text" name="arrival_city" id="arrival_city" :placeholder="placeholder"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" autocomplete="off" required>
                            </div>
                            <ul x-show="open && filtered.length" class="absolute z-50 mt-1 w-full max-h-60 overflow-auto bg-white border border-gray-200 rounded-lg shadow-lg">
                                <template x-for="(city, idx) in filtered" :key="city">
                                    <li @mouseenter="active = idx" @click="onItemClick(city)" @mousedown.prevent
                                        :class="idx === active ? 'bg-green-50 text-green-700 border-l-4 border-l-green-500' : 'hover:bg-gray-50'"
                                        class="px-4 py-3 cursor-pointer text-sm border-l-4 border-l-transparent transition-colors duration-150">
                                        <span x-text="city" class="font-medium"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Departure Date -->
                        <div>
                            <label for="departure_date" class="block text-sm font-semibold text-gray-700 mb-2">Date de départ *</label>
                            <input type="date" name="departure_date" id="departure_date" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                        </div>

                        <!-- Departure Time -->
                        <div>
                            <label for="departure_time" class="block text-sm font-semibold text-gray-700 mb-2">Heure de départ *</label>
                            <input type="time" name="departure_time" id="departure_time" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Estimated Arrival Date -->
                        <div>
                            <label for="estimated_arrival_date" class="block text-sm font-semibold text-gray-700 mb-2">Date d'arrivée estimée *</label>
                            <input type="date" name="estimated_arrival_date" id="estimated_arrival_date" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                        </div>

                        <!-- Estimated Arrival Time -->
                        <div>
                            <label for="estimated_arrival_time" class="block text-sm font-semibold text-gray-700 mb-2">Heure d'arrivée estimée *</label>
                            <input type="time" name="estimated_arrival_time" id="estimated_arrival_time" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                        </div>
                    </div>
                </div>

                <!-- Transport Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Transport</h4>
                        <p class="text-gray-600">Détails sur le moyen de transport</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="transport_type" class="block text-sm font-semibold text-gray-700">Type de transport *</label>
                            <select name="transport_type" id="transport_type" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                                <option value="">Sélectionnez le type de transport</option>
                                <option value="voiture">Voiture</option>
                                <option value="bus">Bus</option>
                                <option value="train">Train</option>
                                <option value="avion">Avion</option>
                                <option value="moto">Moto</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="price_per_kg" class="block text-sm font-semibold text-gray-700">Prix par kg (DH) *</label>
                            <input type="number" name="price_per_kg" id="price_per_kg" step="0.01" min="0" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                   placeholder="Prix en DH">
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Description</h4>
                        <p class="text-gray-600">Décrivez votre voyage en détail</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-semibold text-gray-700">Description détaillée *</label>
                        <textarea name="description" id="description" rows="4" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 resize-none"
                                  placeholder="Décrivez votre voyage, conditions, etc..."></textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-4 pt-6 border-t border-gray-200">
                    <button type="button" @click="showTravelerForm = false" 
                            class="flex-1 px-6 py-4 border-2 border-gray-300 text-lg font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="flex-1 px-6 py-4 border-2 border-transparent text-lg font-semibold rounded-xl text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Soumettre le formulaire
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
// City Picker functionality for the traveler form modal
document.addEventListener('alpine:init', () => {
    window.cityPicker = ({ fieldId, placeholder = '', initial = '' }) => ({
        placeholder, query: initial || '', open: false, active: 0,
        all: [
            // Major Cities
            'Casablanca','Rabat','Marrakech','Fès','Tanger','Agadir','Meknès','Oujda','Kénitra','Tétouan','Taza','Nador','Laayoune','Dakhla','El Jadida','Khouribga','Béni Mellal','Safi','Settat','Salé','Témara','Mohammedia','Ouarzazate','Errachidia','Al Hoceima','Taroudant','Guelmim','Larache','Essaouira','Ksar El Kebir','Berkane','Taourirt','Tiflet','Khemisset','Ouezzane','Sidi Kacem',
            // Northern Cities & Towns
            'Chefchaouen','Fnideq','Mdiq','Martil','Asilah','Ouazzane','Sidi Slimane','Skhirate','Harhoura','Ain Atiq','Ain Diab','Ain Sebaa','Ain Chock','Ain Harrouda','Bouskoura','Deroua','Ezzhiliga','Had Soualem','Lahraouyine','Nouaceur','Oulad Azzouz','Oulad Haddou','Oulad Mbarek','Oulad Said','Oulad Sidi Ben Daoud','Oulad Sidi Moussa','Oulad Sidi Yahya','Oulad Ziane','Sidi Moussa Ben Ali','Sidi Rahal Chatai','Tit Mellil','Ziaida',
            // Central Cities & Towns
            'Sefrou','Boulemane','Ifrane','El Hajeb','Azrou','Midelt','Khenifra','Fquih Ben Salah','Youssoufia','Benslimane','Berrechid','Rommani',
            // Southern Cities & Towns
            'Tan-Tan','Sidi Ifni','Tiznit','Inezgane','Ait Melloul','Dcheira El Jihadia','Bensergao','Biougra','Aourir','Taghazout','Tamraght','Imi Ouaddar','Tifnit','Mirleft','Sidi Moussa Lhamri','El Ouatia','Tata','Foum Zguid','Akka','Tafraout',
            // Eastern Cities & Towns
            'Driouch','Midar','Bni Ansar','Selouane','Zaio',
            // Additional Small Towns
            'Sidi Bennour','El Borouj','Bouznika','Sidi Rahal','Tahla','Guercif','Azilal','Demnate','Skoura','Tinghir','Erfoud','Rissani','Merzouga','Boujdour','Smara','Es-Semara','Tarfaya'
        ],
        filtered: [],
        init(){ this.filter(); },
        normalize(v){ return (v||'').toString().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,''); },
        filter(){ const q=this.normalize(this.query); this.filtered=!q?this.all:this.all.filter(c=>this.normalize(c).includes(q)).slice(0,50); this.active=0; },
        move(d){ if(!this.filtered.length) return; this.active=(this.active+d+this.filtered.length)%this.filtered.length; },
        choose(val){ this.query=val; this.open=false; const el=document.getElementById(fieldId); if(el) el.value=val; },
        onItemClick(val){ this.choose(val); },
        selectActive(){ if(this.filtered[this.active]) this.choose(this.filtered[this.active]); },
        onInput(){ this.filter(); }
    });
});

// Package form image preview functionality
document.addEventListener('DOMContentLoaded', function() {
    const imageInputs = document.querySelectorAll('input[type="file"]');
    const imagePreviews = document.querySelectorAll('.image-preview');
    const removeButtons = document.querySelectorAll('.remove-image');

    // Handle image previews
    imageInputs.forEach((input, index) => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (imagePreviews[index]) {
                        imagePreviews[index].src = e.target.result;
                        imagePreviews[index].classList.remove('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Handle remove image buttons
    removeButtons.forEach((button, index) => {
        button.addEventListener('click', function() {
            if (imageInputs[index]) {
                imageInputs[index].value = '';
            }
            if (imagePreviews[index]) {
                imagePreviews[index].classList.add('hidden');
            }
        });
    });
});
</script>
@endpush
@endsection
