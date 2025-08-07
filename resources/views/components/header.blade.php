{{-- Header Component --}}
<header class="header header-default" id="header">
    <div class="container">
        <nav class="nav">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="logo">
                <div class="logo-icon">
                    <i data-lucide="truck" class="icon text-white"></i>
                </div>
                <span class="logo-text">CarryWay</span>
            </a>

            {{-- Desktop Navigation --}}
            <ul class="nav-links lg:flex">
                <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'text-gray-900' : '' }}">Accueil</a></li>
                <li><a href="#features" class="nav-link">Solutions</a></li>
                <li><a href="#how-it-works" class="nav-link">Entreprise</a></li>
                <li><a href="#" class="nav-link">Ressources</a></li>
                <li><a href="#" class="nav-link">À propos</a></li>
            </ul>

            {{-- Desktop Actions --}}
            <div class="nav-actions lg:flex">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-secondary">Se connecter</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Commencer</a>
                @else
                    <div class="relative">
                        <button class="btn btn-secondary" id="user-menu-toggle">
                            <i data-lucide="user" class="icon-sm mr-2"></i>
                            {{ Auth::user()->name }}
                            <i data-lucide="chevron-down" class="icon-sm ml-2"></i>
                        </button>
                        
                        {{-- User Dropdown Menu --}}
                        <div class="absolute right-0 top-full mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg hidden" id="user-menu">
                            <div class="py-1">
                                <a href="{{ route('dashboard.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i data-lucide="layout-dashboard" class="icon-sm mr-2 inline"></i>
                                    Tableau de bord
                                </a>
                                <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i data-lucide="user" class="icon-sm mr-2 inline"></i>
                                    Profil
                                </a>
                                <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i data-lucide="settings" class="icon-sm mr-2 inline"></i>
                                    Paramètres
                                </a>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i data-lucide="log-out" class="icon-sm mr-2 inline"></i>
                                        Se déconnecter
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>

            {{-- Mobile Menu Toggle --}}
            <button class="mobile-menu-toggle lg:hidden" id="mobile-menu-toggle">
                <i data-lucide="menu" class="icon"></i>
            </button>
        </nav>

        {{-- Mobile Menu --}}
        <div class="mobile-menu hidden" id="mobile-menu">
            <div class="mobile-menu-links">
                <a href="{{ route('home') }}" class="mobile-menu-link">Accueil</a>
                <a href="#features" class="mobile-menu-link">Solutions</a>
                <a href="#how-it-works" class="mobile-menu-link">Entreprise</a>
                <a href="#" class="mobile-menu-link">Ressources</a>
                <a href="#" class="mobile-menu-link">À propos</a>
            </div>
            
            <div class="mobile-menu-actions">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-secondary w-full text-center">Se connecter</a>
                    <a href="{{ route('register') }}" class="btn btn-primary w-full text-center">Commencer</a>
                @else
                    <a href="{{ route('dashboard.index') }}" class="btn btn-secondary w-full text-center">
                        <i data-lucide="layout-dashboard" class="icon-sm mr-2"></i>
                        Tableau de bord
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary w-full text-center">
                            <i data-lucide="log-out" class="icon-sm mr-2"></i>
                            Se déconnecter
                        </button>
                    </form>
                @endguest
            </div>
        </div>
    </div>
</header>

{{-- Header JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // User menu toggle
    const userMenuToggle = document.getElementById('user-menu-toggle');
    const userMenu = document.getElementById('user-menu');
    
    if (userMenuToggle && userMenu) {
        userMenuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenu.classList.toggle('hidden');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function() {
            userMenu.classList.add('hidden');
        });
        
        // Prevent menu from closing when clicking inside
        userMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
});
</script>
