@extends('layouts.app')

@section('title', 'Mon Profil - CarryWay')

@section('content')
<div class="min-h-full">
    <!-- Profile Header -->
    <div class="bg-white shadow-sm border-b border-gray-200 mb-6">
        <div class="px-6 py-4">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Mon Profil</h1>
                    <p class="text-gray-600 text-sm lg:text-base">Gérez vos informations personnelles et vos paramètres de compte</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        {{ $user->isApproved() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($user->isApproved())
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            @endif
                        </svg>
                        {{ $user->isApproved() ? 'Compte approuvé' : 'En attente d\'approbation' }}
                    </span>
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

    <!-- Profile Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-6">
                    <!-- Profile Picture -->
                    <div class="flex flex-col items-center">
                        <div class="relative">
                            @if($userDetail && $userDetail->profile_picture_url)
                                @php
                                    $profileUrl = $userDetail->profile_picture_url;
                                    // Ensure the URL starts with /storage/
                                    if (!str_starts_with($profileUrl, '/storage/')) {
                                        $profileUrl = '/storage/' . $profileUrl;
                                    }
                                @endphp
                                <img src="{{ asset($profileUrl) }}" 
                                     alt="Photo de profil" 
                                     class="w-24 h-24 rounded-full object-cover border-4 border-gray-200"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <!-- Fallback div that shows when image fails to load -->
                                <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center border-4 border-gray-200" style="display: none;">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center border-4 border-gray-200">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            @if($userDetail && $userDetail->profile_picture_url)
                                <form action="{{ route('profile.delete-picture') }}" method="POST" class="absolute -top-2 -right-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre photo de profil ?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        <h2 class="mt-4 text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        @if($user->phone)
                            <p class="text-gray-600">{{ $user->phone }}</p>
                        @endif
                        <p class="text-sm text-gray-500 mt-2">Membre depuis {{ $user->created_at->format('d/m/Y') }}</p>
                    </div>

                    <!-- Quick Stats -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <div class="text-lg font-semibold text-gray-900">
                                    {{ $user->travelerAnnouncements()->where('is_active', true)->count() + $user->senderAnnouncements()->where('is_active', true)->count() }}
                                </div>
                                <div class="text-sm text-gray-500">Annonces actives</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-semibold text-gray-900">{{ $user->messages()->count() }}</div>
                                <div class="text-sm text-gray-500">Messages</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Tabs -->
            <div class="bg-white shadow rounded-lg" x-data="{ activeTab: 'overview' }">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <button @click="activeTab = 'overview'" 
                                :class="activeTab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Aperçu
                        </button>
                        <button @click="activeTab = 'edit'" 
                                :class="activeTab === 'edit' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Informations
                        </button>
                        <button @click="activeTab = 'security'" 
                                :class="activeTab === 'security' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            Sécurité
                        </button>
                        <button @click="activeTab = 'verification'" 
                                :class="activeTab === 'verification' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Vérification
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Overview Tab -->
                    <div x-show="activeTab === 'overview'" x-transition>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Account Status -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Statut du compte</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Statut</span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            {{ $user->isApproved() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $user->isApproved() ? 'Approuvé' : 'En attente' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Vérification ID</span>
                                        @if($userDetail)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                {{ $userDetail->isIdVerified() ? 'bg-green-100 text-green-800' : 
                                                   ($userDetail->isIdVerificationPending() ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($userDetail->isIdVerificationRejected() ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                                {{ $userDetail->isIdVerified() ? 'Vérifié' : 
                                                   ($userDetail->isIdVerificationPending() ? 'En cours' : 
                                                   ($userDetail->isIdVerificationRejected() ? 'Rejeté' : 'Non soumis')) }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Non soumis
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Personal Info -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Informations personnelles</h3>
                                <div class="space-y-2 text-sm">
                                    <div><span class="text-gray-600">Nom:</span> <span class="font-medium">{{ $user->name }}</span></div>
                                    <div><span class="text-gray-600">Email:</span> <span class="font-medium">{{ $user->email }}</span></div>
                                    @if($user->phone)
                                        <div><span class="text-gray-600">Téléphone:</span> <span class="font-medium">{{ $user->phone }}</span></div>
                                    @endif
                                    @if($userDetail && $userDetail->city)
                                        <div><span class="text-gray-600">Ville:</span> <span class="font-medium">{{ $userDetail->city }}</span></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Tab -->
                    <div x-show="activeTab === 'edit'" x-transition>
                        <div class="max-w-4xl">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Modifier les informations personnelles</h3>
                            
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <!-- Profile Picture Upload -->
                                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                    <h4 class="text-md font-medium text-gray-900 mb-3">Photo de profil</h4>
                                    <div class="flex items-center space-x-4">
                                        <input type="file" 
                                               id="profile_picture" 
                                               name="profile_picture" 
                                               accept="image/*"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        <button type="button" onclick="uploadProfilePicture()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                            Mettre à jour la photo
                                        </button>
                                    </div>
                                    @error('profile_picture')
                                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom complet *</label>
                                        <input type="text" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', $user->name) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        @error('name')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                        <input type="email" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $user->email) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        @error('email')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                                        <input type="tel" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ old('phone', $user->phone) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        @error('phone')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                                        <input type="date" 
                                               id="birth_date" 
                                               name="birth_date" 
                                               value="{{ old('birth_date', $userDetail ? $userDetail->birth_date?->format('Y-m-d') : '') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        @error('birth_date')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Genre</label>
                                        <select id="gender" 
                                                name="gender"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Sélectionner</option>
                                            <option value="male" {{ old('gender', $userDetail?->gender) === 'male' ? 'selected' : '' }}>Homme</option>
                                            <option value="female" {{ old('gender', $userDetail?->gender) === 'female' ? 'selected' : '' }}>Femme</option>
                                            <option value="other" {{ old('gender', $userDetail?->gender) === 'other' ? 'selected' : '' }}>Autre</option>
                                        </select>
                                        @error('gender')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="cin" class="block text-sm font-medium text-gray-700 mb-2">CIN</label>
                                        <input type="text" 
                                               id="cin" 
                                               name="cin" 
                                               value="{{ old('cin', $userDetail?->cin) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        @error('cin')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mt-6">
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                                    <textarea id="address" 
                                              name="address" 
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address', $userDetail?->address) }}</textarea>
                                    @error('address')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mt-6">
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                                    <input type="text" 
                                           id="city" 
                                           name="city" 
                                           value="{{ old('city', $userDetail?->city) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('city')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mt-6 flex justify-end">
                                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                        Mettre à jour les informations
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Security Tab -->
                    <div x-show="activeTab === 'security'" x-transition>
                        <div class="max-w-2xl">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Sécurité du compte</h3>
                            
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h4 class="text-md font-medium text-gray-900 mb-4">Changer le mot de passe</h4>
                                
                                <form action="{{ route('profile.update-password') }}" method="POST">
                                    @csrf
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe actuel *</label>
                                            <input type="password" 
                                                   id="current_password" 
                                                   name="current_password"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            @error('current_password')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe *</label>
                                            <input type="password" 
                                                   id="password" 
                                                   name="password"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            @error('password')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le nouveau mot de passe *</label>
                                            <input type="password" 
                                                   id="password_confirmation" 
                                                   name="password_confirmation"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>
                                    
                                    <div class="mt-6 flex justify-end">
                                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                            Changer le mot de passe
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Verification Tab -->
                    <div x-show="activeTab === 'verification'" x-transition>
                        <div class="max-w-4xl">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Vérification d'identité</h3>
                            
                            @if($userDetail && $userDetail->isIdVerified())
                                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                                    <div class="flex items-center">
                                        <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <h4 class="text-lg font-medium text-green-800">Identité vérifiée</h4>
                                            <p class="text-green-700">Votre identité a été vérifiée avec succès le {{ $userDetail->id_verified_at?->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($userDetail && $userDetail->isIdVerificationPending())
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                                    <div class="flex items-center">
                                        <svg class="w-8 h-8 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <h4 class="text-lg font-medium text-yellow-800">Vérification en cours</h4>
                                            <p class="text-yellow-700">Votre demande de vérification est en cours de traitement. Cela peut prendre 1-2 jours ouvrables.</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($userDetail && $userDetail->isIdVerificationRejected())
                                <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                                    <div class="flex items-center">
                                        <svg class="w-8 h-8 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <h4 class="text-lg font-medium text-red-800">Vérification rejetée</h4>
                                            <p class="text-red-700">Votre demande de vérification a été rejetée.</p>
                                            @if($userDetail->id_verification_notes)
                                                <p class="text-red-700 text-sm mt-2"><strong>Raison:</strong> {{ $userDetail->id_verification_notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                                    <div class="flex items-center">
                                        <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <h4 class="text-lg font-medium text-blue-800">Vérification d'identité</h4>
                                            <p class="text-blue-700">La vérification d'identité améliore la confiance et la sécurité de notre plateforme. Elle est recommandée pour tous les utilisateurs.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(!$userDetail || !$userDetail->isIdVerified())
                                <div class="bg-white border border-gray-200 rounded-lg p-6">
                                    <h4 class="text-md font-medium text-gray-900 mb-4">Soumettre vos documents d'identité</h4>
                                    
                                    <form action="{{ route('profile.upload-id-documents') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label for="id_document_front" class="block text-sm font-medium text-gray-700 mb-2">
                                                    Recto de la carte d'identité *
                                                </label>
                                                <input type="file" 
                                                       id="id_document_front" 
                                                       name="id_document_front" 
                                                       accept="image/*"
                                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                                @error('id_document_front')
                                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            
                                            <div>
                                                <label for="id_document_back" class="block text-sm font-medium text-gray-700 mb-2">
                                                    Verso de la carte d'identité *
                                                </label>
                                                <input type="file" 
                                                       id="id_document_back" 
                                                       name="id_document_back" 
                                                       accept="image/*"
                                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                                @error('id_document_back')
                                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="mt-6 flex justify-end">
                                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                                Soumettre pour vérification
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function uploadProfilePicture() {
    const fileInput = document.getElementById('profile_picture');
    const file = fileInput.files[0];
    
    if (!file) {
        alert('Veuillez sélectionner une image');
        return;
    }
    
    const formData = new FormData();
    formData.append('profile_picture', file);
    formData.append('_token', '{{ csrf_token() }}');
    
    // Show loading state
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = 'Upload en cours...';
    button.disabled = true;
    
    fetch('{{ route("profile.update") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Reload the page to show the new image
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'upload. Veuillez réessayer.');
        button.textContent = originalText;
        button.disabled = false;
    });
}
</script>
@endsection
