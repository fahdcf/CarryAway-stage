@extends('layouts.app')

@section('title', 'Mes demandes - CarryWay')

@section('content')
<div class="min-h-full bg-gray-50">
    <!-- Dashboard Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-8">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
                <div>
                        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">Mes demandes</h1>
                        <p class="text-gray-600 text-base lg:text-lg mt-2">Gérez vos demandes de transport et de livraison</p>
                </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Success/Error Messages -->
    @if(session('success'))
            <div class="mb-8 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
            {{ session('success') }}
                </div>
        </div>
    @endif

    @if(session('error'))
            <div class="mb-8 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
            {{ session('error') }}
                </div>
        </div>
    @endif

        <!-- Tabs -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-8" aria-label="Tabs">
                    <a href="{{ route('requests.index', ['tab' => 'sent']) }}" 
                       class="py-6 px-1 border-b-2 font-semibold text-base {{ $activeTab === 'sent' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} transition-colors duration-200">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <span>Demandes envoyées</span>
                            <span class="bg-gray-100 text-gray-900 py-1 px-3 rounded-full text-sm font-medium">{{ $sentRequests->count() }}</span>
                        </div>
                    </a>
                    <a href="{{ route('requests.index', ['tab' => 'received']) }}" 
                       class="py-6 px-1 border-b-2 font-semibold text-base {{ $activeTab === 'received' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} transition-colors duration-200">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <span>Demandes reçues</span>
                            <span class="bg-gray-100 text-gray-900 py-1 px-3 rounded-full text-sm font-medium">{{ $receivedRequests->count() }}</span>
                        </div>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-8">
            <div class="px-8 py-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">Rechercher et filtrer</h3>
            </div>
            <div class="p-8">
                <form method="GET" action="{{ route('requests.index', ['tab' => $activeTab]) }}" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Search Input -->
                    <div>
                            <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">Rechercher</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-colors duration-200"
                               placeholder="Ville, description...">
                            </div>
                    </div>

                    <!-- Type Filter -->
                    <div>
                            <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">Type de demande</label>
                            <select name="type" id="type" class="block w-full px-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-colors duration-200">
                            <option value="">Tous les types</option>
                            <option value="travel" {{ request('type') == 'travel' ? 'selected' : '' }}>Demande de voyage</option>
                            <option value="delivery" {{ request('type') == 'delivery' ? 'selected' : '' }}>Demande de livraison</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Statut</label>
                            <select name="status" id="status" class="block w-full px-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-colors duration-200">
                            <option value="">Tous les statuts</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Acceptée</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Refusée</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminée</option>
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div>
                            <label for="sort" class="block text-sm font-semibold text-gray-700 mb-2">Trier par</label>
                            <select name="sort" id="sort" class="block w-full px-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-colors duration-200">
                            <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Plus récentes</option>
                            <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Plus anciennes</option>
                            <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Date de départ</option>
                        </select>
                    </div>
                </div>

                    <div class="flex justify-between items-center pt-4">
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-semibold rounded-xl text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Rechercher
                    </button>
                    
                    @if(request('search') || request('type') || request('status') || request('sort'))
                            <a href="{{ route('requests.index', ['tab' => $activeTab]) }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">
                            Effacer les filtres
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Results Summary -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
                <h2 class="text-2xl font-bold text-gray-900">
                    @if($activeTab === 'sent')
                        Demandes envoyées ({{ $requests->count() }} demandes)
                    @else
                        Demandes reçues ({{ $requests->count() }} demandes)
                    @endif
            </h2>
                <div class="flex items-center space-x-6 text-sm text-gray-600">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
                        <span>{{ $requests->where('type', 'travel')->count() }} voyages</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-600 rounded-full"></div>
                        <span>{{ $requests->where('type', 'delivery')->count() }} livraisons</span>
                    </div>
            </div>
        </div>
    </div>

    <!-- Requests List -->
        <div class="space-y-6" x-data="{ showDeleteModal: false, deleteRequestId: null, showMeetingVerificationModal: false, showDeliveryVerificationModal: false, currentRequestId: null, showCodesModal: false }">
            @forelse($requests as $request)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300">
                    <!-- Card Header -->
                    <div class="px-8 py-6 border-b border-gray-100 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <!-- Type Badge -->
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold {{ $request->type === 'travel' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    @if($request->type === 'travel')
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                Voyage
                                    @else
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        Livraison
                                    @endif
                            </span>
                                
                                <!-- Status Badge -->
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold 
                                    {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $request->status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $request->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $request->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    @switch($request->status)
                                        @case('pending')
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                En attente
                                            @break
                                        @case('accepted')
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Acceptée
                                            @break
                                        @case('rejected')
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Refusée
                                            @break
                                        @case('completed')
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Terminée
                                            @break
                                        @default
                                            {{ ucfirst($request->status) }}
                                    @endswitch
                            </span>
                            </div>
                            
                            <!-- Date -->
                            <div class="text-sm text-gray-600">
                                <span class="font-semibold">{{ $request->date }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-8">
                        <!-- Title and Route -->
                        <div class="mb-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $request->title }}</h3>
                            <div class="flex items-center text-gray-700">
                                <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="font-semibold text-lg">{{ $request->departure_city }}</span>
                                <svg class="w-6 h-6 mx-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                                <span class="font-semibold text-lg">{{ $request->arrival_city }}</span>
                            </div>
                        </div>

                        <!-- Key Information Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                                        </svg>
                                    </div>
                            <div>
                                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Poids</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $request->weight }} kg</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                            </div>
                            <div>
                                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Prix</p>
                                        <p class="text-lg font-bold text-gray-900">{{ number_format($request->price, 2) }} DH</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                            </div>
                            <div>
                                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Créée le</p>
                                        <p class="text-lg font-bold text-gray-900">
                                            @if(isset($request->created_at))
                                                {{ $request->created_at->format('d/m/Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        @if($request->description && strlen($request->description) > 0)
                            <div class="mb-6">
                                <p class="text-gray-700 text-base leading-relaxed">{{ $request->description }}</p>
                            </div>
                        @endif

                        <!-- User Info -->
                        <div class="flex items-center justify-between py-4 border-t border-gray-200">
                            <div class="flex items-center space-x-3 text-sm text-gray-600">
                                <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                @if($activeTab === 'sent')
                                    <span>Annonce de <span class="font-semibold text-gray-900">{{ $request->announcement_user }}</span></span>
                                @else
                                    <span>Demandé par <span class="font-semibold text-gray-900">{{ $request->requester_user }}</span></span>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-4 pt-6">
                            @if($activeTab === 'sent')
                                <!-- Actions for sent requests -->
                                @if($request->status === 'pending')
                                    <a href="{{ route('messages.create-chat', $request->id) }}" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all duration-200 transform hover:scale-105">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        Contacter
                                    </a>
                                    <button type="button" @click="showDeleteModal = true; deleteRequestId = '{{ $request->id }}'"
                                            class="inline-flex items-center px-6 py-3 border border-red-300 text-red-700 text-sm font-semibold rounded-xl hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 transform hover:scale-105">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Annuler
                                    </button>
                                @elseif($request->status === 'accepted')
                                    <!-- View Codes Button -->
                                    <button type="button" @click="showCodesModal = true; currentRequestId = '{{ $request->id }}'"
                                            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Voir les codes
                                    </button>

                                    @if($request->canBeCompleted())
                                        <button type="button" @click="markAsCompleted('{{ $request->id }}')"
                                                class="inline-flex items-center px-6 py-3 bg-purple-600 text-white text-sm font-semibold rounded-xl hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 transform hover:scale-105">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Marquer comme terminé
                                        </button>
                                    @endif

                                    <a href="{{ route('requests.progress', $request->id) }}" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white text-sm font-semibold rounded-xl hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 transform hover:scale-105">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                        Voir le progrès
                                    </a>
                                    <a href="{{ route('messages.create-chat', $request->id) }}" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all duration-200 transform hover:scale-105">
                                        Contacter
                                    </a>
                                @elseif($request->status === 'completed')
                                    <button class="inline-flex items-center px-6 py-3 bg-gray-600 text-white text-sm font-semibold rounded-xl hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 transform hover:scale-105">
                                        Voir les détails
                                    </button>
                                    <button class="inline-flex items-center px-6 py-3 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105">
                                        Noter
                                    </button>
                                @else
                                    <button class="inline-flex items-center px-6 py-3 bg-gray-600 text-white text-sm font-semibold rounded-xl hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 transform hover:scale-105">
                                        Voir les détails
                                    </button>
                                @endif
                            @else
                                <!-- Actions for received requests -->
                                @if($request->status === 'pending')
                                    @if($request->traveler_announcement_id && !$request->package_details_completed)
                                        <!-- For traveler announcements: Request detailed form first -->
                                        <form method="POST" action="{{ route('requests.request-package-form') }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="request_id" value="{{ $request->id }}">
                                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-orange-600 text-white text-sm font-semibold rounded-xl hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200 transform hover:scale-105">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Demander formulaire détaillé
                                            </button>
                                        </form>
                                        <a href="{{ route('messages.create-chat', $request->id) }}" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all duration-200 transform hover:scale-105">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            </svg>
                                            Contacter
                                        </a>
                                    @else
                                        <!-- For sender announcements or completed traveler requests: Accept/Reject -->
                                        <form method="POST" action="{{ route('requests.accept') }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="request_id" value="{{ $request->id }}">
                                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Accepter
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('requests.reject') }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="request_id" value="{{ $request->id }}">
                                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 transform hover:scale-105">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Refuser
                                            </button>
                                        </form>
                                    @endif
                                @elseif($request->status === 'accepted')
                                    <a href="{{ route('requests.progress', $request->id) }}" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white text-sm font-semibold rounded-xl hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 transform hover:scale-105">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                        Voir le progrès
                                    </a>
                                    
                                    <!-- View Codes Button for Received Requests -->
                                    <button type="button" @click="showCodesModal = true; currentRequestId = '{{ $request->id }}'"
                                            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Voir les codes
                                    </button>
                                    
                                    <a href="{{ route('messages.create-chat', $request->id) }}" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all duration-200 transform hover:scale-105">
                                        Contacter
                                    </a>
                                @else
                                    <button class="inline-flex items-center px-6 py-3 bg-gray-600 text-white text-sm font-semibold rounded-xl hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 transform hover:scale-105">
                                        Voir les détails
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="mx-auto h-12 w-12 text-gray-400">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                        @if($activeTab === 'sent')
                            Aucune demande envoyée
                        @else
                            Aucune demande reçue
                        @endif
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if($activeTab === 'sent')
                            Vous n'avez pas encore créé de demandes de transport.
                        @else
                            Vous n'avez pas encore reçu de demandes de transport.
                        @endif
                    </p>
                </div>
            @endforelse

            <!-- Delete Confirmation Modal -->
            <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4" @keydown.window.escape="showDeleteModal = false">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden" @click.away="showDeleteModal = false">
                    <div class="p-6">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Confirmer la suppression</h3>
                                <p class="text-sm text-gray-600">Cette action ne peut pas être annulée.</p>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-6">
                            Êtes-vous sûr de vouloir supprimer cette demande ? Toutes les informations associées seront définitivement supprimées.
                        </p>
                        <div class="flex space-x-3">
                            <button type="button" @click="showDeleteModal = false"
                                    class="flex-1 px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Annuler
                        </button>
                            <form method="POST" action="{{ route('requests.delete', '') }}" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="request_id" :value="deleteRequestId">
                                <button type="submit"
                                        class="w-full px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Supprimer
                                </button>
                            </form>
                    </div>
                </div>
            </div>
        </div>

            <!-- Meeting Verification Modal -->
            <div x-show="showMeetingVerificationModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4" @keydown.window.escape="showMeetingVerificationModal = false">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden" @click.away="showMeetingVerificationModal = false">
            <div class="p-6">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Vérifier le code de rencontre</h3>
                                <p class="text-sm text-gray-600">Entrez le code affiché lors de la rencontre</p>
                            </div>
                        </div>
                        <div class="mb-6">
                            <label for="meeting_code" class="block text-sm font-semibold text-gray-700 mb-2">Code de rencontre</label>
                            <input type="text" id="meeting_code" maxlength="8" 
                                   class="w-full px-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono text-lg text-center tracking-widest"
                                   placeholder="XXXX-XXXX">
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" @click="showMeetingVerificationModal = false"
                                    class="flex-1 px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Annuler
                            </button>
                            <button type="button" @click="verifyMeetingCode()"
                                    class="flex-1 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Vérifier
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Verification Modal -->
            <div x-show="showDeliveryVerificationModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4" @keydown.window.escape="showDeliveryVerificationModal = false">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden" @click.away="showDeliveryVerificationModal = false">
                    <div class="p-6">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Vérifier le code de livraison</h3>
                                <p class="text-sm text-gray-600">Entrez le code affiché après la livraison</p>
                            </div>
                        </div>
                        <div class="mb-6">
                            <label for="delivery_code" class="block text-sm font-semibold text-gray-700 mb-2">Code de livraison</label>
                            <input type="text" id="delivery_code" maxlength="8" 
                                   class="w-full px-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 font-mono text-lg text-center tracking-widest"
                                   placeholder="XXXX-XXXX">
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" @click="showDeliveryVerificationModal = false"
                                    class="flex-1 px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Annuler
                        </button>
                            <button type="button" @click="verifyDeliveryCode()"
                                    class="flex-1 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Vérifier
                        </button>
                    </div>
                </div>
            </div>
        </div>

            <!-- Codes Modal -->
            <div x-show="showCodesModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4" @keydown.window.escape="showCodesModal = false">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden" @click.away="showCodesModal = false">
            <div class="p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Codes de vérification</h3>
                                <p class="text-sm text-gray-600">Détails de la demande et codes de sécurité</p>
                            </div>
                        </div>
                        
                        <!-- Request Details -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Informations de la demande</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium">Type</p>
                                        <p class="text-sm font-semibold text-gray-900" id="modal-request-type">-</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium">Prix proposé</p>
                                        <p class="text-sm font-semibold text-gray-900" id="modal-request-price">-</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                            </div>
                            <div>
                                        <p class="text-xs text-gray-500 font-medium">Créée le</p>
                                        <p class="text-sm font-semibold text-gray-900" id="modal-request-date">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Verification Codes -->
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-900 mb-4">Codes de vérification</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <div class="w-8 h-8 bg-blue-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h5 class="text-sm font-semibold text-blue-900">Code de rencontre</h5>
                                            <p class="text-xs text-blue-600">À montrer lors de la rencontre</p>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-blue-900 font-mono tracking-widest" id="modal-meeting-code">-</p>
                                        <span class="inline-flex items-center px-2 py-1 mt-2 text-xs font-medium bg-gray-100 text-gray-800 rounded-full" id="modal-meeting-status">
                                            En attente
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="bg-green-50 rounded-xl p-4 border border-green-200" id="modal-delivery-code-container" style="display: none;">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <div class="w-8 h-8 bg-green-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h5 class="text-sm font-semibold text-green-900">Code de livraison</h5>
                                            <p class="text-xs text-green-600">À montrer après la livraison</p>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-green-900 font-mono tracking-widest" id="modal-delivery-code">-</p>
                                        <span class="inline-flex items-center px-2 py-1 mt-2 text-xs font-medium bg-gray-100 text-gray-800 rounded-full" id="modal-delivery-status">
                                            En attente
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="mb-6 p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                            <h4 class="text-sm font-semibold text-yellow-900 mb-2">Instructions d'utilisation</h4>
                            <ul class="text-sm text-yellow-800 space-y-1">
                                <li>• <strong>Code de rencontre :</strong> Montrez ce code à l'autre utilisateur lors de votre rencontre</li>
                                <li id="modal-delivery-instruction" style="display: none;">• <strong>Code de livraison :</strong> Montrez ce code après avoir effectué la livraison avec succès</li>
                                <li>• Ces codes sont générés automatiquement et sont uniques à votre demande</li>
                            </ul>
                    </div>

                        <div class="flex justify-end">
                            <button type="button" @click="showCodesModal = false"
                                    class="px-6 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors duration-200">
                                Fermer
                        </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to populate the codes modal with request data
        function populateCodesModal(requestId) {
            // Fetch request data from the verification codes endpoint
            fetch(`/verification/codes/${requestId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const request = data.request;
                        
                        // Populate request details
                        document.getElementById('modal-request-type').textContent = request.sender_announcement_id ? 'Livraison' : 'Voyage';
                        document.getElementById('modal-request-price').textContent = `${parseFloat(request.proposed_price || 0).toFixed(2)} DH`;
                        document.getElementById('modal-request-date').textContent = new Date(request.created_at).toLocaleDateString('fr-FR');
                        
                        // Populate meeting code
                        document.getElementById('modal-meeting-code').textContent = request.meeting_code || 'N/A';
                        
                        // Update meeting code status
                        const meetingStatus = document.getElementById('modal-meeting-status');
                        if (request.meeting_verified_at) {
                            meetingStatus.textContent = 'Vérifié';
                            meetingStatus.className = 'inline-flex items-center px-2 py-1 mt-2 text-xs font-medium bg-green-100 text-green-800 rounded-full';
                        } else {
                            meetingStatus.textContent = 'En attente';
                            meetingStatus.className = 'inline-flex items-center px-2 py-1 mt-2 text-xs font-medium bg-gray-100 text-gray-800 rounded-full';
                        }
                        
                        // Handle delivery code (only for sender announcements)
                        const deliveryContainer = document.getElementById('modal-delivery-code-container');
                        const deliveryInstruction = document.getElementById('modal-delivery-instruction');
                        
                        if (request.delivery_code) {
                            deliveryContainer.style.display = 'block';
                            deliveryInstruction.style.display = 'block';
                            
                            document.getElementById('modal-delivery-code').textContent = request.delivery_code;
                            
                            // Update delivery code status
                            const deliveryStatus = document.getElementById('modal-delivery-status');
                            if (request.delivery_verified_at) {
                                deliveryStatus.textContent = 'Vérifié';
                                deliveryStatus.className = 'inline-flex items-center px-2 py-1 mt-2 text-xs font-medium bg-green-100 text-green-800 rounded-full';
                            } else {
                                deliveryStatus.textContent = 'En attente';
                                deliveryStatus.className = 'inline-flex items-center px-2 py-1 mt-2 text-xs font-medium bg-gray-100 text-gray-800 rounded-full';
                            }
                        } else {
                            deliveryContainer.style.display = 'none';
                            deliveryInstruction.style.display = 'none';
                        }
                    } else {
                        console.error('Failed to load request data:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error loading request data:', error);
                });
        }

        // Watch for modal opening and populate data
        document.addEventListener('DOMContentLoaded', function() {
            // Watch for Alpine.js showCodesModal changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'x-show') {
                        const modal = mutation.target;
                        if (modal.getAttribute('x-show') === 'true') {
                            // Modal is now visible, populate it
                            const requestId = document.querySelector('[x-data]').__x.$data.currentRequestId;
                            if (requestId) {
                                populateCodesModal(requestId);
                            }
                        }
                    }
                });
            });

            // Observe the codes modal
            const codesModal = document.querySelector('[x-show="showCodesModal"]');
            if (codesModal) {
                observer.observe(codesModal, { attributes: true });
            }
        });

        function verifyMeetingCode() {
            const code = document.getElementById('meeting_code').value.trim();
            const requestId = document.querySelector('[x-data]').__x.$data.currentRequestId;
            
            if (!code) {
                alert('Veuillez entrer le code de rencontre');
                return;
            }

            fetch('/verification/meeting-code', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    request_id: requestId,
                    meeting_code: code
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue lors de la vérification');
            });
        }

        function verifyDeliveryCode() {
            const code = document.getElementById('delivery_code').value.trim();
            const requestId = document.querySelector('[x-data]').__x.$data.currentRequestId;
            
            if (!code) {
                alert('Veuillez entrer le code de livraison');
                return;
            }

            fetch('/verification/delivery-code', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    request_id: requestId,
                    delivery_code: code
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue lors de la vérification');
            });
        }

        function markAsCompleted(requestId) {
            if (!confirm('Êtes-vous sûr de vouloir marquer cette demande comme terminée ?')) {
                return;
            }

            fetch('/verification/mark-completed', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    request_id: requestId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue');
            });
        }
    </script>
@endsection
