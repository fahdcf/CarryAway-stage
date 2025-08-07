<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CarryWay - Livraison collaborative au Maroc')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="nav">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="logo">
                    CarryWay
                </a>

                <!-- Navigation Links -->
                <ul class="nav-links">
                    <li><a href="{{ route('home') }}" class="nav-link">Accueil</a></li>
                    <li><a href="#features" class="nav-link">Fonctionnalités</a></li>
                    <li><a href="#how-it-works" class="nav-link">Comment ça marche</a></li>
                    <li><a href="#contact" class="nav-link">Contact</a></li>
                </ul>

                <!-- Auth Actions -->
                <div class="nav-actions">
                    <a href="#" class="btn btn-secondary">Connexion</a>
                    <a href="#" class="btn btn-primary">Inscription</a>
                </div>

                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle">
                    <i class="bi bi-list"></i>
                </button>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>CarryWay</h3>
                    <p>La plateforme de livraison collaborative qui connecte les expéditeurs et les voyageurs au Maroc.</p>
                </div>
                <div class="footer-section">
                    <h3>Services</h3>
                    <ul class="footer-links">
                        <li><a href="#">Envoyer un colis</a></li>
                        <li><a href="#">Transporter un colis</a></li>
                        <li><a href="#">Suivi des colis</a></li>
                        <li><a href="#">Assurance</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Support</h3>
                    <ul class="footer-links">
                        <li><a href="#">Centre d'aide</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Sécurité</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Légal</h3>
                    <ul class="footer-links">
                        <li><a href="#">Conditions d'utilisation</a></li>
                        <li><a href="#">Politique de confidentialité</a></li>
                        <li><a href="#">Mentions légales</a></li>
                        <li><a href="#">Cookies</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} CarryWay. Tous droits réservés. Fait avec ❤️ au Maroc.</p>
            </div>
        </div>
    </footer>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>
