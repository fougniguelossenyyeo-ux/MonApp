@extends('layouts.template')
@section('maincontent')

@include('layouts.paiement')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <p class="text-gray-600 mt-1">Historique complet des paiements émis</p>
        </div>

        <div class="text-sm text-gray-600">
            <span>{{ $paiements->total() }}</span> paiements
        </div>
    </div>

    {{-- FILTRES (inchangé UI) --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">

            <input type="text"
                placeholder="Référence DP, fournisseur..."
                class="w-full pl-3 py-1.5 text-sm border rounded-md">

            <select class="w-full px-2 py-1.5 text-sm border rounded-md">
                <option>Toutes entités</option>
                <option>GAZ</option>
                <option>KTLS</option>
                <option>KAMACI</option>
            </select>

            <select class="w-full px-2 py-1.5 text-sm border rounded-md">
                <option>Tous statuts</option>
                <option value="en_attente">En attente</option>
                <option value="partiel">Partiel</option>
                <option value="termine">Terminé</option>
            </select>

            <select class="w-full px-2 py-1.5 text-sm border rounded-md">
                <option>Montant</option>
                <option>&lt; 1M</option>
                <option>1M - 5M</option>
                <option>&gt; 5M</option>
            </select>

        </div>
    </div>

    {{-- GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">

        @forelse($paiements as $p)

            @php
                $statut = $p->statut;

                $badgeColor = match($statut) {
                    'en_attente' => "bg-yellow-50 text-yellow-800",
                    'partiel'    => "bg-orange-50 text-orange-800",
                    'termine'    => "bg-green-50 text-green-800",
                    default      => "bg-gray-100 text-gray-700",
                };

                $statusText = match($statut) {
                    'en_attente' => "En attente",
                    'partiel'    => "Partiel",
                    'termine'    => "Terminé",
                    default      => "Inconnu",
                };

                $total = (float) $p->montant_prevu;
                $deja  = (float) $p->montantDejaPaye();
                $rest  = (float) $p->montantRestant();
            @endphp

            <div class="bg-white rounded-xl border overflow-hidden flex flex-col hover:border-gray-300">

                {{-- HEADER --}}
                <div class="px-4 pt-4 pb-3">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-sm font-semibold">
                                {{ $p->demande->reference_dp ?? 'N/A' }}
                            </p>

            {{--  ENTITÉ AJOUTÉE --}}
            <p class="text-xs text-indigo-600 font-medium mt-0.5">
                {{ $p->demande->entite->libelle_entite ?? 'Entité inconnue' }}
            </p>
                            <p class="text-xs text-gray-500">
                                {{ $p->demande->nom_fournisseur ?? 'N/A' }}
                            </p>
                        </div>

                        <span class="{{ $badgeColor }} text-xs px-2 py-1 rounded-md">
                            {{ $statusText }}
                        </span>
                    </div>
                </div>

                <hr>

                {{-- MONTANTS --}}
                <div class="px-4 py-3">
                    <div class="grid grid-cols-3 gap-2">

                        <div class="bg-gray-50 p-2 rounded">
                            <p class="text-xs text-gray-500">Total</p>
                            <p class="text-xs font-semibold">{{ number_format($total, 0, ',', ' ') }}</p>
                        </div>

                        <div class="bg-gray-50 p-2 rounded">
                            <p class="text-xs text-gray-500">Payé</p>
                            <p class="text-xs font-semibold text-green-600">
                                {{ number_format($deja, 0, ',', ' ') }}
                            </p>
                        </div>

                        <div class="bg-gray-50 p-2 rounded">
                            <p class="text-xs text-gray-500">Restant</p>
                            <p class="text-xs font-semibold text-orange-600">
                                {{ number_format($rest, 0, ',', ' ') }}
                            </p>
                        </div>

                    </div>
                </div>

                <hr>

                {{-- FOOTER --}}
                <div class="px-4 py-3 bg-gray-50 flex justify-between text-xs">

                    <div>
                        <p class="text-gray-500">Créé</p>
                        <p class="font-medium">{{ $p->created_at?->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Versements</p>
                        <p class="font-medium">{{ $p->paiementVersements->count() }}</p>
                    </div>

                    <a href="{{ route('paiements.show', $p->id) }}"
                       class="text-indigo-600 font-medium">
                        Voir
                    </a>

                </div>

            </div>

        @empty

            <div class="col-span-full text-center text-gray-500 py-10">
                Aucun paiement trouvé
            </div>

        @endforelse

    </div>

    {{-- PAGINATION --}}
    <div class="mt-6">
        {{ $paiements->links() }}
    </div>

</main>

@endsection