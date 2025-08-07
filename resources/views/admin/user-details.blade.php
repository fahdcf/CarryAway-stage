<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Détails utilisateur - CarryWay Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <i data-lucide="truck" class="h-5 w-5 text-white"></i>
                        </div>
                        <span class="ml-2 text-xl font-bold text-gray-900">CarryWay Admin</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">
                        Retour au tableau de bord
                    </a>
                    <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Détails de l'utilisateur</h1>
                    <p class="mt-2 text-gray-600">Vérifiez les informations avant d'approuver ou rejeter</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($user->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($user->status === 'approved') bg-green-100 text-green-800
                        @elseif($user->status === 'rejected') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($user->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- User Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Informations personnelles</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nom complet</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->phone }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $user->userDetail->birth_date ? $user->userDetail->birth_date->format('d/m/Y') : 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Genre</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $user->userDetail->gender === 'male' ? 'Homme' : ($user->userDetail->gender === 'female' ? 'Femme' : 'N/A') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ville</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->userDetail->city ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Adresse</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->userDetail->address ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Numéro CIN</label>
                            <p class="mt-1 text-sm text-gray-900 font-mono">{{ $user->userDetail->cin ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- ID Documents -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Documents d'identité</h2>
                    </div>
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Front ID -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Recto CIN</label>
                                @if($user->userDetail && $user->userDetail->id_document_front)
                                    <div class="border rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $user->userDetail->id_document_front) }}"
                                             alt="Recto CIN"
                                             class="w-full h-48 object-cover cursor-pointer"
                                             onclick="openImageModal('{{ asset('storage/' . $user->userDetail->id_document_front) }}', 'Recto CIN')">
                                    </div>
                                @else
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg h-48 flex items-center justify-center">
                                        <p class="text-gray-500">Aucun document</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Back ID -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Verso CIN</label>
                                @if($user->userDetail && $user->userDetail->id_document_back)
                                    <div class="border rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $user->userDetail->id_document_back) }}"
                                             alt="Verso CIN"
                                             class="w-full h-48 object-cover cursor-pointer"
                                             onclick="openImageModal('{{ asset('storage/' . $user->userDetail->id_document_back) }}', 'Verso CIN')">
                                    </div>
                                @else
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg h-48 flex items-center justify-center">
                                        <p class="text-gray-500">Aucun document</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Cliquez sur les images pour les agrandir</p>
                    </div>
                </div>
            </div>

            <!-- Actions Sidebar -->
            <div class="space-y-6">
                <!-- Profile Picture -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Photo de profil</h3>
                    </div>
                    <div class="px-6 py-4 text-center">
                        @if($user->userDetail && $user->userDetail->profile_picture_url)
                            <img src="{{ asset('storage/' . $user->userDetail->profile_picture_url) }}"
                                 alt="{{ $user->name }}"
                                 class="mx-auto h-32 w-32 rounded-full object-cover cursor-pointer"
                                 onclick="openImageModal('{{ asset('storage/' . $user->userDetail->profile_picture_url) }}', 'Photo de profil')">
                        @else
                            <div class="mx-auto h-32 w-32 rounded-full bg-gray-300 flex items-center justify-center">
                                <i data-lucide="user" class="h-16 w-16 text-gray-600"></i>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Account Info -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informations du compte</h3>
                    </div>
                    <div class="px-6 py-4 space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date d'inscription</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Statut de vérification ID</label>
                            <p class="mt-1 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($user->userDetail && $user->userDetail->id_verification_status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($user->userDetail && $user->userDetail->id_verification_status === 'verified') bg-green-100 text-green-800
                                    @elseif($user->userDetail && $user->userDetail->id_verification_status === 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $user->userDetail ? ucfirst($user->userDetail->id_verification_status) : 'N/A' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @if($user->status === 'pending')
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Actions</h3>
                        </div>
                        <div class="px-6 py-4 space-y-3">
                            <!-- Approve Button -->
                            <form method="POST" action="{{ route('admin.users.approve', $user->id) }}" class="w-full">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                        onclick="return confirm('Êtes-vous sûr de vouloir approuver cet utilisateur ?')">
                                    <i data-lucide="check" class="h-4 w-4 mr-2"></i>
                                    Approuver
                                </button>
                            </form>

                            <!-- Reject Button -->
                            <button type="button" 
                                    onclick="openRejectModal()"
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i data-lucide="x" class="h-4 w-4 mr-2"></i>
                                Rejeter
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
        <div class="max-w-4xl max-h-full">
            <div class="bg-white rounded-lg overflow-hidden">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 id="imageModalTitle" class="text-lg font-medium text-gray-900"></h3>
                    <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="h-6 w-6"></i>
                    </button>
                </div>
                <div class="p-4">
                    <img id="imageModalImg" src="" alt="" class="max-w-full max-h-96 mx-auto">
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Rejeter la demande</h3>
                <form method="POST" action="{{ route('admin.users.reject', $user->id) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Raison du rejet *
                        </label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="4" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                  placeholder="Expliquez pourquoi cette demande est rejetée..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeRejectModal()"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700">
                            Rejeter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif

    <script>
        lucide.createIcons();

        // Image Modal Functions
        function openImageModal(src, title) {
            document.getElementById('imageModalImg').src = src;
            document.getElementById('imageModalTitle').textContent = title;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        // Reject Modal Functions
        function openRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // Close modals on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
                closeRejectModal();
            }
        });

        // Close modals on backdrop click
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });

        // Auto-hide messages after 5 seconds
        setTimeout(() => {
            const messages = document.querySelectorAll('.fixed.top-4.right-4');
            messages.forEach(msg => {
                msg.style.opacity = '0';
                msg.style.transform = 'translateX(100%)';
                setTimeout(() => msg.remove(), 300);
            });
        }, 5000);
    </script>
</body>
</html>
