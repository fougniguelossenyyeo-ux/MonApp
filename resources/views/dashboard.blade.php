@extends('layouts.template')

@section('maincontent')
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Formulaire de recherche par entité -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <h3 class="text-lg font-semibold text-gray-800">Recherche par entité</h3>
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center space-x-2">
                <i class="fas fa-building text-gray-500"></i>
                <select name="entite" class="border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value=""  empty($selectedEntite ? 'selected' : '' }}>Toutes les entités</option>
                    @foreach($entites as $entite)
                        <option value=" $entite->libelle_entite "  ($selectedEntite == $entite->libelle_entite) ? 'selected' : '' >
                          $entite->libelle_entite 
                        </option>
                    @endforeach
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
                <p class="text-2xl font-bold text-gray-800">$totalDemandes </p>
            </div>
        </div>

        <!-- En attente -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center">
            <div class="bg-amber-100 p-3 rounded-lg">
                <i class="fas fa-clock text-amber-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">En attente</p>
                <p class="text-2xl font-bold text-gray-800"> $enAttente </p>
            </div>
        </div>

        <!-- Validées -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Validées</p>
                <p class="text-2xl font-bold text-gray-800"> $validees </p>
            </div>
        </div>

        <!-- Montant total -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center">
            <div class="bg-purple-100 p-3 rounded-lg">
                <i class="fas fa-money-bill-wave text-purple-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Montant total</p>
                <p class="text-lg font-bold text-gray-800"> formatMontant($montantTotal) F CFA</p>
            </div>
        </div>
  <!-- Montant payé -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-money-check-alt text-green-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Montant payé</p>
                <p class="text-lg font-bold text-gray-800"> formatMontant($montantPaye) F CFA</p>
            </div>
        </div>
        <!-- Rejetées -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center">
            <div class="bg-red-100 p-3 rounded-lg">
                <i class="fas fa-times-circle text-red-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Rejetées</p>
                <p class="text-2xl font-bold text-gray-800"> $refusees </p>
            </div>
        </div>

        <!-- Montant payé -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-money-check-alt text-green-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Montant payé</p>
                <p class="text-lg font-bold text-gray-800"> formatMontant($montantPaye)  F CFA</p>
            </div>
        </div>

        <!-- Montant impayé -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center">
            <div class="bg-red-100 p-3 rounded-lg">
                <i class="fas fa-exclamation-circle text-red-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Montant impayé</p>
                <p class="text-lg font-bold text-gray-800"> formatMontant($montantImpayé) F CFA</p>
            </div>
        </div>
    </div>

    <!-- Tableau des dernières demandes -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Dernières demandes</h3>
        </div>
        <div class="overflow-x-auto">
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
                  
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">$demande->reference_dp </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"> $demande->denomination </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"> $demande->montant_paiement_fournisseur  F CFA</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"> $demande->entite->libelle_entite ?? '' </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ">
                                     $statusText 
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">$demande->created_at->format('Y-m-d') </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">$demande->nom_fournisseur ?? '' </td>
                        </tr>
                   
                </tbody>
            </table>
        </div>
    </div>

</main>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Succès',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    @endif
});
</script>
@endsection
