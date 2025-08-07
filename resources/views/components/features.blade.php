{{-- Features Section Component --}}
<section class="features" id="features">
    <div class="container">
        <div class="section-header fade-in">
            <h2 class="section-title lg:text-4xl">
                {{ $title ?? 'Une plateforme conçue pour l\'excellence' }}
            </h2>
            <p class="section-description">
                {{ $description ?? 'Technologie avancée et processus rigoureux pour garantir sécurité, fiabilité et performance.' }}
            </p>
        </div>

        <div class="features-grid md:grid-cols-2 lg:grid-cols-4">
            @php
                $defaultFeatures = [
                    [
                        'icon' => 'shield',
                        'title' => 'Sécurité renforcée',
                        'description' => 'Vérification d\'identité obligatoire avec CIN et validation en plusieurs étapes pour garantir la confiance.'
                    ],
                    [
                        'icon' => 'award',
                        'title' => 'Qualité certifiée',
                        'description' => 'Système de notation transparent et processus de validation rigoureux pour maintenir l\'excellence.'
                    ],
                    [
                        'icon' => 'globe',
                        'title' => 'Couverture nationale',
                        'description' => 'Réseau étendu couvrant les principales villes du Maroc avec expansion continue.'
                    ],
                    [
                        'icon' => 'trending-up',
                        'title' => 'Performance optimisée',
                        'description' => 'Algorithmes avancés pour optimiser les correspondances et réduire les délais de livraison.'
                    ]
                ];
                $features = $features ?? $defaultFeatures;
            @endphp

            @foreach($features as $feature)
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i data-lucide="{{ $feature['icon'] }}" class="icon text-gray-700"></i>
                    </div>
                    <h3 class="feature-title">{{ $feature['title'] }}</h3>
                    <p class="feature-description">
                        {{ $feature['description'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
