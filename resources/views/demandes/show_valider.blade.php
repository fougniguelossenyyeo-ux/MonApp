@extends('layouts.template')
@section('maincontent')
@include('layouts.demande')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Status Header -->
    <div class="bg-gradient-to-r from-green-500 to-green-700 text-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold">{{ $demande->reference_dp }}</h2>
                <p class="text-2xl font-bold">{{ $demande->denomination }}</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="status-badge bg-green-50 text-green-700 text-xs px-2 py-1 rounded">
                    <b>Validée</b>
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
                    <p class="text-sm text-gray-500">Fournisseur</p>
                    <p class="font-medium text-gray-900">{{ $demande->nom_fournisseur}}</p>
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
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
        <p class="text-gray-700">{{ $demande->description ?? '-' }}</p>
    </div>

    <!-- Pièces jointes -->
   <!-- Pièces jointes -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pièces jointes</h3>
    <div class="space-y-3">
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
</div>
<!-- Modal PDF -->
<div id="fileModal" 
     class="hidden fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[9999] p-4">
    
    <div class="bg-white p-6 rounded-2xl shadow-2xl w-full max-w-5xl mx-auto relative transform transition-all duration-300 scale-100">
        <button id="closeFileModal" 
                class="absolute top-3 right-3 text-gray-600 hover:text-red-600 text-2xl font-bold">
            &times;
        </button>

        <iframe id="fileFrame" src="" 
                class="w-full h-[80vh] rounded-lg border border-gray-200 shadow-inner"></iframe>
    </div>
</div>
<div class="mt-6 flex justify-between">
    <!-- Bouton Retour -->
    <a href="{{ route('demandes.valider') }}" 
       class="px-6 py-3 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 font-semibold">
        Retour à la liste des demandes validées
    </a>

    <!-- Bouton Imprimer -->
    <a href="{{ route('demandes.imprimer', $demande->id) }}" 
      target="_blank" class="px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold">
        <i class="fas fa-print mr-2"></i> Imprimer la demande
    </a>
</div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewBtns = document.querySelectorAll('.view-file-btn');
    const modal = document.getElementById('fileModal');
    const iframe = document.getElementById('fileFrame');
    const closeBtn = document.getElementById('closeFileModal');

    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const file = this.dataset.file;
            iframe.src = file;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden'); // Bloque le scroll arrière-plan
        });
    });

    closeBtn.addEventListener('click', closeModal);
    window.addEventListener('click', function(e) {
        if(e.target === modal) {
            closeModal();
        }
    });

    function closeModal() {
        modal.classList.add('hidden');
        iframe.src = '';
        document.body.classList.remove('overflow-hidden'); // Débloque le scroll arrière-plan
    }
});
</script>


@endsection
