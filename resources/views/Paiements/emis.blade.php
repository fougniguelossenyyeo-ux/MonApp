@extends('layouts.template')
@section('maincontent')

@include('layouts.paiement', [
    'totalEmis' => $totalEmis,
    'totalEncours' => $totalEncours,
    'totalPartiels' => $totalPartiels,
    'totalValide' => $totalValide,
])


<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <p class="text-gray-600 mt-1">Historique complet des paiements émis</p>
        </div>
        <div class="mt-4 md:mt-0">
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-600">
                    <span id="archiveCount">{{ $paiements->total() }}</span> paiements
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Recherche</label>
                <div class="relative">
                    <input type="text" placeholder="Référence DP, fournisseur..." 
                        class="w-full pl-8 pr-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
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
    </div>

    <!-- Cartes des paiements -->
   <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mt-6">
    @forelse($paiements as $p)
        @php
            $statuses = ['Non initié', 'En cours', 'Partiellement payé', 'Payé'];
            $badgeColor = match($p->status_paiement) {
                0 => "bg-amber-50 text-amber-800",
                1 => "bg-yellow-100 text-yellow-800",
                2 => "bg-orange-50 text-orange-700",
                3 => "bg-green-50 text-green-700",
                default => "bg-gray-50 text-gray-700",
            };
            $nombreVersements = $p->paiementVersements()->count();
        @endphp

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden flex flex-col hover:border-gray-300 transition-colors">

            {{-- En-tête : référence + badge statut --}}
            <div class="px-4 pt-4 pb-3">
                <div class="flex justify-between items-start gap-2">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">
                            {{ $p->demande->reference_dp ?? 'N/A' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Délai :
                            <span class="font-medium text-gray-700">
                                {{ $p->demande->date_paiement?->format('d/m/Y') ?? 'Non défini' }}
                            </span>
                        </p>
                    </div>
                    <span class="{{ $badgeColor }} text-xs font-medium px-2 py-1 rounded-full whitespace-nowrap">
                        {{ $statuses[$p->status_paiement] ?? 'Inconnu' }}
                    </span>
                </div>

                {{-- Nom fournisseur --}}
                <p class="text-sm font-medium text-gray-800 mt-3 truncate">
                    {{ $p->demande->nom_fournisseur ?? 'N/A' }}
                </p>
            </div>

            <hr class="border-gray-100">

            {{-- Montants --}}
            <div class="px-4 py-3 flex-1">
                <div class="grid grid-cols-3 gap-2">
                    <div class="bg-gray-50 rounded-lg px-2.5 py-2">
                        <p class="text-xs text-gray-500 mb-0.5">Total</p>
                        <p class="text-xs font-semibold text-gray-900 leading-tight">
                            {{ number_format($p->montant_a_payer, 0, ',', ' ') }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg px-2.5 py-2">
                        <p class="text-xs text-gray-500 mb-0.5">Payé</p>
                        <p class="text-xs font-semibold text-green-700 leading-tight">
                            {{ number_format($p->montant_deja_paye, 0, ',', ' ') }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg px-2.5 py-2">
                        <p class="text-xs text-gray-500 mb-0.5">Restant</p>
                        <p class="text-xs font-semibold text-orange-700 leading-tight">
                            {{ number_format($p->montant_restant, 0, ',', ' ') }}
                        </p>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- Pied de carte : date, versements, bouton --}}
            <div class="px-4 py-3 bg-gray-50 flex items-center justify-between gap-3">
                <div class="flex gap-4 text-xs">
                    <div>
                        <p class="text-gray-500">Créé le</p>
                        <p class="font-medium text-gray-800">{{ $p->created_at?->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Versements</p>
                        <p class="font-medium text-gray-800">{{ $nombreVersements }}</p>
                    </div>
                </div>
                <a href="{{ route('paiements.show', $p->id) }}"
                   class="shrink-0 px-3 py-1.5 bg-indigo-600 text-white text-xs rounded-md hover:bg-indigo-700 transition-colors">
                    Voir plus
                </a>
            </div>

        </div>
        @empty
            <div class="col-span-4 text-center py-12">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun paiement trouvé</h3>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $paiements->links() }}
    </div>
</main>

@endsection
