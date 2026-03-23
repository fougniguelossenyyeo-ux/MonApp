@extends('layouts.template')  

@section('maincontent')

@include('layouts.paiement', [
    'totalEmis' => $totalEmis,
    'totalEncours' => $totalEncours,
    'totalPartiels' => $totalPartiels,
    'totalValide' => $totalValide,
])

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- En-tête -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <p class="text-gray-600 mt-1">Paiements complètement terminés</p>
        </div>
        <div class="text-sm text-gray-600 mt-4 md:mt-0">
            {{ $paiements->total() }} paiements
        </div>
    </div>

    <!-- Cartes -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mt-2">

        @forelse($paiements as $p)

            @php
                $badgeColor = "bg-green-50 text-green-800";
                $statusText = "Terminé";
                $nombreVersements = $p->paiementVersements->count();
            @endphp

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden flex flex-col hover:border-gray-300 transition-colors">

                {{-- En-tête --}}
                <div class="px-4 pt-4 pb-3">
                    <div class="flex justify-between items-start gap-2">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">
                                {{ $p->demande->reference_dp }}
                            </p>
                            <p class="text-sm font-medium text-gray-800 mt-1 truncate">
                                {{ $p->demande->nom_fournisseur }}
                            </p>
                        </div>
                        <span class="{{ $badgeColor }} text-xs font-medium px-2.5 py-1 rounded-full whitespace-nowrap">
                            {{ $statusText }}
                        </span>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Montants --}}
                <div class="px-4 py-3 flex-1">
                    <div class="grid grid-cols-3 gap-2">
                        <div class="bg-gray-50 rounded-lg px-2.5 py-2">
                            <p class="text-xs text-gray-500 mb-0.5">Total</p>
                            <p class="text-xs font-semibold text-gray-900">
                                {{ number_format($p->montant_a_payer, 0, ',', ' ') }} F
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-lg px-2.5 py-2">
                            <p class="text-xs text-gray-500 mb-0.5">Payé</p>
                            <p class="text-xs font-semibold text-green-700">
                                {{ number_format($p->montant_deja_paye, 0, ',', ' ') }} F
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-lg px-2.5 py-2">
                            <p class="text-xs text-gray-500 mb-0.5">Restant</p>
                            <p class="text-xs font-semibold text-orange-700">
                                {{ number_format($p->montant_restant, 0, ',', ' ') }} F
                            </p>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Pied --}}
                <div class="px-4 py-3 bg-gray-50 flex items-center justify-between gap-3">
                    <div class="flex gap-4 text-xs">
                        <div>
                            <p class="text-gray-500">Créé le</p>
                            <p class="font-medium text-gray-800">
                                {{ $p->created_at?->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500">Versements</p>
                            <p class="font-medium text-gray-800">{{ $nombreVersements }}</p>
                        </div>
                    </div>

                    <a href="{{ route('paiements.show', $p->id) }}"
                       class="px-3 py-1.5 bg-indigo-600 text-white text-xs rounded-md hover:bg-indigo-700">
                        Voir
                    </a>
                </div>

            </div>

        @empty
            <div class="col-span-4 text-center py-12">
                <p class="text-gray-500 font-medium">
                    Aucun paiement complètement terminé
                </p>
            </div>
        @endforelse

    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $paiements->links() }}
    </div>

</main>

@endsection