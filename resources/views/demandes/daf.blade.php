@extends('layouts.template')
@section('maincontent')

@include('layouts.demande')

<!-- Contenu principal -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête de page -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <p class="text-gray-600 mt-1">Historique complet des demandes de paiement du contrôleur</p>
        </div>
        <div class="mt-4 md:mt-0">
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-600">
                    <span id="archiveCount">{{ $demandes->total() }}</span> demandes en attente
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres (statiques, non liés à la base) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <!-- Recherche -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Recherche</label>
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="Référence, entité..." 
                        class="w-full pl-8 pr-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                    >
                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 text-xs"></i>
                    </div>
                </div>
            </div>

            <!-- Date début -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Date début</label>
                <input type="date" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
            </div>

            <!-- Date fin -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Date fin</label>
                <input type="date" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
            </div>

            <!-- Entité -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Entité</label>
                <select class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <option value="">Toutes</option>
                    <option value="GAZ">GAZ</option>
                    <option value="KTLS">KTLS</option>
                    <option value="KAMACI">KAMACI</option>
                </select>
            </div>

            <!-- Montant -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Montant</label>
                <select class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <option value="">Tous</option>
                    <option value="0-1000000">&lt; 1M</option>
                    <option value="1000000-5000000">1M - 5M</option>
                    <option value="5000000-10000000">5M - 10M</option>
                    <option value="10000000+">&gt; 10M</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Grille des cartes dynamiques -->
<!-- Grille des cartes dynamiques -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
    @forelse($demandes as $demande)
        <div class="archive-card bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden cursor-pointer hover:shadow-md transition-shadow flex flex-col h-full">
            <div class="p-4 flex-1 flex flex-col justify-between">
                
                <!-- Header carte -->
                <div>
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $demande->reference_dp }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $demande->date_paiement?->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <!-- Statut -->
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
                            3     => "bg-green-50 text-green-700",
                            -1,-2,-3 => "bg-red-50 text-red-700",
                            default => "bg-gray-50 text-gray-700",
                        };
                    @endphp

                    <span class="status-badge {{ $badgeColor }} text-xs px-2 py-1 rounded inline-block mb-2">
                        <b>{{ $statusText }}</b>
                    </span>

                    <!-- Dénomination & Entité -->
                    <div class="mb-3">
                        <p class="text-gray-700 text-sm font-medium truncate">{{ $demande->denomination }}</p>
                        <div class="flex space-x-1 mt-1">
                            <span class="entity-badge bg-slate-100 text-slate-700 inline-block text-xs px-2 py-1 rounded">
                                {{ $demande->entite?->libelle_entite ?? 'non définie' }}
                            </span>
                        </div>
                    </div>

                    <!-- Montant & Date création -->
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <p class="text-xs text-gray-500">Montant</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ number_format($demande->montant_paiement_fournisseur, 0, ',', ' ') }} F CFA
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">Créé le</p>
                            <p class="text-xs font-medium text-gray-900">{{ $demande->created_at?->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="border-t border-gray-100 pt-3 mt-3 flex-1">
                    <p class="text-xs text-gray-600 line-clamp-3">{{ $demande->description }}</p>
                </div>

                <!-- Footer carte -->
                <div class="mt-3 flex justify-between items-center pt-3 border-t border-gray-100">
    <div class="flex flex-col text-xs text-gray-500">
        <span>date de création :</span>
        <span class="text-gray-900">{{ $demande->created_at?->format('d/m/Y H:i') }}</span>
    </div>

    <div class="flex flex-col text-xs text-gray-500">
        <span>Validation Contrôleur:</span>
        <span class="text-gray-900">
            {{ $demande->date_validation_controleur?->format('d/m/Y H:i') ?? 'Non validée' }}
        </span>
    </div>

    <div class="flex space-x-2">
        <a href="{{ route('demandes.showEnAttenteDaf', $demande->id) }}" 
           class="view-details-btn text-indigo-600 hover:text-indigo-800 text-xs font-medium">
            Voir détails
        </a>
    </div>
</div>

            </div>
        </div>
    @empty
        <div class="col-span-4 text-center py-12">
            <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune demande trouvée</h3>
            <p class="text-gray-500">Il n’y a actuellement aucune demande en attente de validation du DAF.</p>
        </div>
    @endforelse
</div>

<!-- Pagination Laravel -->
<div class="mt-6">
    {{ $demandes->links() }}
</div>




<script>

document.getElementById('closeModal').addEventListener('click', () => {
    document.getElementById('detailModal').classList.add('hidden');
    document.body.style.overflow='';
});
document.getElementById('closeDetailModal').addEventListener('click', () => {
    document.getElementById('detailModal').classList.add('hidden');
    document.body.style.overflow='';
});
</script>
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Succès',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: "{{ session('error') }}",
            timer: 3000,
            showConfirmButton: false
        });
    @endif
});
</script>
@endsection


@endsection
