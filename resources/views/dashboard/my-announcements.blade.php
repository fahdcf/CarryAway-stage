@extends('layouts.app')

@section('title', 'Mes annonces - CarryWay')

@section('content')
<div class="min-h-full">
    <!-- Dashboard Header -->
    <div class="bg-white shadow-sm border-b border-gray-200 mb-6">
        <div class="px-6 py-4">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Mes annonces</h1>
                    <p class="text-gray-600 text-sm lg:text-base">Gérez vos annonces de voyage et de livraison</p>
                </div>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('dashboard.create-traveler-announcement') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="hidden sm:inline">Annonce de voyage</span>
                        <span class="sm:hidden">Voyage</span>
                    </a>
                    <a href="{{ route('dashboard.create-sender-announcement') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="hidden sm:inline">Annonce de livraison</span>
                        <span class="sm:hidden">Livraison</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Rechercher et filtrer</h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('dashboard.my-announcements') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search Input -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ville de départ, ville d'arrivée...">
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type d'annonce</label>
                        <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les types</option>
                            <option value="traveler" {{ request('type') == 'traveler' ? 'selected' : '' }}>Voyage</option>
                            <option value="sender" {{ request('type') == 'sender' ? 'selected' : '' }}>Livraison</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les statuts</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Trier par</label>
                        <select name="sort" id="sort" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Plus récentes</option>
                            <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Plus anciennes</option>
                            <option value="departure_date_asc" {{ request('sort') == 'departure_date_asc' ? 'selected' : '' }}>Date de départ</option>
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
                        <a href="{{ route('dashboard.my-announcements') }}" class="text-sm text-gray-500 hover:text-gray-700">
                            Effacer les filtres
                        </a>
                    @endif
                </div>
            </form>
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

    <!-- Results Summary -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">
                Résultats ({{ $myTravelerAnnouncements->count() + $mySenderAnnouncements->count() }} annonces)
            </h2>
            <div class="text-sm text-gray-500">
                {{ $myTravelerAnnouncements->count() }} voyages • {{ $mySenderAnnouncements->count() }} livraisons
            </div>
        </div>
    </div>

    <!-- My Traveler Announcements -->
    @if($myTravelerAnnouncements->count() > 0)
        <div class="mb-8">
            <h3 class="text-xl lg:text-2xl font-bold text-gray-900 mb-6">Mes annonces de voyage</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                @foreach($myTravelerAnnouncements as $announcement)
                    @include('dashboard.components.traveler-announcement-card', ['announcement' => $announcement, 'isOwn' => true])
                @endforeach
            </div>
        </div>
    @endif



    <!-- My Sender Announcements -->
    @if($mySenderAnnouncements->count() > 0)
        <div class="mb-8">
            <h3 class="text-xl lg:text-2xl font-bold text-gray-900 mb-6">Mes annonces de livraison</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                @foreach($mySenderAnnouncements as $announcement)
                    @include('dashboard.components.sender-announcement-card', ['announcement' => $announcement, 'isOwn' => true])
                @endforeach
            </div>
        </div>
    @endif

    

    <!-- Empty State -->
    @if($myTravelerAnnouncements->count() == 0 && $mySenderAnnouncements->count() == 0)
        <div class="text-center py-12">
            <div class="mx-auto h-12 w-12 text-gray-400">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune annonce trouvée</h3>
            <p class="mt-1 text-sm text-gray-500">
                @if(request('search') || request('type') || request('status'))
                    Aucune annonce ne correspond à vos critères de recherche.
                @else
                    Vous n'avez pas encore créé d'annonces.
                @endif
            </p>
            <div class="mt-6">
                <a href="{{ route('dashboard.create-traveler-announcement') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 mr-3">
                    Créer une annonce de voyage
                </a>
                <a href="{{ route('dashboard.create-sender-announcement') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    Créer une annonce de livraison
                </a>
            </div>
        </div>
    @endif
</div>
@endsection


