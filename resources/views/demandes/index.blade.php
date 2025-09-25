@extends('layouts.template')
@section('maincontent')

@include('layouts.demande')

  <!-- Contenu principal -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- En-tête de page -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <p class="text-gray-600 mt-1">Historique complet des demandes de paiement</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="flex items-center space-x-4">
                        <div class="text-sm text-gray-600">
                            <span id="archiveCount">1</span> demande
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Barre de recherche -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="searchInput"
                                placeholder="Rechercher par référence, entité, montant..." 
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            >
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Période -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Période</label>
                        <select id="dateRange" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <option value="">Toutes les périodes</option>
                            <option value="last30">30 derniers jours</option>
                            <option value="last90">90 derniers jours</option>
                            <option value="lastYear">12 derniers mois</option>
                            <option value="custom">Personnalisé</option>
                        </select>
                    </div>

                    <!-- Période personnalisée (Masquée par défaut) -->
                    <div id="customDateRange" class="md:col-span-2 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Période personnalisée</label>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <input type="date" id="startDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            </div>
                            <div>
                                <input type="date" id="endDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            </div>
                        </div>
                    </div>

                    <!-- Filtre Entité -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Entité</label>
                        <select id="entityFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <option value="">Toutes les entités</option>
                            <option value="GAZ">GAZ</option>
                            <option value="KTLS">KTLS</option>
                            <option value="KAMACI">KAMACI</option>
                        </select>
                    </div>

                    <!-- Filtre Pays -->
                 
                    <!-- Filtre Montant -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Montant</label>
                        <select id="amountRange" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <option value="">Tous les montants</option>
                            <option value="0-1000000">Moins de 1 000 000 F CFA</option>
                            <option value="1000000-5000000">1M - 5M F CFA</option>
                            <option value="5000000-10000000">5M - 10M F CFA</option>
                            <option value="10000000+">Plus de 10M F CFA</option>
                        </select>
                    </div>

                    <!-- Bouton de recherche -->
                    <div class="flex items-end">
                        <button id="searchButton" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-search mr-2"></i>
                            Rechercher
                        </button>
                    </div>
                </div>
            </div>

            <!-- Grille des cartes d'archive -->
            <div id="archiveGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Carte d'archive unique -->
                <div class="archive-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden cursor-pointer">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">DP-CI-2024-06-15-001</h3>
                                <p class="text-sm text-gray-500 mt-1">15/06/2024</p>
                            </div>
                            <span class="status-badge bg-yellow-50 text-yellow-700 ring-1 ring-yellow-200">
                                En de validation par le controleur
                            </span>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-gray-700 font-medium">Mamadou Diallo</p>
                            <div class="flex space-x-2 mt-1">
                                <span class="entity-badge bg-slate-100 text-slate-700 inline-block">
                                    GAZ
                                </span>
                                <span class="country-badge bg-orange-100 text-orange-800 inline-block">
                                    Côte d'Ivoire
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-sm text-gray-500">Montant</p>
                                <p class="text-lg font-semibold text-gray-900">2 500 000 F CFA</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Archivée le</p>
                                <p class="text-sm font-medium text-gray-900">01/07/2024</p>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-100 pt-4">
                            <p class="text-sm text-gray-600 line-clamp-2">Achat de matériel informatique pour le service informatique</p>
                        </div>
                        
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-xs text-gray-500">Archivée par Amina Koné</span>
                            <div class="flex space-x-2">
                                <button class="view-details-btn text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    Voir détails
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- État vide -->
            <div id="emptyState" class="hidden text-center py-12">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune archive trouvée</h3>
                <p class="text-gray-500">Aucune demande de paiement archivée ne correspond à vos critères de recherche.</p>
            </div>

            <!-- Pagination -->
            <div id="pagination" class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 mt-8 rounded-lg shadow-sm">
                <div class="flex flex-1 justify-between sm:hidden">
                    <a href="#" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Précédent</a>
                    <a href="#" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Suivant</a>
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Affichage de <span class="font-medium">1</span> à <span class="font-medium">1</span> sur <span class="font-medium">1</span> résultats
                        </p>
                    </div>
                    <div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <a href="#" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Précédent</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="#" aria-current="page" class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">1</a>
                            <a href="#" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Suivant</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de détail d'archive -->
    <div id="detailModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">
                                    Détails de la Demande
                                </h3>
                                <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-4" id="modalContent">
                                <!-- Le contenu du modal sera rempli par JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button id="closeDetailModal" type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Éléments du DOM
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const logoutBtn = document.getElementById('logoutBtn');
        const toggleButton = document.getElementById('toggleSidebar');
        const toggleIcon = document.getElementById('toggleIcon');
        const toggleText = document.getElementById('toggleText');
        const logoText = document.getElementById('logoText');
        const userText = document.getElementById('userText');
        const logoutText = document.getElementById('logoutText');
        
        // Éléments de texte de navigation
        const dashboardText = document.getElementById('dashboardText');
        const registerText = document.getElementById('registerText');
        const paymentText = document.getElementById('paymentText');
        const requestsText = document.getElementById('requestsText');
        const archivesText = document.getElementById('archivesText');
        const adminText = document.getElementById('adminText');
        
        let isCollapsed = false;

        // Fonction de bascule de la barre latérale
        function toggleSidebarFunction() {
            isCollapsed = !isCollapsed;
            
            if (isCollapsed) {
                // Réduire la barre latérale
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-16');
                
                // Masquer les éléments de texte
                logoText.classList.add('hidden');
                userText.classList.add('hidden');
                logoutText.classList.add('hidden');
                
                // Masquer le texte de navigation
                dashboardText.classList.add('hidden');
                registerText.classList.add('hidden');
                paymentText.classList.add('hidden');
                requestsText.classList.add('hidden');
                archivesText.classList.add('hidden');
                adminText.classList.add('hidden');
                
                // Changer le bouton de bascule
                toggleIcon.classList.remove('fa-chevron-left');
                toggleIcon.classList.add('fa-chevron-right');
                toggleText.textContent = '';
                
                // Ajuster le contenu principal
                document.getElementById('mainContent').classList.remove('md:ml-64');
                document.getElementById('mainContent').classList.add('md:ml-16');
            } else {
                // Étendre la barre latérale
                sidebar.classList.remove('w-16');
                sidebar.classList.add('w-64');
                
                // Afficher les éléments de texte
                logoText.classList.remove('hidden');
                userText.classList.remove('hidden');
                logoutText.classList.remove('hidden');
                
                // Afficher le texte de navigation
                dashboardText.classList.remove('hidden');
                registerText.classList.remove('hidden');
                paymentText.classList.remove('hidden');
                requestsText.classList.remove('hidden');
                archivesText.classList.remove('hidden');
                adminText.classList.remove('hidden');
                
                // Changer le bouton de bascule
                toggleIcon.classList.remove('fa-chevron-right');
                toggleIcon.classList.add('fa-chevron-left');
                toggleText.textContent = 'Réduire';
                
                // Ajuster le contenu principal
                document.getElementById('mainContent').classList.remove('md:ml-16');
                document.getElementById('mainContent').classList.add('md:ml-64');
            }
        }

        // Bascule de la barre latérale
        function toggleSidebar() {
            sidebar.classList.toggle('collapsed');
            if (sidebar.classList.contains('collapsed')) {
                sidebarOverlay.classList.add('hidden');
            } else {
                sidebarOverlay.classList.remove('hidden');
            }
        }

        // Fermer la barre latérale
        function closeSidebar() {
            sidebar.classList.add('collapsed');
            sidebarOverlay.classList.add('hidden');
        }

        // Fonction de déconnexion
        function logout() {
            if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                // Dans une application réelle, vous redirigeriez vers la page de connexion
                alert('Déconnexion réussie !');
                console.log('Utilisateur déconnecté');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Éléments du DOM
            const detailModal = document.getElementById('detailModal');
            const closeModal = document.getElementById('closeModal');
            const closeDetailModal = document.getElementById('closeDetailModal');
            const modalContent = document.getElementById('modalContent');

            // Configuration des badges de statut
            const statusConfig = {
                'En de validation par le controleur': { color: 'bg-yellow-50 text-yellow-700 ring-1 ring-yellow-200' },
                'En attente de validation par le DAF': { color: 'bg-blue-50 text-blue-700 ring-1 ring-blue-200' },
                'En attente de validation par le DG': { color: 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200' },
                'En attente d\'autorisation de paiement': { color: 'bg-purple-50 text-purple-700 ring-1 ring-purple-200' },
                'Payée partiellement': { color: 'bg-green-50 text-green-700 ring-1 ring-green-200' }
            };

            // Configuration des badges d'entité
            const entityColors = {
                'GAZ': 'bg-slate-100 text-slate-700',
                'KTLS': 'bg-slate-100 text-slate-700',
                'KAMACI': 'bg-slate-100 text-slate-700'
            };

            // Configuration des badges de pays
            const countryColors = {
                'Guinée': 'bg-green-100 text-green-800',
                'Maroc': 'bg-red-100 text-red-800',
                'Côte d\'Ivoire': 'bg-orange-100 text-orange-800',
                'Mali': 'bg-blue-100 text-blue-800'
            };

            // Formater le montant
            function formatAmount(amount) {
                return new Intl.NumberFormat('fr-FR').format(amount) + ' F CFA';
            }

            // Formater la date
            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }

            // Afficher les détails de l'archive dans le modal
            function showArchiveDetails() {
                const request = {
                    reference: 'DP-CI-2024-06-15-001',
                    date: '2024-06-15',
                    requester: 'Mamadou Diallo',
                    entity: 'GAZ',
                    country: 'Côte d\'Ivoire',
                    amount: 2500000,
                    status: 'En de validation par le controleur',
                    archivedDate: '2024-07-01',
                    description: 'Achat de matériel informatique pour le service informatique',
                    archivedBy: 'Amina Koné'
                };

                const statusInfo = statusConfig[request.status] || { color: 'bg-gray-100 text-gray-800' };
                const entityColor = entityColors[request.entity] || 'bg-gray-100 text-gray-800';
                const countryColor = countryColors[request.country] || 'bg-gray-100 text-gray-800';

                modalContent.innerHTML = `
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Référence</h4>
                                <p class="mt-1 text-lg font-semibold text-gray-900">${request.reference}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Date de la demande</h4>
                                <p class="mt-1 text-lg font-semibold text-gray-900">${formatDate(request.date)}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Demandeur</h4>
                                <p class="mt-1 text-lg font-semibold text-gray-900">${request.requester}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Entité</h4>
                                <span class="entity-badge ${entityColor} mt-1 inline-block">
                                    ${request.entity}
                                </span>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Pays</h4>
                                <span class="country-badge ${countryColor} mt-1 inline-block">
                                    ${request.country}
                                </span>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Montant</h4>
                                <p class="mt-1 text-lg font-semibold text-gray-900">${formatAmount(request.amount)}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Statut</h4>
                                <span class="status-badge ${statusInfo.color}">
                                    ${request.status}
                                </span>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Date d'archivage</h4>
                                <p class="mt-1 text-lg font-semibold text-gray-900">${formatDate(request.archivedDate)}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Archivée par</h4>
                                <p class="mt-1 text-lg font-semibold text-gray-900">${request.archivedBy}</p>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Objet de la dépense</h4>
                            <p class="mt-1 text-gray-900">${request.description}</p>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="text-sm font-medium text-gray-500">Pièces jointes</h4>
                            <div class="mt-2">
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-gray-700">DP-2024-06-15-001_facture.pdf</span>
                                    <button class="ml-auto text-indigo-600 hover:text-indigo-800">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                detailModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            // Ajouter un événement de clic pour afficher les détails
            const viewDetailsBtn = document.querySelector('.view-details-btn');
            viewDetailsBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                showArchiveDetails();
            });

            // Événements de fermeture du modal
            closeModal.addEventListener('click', () => {
                detailModal.classList.add('hidden');
                document.body.style.overflow = '';
            });
            
            closeDetailModal.addEventListener('click', () => {
                detailModal.classList.add('hidden');
                document.body.style.overflow = '';
            });

            // Fermer les modals en cliquant à l'extérieur
            detailModal.addEventListener('click', (e) => {
                if (e.target === detailModal) {
                    detailModal.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });

            // Fermer les modals avec la touche ESC
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    if (!detailModal.classList.contains('hidden')) {
                        detailModal.classList.add('hidden');
                        document.body.style.overflow = '';
                    }
                }
            });
        });

        // Écouteurs d'événements
        toggleButton.addEventListener('click', toggleSidebarFunction);
        sidebarToggle.addEventListener('click', toggleSidebar);
        sidebarClose.addEventListener('click', closeSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);
        logoutBtn.addEventListener('click', logout);
    </script>














@endsection