@extends('layouts.app')

@section('title', 'Notifications - CarryWay')

@section('content')
<div class="min-h-full bg-gray-50">
    <!-- Dashboard Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-8">
                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
                    <div>
                        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">Notifications</h1>
                        <p class="text-gray-600 text-base lg:text-lg mt-2">Restez informé de toutes les activités importantes</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button type="button" onclick="markAllAsRead()" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Tout marquer comme lu
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($notifications->count() > 0)
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 {{ $notification->is_read ? 'opacity-75' : '' }}">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <!-- Notification Header -->
                                    <div class="flex items-center space-x-3 mb-3">
                                        @if($notification->type === 'request_accepted')
                                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification->type === 'meeting_verified')
                                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification->type === 'delivery_verified')
                                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <div class="flex-1">
                                            <p class="text-gray-900 font-medium">{{ $notification->content }}</p>
                                            <p class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                        
                                        @if(!$notification->is_read)
                                            <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
                                        @endif
                                    </div>

                                    <!-- Verification Codes Display (for request_accepted notifications) -->
                                    @if($notification->type === 'request_accepted' && $notification->data)
                                        <div class="mt-4 p-4 bg-blue-50 rounded-xl border border-blue-200">
                                            <h4 class="text-sm font-semibold text-blue-900 mb-3">Codes de vérification</h4>
                                            
                                            <!-- User Information Only -->
                                            @if($notification->request)
                                                <div class="mb-4 p-3 bg-white rounded-lg border border-blue-200">
                                                    <h6 class="text-xs font-semibold text-blue-800 mb-2">Utilisateur concerné</h6>
                                                    <div class="flex items-center space-x-2">
                                                        <div class="w-6 h-6 bg-blue-200 rounded-full flex items-center justify-center">
                                                            <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                            </svg>
                                                        </div>
                                                        <span class="text-xs text-blue-800">
                                                            @if($notification->request->initiator_user_id === auth()->id())
                                                                {{ $notification->request->announcementOwner->name ?? 'Utilisateur inconnu' }}
                                                            @else
                                                                {{ $notification->request->initiator->name ?? 'Utilisateur inconnu' }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-8 h-8 bg-blue-200 rounded-lg flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-blue-600 font-medium">Code de rencontre</p>
                                                        <p class="text-lg font-bold text-blue-900 font-mono">{{ $notification->data['meeting_code'] ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                
                                                @if(isset($notification->data['delivery_code']))
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-8 h-8 bg-green-200 rounded-lg flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-green-600 font-medium">Code de livraison</p>
                                                            <p class="text-lg font-bold text-green-900 font-mono">{{ $notification->data['delivery_code'] }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="mt-3 text-xs text-blue-700">
                                                <p><strong>Code de rencontre :</strong> À montrer lors de la rencontre avec l'autre utilisateur</p>
                                                @if(isset($notification->data['delivery_code']))
                                                    <p><strong>Code de livraison :</strong> À montrer après la livraison réussie</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="flex items-center space-x-3 mt-4">
                                        
                                        @if($notification->request)
                                            <a href="{{ route('requests.index', ['tab' => 'sent']) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Voir la demande
                                            </a>
                                        @endif
                                        
                                        @if(!$notification->is_read)
                                            <button type="button" onclick="markAsRead('{{ $notification->id }}')"
                                                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                                Marquer comme lu
                                            </button>
                                        @endif
                                        
                                        <button type="button" onclick="deleteNotification('{{ $notification->id }}')"
                                                class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Supprimer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM4 5h6V4a1 1 0 00-1-1H5a1 1 0 00-1 1v1zm0 6h6V9H4v2zm0 4h6v-2H4v2z"></path>
                    </svg>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune notification</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Vous n'avez pas encore reçu de notifications.
                </p>
            </div>
        @endif
    </div>
</div>

<script>
    function markAsRead(notificationId) {
        fetch('/notifications/mark-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                notification_id: notificationId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue');
        });
    }

    function markAllAsRead() {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue');
        });
    }

    function deleteNotification(notificationId) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')) {
            return;
        }

        fetch('/notifications', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                notification_id: notificationId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue');
        });
    }
</script>
@endsection
