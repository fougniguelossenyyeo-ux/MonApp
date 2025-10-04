@extends('layouts.template')
@section('maincontent')
@include('layouts.demande')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- En-tête du Paiement -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold">Paiement - {{ $paiement->demande->reference_dp }}</h2>
                <p class="text-lg">{{ $paiement->demande->denomination }}</p>
                <p class="text-sm">Fournisseur : {{ $paiement->demande->nom_fournisseur }}</p>
            </div>
            <div class="mt-4 md:mt-0">
                @php
                    $statusText = match($paiement->status_paiement) {
                        0 => "Non payé",
                        1 => "En attente de validation du DG",
                        2 => "Paiement partiel validé",
                        3 => "Paiement total validé",
                        -1 => "Refusé par le DG",
                        default => "Statut inconnu",
                    };
                    $badgeColor = match($paiement->status_paiement) {
                        1 => "bg-yellow-50 text-yellow-700",
                        2,3 => "bg-green-50 text-green-700",
                        -1 => "bg-red-50 text-red-700",
                        default => "bg-gray-50 text-gray-700",
                    };
                @endphp
                <span class="status-badge {{ $badgeColor }} text-xs px-2 py-1 rounded">
                    <b>{{ $statusText }}</b>
                </span>
            </div>
        </div>
    </div>

    <!-- Informations financières -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Informations du paiement</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Montant total de la demande</p>
                <p class="font-medium text-gray-900 text-xl">
                    {{ number_format($paiement->montant_total, 0, ',', ' ') }} F CFA
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Montant déjà payé</p>
                <p class="font-medium text-gray-900 text-xl">
                    {{ number_format($paiement->montant_deja_paye, 0, ',', ' ') }} F CFA
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Montant restant</p>
                <p class="font-medium text-gray-900 text-xl text-red-600">
                    {{ number_format($paiement->montant_restant, 0, ',', ' ') }} F CFA
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Date du dernier versement</p>
                <p class="font-medium text-gray-900">
                    {{ $paiement->paiementVersements->last()?->date_versement?->format('d/m/Y') ?? 'Aucun versement' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Liste des versements -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Historique des versements</h3>
        @if($paiement->paiementVersements->isNotEmpty())
            <table class="min-w-full divide-y divide-gray-200 border border-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Commentaire</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($paiement->paiementVersements as $v)
                        <tr>
                            <td class="px-4 py-2">{{ $v->date_versement->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">{{ number_format($v->montant, 0, ',', ' ') }} F CFA</td>
                            <td class="px-4 py-2">{{ $v->commentaire ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500">Aucun versement enregistré pour ce paiement.</p>
        @endif
    </div>

    <!-- Boutons d'action DG -->
    <div class="flex space-x-4 mb-6">
        <form action="{{ route('paiements.refuserDG', $paiement->id) }}" method="POST" class="flex-1">
            @csrf
            <button type="submit" class="w-full py-3 bg-red-600 text-white rounded hover:bg-red-700 font-semibold">
                Refuser le paiement
            </button>
        </form>

        <form action="{{ route('paiements.validerDG', $paiement->id) }}" method="POST" class="flex-1">
            @csrf
            <button type="submit" class="w-full py-3 bg-green-600 text-white rounded hover:bg-green-700 font-semibold">
                Valider le paiement
            </button>
        </form>
    </div>

    <!-- Retour -->
    <div class="mt-6">
        <a href="{{ route('paiements.encours') }}" 
           class="px-6 py-3 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 font-semibold">
             Retour à la liste
        </a>
    </div>
</main>
@endsection
