<body class="overflow-x-hidden">

<!-- Overlay mobile -->
<div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-20 md:hidden"></div>

<!-- Bouton burger (menu mobile) -->
<button id="sidebarToggle" class="md:hidden p-2 text-gray-700 z-40 fixed top-4 left-4 bg-white rounded shadow">
    ☰
</button>

<!-- Sidebar -->
<div id="sidebar" class="sidebar fixed left-0 top-0 h-full w-64 max-w-full bg-white shadow-lg z-30 transform -translate-x-full md:translate-x-0 sidebar-transition">

    <style>
        .sidebar-link.active {
            background: linear-gradient(to right, #6366F1, #8B5CF6);
            color: white;
        }
        .sidebar-transition {
            transition: transform 0.3s ease;
        }
        .user-profile-link.active {
            background: linear-gradient(to right, #6366F1, #8B5CF6);
            border-radius: 0.5rem;
        }
        .user-profile-link.active p {
            color: white !important;
        }
        .user-profile-link.active span {
            color: rgba(255,255,255,0.85) !important;
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
        <!-- Bouton fermer mobile -->
        <button id="sidebarClose" class="text-gray-500 hover:text-gray-700 md:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 px-4">
        <div class="space-y-1">

            <!-- Tableau de bord -->
            <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-home mr-3"></i>
                <span id="dashboardText">Tableau de bord</span>
            </a>

            <!-- Enregistrer DP -->
           @if(Auth::user()->hasPermission('cree_demande'))
<a href="{{ route('demandes.create') }}" 
   class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('demandes.create') ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
    
    <i class="fas fa-plus-circle mr-3"></i>
    <span id="registerText">Enregistrer DP</span>

</a>
@endif

            <!-- Faire un paiement -->
           @php
    $canInitPaiement = auth()->user()->role &&
        auth()->user()->role->permissions->contains(function ($perm) {
            return str_starts_with($perm->nom, 'initier_paiement_');
        });
@endphp

@if($canInitPaiement)
    <a href="{{ route('paiements.index') }}" 
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg 
       {{ request()->routeIs('paiements.index') ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
        
        <i class="fas fa-money-bill-wave mr-3"></i>
        <span id="paymentText">Faire un paiement</span>
    </a>
@endif

            <!-- Demandes -->
            <a href="{{ route('demandes.index') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('demandes.index') ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-chart-bar mr-3"></i>
                <span id="requestsText">Demandes</span>
            </a>

            <!-- Paiements -->
            <a href="{{ route('paiements.emis') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('paiements.emis') ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-archive mr-3"></i>
                <span id="archivesText">Paiements</span>
            </a>

            <!-- Administration -->
    @auth
    @if(auth()->user()->email === 'superadmin@kama.ci')
        <!-- Administration -->
        <a href="{{ route('users.list') }}" 
           class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg 
           {{ request()->routeIs('users.list') ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
            
            <i class="fas fa-cog mr-3"></i>
            <span id="adminText">Administration</span>
        </a>
    @endif
@endauth
        </div>

        <!-- Profil utilisateur et logout -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200">

            {{-- Lien profil — actif si on est sur la page d'édition de l'utilisateur connecté --}}
            <a href="{{ route('users.edit', Auth::user()->id) }}"
               id="userText"
               class="user-profile-link flex items-center mb-4 px-2 py-2 transition-colors {{ request()->routeIs('users.edit') && request()->route('user') == Auth::user()->id ? 'active' : 'hover:bg-gray-100 rounded-lg' }}">
                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-indigo-800 font-medium text-sm">
                        {{ strtoupper(substr(Auth::user()->prenom, 0, 1) . substr(Auth::user()->nom, 0, 1)) }}
                    </span>
                </div>
                <div class="ml-3 overflow-hidden">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->role?->libelle ?? 'Non défini' }}</p>
                </div>
            </a>

            <a href="{{ route('logout') }}" id="logoutBtn" class="w-full flex items-center justify-center px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                <i class="fas fa-sign-out-alt mr-2"></i>
                <span id="logoutText">Déconnexion</span>
            </a>
        </div>
    </nav>

    <!-- Script JS -->
    <script>
        // Gestion du clic actif sur les liens de navigation (hors profil)
        const navLinks = document.querySelectorAll('.sidebar-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                // Retirer active de tous les liens de nav
                navLinks.forEach(l => l.classList.remove('active'));
                // Retirer active du lien profil
                document.getElementById('userText').classList.remove('active');
                // Appliquer active sur le lien cliqué
                link.classList.add('active');
            });
        });

        // Gestion du clic sur le lien profil
        const userLink = document.getElementById('userText');
        if (userLink) {
            userLink.addEventListener('click', () => {
                // Retirer active de tous les liens de nav
                navLinks.forEach(l => l.classList.remove('active'));
                // Appliquer active sur le lien profil
                userLink.classList.add('active');
            });
        }

        // Sidebar toggle mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose  = document.getElementById('sidebarClose');
        const sidebar       = document.getElementById('sidebar');
        const overlay       = document.getElementById('overlay');

        // Ouvrir
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        });

        // Fermer via bouton ×
        sidebarClose.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        // Fermer en cliquant sur l'overlay
        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    </script>
</div>