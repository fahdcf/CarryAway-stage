
<aside class="h-full flex flex-col">
    <div class="flex flex-col h-full">
        <!-- Top section with logo -->
        <div class="flex-shrink-0 p-4">
            <a href="{{ route('home') }}"
               class="flex items-center px-2 py-2 rounded-xl transition-all hover:bg-gray-800"
               :class="open ? 'justify-start' : 'justify-center'"
               style="background: transparent;">
                <div class="flex items-center w-full"
                     :class="open ? 'justify-start' : 'justify-center'">
                    <span class="bg-gray-800 rounded-lg p-2" style="display:flex;align-items:center;justify-content:center;">
                        <i data-lucide="truck" class="icon"></i>
                    </span>
                    <span x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="font-bold text-lg sidebar-label ml-2">CarryWay</span>
                </div>
            </a>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 px-4">
            <ul class="space-y-2">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('dashboard.index') }}"
                       class="flex items-center px-3 py-3 rounded-xl transition-all hover:bg-gray-800 {{ request()->routeIs('dashboard.index') ? 'bg-gray-800 font-semibold' : '' }}"
                       :class="open ? 'justify-start' : 'justify-center'">
                        <div class="flex items-center w-full"
                             :class="open ? 'justify-start' : 'justify-center'">
                            <i data-lucide="home" class="text-white flex-shrink-0"></i>
                            <span x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="sidebar-label ml-3">Accueil</span>
                        </div>
                    </a>
                </li>
                
                <!-- Mes annonces -->
                <li>
                    <a href="{{ route('dashboard.my-announcements') }}"
                       class="flex items-center px-3 py-3 rounded-xl transition-all hover:bg-gray-800 {{ request()->routeIs('dashboard.my-announcements') ? 'bg-gray-800 font-semibold' : '' }}"
                       :class="open ? 'justify-start' : 'justify-center'">
                        <div class="flex items-center w-full"
                             :class="open ? 'justify-start' : 'justify-center'">
                            <i data-lucide="list" class="text-white flex-shrink-0"></i>
                            <span x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="sidebar-label ml-3">Mes annonces</span>
                        </div>
                    </a>
                </li>
                
                <!-- Toutes les annonces -->
                <li>
                    <a href="{{ route('dashboard.all-announcements') }}"
                       class="flex items-center px-3 py-3 rounded-xl transition-all hover:bg-gray-800 {{ request()->routeIs('dashboard.all-announcements') ? 'bg-gray-800 font-semibold' : '' }}"
                       :class="open ? 'justify-start' : 'justify-center'">
                        <div class="flex items-center w-full"
                             :class="open ? 'justify-start' : 'justify-center'">
                            <i data-lucide="layers" class="text-white flex-shrink-0"></i>
                            <span x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="sidebar-label ml-3">Toutes les annonces</span>
                        </div>
                    </a>
                </li>
                
                <!-- Messages -->
                <li>
                    <a href="{{ route('messages.index') }}" class="flex items-center px-3 py-3 rounded-xl transition-all hover:bg-gray-800 {{ request()->routeIs('messages.index') ? 'bg-gray-800 font-semibold' : '' }}"
                       :class="open ? 'justify-start' : 'justify-center'">
                        <div class="flex items-center w-full"
                             :class="open ? 'justify-start' : 'justify-center'">
                            <i data-lucide="message-circle" class="text-white flex-shrink-0"></i>
                            <span x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="sidebar-label ml-3">Messages</span>
                        </div>
                    </a>
                </li>
                
                <!-- Mes demandes -->
                <li>
                    <a href="{{ route('requests.index') }}" class="flex items-center px-3 py-3 rounded-xl transition-all hover:bg-gray-800 {{ request()->routeIs('requests.index') ? 'bg-gray-800 font-semibold' : '' }}"
                       :class="open ? 'justify-start' : 'justify-center'">
                        <div class="flex items-center w-full"
                             :class="open ? 'justify-start' : 'justify-center'">
                            <i data-lucide="file-text" class="text-white flex-shrink-0"></i>
                            <span x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="sidebar-label ml-3">Mes demandes</span>
                        </div>
                    </a>
                </li>
                
                <!-- Notifications -->
                <li>
                    <a href="{{ route('notifications.index') }}" class="flex items-center px-3 py-3 rounded-xl transition-all hover:bg-gray-800 {{ request()->routeIs('notifications.index') ? 'bg-gray-800 font-semibold' : '' }}"
                       :class="open ? 'justify-start' : 'justify-center'">
                        <div class="flex items-center w-full"
                             :class="open ? 'justify-start' : 'justify-center'">
                            <i data-lucide="bell" class="text-white flex-shrink-0"></i>
                            <span x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="sidebar-label ml-3">Notifications</span>
                        </div>
                    </a>
                </li>
                
                <!-- Profil -->
                <li>
                    <a href="{{ route('profile.index') }}"
                       class="flex items-center px-3 py-3 rounded-xl transition-all hover:bg-gray-800 {{ request()->routeIs('profile.index') ? 'bg-gray-800 font-semibold' : '' }}"
                       :class="open ? 'justify-start' : 'justify-center'">
                        <div class="flex items-center w-full"
                             :class="open ? 'justify-start' : 'justify-center'">
                            <i data-lucide="user" class="text-white flex-shrink-0"></i>
                            <span x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="sidebar-label ml-3">Profil</span>
                        </div>
                    </a>
                </li>
                
                <!-- Paramètres -->
                <li>
                    <a href="{{ route('settings.index') }}"
                       class="flex items-center px-3 py-3 rounded-xl transition-all hover:bg-gray-800 {{ request()->routeIs('settings.index') ? 'bg-gray-800 font-semibold' : '' }}"
                       :class="open ? 'justify-start' : 'justify-center'">
                        <div class="flex items-center w-full"
                             :class="open ? 'justify-start' : 'justify-center'">
                            <i data-lucide="settings" class="text-white flex-shrink-0"></i>
                            <span x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="sidebar-label ml-3">Paramètres</span>
                        </div>
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- Bottom section with logout -->
        <div class="flex-shrink-0 p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"
                   class="flex items-center px-3 py-3 rounded-xl transition-all hover:bg-gray-800"
                   :class="open ? 'justify-start' : 'justify-center'"
                   tabindex="0">
                    <div class="flex items-center w-full"
                         :class="open ? 'justify-start' : 'justify-center'">
                        <i data-lucide="log-out" class="text-white flex-shrink-0"></i>
                        <span x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="sidebar-label ml-3">Déconnexion</span>
                    </div>
                </a>
            </form>
        </div>
    </div>
</aside>
