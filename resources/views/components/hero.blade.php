{{-- Hero Section Component --}}
<section class="hero" id="hero">
    <div class="container">
        <div class="hero-content lg:grid-cols-2">
            <div class="hero-text slide-in-left">
                <div class="space-y-6">
                    <div class="hero-badge">
                        <i data-lucide="map-pin" class="icon-sm mr-2"></i>
                        {{ $badge ?? 'Plateforme de livraison collaborative' }}
                    </div>

                    <h1 class="hero-title lg:text-6xl">
                        {{ $title ?? 'La livraison collaborative' }}
                        <br>
                        <span class="hero-subtitle">{{ $subtitle ?? 'réinventée au Maroc' }}</span>
                    </h1>

                    <p class="hero-description">
                        {{ $description ?? 'Connectez expéditeurs et transporteurs sur une plateforme sécurisée. Optimisez vos livraisons avec notre réseau de confiance vérifié.' }}
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="hero-actions">
                    {{-- Tab Selector --}}
                    <div class="tab-selector">
                        <button class="tab-button active" data-tab="sender">
                            {{ $senderTabText ?? 'Expéditeur' }}
                        </button>
                        <button class="tab-button" data-tab="traveler">
                            {{ $travelerTabText ?? 'Transporteur' }}
                        </button>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="action-buttons sm:flex-row">
                        <button class="btn btn-primary group" id="primary-action">
                            <i data-lucide="package" class="icon-sm mr-2"></i>
                            <span id="action-text">{{ $primaryActionText ?? 'Publier une demande' }}</span>
                            <i data-lucide="arrow-right" class="icon-sm ml-2 group-hover:translate-x-0.5"></i>
                        </button>

                        <button class="btn btn-secondary">
                            {{ $secondaryActionText ?? 'En savoir plus' }}
                        </button>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="hero-stats lg:grid-cols-4">
                    @php
                        $defaultStats = [
                            ['number' => 15000, 'label' => 'Utilisateurs actifs'],
                            ['number' => 45000, 'label' => 'Livraisons réussies'],
                            ['number' => 12, 'label' => 'Villes couvertes'],
                            ['number' => 98, 'label' => 'Satisfaction client']
                        ];
                        $stats = $stats ?? $defaultStats;
                    @endphp
                    
                    @foreach($stats as $stat)
                        <div class="stat">
                            <div class="stat-number" data-target="{{ $stat['number'] }}">0</div>
                            <div class="stat-label">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Hero Visual --}}
            <div class="hero-visual slide-in-right">
                <div class="hero-image-container">
                    <img src="{{ $heroImage ?? 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=500&fit=crop&crop=center' }}"
                         alt="{{ $heroImageAlt ?? 'Interface CarryWay' }}"
                         class="hero-image">

                    {{-- Floating status indicators --}}
                    <div class="status-indicator">
                        <div class="status-dot"></div>
                        <span>{{ $statusText ?? 'En ligne' }}</span>
                    </div>

                    <div class="delivery-status">
                        <i data-lucide="check-circle" class="icon-sm delivery-icon"></i>
                        <span class="delivery-text">{{ $deliveryStatusText ?? 'Livraison confirmée' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Hero JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switcher functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const primaryAction = document.getElementById('primary-action');
    const actionText = document.getElementById('action-text');
    
    if (tabButtons.length > 0 && primaryAction && actionText) {
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                tabButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                button.classList.add('active');
                
                // Update action button text based on selected tab
                const tabType = button.getAttribute('data-tab');
                const icon = primaryAction.querySelector('i');
                
                if (tabType === 'sender') {
                    actionText.textContent = '{{ $senderActionText ?? "Publier une demande" }}';
                    if (icon) {
                        icon.setAttribute('data-lucide', 'package');
                    }
                } else if (tabType === 'traveler') {
                    actionText.textContent = '{{ $travelerActionText ?? "Proposer un transport" }}';
                    if (icon) {
                        icon.setAttribute('data-lucide', 'truck');
                    }
                }
                
                // Re-initialize Lucide icons
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            });
        });
    }

    // Primary action button click handler
    if (primaryAction) {
        primaryAction.addEventListener('click', function() {
            const activeTab = document.querySelector('.tab-button.active');
            const tabType = activeTab ? activeTab.getAttribute('data-tab') : 'sender';
            
            // Redirect based on tab type
            if (tabType === 'sender') {
                @auth
                    window.location.href = '{{ route("shipments.create") ?? "#" }}';
                @else
                    window.location.href = '{{ route("register") }}?type=sender';
                @endauth
            } else if (tabType === 'traveler') {
                @auth
                    window.location.href = '{{ route("travels.create") ?? "#" }}';
                @else
                    window.location.href = '{{ route("register") }}?type=traveler';
                @endauth
            }
        });
    }
});
</script>
