@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200 mb-6">
        <div class="px-6 py-4">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Notifications</h1>
                    <p class="text-gray-600 text-sm lg:text-base">Gérez vos notifications et alertes</p>
                </div>
                <div class="flex space-x-3">
                    <button class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Marquer tout comme lu
                    </button>
                    <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Paramètres
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Filtrer les notifications</h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('notifications.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" id="type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les types</option>
                            <option value="announcement" {{ request('type') == 'announcement' ? 'selected' : '' }}>Annonces</option>
                            <option value="message" {{ request('type') == 'message' ? 'selected' : '' }}>Messages</option>
                            <option value="request" {{ request('type') == 'request' ? 'selected' : '' }}>Demandes</option>
                            <option value="system" {{ request('type') == 'system' ? 'selected' : '' }}>Système</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                        <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les statuts</option>
                            <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Non lues</option>
                            <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Lues</option>
                        </select>
                    </div>
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Trier par</label>
                        <select name="sort" id="sort" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Plus récentes</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus anciennes</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filtrer
                    </button>
                    @if(request('type') || request('status') || request('sort'))
                        <a href="{{ route('notifications.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                            Effacer les filtres
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Notifications ({{ count($notifications ?? []) }})</h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Afficher</span>
                    <select class="text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="divide-y divide-gray-200">
            <!-- Sample Notifications -->
            <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">Nouvelle demande de voyage</p>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Nouvelle
                                </span>
                                <span class="text-xs text-gray-500">Il y a 2 heures</span>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Jean Dupont a demandé à rejoindre votre voyage vers Paris le 15 décembre.</p>
                        <div class="mt-3 flex items-center space-x-3">
                            <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">Voir la demande</button>
                            <button class="text-sm text-gray-500 hover:text-gray-700">Marquer comme lu</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">Annonce approuvée</p>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Lu
                                </span>
                                <span class="text-xs text-gray-500">Il y a 1 jour</span>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Votre annonce "Colis pour Lyon" a été approuvée et est maintenant visible.</p>
                        <div class="mt-3 flex items-center space-x-3">
                            <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">Voir l'annonce</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM4 5h6V4a1 1 0 00-1-1H5a1 1 0 00-1 1v1zm0 6h6V9H4v2zm0 4h6v-2H4v2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">Nouveau message</p>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Nouveau
                                </span>
                                <span class="text-xs text-gray-500">Il y a 3 jours</span>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Marie Martin vous a envoyé un message concernant votre annonce.</p>
                        <div class="mt-3 flex items-center space-x-3">
                            <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">Répondre</button>
                            <button class="text-sm text-gray-500 hover:text-gray-700">Marquer comme lu</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">Maintenance prévue</p>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Lu
                                </span>
                                <span class="text-xs text-gray-500">Il y a 1 semaine</span>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Une maintenance est prévue le 20 décembre de 2h à 4h du matin. Le service sera temporairement indisponible.</p>
                        <div class="mt-3 flex items-center space-x-3">
                            <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">Plus d'infos</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Affichage de <span class="font-medium">1</span> à <span class="font-medium">4</span> sur <span class="font-medium">12</span> notifications
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-1 text-sm text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        Précédent
                    </button>
                    <span class="px-3 py-1 text-sm text-gray-700 bg-blue-50 border border-blue-200 rounded-md">1</span>
                    <button class="px-3 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">2</button>
                    <button class="px-3 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">3</button>
                    <button class="px-3 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Suivant
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
