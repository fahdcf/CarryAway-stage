{{-- Footer Component --}}
<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- Company Info --}}
            <div class="lg:col-span-2">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-600 rounded-lg p-2 mr-3">
                        <i data-lucide="truck" class="w-6 h-6 text-white"></i>
                    </div>
                    <span class="text-xl font-bold">CarryWay</span>
                </div>
                <p class="text-gray-300 mb-4 max-w-md">
                    La plateforme de livraison collaborative qui connecte expéditeurs et transporteurs au Maroc. 
                    Sécurisé, fiable et efficace.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i data-lucide="twitter" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i data-lucide="linkedin" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>

            {{-- Services --}}
            <div>
                <h4 class="text-lg font-semibold mb-4">Services</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Pour expéditeurs</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Pour transporteurs</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Comment ça marche</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Sécurité</a></li>
                </ul>
            </div>

            {{-- Support --}}
            <div>
                <h4 class="text-lg font-semibold mb-4">Support</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('help') ?? '#' }}" class="text-gray-300 hover:text-white transition-colors">Centre d'aide</a></li>
                    <li><a href="{{ route('contact') ?? '#' }}" class="text-gray-300 hover:text-white transition-colors">Contact</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Signaler un problème</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition-colors">FAQ</a></li>
                </ul>
            </div>
        </div>

        {{-- Bottom Section --}}
        <div class="border-t border-gray-800 mt-8 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm mb-4 md:mb-0">
                    © {{ date('Y') }} CarryWay. Tous droits réservés.
                </p>
                <div class="flex space-x-6 text-sm">
                    <a href="{{ route('terms') ?? '#' }}" class="text-gray-400 hover:text-white transition-colors">
                        Conditions d'utilisation
                    </a>
                    <a href="{{ route('privacy') ?? '#' }}" class="text-gray-400 hover:text-white transition-colors">
                        Politique de confidentialité
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        Cookies
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
