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
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recherche avancée</h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('dashboard.all-announcements') }}" class="space-y-6">
                <!-- Basic Search -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche générale</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ville, description, utilisateur...">
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type d'annonce</label>
                        <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les types</option>
                            <option value="traveler" {{ request('type') == 'traveler' ? 'selected' : '' }}>Voyage</option>
                            <option value="sender" {{ request('type') == 'sender' ? 'selected' : '' }}>Livraison</option>
                        </select>
                    </div>

                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Trier par</label>
                        <select name="sort" id="sort" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Plus récentes</option>
                            <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Plus anciennes</option>
                            <option value="departure_date_asc" {{ request('sort') == 'departure_date_asc' ? 'selected' : '' }}>Date de départ</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                        </select>
                    </div>

                    <div>
                        <label for="limit" class="block text-sm font-medium text-gray-700 mb-1">Résultats par page</label>
                        <select name="limit" id="limit" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="12" {{ request('limit') == '12' ? 'selected' : '' }}>12</option>
                            <option value="24" {{ request('limit') == '24' ? 'selected' : '' }}>24</option>
                            <option value="48" {{ request('limit') == '48' ? 'selected' : '' }}>48</option>
                        </select>
                    </div>
                </div>

                <!-- Advanced Filters -->
                <div class="border-t border-gray-200 pt-6">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Filtres avancés</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Date Range -->
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date de départ (dès)</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Date de départ (jusqu'au)</label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="weight_min" class="block text-sm font-medium text-gray-700 mb-1">Poids minimum (kg)</label>
                            <input type="number" name="weight_min" id="weight_min" value="{{ request('weight_min') }}" min="0" step="0.1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="weight_max" class="block text-sm font-medium text-gray-700 mb-1">Poids maximum (kg)</label>
                            <input type="number" name="weight_max" id="weight_max" value="{{ request('weight_max') }}" min="0" step="0.1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="price_min" class="block text-sm font-medium text-gray-700 mb-1">Prix minimum (€/kg)</label>
                            <input type="number" name="price_min" id="price_min" value="{{ request('price_min') }}" min="0" step="0.01"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="price_max" class="block text-sm font-medium text-gray-700 mb-1">Prix maximum (€/kg)</label>
                            <input type="number" name="price_max" id="price_max" value="{{ request('price_max') }}" min="0" step="0.01"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Rechercher
                    </button>
                    
                    @if(request('search') || request('type') || request('sort') || request('limit') || request('date_from') || request('date_to') || request('weight_min') || request('weight_max') || request('price_min') || request('price_max'))
                        <a href="{{ route('dashboard.all-announcements') }}" class="text-sm text-gray-500 hover:text-gray-700">
                            Effacer tous les filtres
                        </a>
                    @endif
                </div>
            </form>
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
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                        <div class="p-6">
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
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $announcement->user->name }}</h3>
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
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Demander cette trajectoire
                                </button>
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
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 h-full" x-data="{ showDetails: false }">
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
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $announcement->user->name }}</h3>
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

                            <!-- Expandable Details Toggle -->
                            <div class="mb-3">
                                <button type="button" @click="showDetails = !showDetails"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">
                                    <svg :class="{'rotate-180': showDetails}" class="w-4 h-4 mr-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                    <span x-text="showDetails ? 'Moins d\'infos' : 'Plus d\'infos'"></span>
                                </button>
                            </div>

                            <!-- Expandable Details Content -->
                            <div x-show="showDetails" x-transition.opacity class="space-y-4 mb-4">
                                @php
                                    $pics = is_array($announcement->pictures) ? $announcement->pictures : (is_string($announcement->pictures) ? json_decode($announcement->pictures, true) : []);
                                @endphp
                                @if(!empty($pics))
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 mb-2">Photos</div>
                                        <div class="flex space-x-2 overflow-x-auto">
                                            @foreach($pics as $picture)
                                                @php
                                                    $srcRaw = is_string($picture) ? $picture : '';
                                                    $srcNorm = ltrim(str_replace('\\', '/', $srcRaw), '/');
                                                    if (str_starts_with($srcNorm, 'public/')) {
                                                        $srcNorm = substr($srcNorm, 7);
                                                    } elseif (str_starts_with($srcNorm, 'storage/')) {
                                                        $srcNorm = substr($srcNorm, 8);
                                                    }
                                                    $primary = asset('storage/' . $srcNorm);    // user's request to use asset
                                                    $alt1 = route('media', ['path' => $srcNorm]); // robust proxy fallback
                                                    $alt2 = '/' . ltrim($srcNorm, '/');          // raw path fallback
                                                    $alt3 = '/storage/' . $srcNorm;              // relative storage fallback
                                                @endphp
                                                <div class="flex-shrink-0">
                                                    <img src="{{ $primary }}" 
                                                         data-candidates="{{ $alt1 }}|{{ $alt2 }}|{{ $alt3 }}"
                                                         data-idx="0"
                                                         onerror="(function(img){ var c=img.dataset.candidates.split('|'); var i=parseInt(img.dataset.idx||'0'); if(i<c.length){ img.dataset.idx = i+1; img.src=c[i]; } })(this)"
                                                         alt="Photo du colis" 
                                                         class="w-16 h-16 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity duration-200"
                                                         onclick="openImageModal(this.src)">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($announcement->details && $announcement->details->additional_info)
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 mb-1">Instructions</div>
                                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $announcement->details->additional_info }}</p>
                                </div>
                            @endif

                                <div class="text-xs text-gray-500">
                                    <span class="font-medium text-gray-700">Soumise le:</span>
                                    {{ $announcement->created_at->format('d/m/Y H:i') }}
                                    <span class="mx-1">•</span>
                                    {{ $announcement->created_at->diffForHumans() }}
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200 mt-auto">
                                @if($isOwn)
                                    <a href="{{ route('dashboard.edit-sender-announcement', $announcement->id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 border border-blue-300 text-xs font-medium rounded text-blue-700 bg-white hover:bg-blue-50">
                                        <i data-lucide="edit" class="w-3 h-3 mr-1"></i>
                                        Modifier
                                    </a>
                                @else
                                <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Proposer mes services
                                </button>
                                @endif
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
