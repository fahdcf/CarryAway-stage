@extends('layouts.app')

@section('title', (isset($announcement) ? 'Modifier' : 'Créer') . ' une annonce de livraison - CarryWay')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Dashboard Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gray-900 rounded-lg p-3">
                        <i data-lucide="package" class="w-8 h-8 text-white"></i>
                    </div>
                <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ isset($announcement) ? 'Modifier l\'annonce' : 'Créer une annonce' }}</h1>
                        <p class="text-gray-600 text-sm">{{ isset($announcement) ? 'Modifier la livraison' : 'Nouvelle livraison' }}</p>
                    </div>
                </div>
                <a href="{{ route('dashboard.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-colors duration-200">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Form Header -->
            <div class="px-6 py-4 bg-gray-900 text-white">
                <div class="flex items-center space-x-3">
                    <div class="bg-gray-800 rounded-lg p-2">
                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold">Détails de livraison</h2>
                        <p class="text-gray-300 text-sm">Informations requises</p>
                    </div>
                </div>
            </div>
            
                         <form action="{{ isset($announcement) ? route('dashboard.update-sender-announcement', $announcement->id) : route('dashboard.store-sender-announcement') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @if(isset($announcement))
                @method('POST')
            @endif
                @csrf
                
                <!-- Route Information Section -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="bg-blue-100 rounded-lg p-2">
                                <i data-lucide="route" class="w-5 h-5 text-blue-600"></i>
                            </div>
                    <div>
                                <h3 class="text-lg font-semibold text-gray-900">Itinéraire</h3>
                            </div>
                        </div>
                        <button type="button" id="swapCitiesBtn" class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-blue-400 hover:text-blue-600 transition-all duration-200">
                            <i data-lucide="swap" class="w-4 h-4 mr-2"></i>
                            Échanger
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Pickup City Combobox -->
                        <div x-data="cityPicker({ fieldId: 'pickup_city', placeholder: 'Ville de départ', initial: '{{ isset($announcement) ? $announcement->pickup_city : old('pickup_city') }}' })" class="relative">
                        <label for="pickup_city" class="block text-sm font-medium text-gray-700 mb-2">
                                Ville de départ *
                        </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="map-pin" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <input x-model="query" @focus="open = true" @input="onInput()" @keydown.arrow-down.prevent="move(1)" @keydown.arrow-up.prevent="move(-1)" @keydown.enter.prevent="selectActive()" @click.away="open = false"
                                       type="text" name="pickup_city" id="pickup_city" :placeholder="placeholder"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('pickup_city') border-red-500 focus:ring-red-500 @enderror transition-colors duration-200 text-gray-900 placeholder-gray-400" autocomplete="off">
                            </div>
                            <ul x-show="open && filtered.length" class="absolute z-50 mt-1 w-full max-h-60 overflow-auto bg-white border border-gray-200 rounded-lg shadow-lg">
                                <template x-for="(city, idx) in filtered" :key="city">
                                    <li @mouseenter="active = idx" @click="onItemClick(city)" @mousedown.prevent
                                        :class="idx === active ? 'bg-blue-50 text-blue-700 border-l-4 border-l-blue-500' : 'hover:bg-gray-50'"
                                        class="px-4 py-3 cursor-pointer text-sm border-l-4 border-l-transparent transition-colors duration-150">
                                        <span x-text="city" class="font-medium"></span>
                                    </li>
                                </template>
                            </ul>
                        @error('pickup_city')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                        <!-- Delivery City Combobox -->
                        <div x-data="cityPicker({ fieldId: 'delivery_city', placeholder: 'Ville d\'arrivée', initial: '{{ isset($announcement) ? $announcement->delivery_city : old('delivery_city') }}' })" class="relative">
                        <label for="delivery_city" class="block text-sm font-medium text-gray-700 mb-2">
                                Ville d'arrivée *
                        </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="navigation" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <input x-model="query" @focus="open = true" @input="onInput()" @keydown.arrow-down.prevent="move(1)" @keydown.arrow-up.prevent="move(-1)" @keydown.enter.prevent="selectActive()" @click.away="open = false"
                                       type="text" name="delivery_city" id="delivery_city" :placeholder="placeholder"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('delivery_city') border-red-500 focus:ring-red-500 @enderror transition-colors duration-200 text-gray-900 placeholder-gray-400" autocomplete="off">
                            </div>
                            <ul x-show="open && filtered.length" class="absolute z-50 mt-1 w-full max-h-60 overflow-auto bg-white border border-gray-200 rounded-lg shadow-lg">
                                <template x-for="(city, idx) in filtered" :key="city">
                                    <li @mouseenter="active = idx" @click="onItemClick(city)" @mousedown.prevent
                                        :class="idx === active ? 'bg-blue-50 text-blue-700 border-l-4 border-l-blue-500' : 'hover:bg-gray-50'"
                                        class="px-4 py-3 cursor-pointer text-sm border-l-4 border-l-transparent transition-colors duration-150">
                                        <span x-text="city" class="font-medium"></span>
                                    </li>
                                </template>
                            </ul>
                        @error('delivery_city')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                </div>

                <!-- Date and Weight Section -->
                <div class="mb-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="bg-purple-100 rounded-lg p-2">
                            <i data-lucide="calendar" class="w-5 h-5 text-purple-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Expédition</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                            <label for="preferred_date" class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                        <input type="date" name="preferred_date" id="preferred_date" 
                                   value="{{ isset($announcement) ? $announcement->preferred_date->format('Y-m-d') : old('preferred_date') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('preferred_date') border-red-500 focus:ring-red-500 @enderror transition-colors duration-200 text-gray-900">
                        @error('preferred_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                            <label for="package_weight" class="block text-sm font-medium text-gray-700 mb-2">Poids (kg) *</label>
                        <input type="number" name="package_weight" id="package_weight" 
                                   value="{{ isset($announcement) ? $announcement->package_weight : old('package_weight') }}" step="0.1" min="0.1" max="100"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('package_weight') border-red-500 focus:ring-red-500 @enderror transition-colors duration-200 text-gray-900 placeholder-gray-400"
                                   placeholder="2.5">
                        @error('package_weight')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Package Details Section -->
                <div class="mb-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="bg-orange-100 rounded-lg p-2">
                            <i data-lucide="package" class="w-5 h-5 text-orange-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Colis</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                 <div x-data="itemTypePicker({ fieldId: 'item_type', placeholder: 'Type de colis', initial: '{{ isset($announcement) && $announcement->details ? $announcement->details->item_type : old('item_type') }}' })" class="relative">
                             <label for="item_type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                             <div class="relative">
                                 <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                     <i data-lucide="package" class="h-5 w-5 text-gray-400"></i>
                                 </div>
                                 <input x-model="query" @focus="open = true" @input="onInput()" @keydown.arrow-down.prevent="move(1)" @keydown.arrow-up.prevent="move(-1)" @keydown.enter.prevent="selectActive()" @click.away="open = false"
                                        type="text" name="item_type" id="item_type" :placeholder="placeholder"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-gray-900 placeholder-gray-400" autocomplete="off">
                             </div>
                             <ul x-show="open && filtered.length" class="absolute z-50 mt-1 w-full max-h-60 overflow-auto bg-white border border-gray-200 rounded-lg shadow-lg">
                                 <template x-for="(item, idx) in filtered" :key="item">
                                     <li @mouseenter="active = idx" @click="onItemClick(item)" @mousedown.prevent
                                         :class="idx === active ? 'bg-blue-50 text-blue-700 border-l-4 border-l-blue-500' : 'hover:bg-gray-50'"
                                         class="px-4 py-3 cursor-pointer text-sm border-l-4 border-l-transparent transition-colors duration-150">
                                         <span x-text="item" class="font-medium"></span>
                                     </li>
                                 </template>
                             </ul>
                         </div>
                        
                        <div>
                            <label for="dimensions" class="block text-sm font-medium text-gray-700 mb-2">Dimensions</label>
                            <input type="text" name="dimensions" id="dimensions" 
                                   value="{{ isset($announcement) && $announcement->details ? $announcement->details->dimensions : old('dimensions') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('dimensions') border-red-500 focus:ring-red-500 @enderror transition-colors duration-200 text-gray-900 placeholder-gray-400"
                                   placeholder="30x20x10 cm">
                            @error('dimensions')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                                 <!-- Description Section -->
                 <div class="mb-6">
                     <div class="flex items-center space-x-3 mb-4">
                         <div class="bg-teal-100 rounded-lg p-2">
                             <i data-lucide="file-text" class="w-5 h-5 text-teal-600"></i>
                         </div>
                         <h3 class="text-lg font-semibold text-gray-900">Description</h3>
                     </div>
                     
                     <div>
                         <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Détails du colis</label>
                         <textarea name="description" id="description" rows="3"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 focus:ring-red-500 @enderror transition-colors duration-200 text-gray-900 placeholder-gray-400 resize-none"
                                   placeholder="Décrivez votre colis...">{{ isset($announcement) ? $announcement->description : old('description') }}</textarea>
                         @error('description')
                             <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                 <!-- Pictures Section -->
                 <div class="mb-6">
                     <div class="flex items-center space-x-3 mb-4">
                         <div class="bg-indigo-100 rounded-lg p-2">
                             <i data-lucide="image" class="w-5 h-5 text-indigo-600"></i>
                         </div>
                         <h3 class="text-lg font-semibold text-gray-900">Photos du colis</h3>
                    </div>
                    
                     <div>
                         <label class="block text-sm font-medium text-gray-700 mb-2">Photos (1-3 images)</label>
                         @php
                             $existingPics = isset($announcement) && $announcement->pictures ? array_values($announcement->pictures) : [];
                             $existingPics = array_slice($existingPics, 0, 3);
                             $slot = function($i) use ($existingPics) { return $existingPics[$i] ?? null; };
                         @endphp
                         <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                             @for($i=1; $i<=3; $i++)
                                 @php $pic = $slot($i-1); @endphp
                                 <div class="relative">
                                     <input type="file" name="pictures[]" accept="image/*" class="hidden" id="picture{{ $i }}" onchange="previewImage(this, {{ $i }})">
                                     <label for="picture{{ $i }}" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-200" id="label{{ $i }}">
                                         <div class="flex flex-col items-center justify-center pt-5 pb-6 {{ $pic ? 'hidden' : '' }}" id="uploadContent{{ $i }}">
                                             <i data-lucide="plus" class="w-8 h-8 mb-2 text-gray-400"></i>
                                             <p class="mb-2 text-sm text-gray-500">Photo {{ $i }}</p>
                                         </div>
                                         <div id="preview{{ $i }}" class="{{ $pic ? '' : 'hidden' }} w-full h-full">
                                             <img id="img{{ $i }}" class="w-full h-32 object-cover rounded-lg" @if($pic) src="{{ route('media', ['path' => ltrim($pic, '/')]) }}" @endif>
                                             <button type="button" onclick="removeImage({{ $i }})" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600">×</button>
                                             @if($pic)
                                                 <input type="hidden" name="existing_pictures[]" value="{{ $pic }}" class="existing-picture-input">
                                             @endif
                                         </div>
                                     </label>
                                 </div>
                             @endfor
                         </div>
                         <p class="mt-2 text-sm text-gray-500">Formats acceptés: JPG, PNG, GIF. Taille max: 5MB par image.</p>
                         @error('pictures.*')
                             <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                         @enderror
                     </div>
                </div>

                <!-- Declared Value Section -->
                <div class="mb-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="bg-emerald-100 rounded-lg p-2">
                            <i data-lucide="dollar-sign" class="w-5 h-5 text-emerald-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Valeur déclarée</h3>
                    </div>
                    
                    <div>
                        <label for="declared_value" class="block text-sm font-medium text-gray-700 mb-2">Valeur en DH</label>
                        <div class="flex items-center gap-4">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-medium">DH</span>
                                </div>
                    <input type="number" name="declared_value" id="declared_value" 
                                       value="{{ isset($announcement) && $announcement->details ? $announcement->details->declared_value : old('declared_value') }}" step="0.01" min="0" max="100000"
                                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('declared_value') border-red-500 focus:ring-red-500 @enderror transition-colors duration-200 text-gray-900 placeholder-gray-400"
                                       placeholder="500.00">
                            </div>
                            <button type="button" id="geminiSuggestBtn"
                                    class="inline-flex items-center px-4 py-3 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200 shadow-sm">
                                <i data-lucide="brain" class="w-4 h-4 mr-2"></i>
                                IA
                            </button>
                        </div>
                        <p id="geminiStatus" class="mt-2 text-sm text-gray-500 hidden"></p>
                    @error('declared_value')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    </div>
                </div>

                <!-- Additional Info Section -->
                <div class="mb-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="bg-yellow-100 rounded-lg p-2">
                            <i data-lucide="lightbulb" class="w-5 h-5 text-yellow-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Instructions</h3>
                </div>

                    <div>
                        <label for="additional_info" class="block text-sm font-medium text-gray-700 mb-2">Spéciales</label>
                        <textarea name="additional_info" id="additional_info" rows="2"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('additional_info') border-red-500 focus:ring-red-500 @enderror transition-colors duration-200 text-gray-900 placeholder-gray-400 resize-none"
                                  placeholder="Instructions spéciales...">{{ isset($announcement) && $announcement->details ? $announcement->details->additional_info : old('additional_info') }}</textarea>
                    @error('additional_info')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    </div>
                </div>

                <!-- Gemini Error Modal -->
                <div id="geminiErrorModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full hidden z-50">
                    <div class="relative top-20 mx-auto p-6 border-0 w-11/12 md:w-3/4 lg:w-1/2 shadow-xl rounded-lg bg-white">
                        <div class="mt-3">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                    <div class="p-3 bg-red-100 rounded-lg mr-3">
                                        <i data-lucide="alert-triangle" class="w-6 h-6 text-red-600"></i>
                                    </div>
                                    Erreur Gemini
                                </h3>
                                <button id="closeErrorModal" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                                    <i data-lucide="x" class="w-6 h-6"></i>
                                </button>
                            </div>
                            
                            <div class="mb-6">
                                <p class="text-sm text-gray-600 mb-4">Gemini n'a pas pu suggérer une valeur.</p>
                                <div id="errorDetails" class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm font-mono text-gray-800 max-h-96 overflow-y-auto">
                                    <!-- Error details will be populated here -->
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                                <button id="copyErrorDetails" class="inline-flex items-center justify-center px-4 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-colors duration-200">
                                    <i data-lucide="copy" class="w-4 h-4 mr-2"></i>
                                    Copier
                                </button>
                                <button id="closeErrorModalBtn" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 transition-colors duration-200">
                                    <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                    Fermer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons Section -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 sm:space-x-4">
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i data-lucide="shield-check" class="w-5 h-5 text-blue-600"></i>
                            <span>Sécurisé et confidentiel</span>
                </div>

                        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('dashboard.index') }}" 
                               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-colors duration-200">
                                <i data-lucide="x" class="w-5 h-5 mr-2"></i>
                        Annuler
                    </a>
                    <button type="submit" 
                                    class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <i data-lucide="{{ isset($announcement) ? 'save' : 'plus' }}" class="w-5 h-5 mr-2"></i>
                                {{ isset($announcement) ? 'Mettre à jour' : 'Créer l\'annonce' }}
                    </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
