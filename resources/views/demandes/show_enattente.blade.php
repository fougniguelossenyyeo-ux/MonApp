@extends('layouts.template')
@section('maincontent')
@include('layouts.demande')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Status Header -->
        <!-- Status Header -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold">{{ $demande->reference_dp }}</h2>
                <p class="text-2xl font-bold">{{ $demande->denomination }}</p>
            </div>
               <div class="mt-4 md:mt-0">
            @php
                $statusText = match($demande->status) {
                    0  => "En attente de validation du Contrôleur",
                    1  => "En attente de validation du DAF",
                    2  => "En attente de validation du DG",
                    3  => "Validé",
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
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
        <p class="text-gray-700">{{ $demande->description ?? '-' }}</p>
    </div>

    <!-- Pièces jointes -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pièces jointes</h3>
           @if ($pieces && count($pieces) > 0)
    @foreach ($pieces as $piece)
        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
            <span class="text-gray-700 flex-1">{{ basename($piece) }}</span>

            <button 
                class="view-file-btn text-indigo-600 hover:text-indigo-800 ml-2 flex items-center"
                data-file="{{ asset('storage/'.$piece) }}"
            >
                <i class="fas fa-eye mr-1"></i> Voir
            </button>
        </div>
    @endforeach
@else
    <p class="text-gray-500">Aucune pièce jointe pour cette demande.</p>
@endif
    </div>

    <!-- Modal PDF -->
    <div id="fileModal" class="hidden fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[9999] p-4">
        <div class="bg-white p-6 rounded-2xl shadow-2xl w-full max-w-5xl mx-auto relative">
            <button id="closeFileModal" class="absolute top-3 right-3 text-gray-600 hover:text-red-600 text-2xl font-bold">&times;</button>
            <iframe id="fileFrame" src="" class="w-full h-[80vh] rounded-lg border border-gray-200 shadow-inner"></iframe>
        </div>
    </div>
       <!-- Actions -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>

    <div class="flex gap-4">
        
        <!-- REFUSER -->
        <div class="flex-1">
            <form id="refusForm" action="{{ route('demandes.refuserControleur', $demande->id) }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="motif_refus" id="motif_refus">
            </form>

            <button onclick="refuserDemande()" 
                class="w-full py-3 bg-red-600 text-white rounded hover:bg-red-700 font-semibold">
                Refuser
            </button>
        </div>

        <!-- ACCEPTER -->
        <form action="{{ route('demandes.validerControleur', $demande->id) }}" method="POST" class="flex-1">
            @csrf
            <button type="submit" 
                class="w-full py-3 bg-green-600 text-white rounded hover:bg-green-700 font-semibold">
                Accepter
            </button>
        </form>

    </div>

    <!-- RETOUR -->
    <div class="mt-6">
        <a href="{{ route('demandes.enAttenteControl') }}" 
           class="px-6 py-3 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 font-semibold inline-block">
            Retour à la liste
        </a>
    </div>
</div>

</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // =========================
    //  AFFICHAGE PDF MODAL
    // =========================
    const viewBtns = document.querySelectorAll('.view-file-btn');
    const modal = document.getElementById('fileModal');
    const iframe = document.getElementById('fileFrame');
    const closeBtn = document.getElementById('closeFileModal');

    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const fileUrl = this.getAttribute('data-file');

            if (!fileUrl) return;

            iframe.src = fileUrl;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        });
    });

    // Fermer modal
    closeBtn.addEventListener('click', closeModal);

    window.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });

    function closeModal() {
        modal.classList.add('hidden');
        iframe.src = '';
        document.body.classList.remove('overflow-hidden');
    }

});

// =========================
// REFUS AVEC SWEETALERT
// =========================
function refuserDemande() {
    Swal.fire({
        title: 'Motif du refus',
        input: 'textarea',
        inputLabel: 'Veuillez saisir la raison du refus',
        inputPlaceholder: 'Ex: Pièces justificatives manquantes...',
        inputAttributes: {
            'aria-label': 'Motif du refus'
        },
        showCancelButton: true,
        confirmButtonText: 'Refuser',
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#d33',
        preConfirm: (value) => {
            if (!value) {
                Swal.showValidationMessage('Le motif est obligatoire ❗');
                return false;
            }
            return value;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('motif_refus').value = result.value;
            document.getElementById('refusForm').submit();
        }
    });
}
</script>
@endsection
