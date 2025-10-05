@extends('layouts.template') 
@section('maincontent')

@include('layouts.paiement')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- En-tête de page -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <p class="text-gray-600 mt-1">Paiements partiellement payés</p>
        </div>
        <div class="mt-4 md:mt-0">
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-600">
                    <span id="archiveCount">{{ $paiements->total() }}</span> paiements partiels
                </div>
            </div>
        </div>
    </div>
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Recherche</label>
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="Référence DP, fournisseur..." 
                        class="w-full pl-8 pr-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                    >
                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 text-xs"></i>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Entité</label>
                <select class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <option value="">Toutes</option>
                    <option value="GAZ">GAZ</option>
                    <option value="KTLS">KTLS</option>
                    <option value="KAMACI">KAMACI</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Statut</label>
                <select class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <option value="">Tous</option>
                    <option value="0">Non initié</option>
                    <option value="1">En cours</option>
                    <option value="2">Partiellement payé</option>
                    <option value="3">Payé</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Montant restant</label>
                <select class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <option value="">Tous</option>
                    <option value="0-1000000">&lt; 1M</option>
                    <option value="1000000-5000000">1M - 5M</option>
                    <option value="5000000-10000000">5M - 10M</option>
                    <option value="10000000+">&gt; 10M</option>
                </select>
            </div>
        </div>
    </div
    <!-- Grille des cartes -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
        @forelse($paiements as $p)
            <div class="archive-card bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow flex flex-col h-full">
                <div class="p-4 flex-1 flex flex-col justify-between">

                    <!-- Header carte -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $p->demande->reference_dp ?? 'N/A' }}</h3>
                        <p class="text-xs text-gray-500 mt-1">Créé le : {{ $p->created_at?->format('d/m/Y H:i') }}</p>

                        <!-- Statut -->
                        <span class="status-badge bg-orange-50 text-orange-700 text-xs px-2 py-1 rounded inline-block mt-2">
                            <b>Partiellement payé</b>
                        </span>

                        <!-- Fournisseur & Montants -->
                        <div class="mt-3 text-sm">
                            <p class="text-gray-700 font-medium truncate">{{ $p->demande->nom_fournisseur ?? 'N/A' }}</p>
                            <div class="flex justify-between mt-2">
                                <div>
                                    <p class="text-gray-500 text-xs">Montant total</p>
                                    <p class="font-semibold text-gray-900">{{ number_format($p->montant_a_payer, 0, ',', ' ') }} F CFA</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs">Déjà payé</p>
                                    <p class="font-semibold text-gray-900">{{ number_format($p->montant_deja_paye, 0, ',', ' ') }} F CFA</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs">Restant</p>
                                    <p class="font-semibold text-gray-900">{{ number_format($p->montant_restant, 0, ',', ' ') }} F CFA</p>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-red-600 mt-2">
                            En attente de règlement complet
                        </p>
                    </div>

                    <!-- Footer carte -->
                    <div class="mt-3 flex flex-wrap items-center justify-between gap-4 border-t border-gray-100 pt-3">
                        <div class="flex flex-col text-xs text-gray-500">
                            <span>Créé le :</span>
                            <span class="text-gray-900">{{ $p->created_at?->format('d/m/Y H:i') }}</span>
                        </div>

                        <div class="flex items-center">
                            <a href="{{ route('paiements.shown', $p->id) }}" 
                               class="view-details-btn text-indigo-600 hover:text-indigo-800 text-xs font-medium">
                                Payer le reste
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-span-4 text-center py-12">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun paiement partiel</h3>
              
            </div>
        @endforelse
    </div>

    <!-- Pagination Laravel -->
    <div class="mt-6">
        {{ $paiements->links() }}
    </div>

</main>
@endsection
