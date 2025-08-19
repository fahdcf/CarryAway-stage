@extends('layouts.app')

@section('title', 'Suivi de la demande - CarryWay')

@section('content')
<div class="min-h-full" x-data="{ showUpdateModal: false }">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200 mb-6">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('messages.show', $userRequest->chat->id) }}" class="text-blue-600 hover:text-blue-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Suivi de la demande</h1>
                        <p class="text-gray-600 text-sm lg:text-base">
                            {{ $userRequest->traveler_announcement_id ? 'Voyage' : 'Livraison' }} • 
                            {{ $userRequest->traveler_announcement_id ? "{$announcement->departure_city} → {$announcement->arrival_city}" : "{$announcement->pickup_city} → {$announcement->delivery_city}" }}
                        </p>
                    </div>
                </div>
                
                <!-- Status Badge -->
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        {{ $userRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $userRequest->status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $userRequest->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $userRequest->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}">
                        @switch($userRequest->status)
                            @case('pending')
                                En attente
                                @break
                            @case('accepted')
                                Acceptée
                                @break
                            @case('rejected')
                                Refusée
                                @break
                            @case('completed')
                                Terminée
                                @break
                            @default
                                {{ ucfirst($userRequest->status) }}
                        @endswitch
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Progress Timeline -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Progression de la demande</h2>
                        @if($userRequest->status === 'accepted')
                            @php
                                $announcementType = null;
                                if ($userRequest->travelerAnnouncement) {
                                    $announcementType = 'travel';
                                } elseif ($userRequest->senderAnnouncement) {
                                    $announcementType = 'sender';
                                }
                                
                                $canUpdateProgress = false;
                                if ($announcementType === 'travel' && $isOwner) {
                                    $canUpdateProgress = true;
                                } elseif ($announcementType === 'sender' && !$isOwner) {
                                    $canUpdateProgress = true;
                                }
                            @endphp
                            
                            @if($canUpdateProgress)
                                <div class="flex space-x-3">
                                    <button id="openUpdateModalBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Mettre à jour
                                    </button>
                                </div>
                            @endif
                        @endif
                    </div>

                    @if($userRequest->progress->count() > 0)
                        <!-- Progress Timeline -->
                        <div class="space-y-6">
                            @foreach($userRequest->progress as $progress)
                                <div class="relative">
                                    @if(!$loop->last)
                                        <div class="absolute left-6 top-12 w-0.5 h-12 bg-gray-200"></div>
                                    @endif
                                    
                                    <div class="flex items-start space-x-4">
                                        <div class="w-12 h-12 rounded-full flex items-center justify-center 
                                            {{ $progress->status === 'termine' ? 'bg-green-100' : ($progress->status === 'retarde' ? 'bg-red-100' : 'bg-blue-100') }}">
                                            @if($progress->status === 'termine')
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @elseif($progress->status === 'retarde')
                                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $progress->stage_display_name }}</h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $progress->status === 'termine' ? 'bg-green-100 text-green-800' : ($progress->status === 'retarde' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                                    {{ $progress->status_display_name }}
                                                </span>
                                            </div>
                                            
                                            @if($progress->notes)
                                                <p class="text-gray-600 mt-2">{{ $progress->notes }}</p>
                                            @endif
                                            
                                            <p class="text-sm text-gray-400 mt-2">{{ $progress->created_at->format('d/m/Y à H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- No Progress Yet -->
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune progression</h3>
                            <p class="text-gray-500">La progression de cette demande n'a pas encore commencé.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Request Details Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Détails de la demande</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Demandé par</h4>
                            <p class="text-sm font-semibold text-gray-900">{{ $userRequest->initiator->name }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Date de création</h4>
                            <p class="text-sm font-semibold text-gray-900">{{ $userRequest->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        
                        @if($userRequest->package)
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Poids du colis</h4>
                                <p class="text-sm font-semibold text-gray-900">{{ number_format($userRequest->package->weight, 1) }} kg</p>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Type d'objet</h4>
                                <p class="text-sm font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $userRequest->package->item_type)) }}</p>
                            </div>
                            
                            @if($userRequest->package->dimensions)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Dimensions</h4>
                                    <p class="text-sm font-semibold text-gray-900">{{ $userRequest->package->dimensions }}</p>
                                </div>
                            @endif
                            
                            @if($userRequest->package->images && count($userRequest->package->images) > 0)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Photos du colis</h4>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach(array_slice($userRequest->package->images, 0, 4) as $image)
                                            <img src="{{ asset('storage/' . $image) }}" alt="Photo du colis" class="w-full h-20 object-cover rounded-lg">
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif
                        
                        @if($userRequest->proposed_price)
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Prix proposé</h4>
                                <p class="text-sm font-semibold text-gray-900">{{ number_format($userRequest->proposed_price, 2) }} DH</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Progress Modal -->
@if($userRequest->status === 'accepted')
    @php
        $announcementType = null;
        if ($userRequest->travelerAnnouncement) {
            $announcementType = 'travel';
        } elseif ($userRequest->senderAnnouncement) {
            $announcementType = 'sender';
        }
        
        $canUpdateProgress = false;
        if ($announcementType === 'travel' && $isOwner) {
            $canUpdateProgress = true;
        } elseif ($announcementType === 'sender' && !$isOwner) {
            $canUpdateProgress = true;
        }
    @endphp
    
    @if($canUpdateProgress)
    <div id="updateProgressModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Mettre à jour la progression</h3>
                        <p class="text-gray-100 text-sm">Ajouter une nouvelle étape</p>
                    </div>
                </div>
                <button id="closeUpdateModalBtn" type="button" class="p-2 text-white hover:bg-white hover:bg-opacity-10 rounded-full transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('requests.progress.update', $userRequest->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="stage" class="block text-sm font-medium text-gray-700 mb-2">Étape *</label>
                    <select name="stage" id="stage" required class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionnez une étape</option>
                        @foreach(App\Http\Controllers\RequestProgressController::getStages() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                    <select name="status" id="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionnez un statut</option>
                        @foreach(App\Http\Controllers\RequestProgressController::getStatuses() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (optionnel)</label>
                <textarea name="notes" id="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Ajoutez des détails sur cette étape..."></textarea>
            </div>

            <!-- Delivery Code Input (shown only when stage is "livraison" and status is "terminer") -->
            <div id="deliveryCodeSection" class="hidden">
                <label for="delivery_code" class="block text-sm font-medium text-gray-700 mb-2">Code de livraison *</label>
                <p class="text-sm text-gray-600 mb-3">Entrez le code de livraison fourni par l'expéditeur pour confirmer la livraison</p>
                <input type="text" name="delivery_code" id="delivery_code" maxlength="8" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('delivery_code') border-red-500 @enderror" placeholder="Code à 8 chiffres" @error('delivery_code') data-error="true" @enderror>
                @error('delivery_code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-3 pt-4 border-t border-gray-200">
                <button id="closeUpdateModalBtn2" type="button" class="flex-1 px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" class="flex-1 px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
    @endif
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const openBtn = document.getElementById('openUpdateModalBtn');
    const modal = document.getElementById('updateProgressModal');
    const closeBtn = document.getElementById('closeUpdateModalBtn');
    const closeBtn2 = document.getElementById('closeUpdateModalBtn2');
    const stageSelect = document.getElementById('stage');
    const statusSelect = document.getElementById('status');
    const deliveryCodeSection = document.getElementById('deliveryCodeSection');
    const deliveryCodeInput = document.getElementById('delivery_code');

    const open = () => { if (modal) modal.classList.remove('hidden'); };
    const close = () => { if (modal) modal.classList.add('hidden'); };

    if (openBtn) openBtn.addEventListener('click', open);
    if (closeBtn) closeBtn.addEventListener('click', close);
    if (closeBtn2) closeBtn2.addEventListener('click', close);

    if (modal) {
        modal.addEventListener('click', (e) => { if (e.target === modal) close(); });
    }

    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });

    // Handle delivery code section visibility
    function toggleDeliveryCodeSection() {
        const stage = stageSelect.value;
        const status = statusSelect.value;
        
        if (stage === 'livraison' && status === 'termine') {
            deliveryCodeSection.classList.remove('hidden');
            deliveryCodeInput.required = true;
        } else {
            deliveryCodeSection.classList.add('hidden');
            deliveryCodeInput.required = false;
            deliveryCodeInput.value = '';
        }
    }

    if (stageSelect) stageSelect.addEventListener('change', toggleDeliveryCodeSection);
    if (statusSelect) statusSelect.addEventListener('change', toggleDeliveryCodeSection);

    // Show delivery code section if there are validation errors
    if (document.querySelector('[name="delivery_code"]').hasAttribute('data-error')) {
        deliveryCodeSection.classList.remove('hidden');
        deliveryCodeInput.required = true;
    }
});
</script>
@endpush

@endsection
