@extends('layouts.app')

@section('title', 'Mes demandes - CarryWay')

@section('content')
<div class="min-h-full">
    <!-- Dashboard Header -->
    <div class="bg-white shadow-sm border-b border-gray-200 mb-6">
        <div class="px-6 py-4">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Mes demandes</h1>
                    <p class="text-gray-600 text-sm lg:text-base">Gérez vos demandes de transport et de livraison</p>
                </div>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                    <button class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nouvelle demande
                    </button>
                </div>
            </div>
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

    <!-- Search and Filter Section -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Rechercher et filtrer</h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('requests.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search Input -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ville, description...">
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type de demande</label>
                        <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les types</option>
                            <option value="travel" {{ request('type') == 'travel' ? 'selected' : '' }}>Demande de voyage</option>
                            <option value="delivery" {{ request('type') == 'delivery' ? 'selected' : '' }}>Demande de livraison</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les statuts</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Acceptée</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Refusée</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminée</option>
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Trier par</label>
                        <select name="sort" id="sort" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Plus récentes</option>
                            <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Plus anciennes</option>
                            <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Date de départ</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Rechercher
                    </button>
                    
                    @if(request('search') || request('type') || request('status') || request('sort'))
                        <a href="{{ route('requests.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                            Effacer les filtres
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Results Summary -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">
                Résultats ({{ $requests->count() }} demandes)
            </h2>
            <div class="text-sm text-gray-500">
                {{ $requests->where('type', 'travel')->count() }} voyages • {{ $requests->where('type', 'delivery')->count() }} livraisons
            </div>
        </div>
    </div>

    <!-- Requests List -->
    <div class="space-y-4">
        <!-- Sample Travel Request -->
        <div class="bg-white shadow rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Voyage
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                En attente
                            </span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Demande de voyage Paris → Lyon</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600 mb-4">
                            <div>
                                <span class="font-medium">Date de départ:</span> 15 Janvier 2024
                            </div>
                            <div>
                                <span class="font-medium">Poids:</span> 3.5 kg
                            </div>
                            <div>
                                <span class="font-medium">Prix proposé:</span> 25€
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4">
                            Je recherche un transport pour un petit colis de 3.5kg de Paris à Lyon. 
                            Le colis contient des documents importants et doit arriver avant le 16 janvier.
                        </p>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span>Demande créée le 10 Janvier 2024</span>
                            <span>•</span>
                            <span>2 réponses reçues</span>
                        </div>
                    </div>
                    <div class="flex flex-col space-y-2 ml-4">
                        <button class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Voir les réponses
                        </button>
                        <button class="inline-flex items-center px-3 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sample Delivery Request -->
        <div class="bg-white shadow rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Livraison
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Acceptée
                            </span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Demande de livraison Marseille → Nice</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600 mb-4">
                            <div>
                                <span class="font-medium">Date souhaitée:</span> 20 Janvier 2024
                            </div>
                            <div>
                                <span class="font-medium">Poids:</span> 8 kg
                            </div>
                            <div>
                                <span class="font-medium">Prix accepté:</span> 40€
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4">
                            Transport d'un colis fragile de 8kg de Marseille à Nice. 
                            Le colis contient des équipements électroniques et nécessite un transport soigneux.
                        </p>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span>Demande créée le 12 Janvier 2024</span>
                            <span>•</span>
                            <span>Acceptée par Jean Dupont</span>
                        </div>
                    </div>
                    <div class="flex flex-col space-y-2 ml-4">
                        <button class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Voir les détails
                        </button>
                        <button class="inline-flex items-center px-3 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Contacter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sample Completed Request -->
        <div class="bg-white shadow rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Voyage
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Terminée
                            </span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Demande de voyage Bordeaux → Toulouse</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600 mb-4">
                            <div>
                                <span class="font-medium">Date de départ:</span> 5 Janvier 2024
                            </div>
                            <div>
                                <span class="font-medium">Poids:</span> 2 kg
                            </div>
                            <div>
                                <span class="font-medium">Prix payé:</span> 15€
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4">
                            Transport d'un petit colis de 2kg de Bordeaux à Toulouse. 
                            Le transport s'est bien déroulé et le colis est arrivé à destination.
                        </p>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span>Demande créée le 2 Janvier 2024</span>
                            <span>•</span>
                            <span>Terminée le 5 Janvier 2024</span>
                        </div>
                    </div>
                    <div class="flex flex-col space-y-2 ml-4">
                        <button class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Voir les détails
                        </button>
                        <button class="inline-flex items-center px-3 py-2 border border-green-300 text-sm font-medium rounded-md text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Noter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    @if($requests->count() == 0)
        <div class="text-center py-12">
            <div class="mx-auto h-12 w-12 text-gray-400">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900">
                @if(request('search') || request('type') || request('status'))
                    Aucune demande trouvée
                @else
                    Aucune demande
                @endif
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                @if(request('search') || request('type') || request('status'))
                    Aucune demande ne correspond à vos critères de recherche.
                @else
                    Vous n'avez pas encore créé de demandes de transport.
                @endif
            </p>
            <div class="mt-6">
                <button class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Créer une nouvelle demande
                </button>
            </div>
        </div>
    @endif
</div>
@endsection
