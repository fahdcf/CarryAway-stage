@extends('layouts.app')

@section('title', $user->name . ' - Profil - CarryWay')

@section('content')
<div class="min-h-full">
    <!-- Profile Header -->
    <div class="bg-white shadow-sm border-b border-gray-200 mb-6">
        <div class="px-6 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center space-y-4 lg:space-y-0 lg:space-x-6">
                <!-- Profile Picture -->
                <div class="flex-shrink-0">
                    @if($user->userDetail && $user->userDetail->hasProfilePicture())
                        <img src="{{ $user->userDetail->profile_picture_url }}" 
                             alt="{{ $user->name }}" 
                             class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                    @else
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-white text-2xl font-bold">{{ substr($user->name, 0, 2) }}</span>
                        </div>
                    @endif
                </div>

                <!-- Profile Info -->
                <div class="flex-1">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">{{ $user->name }}</h1>
                            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Membre depuis {{ $memberSince }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $totalAnnouncements }} annonces
                                </span>
                            </div>
                        </div>

                        <!-- Rating Summary -->
                        <div class="text-center lg:text-right">
                            <div class="flex items-center justify-center lg:justify-end mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $averageRating)
                                        <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <div class="text-2xl font-bold text-gray-900">{{ number_format($averageRating, 1) }}</div>
                            <div class="text-sm text-gray-600">{{ $totalReviews }} avis</div>
                        </div>
                    </div>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Profile Details & Contact -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Profile Details -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informations du profil</h3>
                </div>
                <div class="p-6 space-y-4">
                    @if($user->userDetail)
                        @if($user->userDetail->city)
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-gray-700">{{ $user->userDetail->city }}</span>
                            </div>
                        @endif

                        @if($user->userDetail->gender)
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-gray-700">{{ ucfirst($user->userDetail->gender) }}</span>
                            </div>
                        @endif

                        @if($user->userDetail->verified)
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-green-700 font-medium">Profil vérifié</span>
                            </div>
                        @endif
                    @endif

                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-gray-700">Dernière activité: {{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            @if(Auth::check() && Auth::id() !== $user->id)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Contact</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($user->phone)
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="text-gray-700">{{ $user->phone }}</span>
                            </div>
                        @endif

                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-700">{{ $user->email }}</span>
                        </div>

                        <a href="{{ route('dashboard.all-announcements') }}?user={{ $user->id }}" 
                           class="inline-flex items-center justify-center w-full px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            Voir les annonces
                        </a>
                    </div>
                </div>
            @endif

            <!-- Rating Distribution -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Répartition des notes</h3>
                </div>
                <div class="p-6 space-y-3">
                    @for($i = 5; $i >= 1; $i--)
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center space-x-1 w-16">
                                <span class="text-sm text-gray-600">{{ $i }}</span>
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                @if($totalReviews > 0)
                                    <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ ($ratingDistribution[$i] / $totalReviews) * 100 }}%"></div>
                                @endif
                            </div>
                            <span class="text-sm text-gray-600 w-12 text-right">{{ $ratingDistribution[$i] }}</span>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Right Column: Reviews & Rating Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Leave a Review Section -->
            @if(Auth::check() && Auth::id() !== $user->id)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            @if($hasReviewed)
                                Modifier votre avis
                            @else
                                Laisser un avis
                            @endif
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($hasReviewed)
                            <!-- Edit Review Form -->
                            <form method="POST" action="{{ route('profiles.review.update', $user->id) }}" class="space-y-4">
                                @csrf
                                @method('PUT')
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                                    <div class="flex items-center space-x-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="rating" value="{{ $i }}" 
                                                       {{ $userReview->rating == $i ? 'checked' : '' }} 
                                                       class="sr-only">
                                                <svg class="w-8 h-8 {{ $userReview->rating >= $i ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition-colors" 
                                                     fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                <div>
                                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Commentaire</label>
                                    <textarea id="comment" name="comment" rows="3" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                              placeholder="Partagez votre expérience avec cet utilisateur...">{{ $userReview->comment }}</textarea>
                                </div>

                                <div class="flex space-x-3">
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Mettre à jour
                                    </button>
                                    <form method="POST" action="{{ route('profiles.review.delete', $user->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </form>
                        @else
                            <!-- New Review Form -->
                            <form method="POST" action="{{ route('profiles.review.store', $user->id) }}" class="space-y-4">
                                @csrf
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                                    <div class="flex items-center space-x-2" x-data="{ rating: 0 }">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="rating" value="{{ $i }}" 
                                                       x-model="rating" 
                                                       class="sr-only">
                                                <svg class="w-8 h-8" 
                                                     :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300'"
                                                     class="hover:text-yellow-400 transition-colors" 
                                                     fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                <div>
                                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Commentaire</label>
                                    <textarea id="comment" name="comment" rows="3" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                              placeholder="Partagez votre expérience avec cet utilisateur..."></textarea>
                                </div>

                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Envoyer l'avis
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Reviews List -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Avis récents</h3>
                </div>
                <div class="p-6">
                    @if($recentReviews->count() > 0)
                        <div class="space-y-6">
                            @foreach($recentReviews as $review)
                                <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            @if($review->reviewer->userDetail && $review->reviewer->userDetail->hasProfilePicture())
                                                <img src="{{ $review->reviewer->userDetail->profile_picture_url }}" 
                                                     alt="{{ $review->reviewer->name }}" 
                                                     class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                                    <span class="text-white text-sm font-bold">{{ substr($review->reviewer->name, 0, 2) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <span class="text-sm font-medium text-gray-900">{{ $review->reviewer->name }}</span>
                                                <div class="flex items-center space-x-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            @if($review->comment)
                                                <p class="text-gray-700 mb-2">{{ $review->comment }}</p>
                                            @endif
                                            <div class="text-sm text-gray-500">{{ $review->created_at->format('d F Y') }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($totalReviews > 5)
                            <div class="mt-6 text-center">
                                <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Voir tous les {{ $totalReviews }} avis
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900 mb-1">Aucun avis encore</h3>
                            <p class="text-sm text-gray-500">Soyez le premier à laisser un avis pour cet utilisateur !</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
