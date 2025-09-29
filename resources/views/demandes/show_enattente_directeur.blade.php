@extends('layouts.template')
@section('maincontent')
@include('layouts.demande')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Status Header -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold">{{ $demande->reference_dp }}</h2>
                <p class="mt-1 opacity-90">{{ $demande->denomination }}</p>
            </div>
            <div class="mt-4 md:mt-0">
                @php
                    $statusText = match($demande->status) {
                        0 => "En attente de validation du Contrôleur",
                        1 => "En attente de validation du DAF",
                        2 => "En attente de validation du DG",
                        3 => "Validé",
                        -1 => "Refusé par le Contrôleur",
                        -2 => "Refusé par le DAF",
                        -3 => "Refusé par le DG",
                        default => "Statut inconnu",
                    };
                    $badgeColor = match($demande->status) {
                        0,1,2 => "bg-yellow-50 text-yellow-700",
                        3 => "bg-green-50 text-green-700",
                        -1,-2,-3 => "bg-red-50 text-red-700",
                        default => "bg-gray-50 text-gray-700",
                    };
                @endphp
                <span class="status-badge {{ $badgeColor }} text-xs px-2 py-1 rounded">
                    <b>{{ $statusText }}</b>
                </span>
            </div>
        </div>
    </div>

    <!-- Request Details -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Informations de la demande</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Info -->
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Demandeur</p>
                    <p class="font-medium text-gray-900">{{ $demande->user->prenom }} {{ $demande->user->nom }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Entité</p>
                    <p class="font-medium text-gray-900">{{ $demande->entite->libelle_entite ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Date de la demande</p>
                    <p class="font-medium text-gray-900">{{ $demande->created_at?->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Contact Téléphonique</p>
                    <p class="font-medium text-gray-900">{{ $demande->contact_fournisseur }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium text-gray-900">{{ $demande->email_fournisseur }}</p>
                </div>
            </div>

            <!-- Financial Info -->
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Montant demandé</p>
                    <p class="font-medium text-gray-900 text-xl">{{ number_format($demande->montant_paiement_fournisseur,0,',',' ') }} F CFA</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Date de paiement souhaitée</p>
                    <p class="font-medium text-gray-900">{{ $demande->date_paiement?->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Priorité</p>
                    <p class="font-medium text-gray-900">{{ ucfirst($demande->priorite) ?? 'Normale' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Référence Facture</p>
                    <p class="font-medium text-gray-900">{{ $demande->reference_facture ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Référence Bon de Commande</p>
                    <p class="font-medium text-gray-900">{{ $demande->reference_bon_commande ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Code Fournisseur</p>
                    <p class="font-medium text-gray-900">{{ $demande->code_fournisseur ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Details -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Détails supplémentaires</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Adresse</p>
                <p class="font-medium text-gray-900">{{ $demande->adresse_fournisseur ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Référence Contrat</p>
                <p class="font-medium text-gray-900">{{ $demande->reference_contrat ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Référence Expression de Besoin</p>
                <p class="font-medium text-gray-900">{{ $demande->reference_expression_besoin ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Code Analytique</p>
                <p class="font-medium text-gray-900">{{ $demande->code_analytique ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Centre Analytique</p>
                <p class="font-medium text-gray-900">{{ $demande->centre_analytique ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Code Projet</p>
                <p class="font-medium text-gray-900">{{ $demande->code_projet ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Description -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Objet de la dépense</h3>
        <p class="text-gray-700">{{ $demande->description ?? '-' }}</p>
    </div>

    <!-- Pièces jointes -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pièces jointes</h3>
        <div class="space-y-3">
            @if ($demande->pieces_jointes)
                <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-gray-700 flex-1">{{ basename($demande->pieces_jointes) }}</span>
                    <button 
                        class="view-file-btn text-indigo-600 hover:text-indigo-800 ml-2 flex items-center"
                        data-file="{{ asset('storage/pieces_jointes/' . basename($demande->pieces_jointes)) }}"
                    >
                        <i class="fas fa-eye mr-1"></i> Voir
                    </button>
                </div>
            @else
                <p class="text-gray-500">Aucune pièce jointe pour cette demande.</p>
            @endif
        </div>
    </div>

    <!-- Modal PDF -->
    <div id="fileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-4/5 h-4/5 relative">
            <button id="closeModal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-xl font-bold">&times;</button>
            <iframe id="pieceViewer" src="" class="w-full h-full"></iframe>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex space-x-4 mb-6">
        <form action="{{ route('demandes.refuserDirecteur', $demande->id) }}" method="POST" class="flex-1">
            @csrf
            <button type="submit" class="w-full py-3 bg-red-600 text-white rounded hover:bg-red-700 font-semibold">
                Refuser 
            </button>
        </form>

        <form action="{{ route('demandes.validerDirecteur', $demande->id) }}" method="POST" class="flex-1">
            @csrf
            <button type="submit" class="w-full py-3 bg-green-600 text-white rounded hover:bg-green-700 font-semibold">
                Valider
            </button>
        </form>
    </div>

    <div class="mt-6">
        <a href="{{ route('demandes.enAttenteDirecteur') }}" 
           class="px-6 py-3 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 font-semibold">
             Retour à la liste
        </a>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewBtns = document.querySelectorAll('.view-file-btn');
    const modal = document.getElementById('fileModal');
    const iframe = document.getElementById('pieceViewer');
    const closeBtn = document.getElementById('closeModal');

    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const file = this.dataset.file;
            iframe.src = file;
            modal.classList.remove('hidden');
        });
    });

    closeBtn.addEventListener('click', function() {
        modal.classList.add('hidden');
        iframe.src = '';
    });

    window.addEventListener('click', function(e) {
        if(e.target === modal) {
            modal.classList.add('hidden');
            iframe.src = '';
        }
    });
});
</script>

@endsection
