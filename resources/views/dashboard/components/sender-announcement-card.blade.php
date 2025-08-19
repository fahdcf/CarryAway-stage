<div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 h-full" x-data="{ showModal: false, activeImage: '' }">
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
                        <!-- Debug: {{ $announcement->user->id }} -->
                        <a href="{{ route('profiles.show', $announcement->user->id) }}" 
                           class="inline-block px-2 py-1 rounded hover:text-blue-600 hover:bg-blue-50 transition-all duration-200 cursor-pointer border border-transparent hover:border-blue-200"
                           onclick="console.log('Profile link clicked:', '{{ route('profiles.show', $announcement->user->id) }}')">
                            {{ $announcement->user->name }}
                        </a>
                    </h3>
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
			<div class="mb-4 relative" x-data="{ expanded: false, overflow: false, h: 0 }" x-init="$nextTick(() => { h = $refs.desc.clientHeight; overflow = $refs.desc.scrollHeight > $refs.desc.clientHeight })">
				<p x-ref="desc"
				   class="text-sm text-gray-600"
				   x-bind:class="expanded ? 'overflow-y-auto pr-2' : ''"
				   x-bind:style="expanded ? ('height:' + h + 'px;') : 'display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;padding-right:64px;'">
					{{ $announcement->description }}
				</p>
				<button type="button" class="absolute bottom-0 right-0 text-xs text-blue-600 hover:underline bg-white bg-opacity-90 px-1" x-show="overflow && !expanded" @click="expanded = true">Voir plus</button>
				<button type="button" class="absolute top-0 right-0 text-[11px] text-blue-600 hover:underline bg-white bg-opacity-90 px-1" x-show="expanded" @click="expanded = false">Réduire</button>
			</div>
		@endif

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-200 mt-auto">
            @if($isOwn)
                <div class="flex space-x-2">
                    <a href="{{ route('dashboard.edit-sender-announcement', $announcement->id) }}" 
                       class="inline-flex items-center px-2 py-1 border border-blue-300 text-xs font-medium rounded text-blue-700 bg-white hover:bg-blue-50">
                        <i data-lucide="edit" class="w-3 h-3 mr-1"></i>
                        Modifier
                    </a>
                    <form action="{{ route('dashboard.toggle-announcement', ['type' => 'sender', 'id' => $announcement->id]) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                            {{ $announcement->is_active ? 'Désactiver' : 'Activer' }}
                        </button>
                    </form>
                    <form action="{{ route('dashboard.delete-announcement', ['type' => 'sender', 'id' => $announcement->id]) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-2 py-1 border border-red-300 text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50">
                            Supprimer
                        </button>
                    </form>
                </div>
            @else
                <div class="flex items-center space-x-2">
                    <button class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Demander cette trajectoire
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
            if (str_starts_with($srcNorm, 'public/')) {
                $srcNorm = substr($srcNorm, 7);
            } elseif (str_starts_with($srcNorm, 'storage/')) {
                $srcNorm = substr($srcNorm, 8);
            }
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
                            <p class="text-green-100 text-sm">{{ $announcement->user->name }}</p>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span class="text-gray-600">Type d'objet:</span>
                        <span class="font-medium text-gray-900">{{ $announcement->details->item_type }}</span>
                    </div>
                @endif

                @if($announcement->details && $announcement->details->dimensions)
                    <div class="flex items-center space-x-3 text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                        </svg>
                        <span class="text-gray-600">Dimensions:</span>
                        <span class="font-medium text-gray-900">{{ $announcement->details->dimensions }}</span>
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
</div> 