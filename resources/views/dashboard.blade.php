
@extends('layouts.template')

@section('maincontent')

 <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Cartes de statistiques -->
                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <div class="flex items-center">
                            <div class="bg-indigo-100 p-3 rounded-lg">
                                <i class="fas fa-file-invoice text-indigo-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Demandes totales</p>
                                <p class="text-2xl font-bold text-gray-800" id="totalRequests">142</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <span class="flex items-center text-sm font-medium text-green-600">
                                <i class="fas fa-arrow-up mr-1"></i>
                                12.5%
                            </span>
                            <span class="text-sm text-gray-500 ml-2">par rapport au mois dernier</span>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <div class="flex items-center">
                            <div class="bg-amber-100 p-3 rounded-lg">
                                <i class="fas fa-clock text-amber-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">En attente</p>
                                <p class="text-2xl font-bold text-gray-800" id="pendingRequests">28</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <span class="flex items-center text-sm font-medium text-red-600">
                                <i class="fas fa-arrow-down mr-1"></i>
                                3.2%
                            </span>
                            <span class="text-sm text-gray-500 ml-2">par rapport au mois dernier</span>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-3 rounded-lg">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Payées</p>
                                <p class="text-2xl font-bold text-gray-800" id="paidRequests">95</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <span class="flex items-center text-sm font-medium text-green-600">
                                <i class="fas fa-arrow-up mr-1"></i>
                                8.7%
                            </span>
                            <span class="text-sm text-gray-500 ml-2">par rapport au mois dernier</span>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-3 rounded-lg">
                                <i class="fas fa-money-bill-wave text-purple-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Montant total</p>
                                <p class="text-lg font-bold text-gray-800" id="totalAmount">542M F CFA</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <span class="flex items-center text-sm font-medium text-green-600">
                                <i class="fas fa-arrow-up mr-1"></i>
                                15.3%
                            </span>
                            <span class="text-sm text-gray-500 ml-2">par rapport au mois dernier</span>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-lg">
                                <i class="fas fa-chart-line text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Validées</p>
                                <p class="text-2xl font-bold text-gray-800" id="validatedRequests">35</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <div class="flex items-center">
                            <div class="bg-red-100 p-3 rounded-lg">
                                <i class="fas fa-times-circle text-red-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Rejetées</p>
                                <p class="text-2xl font-bold text-gray-800" id="rejectedRequests">12</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alertes -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Alertes & Notifications</h3>
                    <div class="space-y-3">
                        <div class="flex items-start p-3 bg-red-50 rounded-lg border border-red-100">
                            <i class="fas fa-clock text-amber-500 mt-1"></i>
                            <div class="ml-3">
                                <p class="text-sm text-gray-800">DP-CI-2024-07-21-002 à valider</p>
                                <p class="text-xs text-gray-500">Il y a 5h</p>
                            </div>
                        </div>
                        <div class="flex items-start p-3 bg-red-50 rounded-lg border border-red-100">
                            <i class="fas fa-exclamation-circle text-red-500 mt-1"></i>
                            <div class="ml-3">
                                <p class="text-sm text-gray-800">DP-CI-2024-07-20-001 rejetée</p>
                                <p class="text-xs text-gray-500">Il y a 1 jour</p>
                            </div>
                        </div>
                        <div class="flex items-start p-3 bg-red-50 rounded-lg border border-red-100">
                            <i class="fas fa-exclamation-triangle text-purple-500 mt-1"></i>
                            <div class="ml-3">
                                <p class="text-sm text-gray-800">Nouvelle demande DP-CI-2024-07-19-004</p>
                                <p class="text-xs text-gray-500">Il y a 2 jours</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Indicateurs financiers par entité -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
                    <h3 class="text-lg font-semibold text-gray-800">Indicateurs financiers par entité</h3>
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                        <!-- Sélecteur de période -->
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-calendar-alt text-gray-500"></i>
                            <select id="periodSelect" class="border border-gray-300 rounded px-2 py-1 text-sm">
                                <option value="month">Ce mois</option>
                                <option value="quarter">Ce trimestre</option>
                                <option value="year">Cette année</option>
                                <option value="custom">Personnalisé</option>
                            </select>
                        </div>
                        
                        <!-- Champs de période personnalisée (masqués par défaut) -->
                        <div id="customPeriodFields" class="hidden flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-2">
                            <div>
                                <input type="date" id="startDate" class="border border-gray-300 rounded px-2 py-1 text-sm">
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-500 mx-1">à</span>
                            </div>
                            <div>
                                <input type="date" id="endDate" class="border border-gray-300 rounded px-2 py-1 text-sm">
                            </div>
                        </div>
                        
                        <!-- Bouton de recherche -->
                        <button id="searchPeriodBtn" class="flex items-center px-3 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-search mr-1"></i>
                            Rechercher
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="border rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-3">GAZ</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total demandes:</span>
                                <span class="font-medium">12 (185M F CFA)</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">En attente:</span>
                                <span class="font-medium">3 (45M F CFA)</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Payées:</span>
                                <span class="font-medium">7 (120M F CFA)</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Impayées:</span>
                                <span class="font-medium">2 (20M F CFA)</span>
                            </div>
                            <div class="flex justify-between text-sm border-t pt-2">
                                <span class="text-gray-600">Tendance:</span>
                                <span class="font-semibold text-green-600">+12.5%</span>
                            </div>
                        </div>
                    </div>
                    <div class="border rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-3">KTLS</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total demandes:</span>
                                <span class="font-medium">8 (142M F CFA)</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">En attente:</span>
                                <span class="font-medium">2 (30M F CFA)</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Payées:</span>
                                <span class="font-medium">4 (95M F CFA)</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Impayées:</span>
                                <span class="font-medium">2 (17M F CFA)</span>
                            </div>
                            <div class="flex justify-between text-sm border-t pt-2">
                                <span class="text-gray-600">Tendance:</span>
                                <span class="font-semibold text-green-600">+8.2%</span>
                            </div>
                        </div>
                    </div>
                    <div class="border rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-3">KAMACI</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total demandes:</span>
                                <span class="font-medium">6 (98M F CFA)</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">En attente:</span>
                                <span class="font-medium">1 (15M F CFA)</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Payées:</span>
                                <span class="font-medium">4 (70M F CFA)</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Impayées:</span>
                                <span class="font-medium">1 (13M F CFA)</span>
                            </div>
                            <div class="flex justify-between text-sm border-t pt-2">
                                <span class="text-gray-600">Tendance:</span>
                                <span class="font-semibold text-amber-600">+3.1%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           
            <!-- Activité récente et Entités les plus actives -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Activité récente -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Activité récente</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <div class="px-6 py-4 flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-green-600"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-900">DP-CI-2024-07-22-003</p>
                                <p class="text-sm text-gray-500">Payée partiellement - Fatou Diop (GAZ)</p>
                            </div>
                            <div class="text-sm text-gray-500">Il y a 2h</div>
                        </div>
                        <div class="px-6 py-4 flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-amber-600"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-900">DP-CI-2024-07-21-002</p>
                                <p class="text-sm text-gray-500">En attente de validation DAF - Amina Koné (KAMACI)</p>
                            </div>
                            <div class="text-sm text-gray-500">Il y a 5h</div>
                        </div>
                        <div class="px-6 py-4 flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-times text-red-600"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-900">DP-CI-2024-07-20-001</p>
                                <p class="text-sm text-gray-500">Rejetée - Mamadou Diallo (KTLS)</p>
                            </div>
                            <div class="text-sm text-gray-500">Il y a 1 jour</div>
                        </div>
                    </div>
                </div>

                <!-- Entités les plus actives -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Entités les plus actives</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-slate-700">GAZ</span>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">GAZ</p>
                                        <p class="text-sm text-gray-500">12 demandes</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">185M F CFA</p>
                                    <p class="text-sm text-green-600">+12.5%</p>
                                </div>
                            </div>
                            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: 85%"></div>
                            </div>
                        </div>
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-slate-700">KTLS</span>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">KTLS</p>
                                        <p class="text-sm text-gray-500">8 demandes</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">142M F CFA</p>
                                    <p class="text-sm text-green-600">+8.2%</p>
                                </div>
                            </div>
                            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-600 h-2 rounded-full" style="width: 65%"></div>
                            </div>
                        </div>
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-slate-700">KAMACI</span>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">KAMACI</p>
                                        <p class="text-sm text-gray-500">6 demandes</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">98M F CFA</p>
                                    <p class="text-sm text-amber-600">+3.1%</p>
                                </div>
                            </div>
                            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-amber-600 h-2 rounded-full" style="width: 45%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des dernières demandes -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Dernières demandes</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Réf. DP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dénomination</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entité</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsable</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">DP-CI-2024-07-22-003</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Fournitures de bureau</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">15M F CFA</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">GAZ</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Payée partiellement
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">2024-07-22</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Fatou Diop</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">DP-CI-2024-07-21-002</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Maintenance informatique</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">25M F CFA</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">KAMACI</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                                        En attente
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">2024-07-21</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Amina Koné</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">DP-CI-2024-07-20-001</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Formation continue</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">12M F CFA</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">KTLS</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Rejetée
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">2024-07-20</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Mamadou Diallo</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">DP-CI-2024-07-19-004</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Équipements de sécurité</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">45M F CFA</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">GAZ</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Validée
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">2024-07-19</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Fatou Sow</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">DP-CI-2024-07-18-005</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Services externes</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">18M F CFA</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">KTLS</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                                        En attente
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">2024-07-18</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Thomas Bernard</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>





@endsection