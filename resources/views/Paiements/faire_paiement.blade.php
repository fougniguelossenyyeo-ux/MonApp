@extends('layouts.template')

@section('maincontent')
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Messages flash -->
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl shadow">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- Recherche -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <form method="POST" action="{{ route('faire-paiement.search') }}" class="flex flex-col md:flex-row gap-2">
            @csrf
            <div class="flex w-full">
                <input 
                    type="text" 
                    name="reference_dp"
                    placeholder="Rechercher par numéro de DP..."
                    value="{{ old('reference_dp') }}"
                    class="w-full md:w-96 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                    aria-label="Recherche par numéro de DP"
                >
                <button type="submit" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-r-lg font-medium hover:bg-indigo-700 text-sm">
                    Rechercher
                </button>
            </div>
        </form>
    </div>

    <h1 class="text-2xl font-semibold text-gray-900 mb-6">
        Paiements
    </h1>

    <!-- Liste des paiements -->
    @if($dernieresDemandes->isEmpty())
        <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded-lg text-center">
             Aucune demande validée trouvée.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($dernieresDemandes as $demande)
                @php
                    $paiement = $demande->paiement;
                    $montantPrevu = $paiement->montant_prevu ?? 0;
                    $montantRestant = $paiement->montant_restant ?? 0;
                @endphp

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 flex flex-col justify-between">

                    <!-- Infos demande -->
                    <div class="space-y-2">
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $demande->reference_dp }}
                        </p>

                        <p class="text-sm text-gray-500">
                            {{ $demande->denomination }}
                        </p>

                        <div class="pt-2 space-y-1">
                            <p class="text-sm text-gray-700 font-medium">
                                 Montant à payer :
                                <span class="font-semibold text-gray-900">
                                    {{ number_format($montantPrevu, 0, ',', ' ') }} F CFA
                                </span>
                            </p>

                            <p class="text-sm text-gray-700 font-medium">
                                 Restant :
                                <span class="font-semibold text-gray-900">
                                    {{ number_format($montantRestant, 0, ',', ' ') }} F CFA
                                </span>
                            </p>
                        </div>
                    </div>

                  <!-- Bouton -->
<div class="mt-4">
    @if($paiement && $montantRestant > 0)
        <a href="{{ route('paiements.payer', $paiement->id) }}"
           class="block text-center w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
           aria-label="Payer la demande {{ $demande->reference_dp }}">
             Payer
        </a>
    @else
        <button disabled 
            class="w-full px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed"
            aria-label="Paiement déjà effectué pour {{ $demande->reference_dp }}">
             Payé
        </button>
    @endif
</div>

                </div>
            @endforeach

        </div>
    @endif

</main>
@endsection