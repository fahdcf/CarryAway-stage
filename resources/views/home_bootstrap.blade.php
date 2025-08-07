@extends('layouts.app_bootstrap')

@section('title', 'CarryWay - Livraison collaborative au Maroc')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title">Envoyez ou transportez des colis facilement entre particuliers au Maroc</h1>
                <p class="hero-subtitle">Rejoignez la communauté CarryWay et profitez d'un service de livraison économique, sécurisé et convivial.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="#" class="btn btn-primary-custom btn-lg">
                        <i class="bi bi-box-seam me-2"></i>
                        Envoyer un colis
                    </a>
                    <a href="#" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-car-front me-2"></i>
                        Transporter un colis
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://via.placeholder.com/500x400/2563eb/ffffff?text=CarryWay" alt="CarryWay" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- How it works Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold">Comment ça marche ?</h2>
                <p class="lead text-muted">Trois étapes simples pour envoyer ou transporter un colis</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="step-number mx-auto">1</div>
                <h4>Publiez votre annonce</h4>
                <p class="text-muted">Décrivez votre colis ou votre trajet avec tous les détails nécessaires</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="step-number mx-auto">2</div>
                <h4>Trouvez votre match</h4>
                <p class="text-muted">Connectez-vous avec des personnes de confiance dans votre région</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="step-number mx-auto">3</div>
                <h4>Finalisez l'échange</h4>
                <p class="text-muted">Organisez la remise et le transport en toute sécurité</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold">Pourquoi choisir CarryWay ?</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5>Sécurisé</h5>
                    <p class="text-muted">Tous nos utilisateurs sont vérifiés pour garantir votre sécurité</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <h5>Vérifié</h5>
                    <p class="text-muted">Profils vérifiés et système de validation d'identité</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <h5>Noté</h5>
                    <p class="text-muted">Système de notation et d'avis pour choisir les meilleurs transporteurs</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h5>Support 24/7</h5>
                    <p class="text-muted">Notre équipe est disponible pour vous aider à tout moment</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="display-5 fw-bold mb-4">Prêt à commencer ?</h2>
                <p class="lead text-muted mb-4">Rejoignez des milliers d'utilisateurs qui font confiance à CarryWay</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="#" class="btn btn-primary-custom btn-lg">
                        Créer un compte
                    </a>
                    <a href="#" class="btn btn-outline-custom btn-lg">
                        En savoir plus
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
