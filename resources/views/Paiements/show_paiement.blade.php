@extends('layouts.template')

@section('maincontent')
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @if($paiement)

    <!-- En-tête Paiement -->
    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-2xl shadow-xl p-8 mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-10 rounded-full -ml-24 -mb-24"></div>
        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-medium text-yellow-100 mb-2">Référence de la demande</p>
                <h2 class="text-3xl font-bold tracking-tight mb-2">{{ $paiement->demande->reference_dp }}</h2>
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
        </div>
    </div>

    <!-- Formulaire pour saisir un versement -->
    @if($paiement->montant_restant > 0)
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-8 hover:shadow-xl transition-shadow duration-300">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Effectuer un versement</h3>

        <form action="{{ route('paiements.payer', $paiement->id) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Montant -->
                <div>
                    <label for="montant" class="block text-sm font-medium text-gray-700 mb-1">Montant du versement</label>
                    <input type="number" name="montant" id="montant" min="1"
                           max="{{ $paiement->montant_restant }}" 
                           value="{{ old('montant', $paiement->montant_restant) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm"
                           required>
                    <p class="text-xs text-gray-500 mt-1">Montant maximum : {{ number_format($paiement->montant_restant,0,',',' ') }} FCFA</p>
                </div>

                <!-- Mode de paiement -->
                <div>
                    <label for="mode_paiement" class="block text-sm font-medium text-gray-700 mb-1">Mode de paiement</label>
                    <select name="mode_paiement" id="mode_paiement" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm">
                        <option value="">-- Sélectionner --</option>
                        <option value="especes">Espèces</option>
                        <option value="virement">Virement</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="cheque">Chèque</option>
                    </select>
                </div>

                <!-- Commentaire -->
                <div class="md:col-span-2">
                    <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-1">Commentaire (optionnel)</label>
                    <input type="text" name="commentaire" id="commentaire" 
                           value="{{ old('commentaire') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm"
                           placeholder="Ex: Versement partiel">
                </div>

            </div>

            <div class="mt-4">
                <button type="submit"
                        class="px-6 py-3 bg-yellow-500 text-white font-semibold rounded-xl shadow-md hover:bg-yellow-600 transition-all duration-300">
                    Soumettre le versement
                </button>
            </div>
        </form>
    </div>
    @endif

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
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($paiement->paiementVersements as $v)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $v->date_versement->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($v->montant, 0, ',', ' ') }} F CFA</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst(str_replace('_',' ',$v->mode_paiement)) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $v->commentaire ?? '-' }}</td>
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
            <p class="text-xl font-bold">Ce paiement n'existe pas.</p>
        </div>
    @endif

</main>
@endsection