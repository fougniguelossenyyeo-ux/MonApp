<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between py-4 space-y-4 md:space-y-0">
            <!-- Titre de la page -->
            <div>
                <h1 id="pageTitle" class="text-2xl font-bold text-gray-900">
                    @if(request()->routeIs('dashboard'))
                        Tableau de bord - Demandes de Paiement
                    @elseif(request()->routeIs('demandes.create'))
                        Enregistrement d'une demande de paiement
                    @elseif(request()->routeIs('demandes.index'))
                        Afficher les demandes
                    @elseif(request()->routeIs('demandes.valider'))
                        Demandes validées
                    @elseif(request()->routeIs('demandes.showValider'))
                        Détails d'une demande validée
                    @elseif(request()->routeIs('demandes.enAttenteDaf'))
                        Demandes en attente DAF
                    @elseif(request()->routeIs('demandes.showEnAttenteDaf'))
                        Détails demande en attente DAF
                    @elseif(request()->routeIs('demandes.enAttenteDirecteur'))
                        Demandes en attente Directeur
                    @elseif(request()->routeIs('demandes.showEnAttenteDirecteur'))
                        Détails demande en attente Directeur
                    @elseif(request()->routeIs('demandes.enAttenteControl'))
                        Demandes en attente Contrôleur
                    @elseif(request()->routeIs('demandes.show_enattente'))
                        Détails demande en attente Contrôleur
                    @elseif(request()->routeIs('demandes.show'))
                        Détails demande
                    @elseif(request()->routeIs('users.list'))
                        Administration
                    @elseif(request()->routeIs('roles.index'))
                        Liste des rôles
                    @elseif(request()->routeIs('roles.create'))
                        Création d'un rôle
                    @elseif(request()->routeIs('roles.edit'))
                        Édition d'un rôle
                    @elseif(request()->routeIs('entites.index'))
                        Liste des entités
                    @elseif(request()->routeIs('entites.create'))
                        Création d'une entité
                    @elseif(request()->routeIs('entites.edit'))
                        Édition d'une entité
                    @else
                        {{ $pageTitle ?? '' }}
                    @endif
                </h1>
            </div>

            <!-- Contrôles -->
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                <!-- Boutons ou autres éléments -->
            </div>
        </div>
    </div>
</header>
