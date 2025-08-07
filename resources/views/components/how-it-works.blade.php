{{-- How It Works Section Component --}}
<section class="how-it-works" id="how-it-works">
    <div class="container">
        <div class="section-header fade-in">
            <h2 class="section-title lg:text-4xl">
                {{ $title ?? 'Processus simplifié' }}
            </h2>
            <p class="section-description">
                {{ $description ?? 'Quatre étapes pour commencer à utiliser CarryWay en toute confiance.' }}
            </p>
        </div>

        <div class="steps-grid lg:grid-cols-4">
            @php
                $defaultSteps = [
                    [
                        'number' => '01',
                        'title' => 'Inscription et vérification',
                        'description' => 'Créez votre compte professionnel et complétez le processus de vérification d\'identité sécurisé.'
                    ],
                    [
                        'number' => '02',
                        'title' => 'Publication d\'annonce',
                        'description' => 'Publiez votre demande de livraison ou votre offre de transport avec tous les détails nécessaires.'
                    ],
                    [
                        'number' => '03',
                        'title' => 'Mise en relation',
                        'description' => 'Notre système intelligent vous met en relation avec les partenaires les plus adaptés à vos besoins.'
                    ],
                    [
                        'number' => '04',
                        'title' => 'Suivi et finalisation',
                        'description' => 'Suivez votre livraison en temps réel et finalisez la transaction en toute sécurité.'
                    ]
                ];
                $steps = $steps ?? $defaultSteps;
            @endphp

            @foreach($steps as $index => $step)
                <div class="step fade-in">
                    @if($index < count($steps) - 1)
                        <div class="step-connector"></div>
                    @endif
                    
                    <div class="step-content">
                        <div class="step-number">{{ $step['number'] }}</div>
                        <div class="step-text">
                            <h3 class="step-title">{{ $step['title'] }}</h3>
                            <p class="step-description">
                                {{ $step['description'] }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
