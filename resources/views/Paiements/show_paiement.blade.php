@extends('layouts.template')
@section('maincontent')
@include('layouts.paiement')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- En-tête avec infos principales --}}
    <div class="bg-indigo-600 text-white rounded-2xl shadow-xl p-8 mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full -ml-24 -mb-24"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-100 mb-2">Référence de la demande</p>
                <h2 class="text-3xl font-bold tracking-tight mb-2">{{ $paiement->demande->reference_dp }}</h2>
              
            </div>
            <div class="mt-4 md:mt-0">
                @php
                    $statusLabels = [
                        0 => 'Non initié',
                        1 => 'En cours',
                        2 => 'Partiellement payé',
                        3 => 'Payé',
                    ];
                    $statusColors = [
                        0 => 'bg-gray-100 text-gray-800 ring-2 ring-gray-300',
                        1 => 'bg-yellow-100 text-yellow-800 ring-2 ring-yellow-400',
                        2 => 'bg-orange-100 text-orange-800 ring-2 ring-orange-400',
                        3 => 'bg-green-100 text-green-800 ring-2 ring-green-400',
                    ];
                @endphp
                <span class="inline-flex items-center {{ $statusColors[$paiement->status_paiement] ?? 'bg-gray-100 text-gray-800 ring-2 ring-gray-300' }} text-sm font-bold px-4 py-2 rounded-full shadow-lg">
                    {{ $statusLabels[$paiement->status_paiement] ?? 'Inconnu' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Informations sur la demande --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-8 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Informations de la demande</h3>
        </div>

        <div class="mb-6 pb-6 border-b border-gray-200">
            <p class="text-xl font-semibold text-gray-900 mb-2">{{ $paiement->demande->denomination }}</p>
            <p class="text-sm text-gray-600 flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Fournisseur : <span class="font-medium text-gray-900 ml-1">{{ $paiement->demande->nom_fournisseur ?? '-' }}</span>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-5 border border-gray-200">
                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-2">Demandeur</p>
                <p class="font-bold text-gray-900">{{ $paiement->demande->user->prenom ?? '' }} {{ $paiement->demande->user->nom ?? '' }}</p>
            </div>

            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-5 border border-gray-200">
                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-2">Entité</p>
                <p class="font-bold text-gray-900">{{ $paiement->demande->entite->libelle_entite ?? '-' }}</p>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-5 border border-purple-200">
                <p class="text-xs font-medium text-purple-600 uppercase tracking-wider mb-2">Date de la demande</p>
                <p class="font-bold text-gray-900">{{ $paiement->demande->created_at?->format('d/m/Y') }}</p>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200">
                <p class="text-xs font-medium text-blue-600 uppercase tracking-wider mb-2">Montant demandé</p>
                <p class="font-bold text-gray-900 text-xl">
                    {{ number_format($paiement->demande->montant_paiement_fournisseur, 0, ',', ' ') }}
                </p>
                <p class="text-xs text-blue-600 font-medium mt-1">FCFA</p>
            </div>

            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-5 border border-indigo-200">
                <p class="text-xs font-medium text-indigo-600 uppercase tracking-wider mb-2">Date de paiement souhaitée</p>
                <p class="font-bold text-gray-900">{{ $paiement->demande->date_paiement?->format('d/m/Y') ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Informations sur le paiement --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-8 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Détails du paiement</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200">
                <p class="text-xs font-medium text-blue-600 uppercase tracking-wider mb-2">Montant à payer</p>
                <p class="font-bold text-gray-900 text-2xl">
                    {{ number_format($paiement->montant_a_payer, 0, ',', ' ') }}
                </p>
                <p class="text-xs text-blue-600 font-medium mt-1">FCFA</p>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border border-green-200">
                <p class="text-xs font-medium text-green-600 uppercase tracking-wider mb-2">Montant déjà versé</p>
                <p class="font-bold text-gray-900 text-2xl">
                    {{ number_format($paiement->montant_deja_paye, 0, ',', ' ') }}
                </p>
                <p class="text-xs text-green-600 font-medium mt-1">FCFA</p>
            </div>

            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-5 border border-red-200">
                <p class="text-xs font-medium text-red-600 uppercase tracking-wider mb-2">Montant restant</p>
                <p class="font-bold text-red-700 text-2xl">
                    {{ number_format($paiement->montant_restant, 0, ',', ' ') }}
                </p>
                <p class="text-xs text-red-600 font-medium mt-1">FCFA</p>
            </div>
        </div>
    </div>

    {{-- Historique des versements --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-8 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Historique des versements</h3>
        </div>

        @if($paiement->paiementsVersements->isEmpty())
            <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="mt-4 text-gray-500 font-medium">Aucun versement enregistré pour ce paiement</p>
            </div>
        @else
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">#</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date et heure</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Montant versé</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Commentaire</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($paiement->paiementsVersements as $i => $v)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-800 font-bold text-sm">
                                        {{ $i + 1 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">{{ $v->date_versement?->format('d/m/Y H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                        {{ number_format($v->montant, 0, ',', ' ') }} FCFA
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-700">{{ $v->commentaire ?? '-' }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Bouton retour --}}
    <div class="mt-6">
        <a href="{{ route('paiements.emis') }}" 
           class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold shadow-md hover:shadow-lg transition-all duration-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
        </a>
    </div>

</main>
@endsection