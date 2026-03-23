@extends('layouts.template')

@section('maincontent')
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @if(in_array($paiement->statut, ['en_attente', 'partiel', 'termine']))

    <!-- En-tête Paiement -->
    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-2xl shadow-xl p-8 mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-10 rounded-full -ml-24 -mb-24"></div>
        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-medium text-yellow-100 mb-2">Référence de la demande</p>
                <h2 class="text-3xl font-bold tracking-tight mb-2">{{ $paiement->demande->reference_dp }}</h2>
            </div>
            <div class="mt-4 md:mt-0">
                @php
                    $statusText = match($paiement->statut) {
                        'en_attente' => "En attente",
                        'partiel' => "Partiellement payé",
                        'termine' => "Payé",
                        'annule' => "Annulé",
                        'echec' => "Échec",
                        default => "Statut inconnu",
                    };
                    $badgeColor = match($paiement->statut) {
                        'en_attente' => "bg-gray-100 text-gray-800 ring-2 ring-gray-300",
                        'partiel' => "bg-orange-100 text-orange-800 ring-2 ring-orange-400",
                        'termine' => "bg-green-100 text-green-800 ring-2 ring-green-400",
                        'annule' => "bg-red-100 text-red-800 ring-2 ring-red-400",
                        'echec' => "bg-red-200 text-red-900 ring-2 ring-red-600",
                        default => "bg-gray-100 text-gray-800 ring-2 ring-gray-300",
                    };
                @endphp
                <span class="inline-flex items-center {{ $badgeColor }} text-sm font-bold px-4 py-2 rounded-full shadow-lg">
                    {{ $statusText }}
                </span>
            </div>
        </div>
    </div>

    <!-- Informations sur le paiement -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-8 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Informations du paiement</h3>
        </div>

        <div class="mb-6 pb-6 border-b border-gray-200">
            <p class="text-xl font-semibold text-gray-900 mb-2">{{ $paiement->demande->denomination }}</p>
            <p class="text-sm text-gray-600 flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Fournisseur : <span class="font-medium text-gray-900 ml-1">{{ $paiement->demande->nom_fournisseur }}</span>
            </p>
        </div>

        <!-- Statistiques paiement -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200">
                <p class="text-xs font-medium text-blue-600 uppercase tracking-wider mb-2">Montant total TTC</p>
                <p class="font-bold text-gray-900 text-2xl">{{ number_format($paiement->montant_prevu, 0, ',', ' ') }}</p>
                <p class="text-xs text-indigo-600 font-medium mt-1">F CFA</p>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border border-green-200">
                <p class="text-xs font-medium text-green-600 uppercase tracking-wider mb-2">Montant déjà payé</p>
                <p class="font-bold text-gray-900 text-2xl">{{ number_format($paiement->montant_paye, 0, ',', ' ') }}</p>
                <p class="text-xs text-green-600 font-medium mt-1">F CFA</p>
            </div>

            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-5 border border-red-200">
                <p class="text-xs font-medium text-red-600 uppercase tracking-wider mb-2">Montant restant</p>
                <p class="font-bold text-red-700 text-2xl">{{ number_format($paiement->montant_restant, 0, ',', ' ') }}</p>
                <p class="text-xs text-red-600 font-medium mt-1">F CFA</p>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-5 border border-purple-200">
                <p class="text-xs font-medium text-purple-600 uppercase tracking-wider mb-2">Dernier versement</p>
                <p class="font-bold text-gray-900 text-lg">{{ optional($paiement->paiementVersements->last())->date_versement?->format('d/m/Y') ?? 'Aucun' }}</p>
                <p class="text-xs text-purple-600 font-medium mt-1">Date</p>
            </div>
        </div>
    </div>

    <!-- Historique des versements -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-8 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Historique des versements</h3>
        </div>

        @if($paiement->paiementVersements->isNotEmpty())
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date et heure</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Montant versé</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Mode de paiement</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Commentaire</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($paiement->paiementVersements as $v)
                            @php
                                $modeBadgeColor = match($v->mode_paiement) {
                                    'especes' => 'bg-yellow-100 text-yellow-800',
                                    'virement' => 'bg-blue-100 text-blue-800',
                                    'mobile_money' => 'bg-green-100 text-green-800',
                                    'cheque' => 'bg-purple-100 text-purple-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                                $statutBadgeColor = match($v->statut_versement) {
                                    'en_attente' => 'bg-gray-100 text-gray-800',
                                    'valide' => 'bg-green-100 text-green-800',
                                    'refuse' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $v->date_versement->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($v->montant, 0, ',', ' ') }} F CFA</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-bold {{ $modeBadgeColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $v->mode_paiement)) ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $v->commentaire ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-bold {{ $statutBadgeColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $v->statut_versement)) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                <p class="mt-4 text-gray-500 font-medium">Aucun versement enregistré pour ce paiement</p>
            </div>
        @endif

    </div>

    <!-- Bouton retour -->
    <div class="mt-6">
        <a href="{{ route('paiements.emis') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold shadow-md">
            Retour à la liste
        </a>
    </div>

    @else
        <div class="bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 p-8 rounded-2xl text-center border-2 border-gray-300 shadow-lg">
            <p class="text-xl font-bold">Ce paiement n'existe pas .</p>
        </div>
    @endif

</main>
@endsection