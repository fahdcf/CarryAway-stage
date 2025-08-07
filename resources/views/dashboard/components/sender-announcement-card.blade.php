<div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
    <div class="p-6">
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
                <span class="text-gray-600">Date préférée:</span>
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
            @if($announcement->details && $announcement->details->dimensions)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Dimensions:</span>
                    <span class="font-medium text-gray-900">{{ $announcement->details->dimensions }}</span>
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
                    <form action="{{ route('dashboard.toggle-announcement', ['type' => 'sender', 'id' => $announcement->id]) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                            {{ $announcement->is_active ? 'Désactiver' : 'Activer' }}
                        </button>
                    </form>
                    <form action="{{ route('dashboard.delete-announcement', ['type' => 'sender', 'id' => $announcement->id]) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-red-300 text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50">
                            Supprimer
                        </button>
                    </form>
                </div>
            @else
                <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Demander cette trajectoire
                </button>
            @endif
        </div>
    </div>
</div> 