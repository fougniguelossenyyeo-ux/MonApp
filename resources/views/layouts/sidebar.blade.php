<div id="sidebar" class="sidebar fixed left-0 top-0 h-full w-64 bg-white shadow-lg z-30 transform translate-x-0 md:translate-x-0 sidebar-transition">

    <style>
        .sidebar-link.active {
            background: linear-gradient(to right, #6366F1, #8B5CF6); /* dégradé comme le logo */
            color: white;
        }
    </style>

    <!-- Header du sidebar -->
    <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <div class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
            <div id="logoText">
                <h1 class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent whitespace-nowrap">
                    DPaie
                </h1>
            </div>
        </div>
        <button id="sidebarClose" class="md:hidden text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 px-4">
        <div class="space-y-1">

            <!-- Tableau de bord -->
            <a href="{{ route('dashboard') }}" 
               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home mr-3"></i>
                <span id="dashboardText">Tableau de bord</span>
            </a>

            <!-- Enregistrer DP -->
            <a href="{{ route('demandes.create') }}" 
               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('demandes.create') ? 'active' : '' }}">
                <i class="fas fa-plus-circle mr-3"></i>
                <span id="registerText">Enregistrer DP</span>
            </a>

            <!-- Faire un paiement -->
            <a href="#" 
               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('paiement') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave mr-3"></i>
                <span id="paymentText">Faire un paiement</span>
            </a>

            <!-- Demandes -->
            <a href="{{ route('demandes.index') }}" 
               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('demandes.index') ? 'active' : '' }}">
                <i class="fas fa-chart-bar mr-3"></i>
                <span id="requestsText">Demandes</span>
            </a>

            <!-- Archives -->
            <a href="#" 
               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('archiveDP') ? 'active' : '' }}">
                <i class="fas fa-archive mr-3"></i>
                <span id="archivesText">Archives</span>
            </a>

            <!-- Administration -->
            <a href="{{ route('users.list') }}" 
               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('users.list') ? 'active' : '' }}">
                <i class="fas fa-cog mr-3"></i>
                <span id="adminText">Administration</span>
            </a>

        </div>

        <!-- Profil utilisateur et logout -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                    <span class="text-indigo-800 font-medium">
                        {{ strtoupper(substr(Auth::user()->prenom,0,1) . substr(Auth::user()->nom,0,1)) }}
                    </span>
                </div>
                <a href="{{ route('users.edit', Auth::user()->id) }}" id="userText" class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->role?->libelle ?? 'Non défini' }}</p>
                </a>
            </div>

            <a href="{{ route('logout') }}" id="logoutBtn"
               class="w-full flex items-center justify-center px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                <i class="fas fa-sign-out-alt mr-2"></i>
                <span id="logoutText">Déconnexion</span>
            </a>
        </div>
    </nav>

    <!-- Script JS pour clic actif (optionnel) -->
    <script>
        const links = document.querySelectorAll('.sidebar-link');
        links.forEach(link => {
            link.addEventListener('click', () => {
                links.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
            });
        });
    </script>
</div>
