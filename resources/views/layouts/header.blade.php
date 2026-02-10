<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-4">
            <h1 id="pageTitle" class="text-2xl font-bold text-gray-900">
                @switch(true)
                    {{-- Tableau de bord --}}
                    @case(request()->routeIs('dashboard'))
                        Tableau de bord - Demandes de Paiement
                        @break

                    {{-- Demandes --}}
                    @case(request()->routeIs('demandes.create'))
                        FORMULAIRE DE SAISIE DP
                        @break
                    @case(request()->routeIs('demandes.index'))
                        Liste des Demandes
                        @break
                    @case(request()->routeIs('demandes.show'))
                        Détails d'une Demande
                        @break
                    @case(request()->routeIs('demandes.valider'))
                        Demandes Validées
                        @break
                    @case(request()->routeIs('demandes.showValider'))
                        Détails d'une Demande Validée
                        @break
                    @case(request()->routeIs('demandes.enAttenteDaf'))
                        Demandes en Attente DAF
                        @break
                    @case(request()->routeIs('demandes.showEnAttenteDaf'))
                        Détails d'une Demande en Attente DAF
                        @break
                    @case(request()->routeIs('demandes.enAttenteDirecteur'))
                        Demandes en Attente Directeur
                        @break
                    @case(request()->routeIs('demandes.showEnAttenteDirecteur'))
                        Détails d'une Demande en Attente Directeur
                        @break
                    @case(request()->routeIs('demandes.enAttenteControl'))
                        Demandes en Attente Contrôleur
                        @break
                    @case(request()->routeIs('demandes.show_enattente'))
                        Détails d'une Demande en Attente Contrôleur
                        @break

                    {{-- Paiements --}}
                    @case(request()->routeIs('paiements.index') || request()->routeIs('faire-paiement.index'))
                        Paiement des Demandes
                        @break
                    @case(request()->routeIs('paiements.valides'))
                        Paiements Effectués
                        @break
                    @case(request()->routeIs('paiements.partiellement'))
                        Paiements Partiels
                        @break
                    @case(request()->routeIs('paiements.encours'))
                        Paiements en Cours
                        @break
                    @case(request()->routeIs('paiements.emis'))
                        Paiements Émis
                        @break
                    @case(request()->routeIs('paiements.show'))
                        Détails du Paiement
                        @break
                    @case(request()->routeIs('paiements.dg_valider'))
                        Validation du Paiement par le DG
                        @break

                    {{-- Administration --}}
                    @case(request()->routeIs('users.list'))
                        Gestion des Utilisateurs
                        @break
                    @case(request()->routeIs('roles.index'))
                        Liste des Rôles
                        @break
                    @case(request()->routeIs('roles.create'))
                        Création d’un Rôle
                        @break
                    @case(request()->routeIs('roles.edit'))
                        Édition d’un Rôle
                        @break
                    @case(request()->routeIs('entites.index'))
                        Liste des Entités
                        @break
                    @case(request()->routeIs('entites.create'))
                        Création d’une Entité
                        @break
                    @case(request()->routeIs('entites.edit'))
                        Édition d’une Entité
                        @break

                    @default
                        {{ $pageTitle ?? '' }}
                @endswitch
            </h1>
        </div>
    </div>
</header>
