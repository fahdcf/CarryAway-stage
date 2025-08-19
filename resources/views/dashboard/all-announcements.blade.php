@extends('layouts.app')

@section('title', 'Toutes les annonces - CarryWay')

@section('content')
<div class="min-h-full">
    <!-- Dashboard Header -->
    <div class="bg-white shadow-sm border-b border-gray-200 mb-6">
        <div class="px-6 py-4">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Toutes les annonces</h1>
                    <p class="text-gray-600 text-sm lg:text-base">Découvrez les opportunités de voyage et de livraison</p>
                </div>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('dashboard.create-traveler-announcement') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="hidden sm:inline">Annonce de voyage</span>
                        <span class="sm:hidden">Voyage</span>
                    </a>
                    <a href="{{ route('dashboard.create-sender-announcement') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="hidden sm:inline">Annonce de livraison</span>
                        <span class="sm:hidden">Livraison</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Search and Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-6" x-data="{ showAdvancedFilters: false }">
        <div class="px-8 py-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900">Recherche avancée</h3>
        </div>
        <div class="p-8">
            <form method="GET" action="{{ route('dashboard.all-announcements') }}" class="space-y-6">
                <!-- Basic Search -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">Recherche générale</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-colors duration-200"
                               placeholder="Ville, description, utilisateur...">
                        </div>
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">Type d'annonce</label>
                        <select name="type" id="type" class="block w-full px-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-colors duration-200">
                            <option value="">Tous les types</option>
                            <option value="traveler" {{ request('type') == 'traveler' ? 'selected' : '' }}>Voyage</option>
                            <option value="sender" {{ request('type') == 'sender' ? 'selected' : '' }}>Livraison</option>
                        </select>
                    </div>

                    <div>
                        <label for="sort" class="block text-sm font-semibold text-gray-700 mb-2">Trier par</label>
                        <select name="sort" id="sort" class="block w-full px-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-colors duration-200">
                            <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Plus récentes</option>
                            <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Plus anciennes</option>
                            <option value="departure_date_asc" {{ request('sort') == 'departure_date_asc' ? 'selected' : '' }}>Date de départ</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                        </select>
                    </div>

                    <div>
                        <label for="limit" class="block text-sm font-semibold text-gray-700 mb-2">Résultats par page</label>
                        <select name="limit" id="limit" class="block w-full px-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-colors duration-200">
                            <option value="12" {{ request('limit') == '12' ? 'selected' : '' }}>12</option>
                            <option value="24" {{ request('limit') == '24' ? 'selected' : '' }}>24</option>
                            <option value="48" {{ request('limit') == '48' ? 'selected' : '' }}>48</option>
                        </select>
                    </div>
                </div>

                <!-- Advanced Filters Toggle -->
                <div class="border-t border-gray-200 pt-6">
                    <button type="button" @click="showAdvancedFilters = !showAdvancedFilters" 
                            class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 transition-colors duration-200">
                        <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-90': showAdvancedFilters }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span class="text-md font-semibold">Filtres avancés</span>
                    </button>
                </div>

                <!-- Advanced Filters -->
                <div x-show="showAdvancedFilters" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Date Range -->
                        <div>
                            <label for="date_from" class="block text-sm font-semibold text-gray-700 mb-2">Date de départ (dès)</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                                   class="block w-full px-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-colors duration-200">
                        </div>

                        <div>
                            <label for="date_to" class="block text-sm font-semibold text-gray-700 mb-2">Date de départ (jusqu'au)</label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                                   class="block w-full px-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-colors duration-200">
                        </div>

                        <div>
                            <label for="weight_min" class="block text-sm font-semibold text-gray-700 mb-2">Poids minimum (kg)</label>
                            <input type="number" name="weight_min" id="weight_min" value="{{ request('weight_min') }}" min="0" step="0.1"
                                   class="block w-full px-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-colors duration-200">
                        </div>

                        <div>
                            <label for="weight_max" class="block text-sm font-semibold text-gray-700 mb-2">Poids maximum (kg)</label>
                            <input type="number" name="weight_max" id="weight_max" value="{{ request('weight_max') }}" min="0" step="0.1"
                                   class="block w-full px-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-colors duration-200">
                        </div>

                        <div>
                            <label for="price_min" class="block text-sm font-semibold text-gray-700 mb-2">Prix minimum (€/kg)</label>
                            <input type="number" name="price_min" id="price_min" value="{{ request('price_min') }}" min="0" step="0.01"
                                   class="block w-full px-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-colors duration-200">
                        </div>

                        <div>
                            <label for="price_max" class="block text-sm font-semibold text-gray-700 mb-2">Prix maximum (€/kg)</label>
                            <input type="number" name="price_max" id="price_max" value="{{ request('price_max') }}" min="0" step="0.01"
                                   class="block w-full px-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-colors duration-200">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-semibold rounded-xl text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all duration-200 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Rechercher
                    </button>
                    
                    @if(request('search') || request('type') || request('sort') || request('limit') || request('date_from') || request('date_to') || request('weight_min') || request('weight_max') || request('price_min') || request('price_max'))
                        <a href="{{ route('dashboard.all-announcements') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">
                            Effacer tous les filtres
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Success/Error  Messages -->
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

    <!-- Results Summary -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">
                Résultats ({{ $otherTravelerAnnouncements->count() + $otherSenderAnnouncements->count() }} annonces)
            </h2>
            <div class="text-sm text-gray-500">
                {{ $otherTravelerAnnouncements->count() }} voyages • {{ $otherSenderAnnouncements->count() }} livraisons
            </div>
        </div>
    </div>

    <!-- Traveler Announcements -->
    @if($otherTravelerAnnouncements->count() > 0)
        <div class="mb-8">
            <h3 class="text-xl lg:text-2xl font-bold text-gray-900 mb-6">Annonces de voyage</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                @foreach($otherTravelerAnnouncements as $announcement)
                    @php $isOwn = auth()->check() && auth()->id() === $announcement->user_id; @endphp
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 h-full" x-data="{ showModal: false, showRequestModal: false }">
                        <div class="p-6 flex flex-col h-full">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            <a href="{{ route('profiles.show', $announcement->user->id) }}" 
                                               class="hover:text-blue-600 transition-colors duration-200 cursor-pointer">
                                                {{ $announcement->user->name }}
                                            </a>
                                        </h3>
                                        <p class="text-sm text-gray-500">{{ $announcement->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Route Information -->
                            <div class="mb-4">
                                <div class="flex items-center space-x-2 mb-2">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                    <span class="text-sm font-medium text-gray-900">{{ $announcement->departure_city }}</span>
                                </div>
                                <div class="flex items-center space-x-2 mb-2">
                                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                    <span class="text-sm font-medium text-gray-900">{{ $announcement->arrival_city }}</span>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Date de départ:</span>
                                    <span class="font-medium text-gray-900">{{ $announcement->departure_date->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Espace disponible:</span>
                                    <span class="font-medium text-gray-900">{{ $announcement->available_kg }} kg</span>
                                </div>
                                @if($announcement->details && $announcement->details->price_per_kg)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Prix par kg:</span>
                                        <span class="font-medium text-green-600">{{ number_format($announcement->details->price_per_kg, 2) }} DH</span>
                                    </div>
                                @endif
                                @if($announcement->details && $announcement->details->transport_mode)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Mode de transport:</span>
                                        <span class="font-medium text-gray-900">{{ $announcement->details->transport_mode }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Description -->
                            @if($announcement->description)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600">{{ Str::limit($announcement->description, 100) }}</p>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200 mt-auto">
                                @if($isOwn)
                                    <div class="flex space-x-2">
                                        <a href="{{ route('dashboard.edit-traveler-announcement', $announcement->id) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50">
                                            <i data-lucide="edit" class="w-3 h-3 mr-1"></i>
                                            Modifier
                                        </a>
                                        <button type="button" @click="showModal = true"
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                            Plus d'infos
                                        </button>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-2">
                                        <button @click="showRequestModal = true" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Demander le trajectoire
                                        </button>
                                        <button type="button" @click="showModal = true"
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                            Plus d'infos
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Modal: Traveler Details -->
                        <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4" @keydown.window.escape="showModal = false">
                            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden" @click.away="showModal = false">
                                <!-- Header -->
                                <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-white">Détails du voyage</h3>
                                                <p class="text-gray-100 text-sm">{{ $announcement->user->name }}</p>
                                            </div>
                                        </div>
                                        <button class="p-2 text-white hover:bg-white hover:bg-opacity-10 rounded-full transition-colors duration-200" @click="showModal = false">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-6 space-y-6">
                                    <!-- Route Section -->
                                    <div class="space-y-3">
                                        <h4 class="text-sm font-semibold text-gray-900 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                            </svg>
                                            Trajet
                                        </h4>
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex items-center justify-between">
                                                <div class="text-center">
                                                    <div class="text-sm font-medium text-gray-900">{{ $announcement->departure_city }}</div>
                                                    <div class="text-xs text-gray-500">Départ</div>
                                                    <div class="text-sm text-blue-600 font-medium mt-1">{{ $announcement->departure_date->format('d/m/Y') }}</div>
                                                    @if($announcement->details && $announcement->details->departure_time)
                                                        <div class="text-xs text-gray-600">{{ $announcement->details->departure_time }}</div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 17l5-5-5-5"/>
                                                    </svg>
                                                </div>
                                                <div class="text-center">
                                                    <div class="text-sm font-medium text-gray-900">{{ $announcement->arrival_city }}</div>
                                                    <div class="text-xs text-gray-500">Arrivée</div>
                                                    @if($announcement->details && $announcement->details->estimated_arrival_date)
                                                        <div class="text-sm text-green-600 font-medium mt-1">{{ $announcement->details->estimated_arrival_date->format('d/m/Y') }}</div>
                                                        @if($announcement->details->estimated_arrival_time)
                                                            <div class="text-xs text-gray-600">{{ $announcement->details->estimated_arrival_time }}</div>
                                                        @endif
                                                    @else
                                                        <div class="text-xs text-gray-400 mt-1">Non spécifiée</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Details Section -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-blue-50 rounded-lg p-3">
                                            <div class="text-xs text-blue-600 font-medium">Espace disponible</div>
                                            <div class="text-lg font-bold text-blue-900">{{ $announcement->available_kg }} kg</div>
                                        </div>
                                        @if($announcement->details && $announcement->details->price_per_kg)
                                            <div class="bg-green-50 rounded-lg p-3">
                                                <div class="text-xs text-green-600 font-medium">Prix par kg</div>
                                                <div class="text-lg font-bold text-green-900">{{ number_format($announcement->details->price_per_kg, 2) }} DH</div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Additional Info -->
                                    @if($announcement->details && $announcement->details->transport_mode)
                                        <div class="flex items-center space-x-3 text-sm">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2v0a2 2 0 01-2-2v-5a2 2 0 00-2-2H8z"/>
                                            </svg>
                                            <span class="text-gray-600">Transport:</span>
                                            <span class="font-medium text-gray-900">{{ $announcement->details->transport_mode }}</span>
                                        </div>
                                    @endif

                                    @if($announcement->description)
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Description du voyage</h4>
                                            <p class="text-sm text-gray-700 leading-relaxed">{{ $announcement->description }}</p>
                                        </div>
                                    @endif

                                    @if($announcement->details && $announcement->details->extra_notes)
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Notes supplémentaires</h4>
                                            <p class="text-sm text-gray-700 leading-relaxed">{{ $announcement->details->extra_notes }}</p>
                                        </div>
                                    @endif

                                    <!-- Footer -->
                                    <div class="pt-4 border-t border-gray-200">
                                        <div class="text-xs text-gray-500">
                                            Publié {{ $announcement->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal: Create Request for Traveler Announcement -->
                        <div x-show="showRequestModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4" @keydown.window.escape="showRequestModal = false">
                            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden" @click.away="showRequestModal = false">
                                <!-- Header -->
                                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-white">Demander le trajectoire</h3>
                                                <p class="text-gray-100 text-sm">{{ $announcement->departure_city }} → {{ $announcement->arrival_city }}</p>
                                            </div>
                                        </div>
                                        <button class="p-2 text-white hover:bg-white hover:bg-opacity-10 rounded-full transition-colors duration-200" @click="showRequestModal = false">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                </button>
                                    </div>
                                </div>

                                <!-- Form -->
                                <form action="{{ route('requests.create') }}" method="POST" class="p-6 space-y-4">
                                    @csrf
                                    <input type="hidden" name="traveler_announcement_id" value="{{ $announcement->id }}">
                                    
                                    <div>
                                        <label for="package_weight_{{ $announcement->id }}" class="block text-sm font-medium text-gray-700 mb-1">Poids du colis (kg)</label>
                                        <input type="number" name="package_weight" id="package_weight_{{ $announcement->id }}" step="0.1" min="0.1"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="Poids en kg">
                                    </div>

                                    <div>
                                        <label for="pickup_location_{{ $announcement->id }}" class="block text-sm font-medium text-gray-700 mb-1">Lieu de récupération</label>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-medium text-gray-600">{{ $announcement->departure_city }} :</span>
                                            <input type="text" name="pickup_location" id="pickup_location_{{ $announcement->id }}" 
                                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                   placeholder="Quartier, rue, point de repère...">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="delivery_location_{{ $announcement->id }}" class="block text-sm font-medium text-gray-700 mb-1">Lieu de livraison</label>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-medium text-gray-600">{{ $announcement->arrival_city }} :</span>
                                            <input type="text" name="delivery_location" id="delivery_location_{{ $announcement->id }}" 
                                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                   placeholder="Quartier, rue, point de repère...">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="description_{{ $announcement->id }}" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                        <textarea name="description" id="description_{{ $announcement->id }}" rows="3"
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                  placeholder="Décrivez votre colis, dimensions, type d'objet... (optionnel)"></textarea>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex space-x-3 pt-4">
                                        <button type="button" @click="showRequestModal = false"
                                                class="flex-1 px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            Annuler
                                        </button>
                                        <button type="submit"
                                                class="flex-1 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                            Envoyer la demande
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Sender Announcements -->
    @if($otherSenderAnnouncements->count() > 0)
        <div class="mb-8">
            <h3 class="text-xl lg:text-2xl font-bold text-gray-900 mb-6">Annonces de livraison</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                @foreach($otherSenderAnnouncements as $announcement)
                    @php $isOwn = auth()->check() && auth()->id() === $announcement->user_id; @endphp
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 h-full" x-data="{ showModal: false, activeImage: '', showRequestModal: false }">
                        <div class="p-6 flex flex-col h-full">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            <a href="{{ route('profiles.show', $announcement->user->id) }}" 
                                               class="hover:text-blue-600 transition-colors duration-200 cursor-pointer">
                                                {{ $announcement->user->name }}
                                            </a>
                                        </h3>
                                        <p class="text-sm text-gray-500">{{ $announcement->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Route Information -->
                            <div class="mb-4">
                                <div class="flex items-center space-x-2 mb-2">
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    <span class="text-sm font-medium text-gray-900">{{ $announcement->pickup_city }}</span>
                                </div>
                                <div class="flex items-center space-x-2 mb-2">
                                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                    <span class="text-sm font-medium text-gray-900">{{ $announcement->delivery_city }}</span>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Date souhaitée:</span>
                                    <span class="font-medium text-gray-900">{{ $announcement->preferred_date->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Poids du colis:</span>
                                    <span class="font-medium text-gray-900">{{ $announcement->package_weight }} kg</span>
                                </div>
                                @if($announcement->details && $announcement->details->item_type)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Type d'objet:</span>
                                        <span class="font-medium text-gray-900">{{ $announcement->details->item_type }}</span>
                                    </div>
                                @endif
                                @if($announcement->details && $announcement->details->declared_value)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Valeur déclarée:</span>
                                        <span class="font-medium text-green-600">{{ number_format($announcement->details->declared_value, 2) }} DH</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Description -->
                            @if($announcement->description)
                                <div class="mb-4 relative" x-data="{ expanded: false, overflow: false }" x-init="$nextTick(() => { overflow = $refs.desc.scrollHeight > $refs.desc.clientHeight })">
                                    <p x-ref="desc" class="text-sm text-gray-600" x-bind:style="expanded ? '' : 'display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;padding-right:64px;'">{{ $announcement->description }}</p>
                                    <button type="button" class="absolute bottom-0 right-0 text-xs text-blue-600 hover:underline bg-white bg-opacity-90 px-1" x-show="overflow && !expanded" @click="expanded = true">Voir plus</button>
                                    <button type="button" class="mt-1 text-xs text-blue-600 hover:underline" x-show="expanded" @click="expanded = false">Réduire</button>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200 mt-auto">
                                @if($isOwn)
                                    <div class="flex space-x-2">
                                        <a href="{{ route('dashboard.edit-sender-announcement', $announcement->id) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50">
                                            <i data-lucide="edit" class="w-3 h-3 mr-1"></i>
                                            Modifier
                                        </a>
                                        <button type="button" @click="showModal = true"
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                            Plus d'infos
                                        </button>
                                    </div>
                                @else
                                <div class="flex items-center space-x-2">
                                    <button @click="showRequestModal = true" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Demander
                                    </button>
                                    <button type="button" @click="showModal = true"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                        Plus d'infos
                                </button>
                            </div>
                                @endif
                            </div>
                        </div>
                                @php
                                    $pics = is_array($announcement->pictures) ? $announcement->pictures : (is_string($announcement->pictures) ? json_decode($announcement->pictures, true) : []);
                            $firstImage = null;
                            foreach ($pics as $picture) {
                                $srcRaw = is_string($picture) ? $picture : '';
                                $srcNorm = ltrim(str_replace('\\', '/', $srcRaw), '/');
                                if (str_starts_with($srcNorm, 'public/')) { $srcNorm = substr($srcNorm, 7); } elseif (str_starts_with($srcNorm, 'storage/')) { $srcNorm = substr($srcNorm, 8); }
                                $firstImage = asset('storage/' . $srcNorm);
                                break;
                            }
                                @endphp
                        <!-- Modal: Sender Details -->
                        <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4" @keydown.window.escape="showModal = false">
                            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden" @click.away="showModal = false">
                                <!-- Header -->
                                <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-white">Détails du colis</h3>
                                                <p class="text-gray-100 text-sm">{{ $announcement->user->name }}</p>
                                            </div>
                                        </div>
                                        <button class="p-2 text-white hover:bg-white hover:bg-opacity-10 rounded-full transition-colors duration-200" @click="showModal = false">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                </div>

                                <!-- Content -->
                                <div class="p-6 space-y-6 max-h-[32rem] overflow-y-auto">
                                    <!-- Photos Section -->
                                @if(!empty($pics))
                                        <div x-init="activeImage = activeImage || '{{ $firstImage }}'">
                                            <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                Photos du colis
                                            </h4>
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <img :src="activeImage || '{{ $firstImage }}'" alt="Photo du colis" class="w-full max-h-48 object-contain rounded-md mx-auto">
                                                @if(count($pics) > 1)
                                                    <div class="flex space-x-2 overflow-x-auto mt-3 justify-center">
                                            @foreach($pics as $picture)
                                                @php
                                                    $srcRaw = is_string($picture) ? $picture : '';
                                                    $srcNorm = ltrim(str_replace('\\', '/', $srcRaw), '/');
                                                        if (str_starts_with($srcNorm, 'public/')) { $srcNorm = substr($srcNorm, 7); } elseif (str_starts_with($srcNorm, 'storage/')) { $srcNorm = substr($srcNorm, 8); }
                                                        $primary = asset('storage/' . $srcNorm);
                                                @endphp
                                                            <img src="{{ $primary }}" alt="Miniature" class="w-12 h-12 object-cover rounded-md border border-gray-200 cursor-pointer hover:ring-2 hover:ring-green-400 transition" @click="activeImage = '{{ $primary }}'">
                                            @endforeach
                                        </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Route Section -->
                                    <div class="space-y-3">
                                        <h4 class="text-sm font-semibold text-gray-900 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                            </svg>
                                            Livraison demandée
                                        </h4>
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex items-center justify-between">
                                                <div class="text-center">
                                                    <div class="text-sm font-medium text-gray-900">{{ $announcement->pickup_city }}</div>
                                                    <div class="text-xs text-gray-500">Récupération</div>
                                                </div>
                                                <div class="flex-1 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 17l5-5-5-5"/>
                                                    </svg>
                                                </div>
                                                <div class="text-center">
                                                    <div class="text-sm font-medium text-gray-900">{{ $announcement->delivery_city }}</div>
                                                    <div class="text-xs text-gray-500">Livraison</div>
                                                </div>
                                            </div>
                                            <div class="text-center mt-3">
                                                <div class="text-sm text-green-600 font-medium">{{ $announcement->preferred_date->format('d/m/Y') }}</div>
                                                <div class="text-xs text-gray-500">Date souhaitée</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Package Details -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-green-50 rounded-lg p-3">
                                            <div class="text-xs text-green-600 font-medium">Poids du colis</div>
                                            <div class="text-lg font-bold text-green-900">{{ $announcement->package_weight }} kg</div>
                                        </div>
                                        @if($announcement->details && $announcement->details->declared_value)
                                            <div class="bg-blue-50 rounded-lg p-3">
                                                <div class="text-xs text-blue-600 font-medium">Valeur déclarée</div>
                                                <div class="text-lg font-bold text-blue-900">{{ number_format($announcement->details->declared_value, 2) }} DH</div>
                                    </div>
                                @endif
                                    </div>

                                    <!-- Additional Info -->
                                    @if($announcement->details && $announcement->details->item_type)
                                        <div class="flex items-center space-x-3 text-sm">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                            <span class="text-gray-600">Type d'objet:</span>
                                            <span class="text-gray-900 font-medium">{{ $announcement->details->item_type }}</span>
                                    </div>
                                    @endif

                                    @if($announcement->details && $announcement->details->dimensions)
                                        <div class="flex items-center space-x-3 text-sm">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                            </svg>
                                            <span class="text-gray-600">Dimensions:</span>
                                            <span class="text-gray-900 font-medium">{{ $announcement->details->dimensions }}</span>
                                </div>
                            @endif

                                    @if($announcement->description)
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Description</h4>
                                            <p class="text-sm text-gray-700 leading-relaxed">{{ $announcement->description }}</p>
                                        </div>
                                    @endif

                                    @if($announcement->details && $announcement->details->additional_info)
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Instructions spéciales</h4>
                                            <p class="text-sm text-gray-700 leading-relaxed">{{ $announcement->details->additional_info }}</p>
                            </div>
                                @endif

                                    <!-- Footer -->
                                    <div class="pt-4 border-t border-gray-200">
                                        <div class="text-xs text-gray-500">
                                            Publié {{ $announcement->created_at->diffForHumans() }}
                                        </div>
                                </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal: Create Request for Sender Announcement -->
                        <div x-show="showRequestModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4" @keydown.window.escape="showRequestModal = false">
                            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden" @click.away="showRequestModal = false">
                                <!-- Header -->
                                <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-white">Demander la livraison</h3>
                                                <p class="text-gray-100 text-sm">{{ $announcement->pickup_city }} → {{ $announcement->delivery_city }}</p>
                                            </div>
                                        </div>
                                        <button class="p-2 text-white hover:bg-white hover:bg-opacity-10 rounded-full transition-colors duration-200" @click="showRequestModal = false">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Form -->
                                <form action="{{ route('requests.create-sender') }}" method="POST" class="p-6 space-y-4">
                                    @csrf
                                    <input type="hidden" name="sender_announcement_id" value="{{ $announcement->id }}">
                                    
                                    <div>
                                        <label for="proposed_price_{{ $announcement->id }}" class="block text-sm font-medium text-gray-700 mb-1">Prix proposé (DH)</label>
                                        <input type="number" name="proposed_price" id="proposed_price_{{ $announcement->id }}" step="0.01" min="0" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                               placeholder="Prix total proposé">
                                    </div>

                                    <div>
                                        <label for="pickup_location_{{ $announcement->id }}" class="block text-sm font-medium text-gray-700 mb-1">Lieu de récupération</label>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-medium text-gray-600">{{ $announcement->pickup_city }} :</span>
                                            <input type="text" name="pickup_location" id="pickup_location_{{ $announcement->id }}" 
                                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                                   placeholder="Quartier, rue, point de repère...">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="delivery_location_{{ $announcement->id }}" class="block text-sm font-medium text-gray-700 mb-1">Lieu de livraison</label>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-medium text-gray-600">{{ $announcement->delivery_city }} :</span>
                                            <input type="text" name="delivery_location" id="delivery_location_{{ $announcement->id }}" 
                                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                                   placeholder="Quartier, rue, point de repère...">
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex space-x-3 pt-4">
                                        <button type="button" @click="showRequestModal = false"
                                                class="flex-1 px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            Annuler
                                        </button>
                                        <button type="submit"
                                                class="flex-1 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                            Envoyer la demande
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Empty State -->
    @if($otherTravelerAnnouncements->count() == 0 && $otherSenderAnnouncements->count() == 0)
        <div class="text-center py-12">
            <div class="mx-auto h-12 w-12 text-gray-400">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900">
                @if(request('search') || request('type') || request('sort') || request('date_from') || request('date_to') || request('weight_min') || request('weight_max') || request('price_min') || request('price_max'))
                    Aucune annonce trouvée
                @else
                    Aucune annonce disponible
                @endif
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                @if(request('search') || request('type') || request('sort') || request('date_from') || request('date_to') || request('weight_min') || request('weight_max') || request('price_min') || request('price_max'))
                    Aucune annonce ne correspond à vos critères de recherche.
                @else
                    Il n'y a actuellement aucune annonce active sur la plateforme.
                @endif
            </p>
            <div class="mt-6">
                <a href="{{ route('dashboard.create-traveler-announcement') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 mr-3">
                    Créer une annonce de voyage
                </a>
                <a href="{{ route('dashboard.create-sender-announcement') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    Créer une annonce de livraison
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
