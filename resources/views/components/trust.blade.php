{{-- Trust Section Component --}}
<section class="trust" id="trust">
    <div class="container">
        <div class="section-header fade-in">
            <h2 class="section-title lg:text-4xl">
                {{ $title ?? 'Pourquoi nous faire confiance' }}
            </h2>
            <p class="section-description">
                {{ $description ?? 'Des garanties solides pour une expérience de livraison sans souci.' }}
            </p>
        </div>

        <div class="trust-grid md:grid-cols-3">
            @php
                $defaultTrustItems = [
                    [
                        'icon' => 'shield-check',
                        'iconColor' => 'blue',
                        'title' => 'Assurance complète',
                        'description' => 'Tous les colis sont assurés contre les dommages et la perte pendant le transport.'
                    ],
                    [
                        'icon' => 'user-check',
                        'iconColor' => 'green',
                        'title' => 'Transporteurs vérifiés',
                        'description' => 'Processus de vérification rigoureux incluant vérification d\'identité et antécédents.'
                    ],
                    [
                        'icon' => 'headphones',
                        'iconColor' => 'purple',
                        'title' => 'Support 24/7',
                        'description' => 'Équipe de support dédiée disponible 24h/24 pour résoudre tous vos problèmes.'
                    ]
                ];
                $trustItems = $trustItems ?? $defaultTrustItems;
            @endphp

            @foreach($trustItems as $item)
                <div class="trust-item fade-in">
                    <div class="trust-icon {{ $item['iconColor'] }}">
                        <i data-lucide="{{ $item['icon'] }}" class="icon-lg"></i>
                    </div>
                    <h3 class="trust-title">{{ $item['title'] }}</h3>
                    <p class="trust-description">
                        {{ $item['description'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
