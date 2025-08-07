@extends('layouts.app')

@section('content')
    {{-- Hero Section --}}
    @include('components.hero', [
        'badge' => 'Plateforme de livraison collaborative',
        'title' => 'La livraison collaborative',
        'subtitle' => 'réinventée au Maroc',
        'description' => 'Connectez expéditeurs et transporteurs sur une plateforme sécurisée. Optimisez vos livraisons avec notre réseau de confiance vérifié.',
        'senderTabText' => 'Expéditeur',
        'travelerTabText' => 'Transporteur',
        'primaryActionText' => 'Publier une demande',
        'secondaryActionText' => 'En savoir plus',
        'senderActionText' => 'Publier une demande',
        'travelerActionText' => 'Proposer un transport',
        'heroImage' => asset('storage/images/Orange and Red Modern Delivery Service Instagram Post.gif'),
        'heroImageAlt' => 'CarryWay Delivery Service Animation',
        'statusText' => 'En ligne',
        'deliveryStatusText' => 'Livraison confirmée',
        'stats' => [
            ['number' => 15000, 'label' => 'Utilisateurs actifs'],
            ['number' => 45000, 'label' => 'Livraisons réussies'],
            ['number' => 12, 'label' => 'Villes couvertes'],
            ['number' => 98, 'label' => 'Satisfaction client']
        ]
    ])

    {{-- Features Section --}}
    @include('components.features', [
        'title' => 'Une plateforme conçue pour l\'excellence',
        'description' => 'Technologie avancée et processus rigoureux pour garantir sécurité, fiabilité et performance.',
        'features' => [
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
        ]
    ])

    {{-- How It Works Section --}}
    @include('components.how-it-works', [
        'title' => 'Processus simplifié',
        'description' => 'Quatre étapes pour commencer à utiliser CarryWay en toute confiance.',
        'steps' => [
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
        ]
    ])

    {{-- Trust Section --}}
    @include('components.trust', [
        'title' => 'Pourquoi nous faire confiance',
        'description' => 'Des garanties solides pour une expérience de livraison sans souci.',
        'trustItems' => [
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
        ]
    ])

    {{-- CTA Section --}}
    @include('components.cta', [
        'title' => 'Prêt à révolutionner vos livraisons ?',
        'description' => 'Rejoignez des milliers d\'utilisateurs qui font confiance à CarryWay pour leurs besoins de livraison.',
        'primaryActionText' => 'Commencer maintenant',
        'primaryActionIcon' => 'rocket',
        'primaryActionUrl' => route('register') ?? '#',
        'secondaryActionText' => 'Planifier une démo',
        'secondaryActionUrl' => '#'
    ])
@endsection

@push('styles')
<style>
    /* Additional page-specific styles if needed */
</style>
@endpush

@push('scripts')
<script>
    // Additional page-specific JavaScript if needed
    document.addEventListener('DOMContentLoaded', function() {
        // Any homepage-specific functionality can go here
        console.log('CarryWay Modern Homepage loaded successfully');
    });
</script>
@endpush
