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
            @if($isOwn)
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $announcement->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            @endif
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
            @if($isOwn)
                <div class="flex space-x-2">
                    <form action="{{ route('dashboard.toggle-announcement', ['type' => 'traveler', 'id' => $announcement->id]) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                            {{ $announcement->is_active ? 'Désactiver' : 'Activer' }}
                        </button>
                    </form>
                    <form action="{{ route('dashboard.delete-announcement', ['type' => 'traveler', 'id' => $announcement->id]) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-red-300 text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50">
                            Supprimer
                        </button>
                    </form>
                </div>
            @else
                <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Demander cette trajectoire
                </button>
            @endif
        </div>
    </div>
</div> 