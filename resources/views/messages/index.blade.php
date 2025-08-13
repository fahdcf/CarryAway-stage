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
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                    <button class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nouveau message
                    </button>
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
        <div class="flex h-96">
            <!-- Conversations List -->
            <div class="w-1/3 border-r border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Conversations</h3>
                </div>
                <div class="overflow-y-auto h-full">
                    <!-- Sample conversations -->
                    <div class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-medium">JD</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Jean Dupont</p>
                                <p class="text-sm text-gray-500 truncate">Bonjour, je suis intéressé par votre annonce...</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Nouveau
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-medium">ML</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Marie Laurent</p>
                                <p class="text-sm text-gray-500 truncate">Parfait, on se retrouve demain à 14h</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="text-xs text-gray-400">14:30</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-medium">PM</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Pierre Martin</p>
                                <p class="text-sm text-gray-500 truncate">Merci pour le transport, tout s'est bien passé</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="text-xs text-gray-400">Hier</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message Content -->
            <div class="flex-1 flex flex-col">
                <!-- Message Header -->
                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-medium">JD</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Jean Dupont</h3>
                                <p class="text-sm text-gray-500">En ligne</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="p-2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <div class="flex-1 overflow-y-auto p-4 space-y-4">
                    <!-- Received message -->
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-xs font-medium">JD</span>
                        </div>
                        <div class="flex-1">
                            <div class="bg-gray-100 rounded-lg px-4 py-2 max-w-xs">
                                <p class="text-sm text-gray-900">Bonjour, je suis intéressé par votre annonce de voyage Paris-Lyon. Est-ce que vous avez encore de la place disponible ?</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">14:25</p>
                        </div>
                    </div>

                    <!-- Sent message -->
                    <div class="flex items-start space-x-3 justify-end">
                        <div class="flex-1">
                            <div class="bg-blue-600 rounded-lg px-4 py-2 max-w-xs ml-auto">
                                <p class="text-sm text-white">Bonjour Jean ! Oui, j'ai encore 5kg disponibles. Quand partez-vous ?</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 text-right">14:27</p>
                        </div>
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-xs font-medium">Moi</span>
                        </div>
                    </div>

                    <!-- Received message -->
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-xs font-medium">JD</span>
                        </div>
                        <div class="flex-1">
                            <div class="bg-gray-100 rounded-lg px-4 py-2 max-w-xs">
                                <p class="text-sm text-gray-900">Parfait ! Je pars vendredi matin. Combien coûte le transport ?</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">14:30</p>
                        </div>
                    </div>
                </div>

                <!-- Message Input -->
                <div class="p-4 border-t border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="flex-1">
                            <input type="text" placeholder="Tapez votre message..." 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
