@extends('layouts.app')

@section('title', (isset($announcement) ? 'Modifier' : 'Créer') . ' une annonce de voyage - CarryWay')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Dashboard Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gray-900 rounded-lg p-3">
                        <i data-lucide="route" class="w-8 h-8 text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ isset($announcement) ? 'Modifier l\'annonce' : 'Créer une annonce de voyage' }}</h1>
                        <p class="text-gray-600 text-sm">{{ isset($announcement) ? 'Modifier votre voyage' : 'Annoncez votre voyage et proposez de transporter des colis' }}</p>
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
                        <h2 class="text-lg font-semibold">Détails du voyage</h2>
                        <p class="text-gray-300 text-sm">Informations requises</p>
                    </div>
                </div>
            </div>

            <form action="{{ isset($announcement) ? route('dashboard.update-traveler-announcement', $announcement->id) : route('dashboard.store-traveler-announcement') }}" method="POST" class="p-6">
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
                        <button type="button" id="swapCitiesBtnTravel" class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-blue-400 hover:text-blue-600 transition-all duration-200">
                            <i data-lucide="swap" class="w-4 h-4 mr-2"></i>
                            Échanger
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Departure City Combobox -->
                        <div x-data="cityPicker({ fieldId: 'departure_city', placeholder: 'Ville de départ', initial: '{{ isset($announcement) ? $announcement->departure_city : old('departure_city') }}' })" class="relative">
                            <label for="departure_city" class="block text-sm font-medium text-gray-700 mb-2">Ville de départ *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="map-pin" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <input x-model="query" @focus="open = true" @input="onInput()" @keydown.arrow-down.prevent="move(1)" @keydown.arrow-up.prevent="move(-1)" @keydown.enter.prevent="selectActive()" @click.away="open = false"
                                       type="text" name="departure_city" id="departure_city" :placeholder="placeholder"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('departure_city') border-red-500 focus:ring-red-500 @enderror transition-colors duration-200 text-gray-900 placeholder-gray-400" autocomplete="off">
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
                            @error('departure_city')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Arrival City Combobox -->
                        <div x-data="cityPicker({ fieldId: 'arrival_city', placeholder: 'Ville d\'arrivée', initial: '{{ isset($announcement) ? $announcement->arrival_city : old('arrival_city') }}' })" class="relative">
                            <label for="arrival_city" class="block text-sm font-medium text-gray-700 mb-2">Ville d'arrivée *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="navigation" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <input x-model="query" @focus="open = true" @input="onInput()" @keydown.arrow-down.prevent="move(1)" @keydown.arrow-up.prevent="move(-1)" @keydown.enter.prevent="selectActive()" @click.away="open = false"
                                       type="text" name="arrival_city" id="arrival_city" :placeholder="placeholder"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('arrival_city') border-red-500 focus:ring-red-500 @enderror transition-colors duration-200 text-gray-900 placeholder-gray-400" autocomplete="off">
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
                            @error('arrival_city')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Date and Time Setup -->
                <div class="mb-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="bg-purple-100 rounded-lg p-2">
                            <i data-lucide="calendar-clock" class="w-5 h-5 text-purple-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Dates et heures</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Departure Date & Time -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Départ *</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label for="departure_date" class="block text-xs font-medium text-gray-500 mb-1">Date</label>
                                    <input type="date" name="departure_date" id="departure_date" value="{{ isset($announcement) ? $announcement->departure_date->format('Y-m-d') : old('departure_date') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('departure_date') border-red-500 focus:ring-red-500 @enderror transition-colors duration-200 text-gray-900">
                                </div>
                                <div>
                                    <label for="departure_time" class="block text-xs font-medium text-gray-500 mb-1">Heure</label>
                                    <input type="time" name="departure_time" id="departure_time" value="{{ isset($announcement) && $announcement->details && $announcement->details->departure_time ? \Carbon\Carbon::parse($announcement->details->departure_time)->format('H:i') : old('departure_time') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('departure_time') border-red-500 focus:ring-red-500 @enderror transition-colors duration-200 text-gray-900" placeholder="HH:MM">
                                </div>
                            </div>
                            @error('departure_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('departure_time')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Arrival Date & Time -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Arrivée estimée</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label for="estimated_arrival_date" class="block text-xs font-medium text-gray-500 mb-1">Date</label>
                                    <input type="date" name="estimated_arrival_date" id="estimated_arrival_date" value="{{ isset($announcement) && $announcement->details && $announcement->details->estimated_arrival_date ? $announcement->details->estimated_arrival_date->format('Y-m-d') : old('estimated_arrival_date') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('estimated_arrival_date') border-red-500 focus:ring-red-500 @enderror transition-colors duration-200 text-gray-900">
                                </div>
                                <div>
                                    <label for="estimated_arrival_time" class="block text-xs font-medium text-gray-500 mb-1">Heure</label>
                                    <input type="time" name="estimated_arrival_time" id="estimated_arrival_time" value="{{ isset($announcement) && $announcement->details && $announcement->details->estimated_arrival_time ? \Carbon\Carbon::parse($announcement->details->estimated_arrival_time)->format('H:i') : old('estimated_arrival_time') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('estimated_arrival_time') border-red-500 focus:ring-red-500 @enderror transition-colors duration-200 text-gray-900" placeholder="HH:MM">
                                </div>
                            </div>
                            @error('estimated_arrival_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('estimated_arrival_time')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p id="datetime-help-text" class="mt-3 text-xs text-gray-500 flex items-center">
                        <i data-lucide="info" class="w-3 h-3 mr-1"></i>
                        <span>L'arrivée doit être après le départ (même jour possible avec heure postérieure)</span>
                    </p>
                </div>

                <!-- Transport Details -->
                <div class="mb-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="bg-indigo-100 rounded-lg p-2">
                            <i data-lucide="truck" class="w-5 h-5 text-indigo-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Transport</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Transport Mode -->
                        <div>
                            <label for="transport_mode" class="block text-sm font-medium text-gray-700 mb-2">Mode de transport</label>
                            <select name="transport_mode" id="transport_mode" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white pr-10">
                                <option value="">Sélectionner</option>
                                <option value="Voiture" {{ (isset($announcement) && $announcement->details ? $announcement->details->transport_mode : old('transport_mode')) == 'Voiture' ? 'selected' : '' }}>🚗 Voiture</option>
                                <option value="Bus" {{ (isset($announcement) && $announcement->details ? $announcement->details->transport_mode : old('transport_mode')) == 'Bus' ? 'selected' : '' }}>🚌 Bus</option>
                                <option value="Train" {{ (isset($announcement) && $announcement->details ? $announcement->details->transport_mode : old('transport_mode')) == 'Train' ? 'selected' : '' }}>🚆 Train</option>
                                <option value="Avion" {{ (isset($announcement) && $announcement->details ? $announcement->details->transport_mode : old('transport_mode')) == 'Avion' ? 'selected' : '' }}>✈️ Avion</option>
                                <option value="Autre" {{ (isset($announcement) && $announcement->details ? $announcement->details->transport_mode : old('transport_mode')) == 'Autre' ? 'selected' : '' }}>🚛 Autre</option>
                            </select>
                        </div>

                        <!-- Available Space -->
                        <div>
                            <label for="available_kg" class="block text-sm font-medium text-gray-700 mb-2">Espace disponible *</label>
                            <div class="relative">
                                <input type="number" name="available_kg" id="available_kg" value="{{ isset($announcement) ? $announcement->available_kg : old('available_kg') }}" step="0.1" min="0.1" max="100" class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('available_kg') border-red-500 focus:ring-red-500 @enderror transition-colors duration-200 text-gray-900" placeholder="Ex: 5.5">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm font-medium">kg</span>
                                </div>
                            </div>
                            @error('available_kg')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="bg-teal-100 rounded-lg p-2">
                            <i data-lucide="file-text" class="w-5 h-5 text-teal-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Description</h3>
                    </div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Détails du voyage</label>
                    <textarea name="description" id="description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 focus:ring-red-500 @enderror" placeholder="Décrivez votre voyage, les conditions, etc.">{{ isset($announcement) ? $announcement->description : old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price with Gemini suggestion -->
                <div class="mb-6">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="bg-emerald-100 rounded-lg p-2">
                            <i data-lucide="dollar-sign" class="w-5 h-5 text-emerald-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Tarification</h3>
                    </div>
                    <label for="price_per_kg" class="block text-sm font-medium text-gray-700 mb-2">Prix par kg (DH)</label>
                    <div class="flex items-stretch gap-0">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-500">DH/kg</span>
                            </div>
                            <input type="number" name="price_per_kg" id="price_per_kg" value="{{ isset($announcement) && $announcement->details ? $announcement->details->price_per_kg : old('price_per_kg') }}" step="0.01" min="0" max="1000" class="w-full pl-16 pr-4 py-3 border border-gray-300 rounded-l-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price_per_kg') border-red-500 focus:ring-red-500 @enderror" placeholder="Ex: 15.50">
                        </div>
                        <button type="button" id="geminiSuggestPriceBtn" class="inline-flex items-center px-4 py-3 rounded-r-lg border border-l-0 border-blue-600 bg-blue-600 text-white font-medium hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200 shadow-sm">
                            <i data-lucide="brain" class="w-4 h-4 mr-2"></i>
                            IA
                        </button>
                    </div>
                    <p id="geminiPriceStatus" class="mt-2 text-sm text-gray-500 hidden"></p>
                    @error('price_per_kg')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="bg-yellow-100 rounded-lg p-2">
                            <i data-lucide="sticky-note" class="w-5 h-5 text-yellow-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Notes supplémentaires</h3>
                    </div>
                    <label for="extra_notes" class="block text-sm font-medium text-gray-700 mb-2">Détails complémentaires</label>
                    <textarea name="extra_notes" id="extra_notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('extra_notes') border-red-500 @enderror" placeholder="Informations supplémentaires, conditions spéciales, etc.">{{ isset($announcement) && $announcement->details ? $announcement->details->extra_notes : old('extra_notes') }}</textarea>
                    @error('extra_notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons Section -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 sm:space-x-4">
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i data-lucide="shield-check" class="w-5 h-5 text-blue-600"></i>
                            <span>Sécurisé et confidentiel</span>
                        </div>
                        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('dashboard.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-colors duration-200">
                                <i data-lucide="x" class="w-5 h-5 mr-2"></i>
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
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
  // Use cityPicker if already defined (from sender page). Minimal fallback otherwise.
  if (!window.cityPicker) {
    document.addEventListener('alpine:init', () => {
      window.cityPicker = ({ fieldId, placeholder = '', initial = '' }) => ({
        placeholder, query: initial || '', open: false, active: 0,
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
        init(){ this.filter(); },
        normalize(v){ return (v||'').toString().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,''); },
        filter(){ const q=this.normalize(this.query); this.filtered=!q?this.all:this.all.filter(c=>this.normalize(c).includes(q)).slice(0,50); this.active=0; },
        move(d){ if(!this.filtered.length) return; this.active=(this.active+d+this.filtered.length)%this.filtered.length; },
        choose(val){ this.query=val; this.open=false; const el=document.getElementById(fieldId); if(el) el.value=val; },
        onItemClick(val){ this.choose(val); },
        selectActive(){ if(this.filtered[this.active]) this.choose(this.filtered[this.active]); },
        onInput(){ this.filter(); }
      });
    });
  }

  // Swap cities
  const swapBtn = document.getElementById('swapCitiesBtnTravel');
  const dep = document.getElementById('departure_city');
  const arr = document.getElementById('arrival_city');
  if (swapBtn && dep && arr) {
    swapBtn.addEventListener('click', () => {
      const a = dep.value, b = arr.value; if (!a || !b) return;
      const depPicker = Alpine.$data(dep.closest('[x-data]')); if (depPicker) { depPicker.query = b; depPicker.filter(); }
      const arrPicker = Alpine.$data(arr.closest('[x-data]')); if (arrPicker) { arrPicker.query = a; arrPicker.filter(); }
      dep.value = b; arr.value = a;
    });
  }

  // Gemini price per kg
  const priceBtn = document.getElementById('geminiSuggestPriceBtn');
  const statusEl = document.getElementById('geminiPriceStatus');
  const priceEl = document.getElementById('price_per_kg');
  const endpoint = '{{ route('ai.suggest-price-per-kg') }}';

  function setStatus(msg, type) {
    if (!statusEl) return; statusEl.textContent = msg; statusEl.classList.remove('hidden','text-red-600','text-green-600','text-gray-500'); statusEl.classList.add(type==='error'?'text-red-600':(type==='success'?'text-green-600':'text-gray-500'));
  }

  async function suggestPrice() {
    if (!priceBtn || !priceEl) return;
    try {
      priceBtn.disabled = true; setStatus('Suggestion en cours…','info');
      const payload = {
        departure_city: dep?.value || '',
        arrival_city: arr?.value || '',
        departure_date: document.getElementById('departure_date')?.value || '',
        available_kg: document.getElementById('available_kg')?.value || '',
        transport_mode: document.getElementById('transport_mode')?.value || '',
        description: document.getElementById('description')?.value || '',
        no_fallback: true,
      };
      const res = await fetch(endpoint, { method:'POST', headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify(payload) });
      let data = null; try { data = await res.json(); } catch(_) {}
      if (data && typeof data.price_per_kg_mad !== 'undefined') {
        const value = Number(data.price_per_kg_mad);
        if (Number.isFinite(value) && value >= 0) { priceEl.value = value.toFixed(value % 1 === 0 ? 0 : 2); setStatus('Prix suggéré (AI). Modifiable avant soumission.','success'); return; }
      }
      if (data && data.error) { setStatus('Suggestion indisponible ('+data.error+'). Renseignez manuellement.','error'); return; }
      if (!res.ok) { setStatus('Suggestion indisponible (HTTP '+res.status+').','error'); return; }
      setStatus('Suggestion indisponible.','error');
    } catch (e) {
      setStatus('Erreur lors de la suggestion. Réessayez.','error');
    } finally { priceBtn.disabled = false; }
  }

  if (priceBtn) priceBtn.addEventListener('click', suggestPrice);

  // Enhanced datetime validation
  const departureDateEl = document.getElementById('departure_date');
  const departureTimeEl = document.getElementById('departure_time');
  const arrivalDateEl = document.getElementById('estimated_arrival_date');
  const arrivalTimeEl = document.getElementById('estimated_arrival_time');

  function validateDateTime() {
    if (departureDateEl && departureTimeEl && arrivalDateEl && arrivalTimeEl) {
      const departureDate = departureDateEl.value;
      const departureTime = departureTimeEl.value;
      const arrivalDate = arrivalDateEl.value;
      const arrivalTime = arrivalTimeEl.value;
      
      // Reset validation styles
      departureTimeEl.classList.remove('border-red-300', 'border-yellow-300');
      arrivalDateEl.classList.remove('border-red-300', 'border-yellow-300');
      arrivalTimeEl.classList.remove('border-red-300', 'border-yellow-300');
      departureTimeEl.setCustomValidity('');
      arrivalDateEl.setCustomValidity('');
      arrivalTimeEl.setCustomValidity('');
      
      if (departureDate && arrivalDate) {
        // Arrival date must be >= departure date
        if (arrivalDate < departureDate) {
          arrivalDateEl.classList.add('border-red-300');
          arrivalDateEl.setCustomValidity('La date d\'arrivée doit être égale ou postérieure à la date de départ');
          return;
        }
        
        // If same day, both times required and arrival time must be after departure time
        if (arrivalDate === departureDate) {
          // Require both times for same day
          if (!departureTime || !arrivalTime) {
            if (!departureTime) {
              departureTimeEl.classList.add('border-yellow-300');
              departureTimeEl.setCustomValidity('L\'heure de départ est requise pour une livraison le même jour');
            }
            if (!arrivalTime) {
              arrivalTimeEl.classList.add('border-yellow-300');
              arrivalTimeEl.setCustomValidity('L\'heure d\'arrivée est requise pour une livraison le même jour');
            }
            return;
          }
          
          // Validate time sequence for same day
          const depDateTime = new Date(`${departureDate}T${departureTime}`);
          const arrDateTime = new Date(`${arrivalDate}T${arrivalTime}`);
          
          if (arrDateTime <= depDateTime) {
            arrivalTimeEl.classList.add('border-red-300');
            arrivalTimeEl.setCustomValidity('L\'heure d\'arrivée doit être postérieure à l\'heure de départ');
            return;
          }
        }
      }
      
      // Update help text
      const helpText = document.getElementById('datetime-help-text');
      const helpTextSpan = helpText?.querySelector('span');
      if (helpText && helpTextSpan && departureDate && arrivalDate && departureDate === arrivalDate) {
        helpText.classList.add('text-blue-600', 'font-medium');
        helpTextSpan.textContent = 'Livraison le même jour - vérifiez que l\'heure d\'arrivée soit après le départ';
      } else if (helpText && helpTextSpan) {
        helpText.classList.remove('text-blue-600', 'font-medium');
        helpTextSpan.textContent = 'L\'arrivée doit être après le départ (même jour possible avec heure postérieure)';
      }
    }
  }

  // Auto-fill arrival date when departure date is selected
  function autoFillArrivalDate() {
    if (departureDateEl && arrivalDateEl && departureDateEl.value && !arrivalDateEl.value) {
      arrivalDateEl.value = departureDateEl.value;
      validateDateTime();
    }
  }

  // Add event listeners
  if (departureDateEl) {
    departureDateEl.addEventListener('change', function() {
      autoFillArrivalDate();
      validateDateTime();
    });
  }
  
  if (departureTimeEl) {
    departureTimeEl.addEventListener('change', validateDateTime);
  }
  
  if (arrivalDateEl) {
    arrivalDateEl.addEventListener('change', validateDateTime);
  }
  
  if (arrivalTimeEl) {
    arrivalTimeEl.addEventListener('change', validateDateTime);
  }

  // Initialize validation on page load
  validateDateTime();
})();
</script>
@endpush
@endsection

