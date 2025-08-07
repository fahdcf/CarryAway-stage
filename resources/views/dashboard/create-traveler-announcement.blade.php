@extends('layouts.app')

@section('title', 'Créer une annonce de voyage - CarryWay')

@section('content')
<div class="min-h-screen bg-gray-50 pt-16">
    <!-- Dashboard Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Créer une annonce de voyage</h1>
                    <p class="text-gray-600">Annoncez votre voyage et proposez de transporter des colis</p>
                </div>
                <a href="{{ route('dashboard.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Informations du voyage</h2>
            </div>
            
            <form action="{{ route('dashboard.store-traveler-announcement') }}" method="POST" class="p-6">
                @csrf
                
                <!-- Route Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="departure_city" class="block text-sm font-medium text-gray-700 mb-2">
                            Ville de départ *
                        </label>
                        <input type="text" name="departure_city" id="departure_city" 
                               value="{{ old('departure_city') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('departure_city') border-red-500 @enderror"
                               placeholder="Ex: Casablanca">
                        @error('departure_city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="arrival_city" class="block text-sm font-medium text-gray-700 mb-2">
                            Ville d'arrivée *
                        </label>
                        <input type="text" name="arrival_city" id="arrival_city" 
                               value="{{ old('arrival_city') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('arrival_city') border-red-500 @enderror"
                               placeholder="Ex: Marrakech">
                        @error('arrival_city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Date and Weight -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="departure_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de départ *
                        </label>
                        <input type="date" name="departure_date" id="departure_date" 
                               value="{{ old('departure_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('departure_date') border-red-500 @enderror">
                        @error('departure_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="available_kg" class="block text-sm font-medium text-gray-700 mb-2">
                            Espace disponible (kg) *
                        </label>
                        <input type="number" name="available_kg" id="available_kg" 
                               value="{{ old('available_kg') }}" step="0.1" min="0.1" max="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('available_kg') border-red-500 @enderror"
                               placeholder="Ex: 5.5">
                        @error('available_kg')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Additional Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="transport_mode" class="block text-sm font-medium text-gray-700 mb-2">
                            Mode de transport
                        </label>
                        <select name="transport_mode" id="transport_mode" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sélectionner</option>
                            <option value="Voiture" {{ old('transport_mode') == 'Voiture' ? 'selected' : '' }}>Voiture</option>
                            <option value="Bus" {{ old('transport_mode') == 'Bus' ? 'selected' : '' }}>Bus</option>
                            <option value="Train" {{ old('transport_mode') == 'Train' ? 'selected' : '' }}>Train</option>
                            <option value="Avion" {{ old('transport_mode') == 'Avion' ? 'selected' : '' }}>Avion</option>
                            <option value="Autre" {{ old('transport_mode') == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="estimated_arrival_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date d'arrivée estimée
                        </label>
                        <input type="date" name="estimated_arrival_date" id="estimated_arrival_date" 
                               value="{{ old('estimated_arrival_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('estimated_arrival_date') border-red-500 @enderror">
                        @error('estimated_arrival_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Price -->
                <div class="mb-6">
                    <label for="price_per_kg" class="block text-sm font-medium text-gray-700 mb-2">
                        Prix par kg (DH)
                    </label>
                    <input type="number" name="price_per_kg" id="price_per_kg" 
                           value="{{ old('price_per_kg') }}" step="0.01" min="0" max="1000"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('price_per_kg') border-red-500 @enderror"
                           placeholder="Ex: 15.50">
                    @error('price_per_kg')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description du voyage
                    </label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Décrivez votre voyage, les conditions, etc.">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Extra Notes -->
                <div class="mb-6">
                    <label for="extra_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes supplémentaires
                    </label>
                    <textarea name="extra_notes" id="extra_notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('extra_notes') border-red-500 @enderror"
                              placeholder="Informations supplémentaires, conditions spéciales, etc.">{{ old('extra_notes') }}</textarea>
                    @error('extra_notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('dashboard.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Créer l'annonce
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 