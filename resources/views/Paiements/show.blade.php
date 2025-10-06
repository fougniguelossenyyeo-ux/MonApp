@extends('layouts.template')

@section('maincontent')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @if($paiement->status_paiement === 1 || $paiement->status_paiement === 2)

    <!-- En-tête du Paiement Partiel -->
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
                    $statusText = match($paiement->status_paiement) {
                        1 => "En cours",
                        2 => "Partiellement payé",
                        default => "Statut inconnu",
                    };
                    $badgeColor = match($paiement->status_paiement) {
                        1 => "bg-yellow-100 text-yellow-800 ring-2 ring-yellow-400",
                        2 => "bg-orange-100 text-orange-800 ring-2 ring-orange-400",
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200">
                <p class="text-xs font-medium text-blue-600 uppercase tracking-wider mb-2">Montant total TTC</p>
                <p class="font-bold text-gray-900 text-2xl">
                    {{ number_format($paiement->demande->montant_paiement_fournisseur, 0, ',', ' ') }}
                </p>
                <p class="text-xs text-indigo-600 font-medium mt-1">F CFA</p>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border border-green-200">
                <p class="text-xs font-medium text-green-600 uppercase tracking-wider mb-2">Montant déjà payé</p>
                <p class="font-bold text-gray-900 text-2xl">
                    {{ number_format($paiement->montant_deja_paye, 0, ',', ' ') }}
                </p>
                <p class="text-xs text-green-600 font-medium mt-1">F CFA</p>
            </div>

            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-5 border border-red-200">
                <p class="text-xs font-medium text-red-600 uppercase tracking-wider mb-2">Montant restant</p>
                <p class="font-bold text-red-700 text-2xl">
                    {{ number_format($paiement->montant_restant, 0, ',', ' ') }}
                </p>
                <p class="text-xs text-red-600 font-medium mt-1">F CFA</p>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-5 border border-purple-200">
                <p class="text-xs font-medium text-purple-600 uppercase tracking-wider mb-2">Dernier versement</p>
                <p class="font-bold text-gray-900 text-lg">
                    {{ $paiement->paiementsVersements->last()?->date_versement?->format('d/m/Y') ?? 'Aucun' }}
                </p>
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

        @if($paiement->paiementsVersements->isNotEmpty())
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Date et heure
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Montant versé
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Commentaire
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($paiement->paiementsVersements as $v)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">{{ $v->date_versement->format('d/m/Y H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                        {{ number_format($v->montant, 0, ',', ' ') }} F CFA
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
        @else
            <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="mt-4 text-gray-500 font-medium">Aucun versement enregistré pour ce paiement</p>
            </div>
        @endif
    </div>

    <!-- Formulaire pour payer le reste -->
    @if($paiement->montant_restant > 0)
    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl shadow-lg border-2 border-green-200 p-8 mb-8">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Effectuer un paiement</h3>
        </div>

        <form method="POST" action="{{ route('paiements.payer', $paiement->id) }}" class="flex flex-col sm:flex-row sm:items-end gap-4">
            @csrf
            <div class="w-full sm:w-80">
                <label for="montant_a_payer" class="block text-sm font-bold text-gray-700 mb-2">
                    Montant à payer
                </label>
                <input type="number" 
                       name="montant" 
                       id="montant_a_payer"
                       min="0" 
                       max="{{ $paiement->montant_restant }}" 
                       value="{{ $paiement->montant_restant }}" 
                       required
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-lg font-semibold">
                <p class="mt-2 text-xs text-gray-600">
                    Maximum : <span class="font-bold text-red-600">{{ number_format($paiement->montant_restant, 0, ',', ' ') }} F CFA</span>
                </p>
            </div>
            <button type="submit" class="sm:mb-7 px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl font-bold hover:from-green-700 hover:to-green-800 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center whitespace-nowrap">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Payer maintenant
            </button>
        </form>
    </div>
    @endif

    <!-- Retour -->
    <div class="mt-6">
        <a href="{{ route('paiements.partiellement') }}" 
           class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold shadow-md hover:shadow-lg transition-all duration-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
        </a>
    </div>

    @else
        <div class="bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 p-8 rounded-2xl text-center border-2 border-gray-300 shadow-lg">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <p class="text-xl font-bold">Ce paiement n'est pas un paiement partiel.</p>
        </div>
    @endif

</main>

@endsection