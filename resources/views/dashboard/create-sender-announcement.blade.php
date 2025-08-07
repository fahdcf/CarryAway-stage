@extends('layouts.app')

@section('title', 'Créer une annonce de livraison - CarryWay')

@section('content')
<div class="min-h-screen bg-gray-50 pt-16">
    <!-- Dashboard Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Créer une annonce de livraison</h1>
                    <p class="text-gray-600">Demandez à quelqu'un de transporter votre colis</p>
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
                <h2 class="text-lg font-semibold text-gray-900">Informations de livraison</h2>
            </div>
            
            <form action="{{ route('dashboard.store-sender-announcement') }}" method="POST" class="p-6">
                @csrf
                
                <!-- Route Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="pickup_city" class="block text-sm font-medium text-gray-700 mb-2">
                            Ville de ramassage *
                        </label>
                        <input type="text" name="pickup_city" id="pickup_city" 
                               value="{{ old('pickup_city') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('pickup_city') border-red-500 @enderror"
                               placeholder="Ex: Casablanca">
                        @error('pickup_city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="delivery_city" class="block text-sm font-medium text-gray-700 mb-2">
                            Ville de livraison *
                        </label>
                        <input type="text" name="delivery_city" id="delivery_city" 
                               value="{{ old('delivery_city') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('delivery_city') border-red-500 @enderror"
                               placeholder="Ex: Marrakech">
                        @error('delivery_city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Date and Weight -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="preferred_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date préférée *
                        </label>
                        <input type="date" name="preferred_date" id="preferred_date" 
                               value="{{ old('preferred_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('preferred_date') border-red-500 @enderror">
                        @error('preferred_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="package_weight" class="block text-sm font-medium text-gray-700 mb-2">
                            Poids du colis (kg) *
                        </label>
                        <input type="number" name="package_weight" id="package_weight" 
                               value="{{ old('package_weight') }}" step="0.1" min="0.1" max="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('package_weight') border-red-500 @enderror"
                               placeholder="Ex: 2.5">
                        @error('package_weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Package Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="item_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Type d'objet
                        </label>
                        <select name="item_type" id="item_type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                            <option value="">Sélectionner</option>
                            <option value="Documents" {{ old('item_type') == 'Documents' ? 'selected' : '' }}>Documents</option>
                            <option value="Vêtements" {{ old('item_type') == 'Vêtements' ? 'selected' : '' }}>Vêtements</option>
                            <option value="Électronique" {{ old('item_type') == 'Électronique' ? 'selected' : '' }}>Électronique</option>
                            <option value="Nourriture" {{ old('item_type') == 'Nourriture' ? 'selected' : '' }}>Nourriture</option>
                            <option value="Médicaments" {{ old('item_type') == 'Médicaments' ? 'selected' : '' }}>Médicaments</option>
                            <option value="Autre" {{ old('item_type') == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="dimensions" class="block text-sm font-medium text-gray-700 mb-2">
                            Dimensions
                        </label>
                        <input type="text" name="dimensions" id="dimensions" 
                               value="{{ old('dimensions') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('dimensions') border-red-500 @enderror"
                               placeholder="Ex: 30x20x10 cm">
                        @error('dimensions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Value -->
                <div class="mb-6">
                    <label for="declared_value" class="block text-sm font-medium text-gray-700 mb-2">
                        Valeur déclarée (DH)
                    </label>
                    <input type="number" name="declared_value" id="declared_value" 
                           value="{{ old('declared_value') }}" step="0.01" min="0" max="100000"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('declared_value') border-red-500 @enderror"
                           placeholder="Ex: 500.00">
                    @error('declared_value')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description du colis
                    </label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('description') border-red-500 @enderror"
                              placeholder="Décrivez votre colis, son contenu, etc.">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Additional Info -->
                <div class="mb-6">
                    <label for="additional_info" class="block text-sm font-medium text-gray-700 mb-2">
                        Informations supplémentaires
                    </label>
                    <textarea name="additional_info" id="additional_info" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('additional_info') border-red-500 @enderror"
                              placeholder="Instructions spéciales, conditions, etc.">{{ old('additional_info') }}</textarea>
                    @error('additional_info')
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
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
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