@extends('layouts.template')

@section('maincontent')
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Formulaire de recherche par entité -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <h3 class="text-lg font-semibold text-gray-800">Recherche par entité</h3>
            <form method="GET" action="#" class="flex items-center space-x-2">
                <i class="fas fa-building text-gray-500"></i>
                <select name="entite" class="border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="" selected>Toutes les entités</option>
                    <option value="GAZ">GAZ</option>
                    <option value="KTLS">KTLS</option>
                    <option value="KAMACI">KAMACI</option>
                </select>

                <button type="submit" class="flex items-center px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-search mr-2"></i> Rechercher
                </button>
            </form>
        </div>
    </div>

    <!-- Cartes de statistiques globales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <!-- Demandes totales -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center">
            <div class="bg-indigo-100 p-3 rounded-lg">
                <i class="fas fa-file-invoice text-indigo-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Demandes totales</p>
                <p class="text-2xl font-bold text-gray-800"> {{ rand(50, 150) }} </p>
            </div>
        </div>

        <!-- En attente -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center">
            <div class="bg-amber-100 p-3 rounded-lg">
                <i class="fas fa-clock text-amber-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">En attente</p>
                <p class="text-2xl font-bold text-gray-800"> {{ rand(5, 50) }} </p>
            </div>
        </div>

        <!-- Validées -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Validées</p>
                <p class="text-2xl font-bold text-gray-800"> {{ rand(20, 80) }} </p>
            </div>
        </div>

        <!-- Montant total -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center">
            <div class="bg-purple-100 p-3 rounded-lg">
                <i class="fas fa-money-bill-wave text-purple-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Montant total</p>
                <p class="text-lg font-bold text-gray-800">{{ number_format(rand(1000000, 5000000), 0, ',', ' ') }} F CFA</p>
            </div>
        </div>

        <!-- Montant payé -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-money-check-alt text-green-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Montant payé</p>
                <p class="text-lg font-bold text-gray-800">{{ number_format(rand(500000, 2500000), 0, ',', ' ') }} F CFA</p>
            </div>
        </div>

        <!-- Rejetées -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center">
            <div class="bg-red-100 p-3 rounded-lg">
                <i class="fas fa-times-circle text-red-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Rejetées</p>
                <p class="text-2xl font-bold text-gray-800"> {{ rand(1, 10) }} </p>
            </div>
        </div>

        <!-- Montant impayé -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center">
            <div class="bg-red-100 p-3 rounded-lg">
                <i class="fas fa-exclamation-circle text-red-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Montant impayé</p>
                <p class="text-lg font-bold text-gray-800">{{ number_format(rand(100000, 1000000), 0, ',', ' ') }} F CFA</p>
            </div>
        </div>

    </div>

    <!-- Tableau des dernières demandes -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Dernières demandes</h3>
        </div>
        <div class="overflow-x-auto max-w-full box-border">
            <table class="w-full min-w-max table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Réf. DP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dénomination</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fournisseur</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @for($i=1; $i<=8; $i++)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">DP-{{ rand(1000,9999) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Achat équipement {{ $i }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format(rand(50000, 500000), 0, ',', ' ') }} F CFA</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ ['GAZ','KTLS','KAMACI'][rand(0,2)] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ ['bg-green-100 text-green-800','bg-amber-100 text-amber-800','bg-red-100 text-red-800'][rand(0,2)] }}">
                                    {{ ['Validée','En attente','Rejetée'][rand(0,2)] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ now()->subDays(rand(0,30))->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Fournisseur {{ chr(64+$i) }}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

</main>
@endsection