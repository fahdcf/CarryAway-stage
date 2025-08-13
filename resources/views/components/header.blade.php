{{-- Header Component --}}
<header class="w-full h-full flex items-center px-6">
    <div class="flex items-center justify-between w-full">
        <!-- Left side: Sidebar toggle and logo -->
        <div class="flex items-center">
            <!-- Collapse/Expand Sidebar Button -->
            <button @click="open = !open"
                class="bg-gray-900 text-white rounded-full p-2 mr-4 shadow-lg transition-all duration-300 focus:outline-none hover:bg-gray-800"
                aria-label="Toggle sidebar">
                <span x-show="open">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </span>
                <span x-show="!open">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </span>
            </button>
            
           
        </div>

        <!-- Right side: Actions and user menu -->
        <div class="flex items-center space-x-4">
            <!-- Notification and Search buttons -->
            <button class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" title="Notifications">
                <i data-lucide="bell" class="w-5 h-5"></i>
            </button>
            <button class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" title="Rechercher">
                <i data-lucide="search" class="w-5 h-5"></i>
            </button>
            
            @guest
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">Se connecter</a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Commencer</a>
            @else
                <div class="relative">
                    <button class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors" id="user-menu-toggle">
                        <i data-lucide="user" class="w-5 h-5"></i>
                        <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </button>
                    {{-- User Dropdown Menu --}}
                    <div class="absolute right-0 top-full mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-50" id="user-menu">
                        <div class="py-1">
                            <a href="{{ route('dashboard.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i data-lucide="layout-dashboard" class="w-4 h-4 mr-2 inline"></i>
                                Tableau de bord
                            </a>
                            <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i data-lucide="user" class="w-4 h-4 mr-2 inline"></i>
                                Profil
                            </a>
                            <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i data-lucide="settings" class="w-4 h-4 mr-2 inline"></i>
                                Paramètres
                            </a>
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i data-lucide="log-out" class="w-4 h-4 mr-2 inline"></i>
                                    Se déconnecter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endguest
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