(function() {
  // Alpine city combobox component
  document.addEventListener('alpine:init', () => {
    window.cityPicker = ({ fieldId, placeholder = '', initial = '' }) => ({
      placeholder,
      query: initial || '',
      open: false,
      active: 0,
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
      init() {
        this.filter();
        // Add manual click outside handler
        this.setupClickOutside();
      },
      normalize(v) {
        // Lowercase and strip diacritics for broad browser support
        return (v || '').toString().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
      },
      filter() {
        const q = this.normalize(this.query);
        this.filtered = !q ? this.all : this.all.filter(c => this.normalize(c).includes(q)).slice(0, 50);
        this.active = 0;
      },
      move(d) {
        if (!this.filtered.length) return;
        this.active = (this.active + d + this.filtered.length) % this.filtered.length;
      },
             choose(val) {
         this.query = val;
         this.open = false;
         // sync value back to input for server submit
         const el = document.getElementById(fieldId);
         if (el) el.value = val;
       },
       // Keep list open when clicking on items
       onItemClick(val) {
         this.choose(val);
       },
      selectActive() {
        if (this.filtered[this.active]) this.choose(this.filtered[this.active]);
      },
             // Allow typing and searching
       onInput() {
         // Filter cities based on what user types
         this.filter();
       },
       // Manual click outside handler with delay
       setupClickOutside() {
         setTimeout(() => {
           document.addEventListener('click', (e) => {
             const input = document.getElementById(fieldId);
             const list = input?.parentElement?.querySelector('ul');
             
             if (input && list && this.open) {
               // Don't close if clicking on input or list
               if (input.contains(e.target) || list.contains(e.target)) {
                 return;
               }
               
               // Close with delay to prevent immediate closing
               setTimeout(() => {
                 this.open = false;
               }, 200);
             }
           });
         }, 100);
                }
       });
     });

     // Alpine item type picker component
     window.itemTypePicker = ({ fieldId, placeholder = '', initial = '' }) => ({
       placeholder,
       query: initial || '',
       open: false,
       active: 0,
       all: [
         '📄 Documents',
         '👕 Vêtements', 
         '📱 Électronique',
         '🍎 Nourriture',
         '💊 Médicaments',
         '📦 Autre'
       ],
       filtered: [],
       init() {
         this.filter();
         this.setupClickOutside();
       },
       normalize(v) {
         return (v || '').toString().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
       },
       filter() {
         const q = this.normalize(this.query);
         this.filtered = !q ? this.all : this.all.filter(item => this.normalize(item).includes(q));
         this.active = 0;
       },
       move(d) {
         if (!this.filtered.length) return;
         this.active = (this.active + d + this.filtered.length) % this.filtered.length;
       },
       choose(val) {
         this.query = val;
         this.open = false;
         const el = document.getElementById(fieldId);
         if (el) el.value = val;
       },
       onItemClick(val) {
         this.choose(val);
       },
       selectActive() {
         if (this.filtered[this.active]) this.choose(this.filtered[this.active]);
       },
       onInput() {
         this.filter();
       },
       setupClickOutside() {
         setTimeout(() => {
           document.addEventListener('click', (e) => {
             const input = document.getElementById(fieldId);
             const list = input?.parentElement?.querySelector('ul');
             
             if (input && list && this.open) {
               if (input.contains(e.target) || list.contains(e.target)) {
                 return;
               }
               
               setTimeout(() => {
                 this.open = false;
               }, 200);
             }
           });
         }, 100);
       }
     });

  // Enhanced swap cities functionality
  const swapBtn = document.getElementById('swapCitiesBtn');
  const pickupEl = document.getElementById('pickup_city');
  const deliveryEl = document.getElementById('delivery_city');
  
  if (swapBtn && pickupEl && deliveryEl) {
    swapBtn.addEventListener('click', function() {
      // Add animation class
      swapBtn.classList.add('animate-pulse');
      
      // Get current values
      const pickupValue = pickupEl.value;
      const deliveryValue = deliveryEl.value;
      
      // Only swap if both fields have values
      if (pickupValue && deliveryValue) {
        // Animate the swap with a brief delay
        setTimeout(() => {
          // Update Alpine.js data for pickup city
          const pickupCityPicker = Alpine.$data(pickupEl.closest('[x-data]'));
          if (pickupCityPicker) {
            pickupCityPicker.query = deliveryValue;
            pickupCityPicker.filter();
          }
          
          // Update Alpine.js data for delivery city
          const deliveryCityPicker = Alpine.$data(deliveryEl.closest('[x-data]'));
          if (deliveryCityPicker) {
            deliveryCityPicker.query = pickupValue;
            deliveryCityPicker.filter();
          }
          
          // Update actual input values
          pickupEl.value = deliveryValue;
          deliveryEl.value = pickupValue;
          
          // Show success feedback
          swapBtn.innerHTML = `
            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Échangé !
          `;
          swapBtn.classList.remove('animate-pulse');
          swapBtn.classList.add('bg-green-50', 'border-green-300', 'text-green-600');
          
          // Reset button after 2 seconds
          setTimeout(() => {
            swapBtn.innerHTML = `
              <svg class="w-4 h-4 mr-2 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4"/>
              </svg>
              <span class="relative">
                Intervertir
                <span class="absolute -top-1 -right-1 w-2 h-2 bg-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
              </span>
            `;
            swapBtn.classList.remove('bg-green-50', 'border-green-300', 'text-green-600');
          }, 2000);
        }, 300);
      } else {
        // Show error if one field is empty
        swapBtn.innerHTML = `
          <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          Remplir les 2 villes
        `;
        swapBtn.classList.remove('animate-pulse');
        swapBtn.classList.add('bg-red-50', 'border-red-300', 'text-red-600');
        
        // Reset button after 2 seconds
        setTimeout(() => {
          swapBtn.innerHTML = `
            <svg class="w-4 h-4 mr-2 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4"/>
            </svg>
            <span class="relative">
              Intervertir
              <span class="absolute -top-1 -right-1 w-2 h-2 bg-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
            </span>
          `;
          swapBtn.classList.remove('bg-red-50', 'border-red-300', 'text-red-600');
        }, 2000);
      }
    });
  }

  const suggestBtn = document.getElementById('geminiSuggestBtn');
  const statusEl = document.getElementById('geminiStatus');
  const declaredEl = document.getElementById('declared_value');
  // Use backend proxy to avoid CORS and keep key server-side
  const endpoint = '{{ route('ai.suggest-declared-value') }}';

  function setStatus(msg, type) {
    if (!statusEl) return;
    statusEl.textContent = msg;
    statusEl.classList.remove('hidden', 'text-red-600', 'text-green-600', 'text-gray-500');
    statusEl.classList.add(type === 'error' ? 'text-red-600' : (type === 'success' ? 'text-green-600' : 'text-gray-500'));
  }

  function buildPrompt() {
    const data = {
      pickup_city: pickupEl?.value?.trim() || '',
      delivery_city: deliveryEl?.value?.trim() || '',
      preferred_date: document.getElementById('preferred_date')?.value || '',
      package_weight: document.getElementById('package_weight')?.value || '',
      item_type: document.getElementById('item_type')?.value || '',
      dimensions: document.getElementById('dimensions')?.value || '',
      description: document.getElementById('description')?.value || '',
      additional_info: document.getElementById('additional_info')?.value || ''
    };

    const prompt = `You are an expert e-commerce appraiser estimating realistic DECLARED VALUE for shipping insurance in Moroccan Dirhams (MAD).

Context (JSON): ${JSON.stringify(data)}

Strict instructions:
- Output ONLY strict JSON with a single numeric field in MAD, no text, no units, no explanation.
- The exact schema must be: {"declared_value_mad": <number>} with a reasonable integer or decimal.
- Consider: item type, weight, dimensions, route (from pickup_city to delivery_city), urgency (preferred_date), and description.
- Be conservative and avoid inflated prices. If insufficient info, infer a typical reasonable value for Morocco market.
- Do not include any other keys or commentary. Do not hallucinate brands or models.
`;
    return prompt;
  }

  async function suggestDeclaredValue() {
    if (!suggestBtn || !declaredEl) return;
    try {
      suggestBtn.disabled = true;
      setStatus('Suggestion en cours…', 'info');

      const payload = {
        pickup_city: pickupEl?.value?.trim() || '',
        delivery_city: deliveryEl?.value?.trim() || '',
        preferred_date: document.getElementById('preferred_date')?.value || '',
        package_weight: document.getElementById('package_weight')?.value || '',
        item_type: document.getElementById('item_type')?.value || '',
        dimensions: document.getElementById('dimensions')?.value || '',
        description: document.getElementById('description')?.value || '',
        additional_info: document.getElementById('additional_info')?.value || '',
        no_fallback: true
      };

      const res = await fetch(endpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(payload)
      });

      let data = null;
      try { data = await res.json(); } catch (_) {}
      
      // Check for success case first
      if (data && typeof data.declared_value_mad !== 'undefined') {
        const value = Number(data.declared_value_mad);
        if (Number.isFinite(value) && value >= 0) {
          declaredEl.value = value.toFixed(value % 1 === 0 ? 0 : 2);
          const source = data.source ? String(data.source) : 'unknown';
          const label = source.includes('gemini') ? 'AI (Gemini)' : 'Fallback';
          setStatus(`Valeur suggérée (${label}). Vous pouvez la modifier avant de soumettre.`, 'success');
          return;
        }
      }
      
             // Handle error cases with detailed popup
       if (data && data.error) {
         showErrorModal(data);
         setStatus(`Suggestion indisponible (${data.error}). Veuillez renseigner manuellement.`, 'error');
         return;
       }
      
      // Handle HTTP errors
      if (!res.ok) {
        const code = data && data.error ? String(data.error) : `HTTP ${res.status}`;
        setStatus(`Suggestion indisponible (${code}). Veuillez renseigner manuellement.`, 'error');
        return;
      }

      setStatus('Suggestion indisponible. Veuillez renseigner manuellement.', 'error');
    } catch (err) {
      setStatus('Erreur lors de la suggestion. Veuillez réessayer.', 'error');
    } finally {
      if (suggestBtn) suggestBtn.disabled = false;
    }
  }

     if (suggestBtn) {
     suggestBtn.addEventListener('click', suggestDeclaredValue);
   }

   // Modal functions
   function showErrorModal(data) {
     const modal = document.getElementById('geminiErrorModal');
     const errorDetails = document.getElementById('errorDetails');
     
     if (!modal || !errorDetails) return;
     
     let detailsHtml = `<div class="space-y-3">`;
     detailsHtml += `<div><strong>Type d'erreur:</strong> <span class="text-red-600">${data.error}</span></div>`;
     
     if (data.endpoint) {
       detailsHtml += `<div><strong>Endpoint:</strong> <span class="text-blue-600">${data.endpoint}</span></div>`;
     }
     
     if (data.debug) {
       detailsHtml += `<div class="border-t pt-3"><strong>Informations de débogage:</strong></div>`;
       
       if (data.debug.attempt1_status) {
         detailsHtml += `<div class="ml-4"><strong>Tentative 1:</strong></div>`;
         detailsHtml += `<div class="ml-6 text-xs">Status: <span class="font-mono">${data.debug.attempt1_status}</span></div>`;
         if (data.debug.attempt1_body) {
           detailsHtml += `<div class="ml-6 text-xs">Réponse: <span class="font-mono bg-gray-100 px-1 rounded">${data.debug.attempt1_body}</span></div>`;
         }
         if (data.debug.attempt1_text) {
           detailsHtml += `<div class="ml-6 text-xs">Texte: <span class="font-mono bg-gray-100 px-1 rounded">${data.debug.attempt1_text}</span></div>`;
         }
       }
       
       if (data.debug.attempt2_status) {
         detailsHtml += `<div class="ml-4 mt-2"><strong>Tentative 2:</strong></div>`;
         detailsHtml += `<div class="ml-6 text-xs">Status: <span class="font-mono">${data.debug.attempt2_status}</span></div>`;
         if (data.debug.attempt2_body) {
           detailsHtml += `<div class="ml-6 text-xs">Réponse: <span class="font-mono bg-gray-100 px-1 rounded">${data.debug.attempt2_body}</span></div>`;
         }
         if (data.debug.attempt2_text) {
           detailsHtml += `<div class="ml-6 text-xs">Texte: <span class="font-mono bg-gray-100 px-1 rounded">${data.debug.attempt2_text}</span></div>`;
         }
       }
       
       if (data.debug.exception) {
         detailsHtml += `<div class="ml-4 mt-2"><strong>Exception:</strong></div>`;
         detailsHtml += `<div class="ml-6 text-xs font-mono bg-red-50 px-2 py-1 rounded border">${data.debug.exception}</div>`;
       }
     }
     
     detailsHtml += `</div>`;
     errorDetails.innerHTML = detailsHtml;
     
     modal.classList.remove('hidden');
   }

   function hideErrorModal() {
     const modal = document.getElementById('geminiErrorModal');
     if (modal) {
       modal.classList.add('hidden');
     }
   }

   // Modal event handlers
   document.getElementById('closeErrorModal')?.addEventListener('click', hideErrorModal);
   document.getElementById('closeErrorModalBtn')?.addEventListener('click', hideErrorModal);
   
   // Close modal when clicking outside
   document.getElementById('geminiErrorModal')?.addEventListener('click', function(e) {
     if (e.target === this) {
       hideErrorModal();
     }
   });

   // Copy error details to clipboard
   document.getElementById('copyErrorDetails')?.addEventListener('click', function() {
     const errorDetails = document.getElementById('errorDetails');
     if (errorDetails) {
       navigator.clipboard.writeText(errorDetails.textContent || '').then(() => {
         const btn = this;
         const originalText = btn.innerHTML;
         btn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Copié!';
         btn.classList.add('bg-green-50', 'text-green-700', 'border-green-300');
         setTimeout(() => {
           btn.innerHTML = originalText;
           btn.classList.remove('bg-green-50', 'text-green-700', 'border-green-300');
         }, 2000);
       });
     }
   });
   })();

   // Image handling functions - Global scope (outside the IIFE)
   function previewImage(input, number) {
     if (input.files && input.files[0]) {
       const file = input.files[0];
       
       // Validate file size (5MB max)
       if (file.size > 5 * 1024 * 1024) {
         alert('L\'image est trop volumineuse. Taille maximum: 5MB');
         input.value = '';
         return;
       }
       
       // Validate file type
       if (!file.type.startsWith('image/')) {
         alert('Veuillez sélectionner une image valide');
         input.value = '';
         return;
       }
       
       const reader = new FileReader();
       reader.onload = function(e) {
         document.getElementById(`img${number}`).src = e.target.result;
         document.getElementById(`preview${number}`).classList.remove('hidden');
         document.getElementById(`uploadContent${number}`).classList.add('hidden');
       };
       reader.readAsDataURL(file);
     }
   }
   
   function removeImage(number) {
        const fileInput = document.getElementById(`picture${number}`);
        const preview = document.getElementById(`preview${number}`);
        const img = document.getElementById(`img${number}`);
        const label = document.getElementById(`uploadContent${number}`);
        // Clear file input
        fileInput.value = '';
        // Remove any hidden existing picture input within this slot
        const hiddenExisting = preview.querySelector('input.existing-picture-input');
        if (hiddenExisting) hiddenExisting.remove();
        // Reset UI
        preview.classList.add('hidden');
        img.src = '';
        if (label) label.classList.remove('hidden');
   }
   
   function removeExistingImage(index) {
     const container = event.target.closest('.relative');
     if (container) {
       // Remove the hidden input for this picture
       const hiddenInput = container.querySelector('input[name="existing_pictures[]"]');
       if (hiddenInput) {
         hiddenInput.remove();
       }
       // Remove the container
       container.remove();
     }
   }
</script>
<script>
  // Safety: ensure no global JS prevents the form submission on this page
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action*="sender-announcement"]');
    if (form) {
      // Capture-phase listener that blocks other submit listeners from running
      form.addEventListener('submit', function(e) {
        // Allow default submission, but stop other JS handlers (that may call preventDefault)
        e.stopImmediatePropagation();
      }, true);
    }
  });
</script>
@endpush
@endsection 