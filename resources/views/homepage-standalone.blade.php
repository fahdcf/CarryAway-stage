<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CarryWay - Plateforme de livraison collaborative au Maroc</title>
    <meta name="description" content="Plateforme de livraison collaborative au Maroc. Connectez expéditeurs et transporteurs en toute sécurité.">
    <meta name="keywords" content="livraison, transport, Maroc, collaborative, sécurisé">

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

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .animate-float-delayed {
            animation: float 3s ease-in-out infinite 1.5s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(-2px); }
            50% { transform: translateY(2px); }
        }

        .animate-bounce-slow {
            animation: bounce-slow 4s ease-in-out infinite;
        }

        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            25% { transform: translateY(-3px) rotate(1deg); }
            50% { transform: translateY(-1px) rotate(-1deg); }
            75% { transform: translateY(-2px) rotate(0.5deg); }
        }

        .animate-pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 5px rgba(34, 197, 94, 0.3); }
            50% { box-shadow: 0 0 20px rgba(34, 197, 94, 0.6); }
        }

        .animate-slide-in-out {
            animation: slide-in-out 6s ease-in-out infinite;
        }

        @keyframes slide-in-out {
            0%, 100% { transform: translateX(0px); opacity: 0.9; }
            50% { transform: translateX(5px); opacity: 1; }
        }

        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease-out;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .slide-in-left {
            opacity: 0;
            transform: translateX(-20px);
            transition: all 0.6s ease-out;
        }

        .slide-in-left.visible {
            opacity: 1;
            transform: translateX(0);
        }

        /* Mobile container adjustments */
        @media (max-width: 768px) {
            .container-mobile-full {
                padding-left: 0;
                padding-right: 0;
            }
            
            .container-mobile-full > div {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        .slide-in-right {
            opacity: 0;
            transform: translateX(20px);
            transition: all 0.6s ease-out;
        }

        .slide-in-right.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .scale-line {
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.8s ease-out;
        }

        .scale-line.visible {
            transform: scaleX(1);
        }

        /* Process Stepper Styles */
        .stepper {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
            max-width: 1000px;
            margin: 0 auto;
        }

        .stepper::before {
            content: '';
            position: absolute;
            top: 24px;
            left: 12.5%;
            right: 12.5%;
            height: 4px;
            background: #e2e8f0;
            z-index: 1;
            border-radius: 2px;
        }

        .progress-line {
            position: absolute;
            top: 24px;
            left: 12.5%;
            right: 12.5%;
            height: 4px;
            z-index: 2;
        }

        .progress-line::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 0%;
            background: #059669;
            transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 2px;
            z-index: 3;
        }

        .progress-line.step-1::before { width: 25%; }
        .progress-line.step-2::before { width: 50%; }
        .progress-line.step-3::before { width: 75%; }
        .progress-line.step-4::before { width: 100%; }

        /* Mobile styles for vertical stepper */
        @media (max-width: 768px) {
            .stepper {
                flex-direction: column;
                align-items: flex-start;
                gap: 3rem;
                position: relative;
                padding-left: 60px;
            }

            .stepper::before {
                top: 24px;
                left: 36px;
                right: auto;
                width: 4px;
                height: calc(100% - 48px);
                z-index: 0;
            }

            .progress-line {
                top: 24px;
                left: 36px;
                right: auto;
                width: 4px;
                height: calc(100% - 48px);
                z-index: 0;
            }

            .progress-line::before {
                width: 100%;
                height: 0%;
                transition: height 1s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .progress-line.step-1::before { height: 25%; }
            .progress-line.step-2::before { height: 50%; }
            .progress-line.step-3::before { height: 75%; }
            .progress-line.step-4::before { height: 100%; }

            .step {
                max-width: 280px;
                width: 100%;
                z-index: 10;
                position: relative;
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .step-circle {
                z-index: 15;
                position: relative;
                background: white;
                border: 3px solid #e2e8f0;
                flex-shrink: 0;
                width: 48px;
                height: 48px;
            }

            .step.active .step-circle {
                background: #059669;
                color: white;
                border-color: #059669;
                transform: scale(1.1);
            }

            .step.completed .step-circle {
                background: #059669;
                color: white;
                border-color: #059669;
            }

            .step-content {
                z-index: 10;
                position: relative;
                flex: 1;
            }
        }

        .step {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex: 1;
            max-width: 200px;
            z-index: 4;
        }

        .step-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #f1f5f9;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border: 3px solid #e2e8f0;
            z-index: 5;
        }

        .step.active .step-circle {
            background: #059669;
            color: white;
            border-color: #059669;
            transform: scale(1.1);
        }

        .step.completed .step-circle {
            background: #059669;
            color: white;
            border-color: #059669;
        }

        .step-content {
            text-align: center;
            max-width: 180px;
        }

        .step-number {
            font-size: 12px;
            font-weight: 500;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .step-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .step-description {
            font-size: 14px;
            color: #64748b;
            line-height: 1.4;
        }

        .step.active .step-number,
        .step.active .step-title {
            color: #059669;
        }

        .step.completed .step-number,
        .step.completed .step-title {
            color: #059669;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .step {
            animation: fadeInUp 0.6s ease forwards;
            opacity: 0;
        }

        .step:nth-child(2) { animation-delay: 0.1s; }
        .step:nth-child(3) { animation-delay: 0.2s; }
        .step:nth-child(4) { animation-delay: 0.3s; }
        .step:nth-child(5) { animation-delay: 0.4s; }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .stepper {
                flex-direction: column;
                gap: 2rem;
            }

            .step {
                flex-direction: row;
                text-align: left;
                width: 100%;
                max-width: none;
            }

            .step-circle {
                margin-bottom: 0;
                margin-right: 1rem;
                width: 50px;
                height: 50px;
                flex-shrink: 0;
            }

            .step-connector {
                display: none;
            }

            .step-content {
                text-align: left;
                max-width: none;
                flex: 1;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-white text-gray-900 overflow-x-hidden font-inter">
    <!-- Header -->
    <header id="header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-white/80 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 container-mobile-full">
            <nav class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center space-x-3 hover:scale-105 transition-transform cursor-pointer">
                    <div class="w-8 h-8 bg-gray-900 rounded-lg flex items-center justify-center">
                        <i data-lucide="truck" class="w-5 h-5 text-white"></i>
                    </div>
                    <span class="text-xl font-semibold text-gray-900">CarryWay</span>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="#" class="text-gray-600 hover:text-gray-900 font-medium transition-colors text-sm hover:-translate-y-0.5 transition-transform">Accueil</a>
                    <a href="#features" class="text-gray-600 hover:text-gray-900 font-medium transition-colors text-sm hover:-translate-y-0.5 transition-transform">Solutions</a>
                    <a href="#how-it-works" class="text-gray-600 hover:text-gray-900 font-medium transition-colors text-sm hover:-translate-y-0.5 transition-transform">Processus</a>
                    <a href="#infos" class="text-gray-600 hover:text-gray-900 font-medium transition-colors text-sm hover:-translate-y-0.5 transition-transform">À propos</a>
                </div>

                <!-- Desktop Actions -->
                <div class="hidden lg:flex items-center space-x-4">
                    @auth
                        <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('dashboard.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900 font-medium transition-colors text-sm hover:scale-105 transition-transform">{{ Auth::user()->isAdmin() ? 'Admin Dashboard' : 'Tableau de bord' }}</a>
                        <form method="POST" action="{{ route('logout') ?? '#' }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="px-6 py-2 bg-gray-900 text-white rounded-lg font-medium hover:bg-gray-800 transition-all text-sm hover:scale-105 transition-transform">Se déconnecter</button>
                        </form>
                    @else
                        <a href="{{ route('login') ?? '#' }}" class="px-4 py-2 text-gray-600 hover:text-gray-900 font-medium transition-colors text-sm hover:scale-105 transition-transform">Se connecter</a>
                        <a href="{{ route('register') ?? '#' }}" class="px-6 py-2 bg-gray-900 text-white rounded-lg font-medium hover:bg-gray-800 transition-all text-sm hover:scale-105 transition-transform">Commencer</a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-toggle" class="lg:hidden p-2 text-gray-600">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
            </nav>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="lg:hidden mt-4 py-4 border-t border-gray-100 hidden">
                <div class="space-y-4">
                    <a href="#" class="block text-gray-600 hover:text-gray-900 font-medium text-sm">Accueil</a>
                    <a href="#features" class="block text-gray-600 hover:text-gray-900 font-medium text-sm">Solutions</a>
                    <a href="#how-it-works" class="block text-gray-600 hover:text-gray-900 font-medium text-sm">Entreprise</a>
                    <a href="#" class="block text-gray-600 hover:text-gray-900 font-medium text-sm">Ressources</a>
                    <a href="#" class="block text-gray-600 hover:text-gray-900 font-medium text-sm">À propos</a>
                    <div class="pt-4 space-y-2">
                        @auth
                            <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('dashboard.index') }}" class="block w-full text-left text-gray-600 hover:text-gray-900 font-medium text-sm">{{ Auth::user()->isAdmin() ? 'Admin Dashboard' : 'Tableau de bord' }}</a>
                            <form method="POST" action="{{ route('logout') ?? '#' }}">
                                @csrf
                                <button type="submit" class="block w-full px-6 py-2 bg-gray-900 text-white rounded-lg font-medium text-center text-sm">Se déconnecter</button>
                            </form>
                        @else
                            <a href="{{ route('login') ?? '#' }}" class="block w-full text-left text-gray-600 hover:text-gray-900 font-medium text-sm">Se connecter</a>
                            <a href="{{ route('register') ?? '#' }}" class="block w-full px-6 py-2 bg-gray-900 text-white rounded-lg font-medium text-center text-sm">Commencer</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="hero" class="relative pt-24 pb-16 lg:pt-32 lg:pb-24">
        <div class="max-w-7xl mx-auto px-6 container-mobile-full">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Hero Content -->
                <div class="space-y-8 slide-in-left">
                    <div class="space-y-6">
                        <!-- Badge -->
                        <div class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-medium fade-in">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 mr-2"></i>
                            Plateforme de livraison collaborative
                        </div>

                        <!-- Title -->
                        <h1 class="text-4xl lg:text-6xl font-bold leading-tight text-gray-900 fade-in">
                            La livraison collaborative
                            <br>
                            <span class="text-gray-600">réinventée au Maroc</span>
                        </h1>

                        <!-- Description -->
                        <p class="text-xl text-gray-600 leading-relaxed max-w-2xl fade-in">
                            Connectez expéditeurs et transporteurs sur une plateforme sécurisée. Optimisez vos livraisons avec
                            notre réseau de confiance vérifié.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-6 fade-in">
                        <!-- General Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button class="px-8 py-3 bg-gray-900 text-white rounded-lg font-medium hover:bg-gray-800 transition-all flex items-center justify-center group hover:scale-105 transform">
                                <i data-lucide="rocket" class="w-4.5 h-4.5 mr-2"></i>
                                <span>Commencer maintenant</span>
                                <i data-lucide="arrow-right" class="w-4 h-4 ml-2 group-hover:translate-x-0.5 transition-transform"></i>
                            </button>

                            <button class="px-8 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:border-gray-400 hover:text-gray-900 transition-all flex items-center justify-center hover:scale-105 transform">
                                <i data-lucide="play-circle" class="w-4 h-4 mr-2"></i>
                                Voir la démo
                            </button>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 pt-8 border-t border-gray-100 fade-in">
                        <div>
                            <div class="text-2xl font-bold text-gray-900" id="stat-users">0+</div>
                            <div class="text-sm text-gray-600">Utilisateurs actifs</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900" id="stat-deliveries">0+</div>
                            <div class="text-sm text-gray-600">Livraisons réussies</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900" id="stat-cities">0+</div>
                            <div class="text-sm text-gray-600">Villes couvertes</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900" id="stat-satisfaction">0%</div>
                            <div class="text-sm text-gray-600">Satisfaction client</div>
                        </div>
                    </div>
                </div>

                <!-- Hero Visual -->
                <div class="relative slide-in-right">
                    <div class="relative hero-visual-container">
                        <!-- GIF with gradient background blend -->
                        <div class="relative p-8 rounded-3xl" style="background: radial-gradient(ellipse at center, #fcfcfc 20%, rgba(252, 252, 252, 0.9) 40%, rgba(253, 253, 253, 0.7) 60%, rgba(254, 254, 254, 0.5) 75%, #ffffff 90%, #ffffff 100%);">
                            <img src="{{ asset('storage/images/Orange and Red Modern Delivery Service Instagram Post.gif') }}"
                                 alt="CarryWay Delivery Service Animation"
                                 class="w-full h-auto max-w-lg mx-auto relative z-0">
                        </div>

                        <!-- Integrated UI Elements - like part of the app interface -->

                        <!-- Live Activity Indicator -->
                        <div class="absolute top-6 right-6 bg-white/95 backdrop-blur-sm border border-gray-200/50 px-4 py-2 rounded-xl shadow-sm animate-float z-20">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                    <span class="text-sm font-medium text-gray-700">124 transporteurs</span>
                                </div>
                                <div class="w-px h-4 bg-gray-200"></div>
                                <span class="text-xs text-gray-500">actifs maintenant</span>
                            </div>
                        </div>

                        <!-- Delivery Stats Card -->
                        <div class="absolute bottom-6 left-6 bg-white/95 backdrop-blur-sm border border-gray-200/50 px-4 py-3 rounded-xl shadow-sm animate-float-delayed hover:shadow-md transition-shadow cursor-pointer z-20">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i data-lucide="package-check" class="w-4 h-4 text-green-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900" id="delivery-count">2,847</div>
                                    <div class="text-xs text-gray-500">livraisons aujourd'hui</div>
                                </div>
                            </div>
                        </div>

                        <!-- Route Optimization Indicator -->
                        <div class="absolute top-1/2 -translate-y-1/2 -left-8 bg-blue-50 border border-blue-200/50 px-3 py-2 rounded-lg shadow-sm animate-bounce-slow z-20">
                            <div class="flex items-center space-x-2">
                                <i data-lucide="route" class="w-4 h-4 text-blue-600"></i>
                                <div>
                                    <div class="text-xs font-medium text-blue-900">Route optimisée</div>
                                    <div class="text-xs text-blue-600">-23% temps</div>
                                </div>
                            </div>
                        </div>

                        <!-- Real-time Notification -->
                        <div class="absolute top-1/3 -right-8 bg-orange-50 border border-orange-200/50 px-3 py-2 rounded-lg shadow-sm animate-slide-in-out z-20">
                            <div class="flex items-center space-x-2">
                                <i data-lucide="bell" class="w-4 h-4 text-orange-600"></i>
                                <div>
                                    <div class="text-xs font-medium text-orange-900">Nouvelle demande</div>
                                    <div class="text-xs text-orange-600">Casablanca → Rabat</div>
                                </div>
                            </div>
                        </div>

                        <!-- Trust Score Indicator -->
                        <div class="absolute bottom-1/4 -right-6 bg-purple-50 border border-purple-200/50 px-3 py-2 rounded-lg shadow-sm animate-float z-20">
                            <div class="flex items-center space-x-2">
                                <div class="flex">
                                    <i data-lucide="star" class="w-3 h-3 text-yellow-400 fill-current"></i>
                                    <i data-lucide="star" class="w-3 h-3 text-yellow-400 fill-current"></i>
                                    <i data-lucide="star" class="w-3 h-3 text-yellow-400 fill-current"></i>
                                    <i data-lucide="star" class="w-3 h-3 text-yellow-400 fill-current"></i>
                                    <i data-lucide="star" class="w-3 h-3 text-yellow-400 fill-current"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-medium text-purple-900">4.9/5</div>
                                    <div class="text-xs text-purple-600">confiance</div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 lg:py-24 bg-gray-50 morphing-bg">
        <div class="max-w-7xl mx-auto px-6 container-mobile-full">
            <div class="text-center mb-16 scroll-reveal">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Une plateforme conçue pour l'excellence
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Technologie avancée et processus rigoureux pour garantir sécurité, fiabilité et performance.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-gray-300 transition-all duration-500 interactive-overlay scroll-reveal-left glass-morphism floating-element" style="animation-delay: 0.1s;">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mb-4 pulse-glow">
                        <i data-lucide="shield" class="w-6 h-6 text-gray-700"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Sécurité renforcée</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Vérification d'identité obligatoire avec CIN et validation en plusieurs étapes pour garantir la confiance.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-gray-300 transition-all duration-500 interactive-overlay scroll-reveal-scale glass-morphism floating-element" style="animation-delay: 0.2s;">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mb-4 pulse-glow">
                        <i data-lucide="award" class="w-6 h-6 text-gray-700"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Qualité certifiée</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Système de notation transparent et processus de validation rigoureux pour maintenir l'excellence.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-gray-300 transition-all duration-500 interactive-overlay scroll-reveal-right glass-morphism floating-element" style="animation-delay: 0.3s;">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mb-4 pulse-glow">
                        <i data-lucide="globe" class="w-6 h-6 text-gray-700"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Couverture nationale</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Réseau étendu couvrant les principales villes du Maroc avec expansion continue.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-gray-300 transition-all duration-500 interactive-overlay scroll-reveal-left glass-morphism floating-element" style="animation-delay: 0.4s;">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mb-4 pulse-glow">
                        <i data-lucide="trending-up" class="w-6 h-6 text-gray-700"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Performance optimisée</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Algorithmes avancés pour optimiser les correspondances et réduire les délais de livraison.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 container-mobile-full">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Processus simplifié</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Découvrez comment notre plateforme simplifie vos livraisons en 4 étapes
                </p>
            </div>

            <div class="bg-white rounded-2xl p-8 lg:p-12">
                <div class="stepper" id="carryway-stepper">
                    <div class="progress-line" id="progressLine"></div>

                    <div class="step pending" data-step="0">
                        <div class="step-circle">01</div>
                        <div class="step-content">
                            <div class="step-number">Étape 1</div>
                            <div class="step-title">Inscription</div>
                            <div class="step-description">Créez votre compte professionnel</div>
                        </div>
                    </div>

                    <div class="step pending" data-step="1">
                        <div class="step-circle">02</div>
                        <div class="step-content">
                            <div class="step-number">Étape 2</div>
                            <div class="step-title">Publication</div>
                            <div class="step-description">Publiez votre demande de livraison</div>
                        </div>
                    </div>

                    <div class="step pending" data-step="2">
                        <div class="step-circle">03</div>
                        <div class="step-content">
                            <div class="step-number">Étape 3</div>
                            <div class="step-title">Connexion</div>
                            <div class="step-description">Trouvez le partenaire idéal</div>
                        </div>
                    </div>

                    <div class="step pending" data-step="3">
                        <div class="step-circle">04</div>
                        <div class="step-content">
                            <div class="step-number">Étape 4</div>
                            <div class="step-title">Livraison</div>
                            <div class="step-description">Suivez votre commande en temps réel</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Section -->
    <section class="py-16 lg:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 container-mobile-full">
            <div class="text-center mb-12 fade-in">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">La confiance au cœur de notre mission</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Processus de vérification rigoureux et support client dédié pour une expérience sécurisée.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center fade-in">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="shield" class="w-8 h-8 text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Vérification d'identité</h3>
                    <p class="text-gray-600 text-sm">
                        Validation obligatoire avec CIN et photo pour tous les utilisateurs de la plateforme.
                    </p>
                </div>

                <div class="text-center fade-in">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="message-circle" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Support dédié</h3>
                    <p class="text-gray-600 text-sm">
                        Équipe de support disponible pour vous accompagner à chaque étape de vos livraisons.
                    </p>
                </div>

                <div class="text-center fade-in">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="star" class="w-8 h-8 text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Système de notation</h3>
                    <p class="text-gray-600 text-sm">
                        Évaluations transparentes pour maintenir la qualité et la confiance dans notre communauté.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 lg:py-24 bg-gray-900">
        <div class="max-w-7xl mx-auto px-6 text-center container-mobile-full">
            <div class="max-w-3xl mx-auto fade-in">
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-6">Prêt à rejoindre CarryWay ?</h2>
                <p class="text-xl text-gray-300 mb-8">
                    Commencez dès aujourd'hui et découvrez une nouvelle façon de gérer vos livraisons.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button class="px-8 py-3 bg-white text-gray-900 rounded-lg font-medium hover:bg-gray-100 transition-all hover:scale-105 transform">
                        Créer un compte
                    </button>
                    <button class="px-8 py-3 border border-gray-600 text-white rounded-lg font-medium hover:border-gray-500 transition-all hover:scale-105 transform">
                        Contacter l'équipe
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-12" id="infos">
        <div class="max-w-7xl mx-auto px-6 container-mobile-full">
            <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-8 mb-8">
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 bg-gray-900 rounded-lg flex items-center justify-center">
                            <i data-lucide="truck" class="w-5 h-5 text-white"></i>
                        </div>
                        <span class="text-xl font-semibold text-gray-900">CarryWay</span>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4 max-w-md">
                        Plateforme de livraison collaborative connectant expéditeurs et transporteurs au Maroc.
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Produit</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Fonctionnalités</a></li>
                        <li><a href="#" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Tarification</a></li>
                        <li><a href="#" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Sécurité</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Entreprise</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">À propos</a></li>
                        <li><a href="#" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Carrières</a></li>
                        <li><a href="#" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Partenaires</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Centre d'aide</a></li>
                        <li><a href="#" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Contact</a></li>
                        <li><a href="#" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Statut</a></li>
                        <li><a href="#" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Communauté</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm text-gray-600 mb-4 md:mb-0">© {{ date('Y') }} CarryWay. Tous droits réservés.</p>
                <div class="flex space-x-6">
                    <a href="#" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Confidentialité</a>
                    <a href="#" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Conditions</a>
                    <a href="#" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Modern Scroll Effects & Interactive Overlays

        // Intersection Observer for scroll-triggered animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const scrollObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');

                    // Add staggered animation for child elements
                    const children = entry.target.querySelectorAll('.floating-element');
                    children.forEach((child, index) => {
                        setTimeout(() => {
                            child.style.animationPlayState = 'running';
                        }, index * 100);
                    });
                }
            });
        }, observerOptions);

        // Observe all scroll-reveal elements
        document.querySelectorAll('.scroll-reveal, .scroll-reveal-left, .scroll-reveal-right, .scroll-reveal-scale').forEach(el => {
            scrollObserver.observe(el);
        });

        // Parallax scrolling effect
        let ticking = false;

        function updateParallax() {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.scroll-parallax');

            parallaxElements.forEach((element, index) => {
                const speed = 0.5 + (index * 0.1);
                const yPos = -(scrolled * speed);
                element.style.transform = `translateY(${yPos}px)`;
            });

            // Scale effect on scroll
            const scaleElements = document.querySelectorAll('.scroll-scale');
            scaleElements.forEach(element => {
                const rect = element.getBoundingClientRect();
                const elementTop = rect.top;
                const elementHeight = rect.height;
                const windowHeight = window.innerHeight;

                if (elementTop < windowHeight && elementTop + elementHeight > 0) {
                    const progress = Math.max(0, Math.min(1, (windowHeight - elementTop) / (windowHeight + elementHeight)));
                    const scale = 0.8 + (progress * 0.2);
                    element.style.transform = `scale(${scale})`;
                }
            });

            ticking = false;
        }

        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateParallax);
                ticking = true;
            }
        }

        window.addEventListener('scroll', requestTick);

        // Interactive hover effects for cards
        document.querySelectorAll('.interactive-overlay').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
                this.style.boxShadow = '0 20px 40px rgba(0,0,0,0.1)';
                this.style.zIndex = '10';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
                this.style.boxShadow = '';
                this.style.zIndex = 'auto';
            });
        });

        // Glass morphism effect enhancement
        document.querySelectorAll('.glass-morphism').forEach(element => {
            element.addEventListener('mouseenter', function() {
                this.style.background = 'rgba(255, 255, 255, 0.2)';
                this.style.backdropFilter = 'blur(15px)';
            });

            element.addEventListener('mouseleave', function() {
                this.style.background = 'rgba(255, 255, 255, 0.1)';
                this.style.backdropFilter = 'blur(10px)';
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');

            if (mobileMenuToggle && mobileMenu) {
                mobileMenuToggle.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }



            // Scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, observerOptions);

            // Observe all animation elements
            document.querySelectorAll('.fade-in, .slide-in-left, .slide-in-right, .scale-line').forEach(el => {
                observer.observe(el);
            });

            // Counter animations for stats
            function animateCounter(element, target, suffix = '') {
                let current = 0;
                const increment = target / 100;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    element.textContent = Math.floor(current).toLocaleString() + suffix;
                }, 20);
            }

            // Animate stats when they come into view
            const statsObserver = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const statUsers = document.getElementById('stat-users');
                        const statDeliveries = document.getElementById('stat-deliveries');
                        const statCities = document.getElementById('stat-cities');
                        const statSatisfaction = document.getElementById('stat-satisfaction');

                        if (statUsers) animateCounter(statUsers, 15000, '+');
                        if (statDeliveries) animateCounter(statDeliveries, 45000, '+');
                        if (statCities) animateCounter(statCities, 12, '+');
                        if (statSatisfaction) animateCounter(statSatisfaction, 98, '%');

                        statsObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            const heroSection = document.getElementById('hero');
            if (heroSection) {
                statsObserver.observe(heroSection);
            }

            // Header scroll effect
            const header = document.getElementById('header');
            if (header) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        header.classList.add('bg-white/95');
                        header.classList.remove('bg-white/80');
                    } else {
                        header.classList.add('bg-white/80');
                        header.classList.remove('bg-white/95');
                    }
                });
            }

            // Dynamic integrated UI elements
            const heroVisual = document.querySelector('.hero-visual-container');
            if (heroVisual) {
                // Live counter animations
                const deliveryCounter = document.getElementById('delivery-count');
                const transporterCount = heroVisual.querySelector('.text-sm.font-medium.text-gray-700');

                // Animate delivery counter
                if (deliveryCounter) {
                    let currentCount = 2847;
                    setInterval(() => {
                        currentCount += Math.floor(Math.random() * 3) + 1;
                        deliveryCounter.textContent = currentCount.toLocaleString();
                    }, 5000);
                }

                // Animate transporter count
                if (transporterCount) {
                    let transporters = 124;
                    setInterval(() => {
                        const change = Math.floor(Math.random() * 6) - 2; // -2 to +3
                        transporters = Math.max(120, Math.min(150, transporters + change));
                        transporterCount.textContent = `${transporters} transporteurs`;
                    }, 3000);
                }

                // Interactive hover effects for UI cards
                const uiCards = heroVisual.querySelectorAll('[class*="bg-white/95"], [class*="bg-blue-50"], [class*="bg-orange-50"], [class*="bg-purple-50"]');
                uiCards.forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        this.style.transform = 'scale(1.05) translateY(-2px)';
                        this.style.boxShadow = '0 10px 25px rgba(0,0,0,0.1)';
                    });

                    card.addEventListener('mouseleave', function() {
                        this.style.transform = 'scale(1) translateY(0)';
                        this.style.boxShadow = '';
                    });
                });

                // Dynamic route optimization updates
                const routeCard = heroVisual.querySelector('.bg-blue-50');
                if (routeCard) {
                    const percentages = ['-23%', '-18%', '-31%', '-26%', '-29%'];
                    let routeIndex = 0;

                    setInterval(() => {
                        const percentElement = routeCard.querySelector('.text-blue-600');
                        if (percentElement) {
                            routeIndex = (routeIndex + 1) % percentages.length;
                            percentElement.textContent = `${percentages[routeIndex]} temps`;
                        }
                    }, 4000);
                }

                // Dynamic notification updates
                const notificationCard = heroVisual.querySelector('.bg-orange-50');
                if (notificationCard) {
                    const routes = [
                        'Casablanca → Rabat',
                        'Marrakech → Agadir',
                        'Fès → Meknès',
                        'Tanger → Tétouan',
                        'Oujda → Nador'
                    ];
                    let routeIndex = 0;

                    setInterval(() => {
                        const routeElement = notificationCard.querySelector('.text-orange-600');
                        if (routeElement) {
                            routeIndex = (routeIndex + 1) % routes.length;
                            routeElement.textContent = routes[routeIndex];
                        }
                    }, 6000);
                }

                // Trust score fluctuation
                const trustCard = heroVisual.querySelector('.bg-purple-50');
                if (trustCard) {
                    const scores = ['4.9/5', '4.8/5', '5.0/5', '4.9/5'];
                    let scoreIndex = 0;

                    setInterval(() => {
                        const scoreElement = trustCard.querySelector('.text-purple-900');
                        if (scoreElement) {
                            scoreIndex = (scoreIndex + 1) % scores.length;
                            scoreElement.textContent = scores[scoreIndex];
                        }
                    }, 8000);
                }

                // Add subtle parallax effect on scroll
                window.addEventListener('scroll', () => {
                    const scrolled = window.pageYOffset;
                    const parallaxElements = heroVisual.querySelectorAll('[class*="absolute"]');

                    parallaxElements.forEach((element, index) => {
                        const speed = 0.1 + (index * 0.05);
                        element.style.transform += ` translateY(${scrolled * speed}px)`;
                    });
                });
            }
        });

        // Process Stepper with Scroll-Triggered Animation
        class ScrollTriggeredProcessStepper {
            constructor() {
                this.currentStep = -1;
                this.steps = document.querySelectorAll('#carryway-stepper .step');
                this.progressLine = document.getElementById('progressLine');
                this.stepDuration = 1000; // 1 second per step (4 steps = 4 seconds total)
                this.hasCompleted = false;
                this.isAnimating = false;
                this.section = document.getElementById('how-it-works');

                this.init();
            }

            init() {
                // Set up intersection observer for scroll-triggered animation
                this.setupScrollObserver();
            }

            setupScrollObserver() {
                const observerOptions = {
                    threshold: 0.3, // Trigger when 30% of section is visible
                    rootMargin: '0px 0px -100px 0px'
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && !this.isAnimating) {
                            this.startAnimation();
                        } else if (!entry.isIntersecting && this.hasCompleted) {
                            // Reset animation when section leaves viewport
                            this.resetAnimation();
                        }
                    });
                }, observerOptions);

                if (this.section) {
                    observer.observe(this.section);
                }
            }

            startAnimation() {
                if (this.isAnimating) return;

                this.isAnimating = true;
                this.hasCompleted = false;
                this.currentStep = -1;

                // Reset all steps to pending state
                this.steps.forEach(step => {
                    step.classList.remove('active', 'completed');
                    step.classList.add('pending');
                });

                // Reset progress line
                if (this.progressLine) {
                    this.progressLine.className = 'progress-line';
                }

                // Start the animation sequence
                setTimeout(() => {
                    this.animateNextStep();
                }, 500); // Initial delay
            }

            animateNextStep() {
                if (this.currentStep < this.steps.length - 1) {
                    this.currentStep++;
                    this.updateSteps();
                    this.updateProgress();

                    // Schedule next step
                    setTimeout(() => {
                        this.animateNextStep();
                    }, this.stepDuration);
                } else {
                    // Animation completed
                    this.hasCompleted = true;
                    this.isAnimating = false;
                }
            }

            updateSteps() {
                this.steps.forEach((step, index) => {
                    step.classList.remove('active', 'completed', 'pending');

                    if (index < this.currentStep) {
                        step.classList.add('completed');
                        this.updateStepContent(step, 'checkmark');
                    } else if (index === this.currentStep) {
                        step.classList.add('active');
                        this.updateStepContent(step, 'dot');
                    } else {
                        step.classList.add('pending');
                        this.updateStepContent(step, 'number');
                    }
                });
            }

            updateStepContent(step, iconType) {
                const circle = step.querySelector('.step-circle');
                const stepNumber = step.dataset.step;

                if (iconType === 'checkmark') {
                    circle.innerHTML = `
                        <svg class="checkmark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <polyline points="20,6 9,17 4,12"></polyline>
                        </svg>
                    `;
                } else if (iconType === 'dot') {
                    circle.innerHTML = '<div style="width: 10px; height: 10px; background: currentColor; border-radius: 50%;"></div>';
                } else {
                    circle.textContent = String(parseInt(stepNumber) + 1).padStart(2, '0');
                }
            }

            updateProgress() {
                // Update progress line
                if (this.progressLine) {
                    this.progressLine.className = 'progress-line';
                    if (this.currentStep >= 0) {
                        this.progressLine.classList.add(`step-${this.currentStep + 1}`);
                        if (this.currentStep === this.steps.length - 1) {
                            this.progressLine.classList.add('active');
                        }
                    }
                }
            }

            resetAnimation() {
                // Reset to initial state when section leaves viewport
                this.hasCompleted = false;
                this.isAnimating = false;
                this.currentStep = -1;

                // Reset all steps to initial state
                this.steps.forEach((step, index) => {
                    step.classList.remove('active', 'completed');
                    step.classList.add('pending');

                    const circle = step.querySelector('.step-circle');
                    const stepNumber = step.dataset.step;
                    circle.textContent = String(parseInt(stepNumber) + 1).padStart(2, '0');
                });

                // Reset progress line
                if (this.progressLine) {
                    this.progressLine.className = 'progress-line';
                }
            }
        }

        // Initialize Process Stepper when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new ScrollTriggeredProcessStepper();
        });
    </script>

    <!-- Flash Messages -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                alert('{{ session('success') }}');
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                alert('{{ session('error') }}');
            });
        </script>
    @endif
</body>
</html>
