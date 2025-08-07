@extends('layouts.app')

@section('title', 'Toutes les annonces - CarryWay')

@section('content')
<div class="min-h-full">
    <!-- Dashboard Header -->
    <div class="bg-white shadow-sm border-b border-gray-200 mb-6">
        <div class="px-6 py-4">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Toutes les annonces</h1>
                    <p class="text-gray-600 text-sm lg:text-base">Découvrez les opportunités de voyage et de livraison</p>
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

    <!-- Navigation Tabs -->
    <div class="bg-white border-b border-gray-200 mb-6">
        <div class="px-6">
            <nav class="flex space-x-4 lg:space-x-8 overflow-x-auto">
                <a href="{{ route('dashboard.index') }}" 
                   class="border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-2 lg:px-1 text-sm font-medium whitespace-nowrap">
                    Vue d'ensemble
                </a>
                <a href="{{ route('dashboard.my-announcements') }}" 
                   class="border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-2 lg:px-1 text-sm font-medium whitespace-nowrap">
                    Mes annonces
                </a>
                <a href="{{ route('dashboard.all-announcements') }}" 
                   class="border-b-2 border-blue-500 text-blue-600 py-4 px-2 lg:px-1 text-sm font-medium whitespace-nowrap">
                    Toutes les annonces
                </a>
            </nav>
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

    <!-- Traveler Announcements -->
    @if($otherTravelerAnnouncements->count() > 0)
        <div class="mb-8">
            <h2 class="text-xl lg:text-2xl font-bold text-gray-900 mb-6">Annonces de voyage</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                @foreach($otherTravelerAnnouncements as $announcement)
                    @include('dashboard.components.traveler-announcement-card', ['announcement' => $announcement, 'isOwn' => false])
                @endforeach
            </div>
        </div>
    @endif

    <!-- Sender Announcements -->
    @if($otherSenderAnnouncements->count() > 0)
        <div class="mb-8">
            <h2 class="text-xl lg:text-2xl font-bold text-gray-900 mb-6">Annonces de livraison</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                @foreach($otherSenderAnnouncements as $announcement)
                    @include('dashboard.components.sender-announcement-card', ['announcement' => $announcement, 'isOwn' => false])
                @endforeach
            </div>
        </div>
    @endif

    <!-- Empty State -->
    @if($otherTravelerAnnouncements->count() == 0 && $otherSenderAnnouncements->count() == 0)
        <div class="text-center py-12">
            <div class="mx-auto h-12 w-12 text-gray-400">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune annonce disponible</h3>
            <p class="mt-1 text-sm text-gray-500">Il n'y a actuellement aucune annonce active sur la plateforme.</p>
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
