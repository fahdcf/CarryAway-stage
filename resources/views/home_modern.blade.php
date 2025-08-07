@extends('layouts.modern')

@section('title', 'CarryWay - Livraison collaborative révolutionnaire au Maroc')

@section('content')
<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text slide-in-left">
                <h1>
                    L'avenir de la <span class="highlight">livraison</span> collaborative au Maroc
                </h1>
                <p>
                    Découvrez une plateforme révolutionnaire qui connecte expéditeurs et transporteurs
                    grâce à l'intelligence artificielle et la technologie blockchain pour des livraisons
                    ultra-sécurisées et instantanées.
                </p>
                <div class="hero-actions">
                    <a href="#" class="btn btn-primary">
                        <i class="bi bi-rocket-takeoff me-2"></i>
                        Lancer une mission
                    </a>
                    <a href="#" class="btn btn-ghost">
                        <i class="bi bi-cpu me-2"></i>
                        Rejoindre le réseau
                    </a>
                </div>
            </div>
            <div class="hero-visual slide-in-right">
                <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=500&h=400&fit=crop&crop=center"
                     alt="Technologie futuriste CarryWay"
                     class="hero-image">

                <!-- Floating Tech Elements -->
                <div class="floating-element" style="background: linear-gradient(135deg, rgba(0,204,255,0.3), rgba(0,153,255,0.2)); width: 60px; height: 60px; border-radius: 50%; backdrop-filter: blur(20px); border: 1px solid rgba(0,204,255,0.3);"></div>
                <div class="floating-element" style="background: linear-gradient(135deg, rgba(0,179,255,0.3), rgba(0,128,255,0.2)); width: 40px; height: 40px; border-radius: 50%; backdrop-filter: blur(20px); border: 1px solid rgba(0,179,255,0.3);"></div>
                <div class="floating-element" style="background: linear-gradient(135deg, rgba(0,153,255,0.3), rgba(0,102,255,0.2)); width: 80px; height: 80px; border-radius: 50%; backdrop-filter: blur(20px); border: 1px solid rgba(0,153,255,0.3);"></div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features" id="features">
    <div class="container">
        <div class="section-header fade-in">
            <h2 class="section-title">
                Technologie <span class="text-gradient">Next-Gen</span> pour vos livraisons
            </h2>
            <p class="section-subtitle">
                Intelligence artificielle, blockchain et IoT au service d'une logistique révolutionnaire
            </p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h3 class="feature-title">Blockchain Security</h3>
                <p class="feature-description">
                    Sécurité cryptographique avancée avec smart contracts et
                    vérification biométrique pour une protection maximale.
                </p>
            </div>

            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="bi bi-cpu"></i>
                </div>
                <h3 class="feature-title">IA Prédictive</h3>
                <p class="feature-description">
                    Algorithmes d'apprentissage automatique pour optimiser les routes
                    et prédire les meilleurs transporteurs en temps réel.
                </p>
            </div>

            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <h3 class="feature-title">ROI Optimisé</h3>
                <p class="feature-description">
                    Économies jusqu'à 80% grâce à l'optimisation algorithmique
                    et la mutualisation intelligente des ressources.
                </p>
            </div>
            
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="bi bi-globe-americas"></i>
                </div>
                <h3 class="feature-title">Réseau Neural</h3>
                <p class="feature-description">
                    Couverture nationale intelligente avec points de relais connectés
                    et géolocalisation satellite précise au centimètre.
                </p>
            </div>

            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="bi bi-diagram-3"></i>
                </div>
                <h3 class="feature-title">Écosystème Digital</h3>
                <p class="feature-description">
                    Communauté de <span class="counter" data-target="50000">0</span>+
                    utilisateurs connectés via notre plateforme décentralisée.
                </p>
            </div>

            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="bi bi-robot"></i>
                </div>
                <h3 class="feature-title">Assistant IA</h3>
                <p class="feature-description">
                    Chatbot intelligent disponible 24/7 avec traitement du langage naturel
                    et résolution automatique des problèmes.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="how-it-works" id="how-it-works">
    <div class="container">
        <div class="section-header fade-in">
            <h2 class="section-title">Comment ça marche ?</h2>
            <p class="section-subtitle">
                Trois étapes simples pour révolutionner vos livraisons
            </p>
        </div>
        
        <div class="steps-container">
            <div class="step fade-in">
                <div class="step-number">1</div>
                <h3 class="step-title">Publiez votre demande</h3>
                <p class="step-description">
                    Décrivez votre colis, choisissez vos villes de départ et d'arrivée, 
                    et fixez votre budget en quelques clics.
                </p>
            </div>
            
            <div class="step fade-in">
                <div class="step-number">2</div>
                <h3 class="step-title">Trouvez votre match</h3>
                <p class="step-description">
                    Notre algorithme intelligent vous connecte avec des transporteurs 
                    vérifiés qui correspondent à vos critères.
                </p>
            </div>
            
            <div class="step fade-in">
                <div class="step-number">3</div>
                <h3 class="step-title">Suivez en temps réel</h3>
                <p class="step-description">
                    Organisez la remise, suivez le transport en direct et 
                    confirmez la livraison en toute sérénité.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta">
    <div class="container">
        <div class="cta-content fade-in">
            <h2 class="cta-title">
                Prêt à révolutionner vos livraisons ?
            </h2>
            <p class="cta-description">
                Rejoignez des milliers d'utilisateurs qui ont déjà adopté 
                la livraison collaborative avec CarryWay
            </p>
            <div class="cta-actions">
                <a href="#" class="btn btn-primary">
                    <i class="bi bi-rocket-takeoff me-2"></i>
                    Commencer maintenant
                </a>
                <a href="#" class="btn btn-ghost">
                    <i class="bi bi-play-circle me-2"></i>
                    Voir la démo
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
