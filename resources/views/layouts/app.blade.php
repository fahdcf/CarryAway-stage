<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'CarryWay'))</title>
    <meta name="description" content="{{ $description ?? 'Plateforme de livraison collaborative au Maroc. Connectez expéditeurs et transporteurs en toute sécurité.' }}">
    <meta name="keywords" content="{{ $keywords ?? 'livraison, transport, Maroc, collaborative, sécurisé' }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $title ?? config('app.name', 'CarryWay') }}">
    <meta property="og:description" content="{{ $description ?? 'Plateforme de livraison collaborative au Maroc' }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('images/og-image.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $title ?? config('app.name', 'CarryWay') }}">
    <meta property="twitter:description" content="{{ $description ?? 'Plateforme de livraison collaborative au Maroc' }}">
    <meta property="twitter:image" content="{{ $ogImage ?? asset('images/og-image.jpg') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/carryway-modern.css') }}">
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
    @stack('styles')

    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
    </style>

    <!-- Analytics -->
    @if(config('services.google_analytics.id'))
        <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google_analytics.id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ config('services.google_analytics.id') }}');
        </script>
    @endif
</head>
<body class="overflow-x-hidden">
    <!-- Alpine.js for sidebar state -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <div x-data="{ open: true }" class="flex h-screen">
        <!-- Sidebar - Full height from top to bottom -->
        <aside class="bg-gray-900 text-white transition-all duration-300 flex-shrink-0 z-40" 
               :class="open ? 'w-64' : 'w-16'">
            @include('layouts.sidebar')
        </aside>
        
        <!-- Main container - takes remaining width -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Header - Floating on top -->
            <header class="bg-white shadow-sm border-b border-gray-200 h-16 flex-shrink-0 z-50">
                @include('components.header')
            </header>
            
            <!-- Main content area -->
            <main class="flex-1 overflow-auto bg-gray-50">
                <div class="p-6">
                    @yield('content')
                </div>
                @include('components.footer')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/carryway-modern.js') }}"></script>

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>

    @stack('scripts')

    <!-- Flash Messages -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.CarryWayApp && window.CarryWayApp.prototype.showNotification) {
                    const app = new window.CarryWayApp();
                    app.showNotification('{{ session('success') }}', 'success');
                }
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.CarryWayApp && window.CarryWayApp.prototype.showNotification) {
                    const app = new window.CarryWayApp();
                    app.showNotification('{{ session('error') }}', 'error');
                }
            });
        </script>
    @endif

    @if(session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.CarryWayApp && window.CarryWayApp.prototype.showNotification) {
                    const app = new window.CarryWayApp();
                    app.showNotification('{{ session('warning') }}', 'warning');
                }
            });
        </script>
    @endif

    @if(session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.CarryWayApp && window.CarryWayApp.prototype.showNotification) {
                    const app = new window.CarryWayApp();
                    app.showNotification('{{ session('info') }}', 'info');
                }
            });
        </script>
    @endif
</body>
</html>
