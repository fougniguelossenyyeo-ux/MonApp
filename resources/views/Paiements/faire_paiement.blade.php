@extends('layouts.template')

@section('maincontent')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- 🔍 Section Recherche -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <form method="POST" action="{{ route('faire-paiement.search') }}" class="flex flex-col md:flex-row gap-2">
            @csrf
            <div class="flex">
                <input 
                    type="text" 
                    name="reference_dp"
                    placeholder="Rechercher par numéro de DP..."
                    value="{{ old('reference_dp') }}"
                    class="w-full md:w-96 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm"
                >
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-r-lg font-medium hover:bg-indigo-700 transition-colors flex items-center justify-center text-sm">
                    <i class="fas fa-search mr-2"></i> Rechercher
                </button>
            </div>
        </form>
    </div>

    <!-- 🚫 Message d'erreur ou d’info -->
    @if(isset($error))
        <div class="bg-red-100 text-red-700 p-4 rounded mb-6 text-center font-medium">
            {{ $error }}
        </div>
    @endif

    <!-- ✅ Si une demande est trouvée -->
    @if($demande)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

        <!-- 🧾 Informations de la demande -->
        <h2 class="text-xl font-semibold text-gray-900 mb-6">
            Informations de la Demande de Paiement
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div><p class="text-sm text-gray-500">Numéro de DP</p><p class="font-medium">{{ $demande->reference_dp }}</p></div>
                <div><p class="text-sm text-gray-500">Dénomination</p><p class="font-medium">{{ $demande->denomination }}</p></div>
                <div><p class="text-sm text-gray-500">Fournisseur</p><p class="font-medium">{{ $demande->nom_fournisseur }}</p></div>
                <div><p class="text-sm text-gray-500">Montant TTC</p><p class="font-medium">{{ number_format($montantTotal, 0, ',', ' ') }} F CFA</p></div>
                <div><p class="text-sm text-gray-500">Date de paiement souhaitée</p><p class="font-medium">{{ \Carbon\Carbon::parse($demande->date_paiement)->format('d/m/Y') }}</p></div>
                <div><p class="text-sm text-gray-500">Contact Fournisseur</p><p class="font-medium">{{ $demande->contact_fournisseur }}</p></div>
                <div><p class="text-sm text-gray-500">Adresse Fournisseur</p><p class="font-medium">{{ $demande->adresse_fournisseur }}</p></div>
                <div><p class="text-sm text-gray-500">Email Fournisseur</p><p class="font-medium">{{ $demande->email_fournisseur }}</p></div>
            </div>

            <div class="space-y-4">
                <div><p class="text-sm text-gray-500">Référence Facture</p><p class="font-medium">{{ $demande->reference_facture ?? '-' }}</p></div>
                <div><p class="text-sm text-gray-500">Bon de Commande</p><p class="font-medium">{{ $demande->reference_bon_commande ?? '-' }}</p></div>
                <div><p class="text-sm text-gray-500">Contrat</p><p class="font-medium">{{ $demande->reference_contrat ?? '-' }}</p></div>
                <div><p class="text-sm text-gray-500">Expression de Besoin</p><p class="font-medium">{{ $demande->reference_expression_besoin ?? '-' }}</p></div>
                <div><p class="text-sm text-gray-500">Code Fournisseur</p><p class="font-medium">{{ $demande->code_fournisseur ?? '-' }}</p></div>
                <div><p class="text-sm text-gray-500">Code Analytique</p><p class="font-medium">{{ $demande->code_analytique ?? '-' }}</p></div>
                <div><p class="text-sm text-gray-500">Centre Analytique</p><p class="font-medium">{{ $demande->centre_analytique ?? '-' }}</p></div>
                <div><p class="text-sm text-gray-500">Code Projet</p><p class="font-medium">{{ $demande->code_projet ?? '-' }}</p></div>
            </div>
        </div>

        <!-- 📋 Objet & priorité -->
        <div class="mt-6 border-t border-gray-200 pt-4">
            <p class="text-sm text-gray-500 mb-1">Objet de la Dépense</p>
            <p class="font-medium">{{ $demande->description ?? 'N/A' }}</p>

            <div class="mt-4">
                <p class="text-sm text-gray-500 mb-1">Priorité</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($demande->priorite === 'urgent') bg-red-100 text-red-800
                    @elseif($demande->priorite === 'tres_urgent') bg-red-600 text-white
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ ucfirst($demande->priorite) }}
                </span>
            </div>
        </div>

        <!-- 💰 Bloc Paiement -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Montant total TTC</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format($montantTotal, 0, ',', ' ') }} F CFA</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Montant déjà payé</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format($montantDejaPaye, 0, ',', ' ') }} F CFA</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Montant restant</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format($montantRestant, 0, ',', ' ') }} F CFA</p>
                    </div>
                </div>

                {{-- ⚙️ Conditions de paiement --}}
                @if($demande->status != 3)
                    <div class="mt-6 bg-yellow-100 text-yellow-800 px-4 py-3 rounded-lg text-center font-medium">
                        ⚠️ Cette demande n’a pas encore été totalement validée. Le paiement ne peut pas être effectué.
                    </div>

                @elseif($paiement && $paiement->status_paiement == 1)
                    <div class="mt-6 bg-blue-100 text-blue-800 px-4 py-3 rounded-lg text-center font-medium">
                        ⏳ Un paiement est déjà en cours pour cette demande. Vous ne pouvez pas en lancer un autre tant qu’il n’est pas terminé.
                    </div>
                    <div class="mt-4 text-center">
                        <button disabled class="px-4 py-2 bg-gray-400 text-white rounded-lg font-medium cursor-not-allowed">
                            Paiement en cours...
                        </button>
                    </div>

                @elseif($montantRestant <= 0 || ($paiement && $paiement->status_paiement == 3))
                    <div class="mt-6 bg-green-100 text-green-800 px-4 py-3 rounded-lg text-center font-semibold">
                        ✅ Cette demande a déjà été totalement payée. Aucun autre paiement n’est possible.
                    </div>
                    <div class="mt-4 text-center">
                        <button disabled class="px-4 py-2 bg-gray-400 text-white rounded-lg font-medium cursor-not-allowed">
                            Paiement terminé
                        </button>
                    </div>

                @else
                    <form method="POST" action="{{ route('paiements.payer', $paiement->id) }}" class="flex flex-col sm:flex-row sm:items-center gap-2 pt-4">
                        @csrf
                        <div class="w-full md:w-64">
                            <label for="paymentAmount" class="block text-sm font-medium text-gray-700 mb-2">
                                Montant à payer
                            </label>
                            <input 
                                type="number" 
                                name="montant"
                                min="0"
                                max="{{ $montantRestant }}"
                                value="{{ $montantRestant }}"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm"
                            >
                        </div>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors flex items-center justify-center mt-6 sm:mt-7 text-sm">
                            <i class="fas fa-money-bill-wave mr-2"></i> Effectuer le paiement
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    @endif
</main>

@endsection
