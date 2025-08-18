<div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 h-full" x-data="{ showModal: false }">
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
                    <h3 class="text-lg font-semibold text-gray-900">{{ $announcement->user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $announcement->created_at->diffForHumans() }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                @if($isOwn)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $announcement->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                    </span>
                @endif
                <!-- More Info Icon -->
                <button type="button" @click="showModal = true" 
                        class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors duration-200" 
                        title="Plus d'informations">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
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
                <span class="text-gray-600">Départ:</span>
                <span class="font-medium text-gray-900">
                    {{ $announcement->departure_date->format('d/m/Y') }}
                    @if($announcement->details && $announcement->details->departure_time)
                        à {{ $announcement->details->departure_time }}
                    @endif
                </span>
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
            @if($announcement->details && $announcement->details->estimated_arrival_date)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Arrivée estimée:</span>
                    <span class="font-medium text-gray-900">
                        {{ $announcement->details->estimated_arrival_date->format('d/m/Y') }}
                        @if($announcement->details->estimated_arrival_time)
                            à {{ $announcement->details->estimated_arrival_time }}
                        @endif
                    </span>
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
                       class="inline-flex items-center px-2 py-1 border border-blue-300 text-xs font-medium rounded text-blue-700 bg-white hover:bg-blue-50">
                        <i data-lucide="edit" class="w-3 h-3 mr-1"></i>
                        Modifier
                    </a>
                    <form action="{{ route('dashboard.toggle-announcement', ['type' => 'traveler', 'id' => $announcement->id]) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                            {{ $announcement->is_active ? 'Désactiver' : 'Activer' }}
                        </button>
                    </form>
                    <form action="{{ route('dashboard.delete-announcement', ['type' => 'traveler', 'id' => $announcement->id]) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-2 py-1 border border-red-300 text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50">
                            Supprimer
                        </button>
                    </form>
                </div>
            @else
                <div class="flex items-center space-x-2">
                    <button class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Demander cette trajectoire
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
                            <p class="text-blue-100 text-sm">{{ $announcement->user->name }}</p>
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
</div> 