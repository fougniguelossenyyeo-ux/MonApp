@extends('layouts.template')
@section('maincontent')

@include('layouts.demande')

<!-- Contenu principal -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête de page -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <p class="text-gray-600 mt-1">Historique complet des demandes de paiement validées</p>
        </div>
        <div class="mt-4 md:mt-0">
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-600">
                    <span id="validatedCount">{{ $demandes->total() }}</span> demandes validées
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
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

    <!-- Grille des cartes -->
<!-- Grille des cartes -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mt-6">
    @forelse($demandes as $demande)
        <div class="archive-card bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full">
            <!-- Contenu principal de la card -->
            <div class="p-4 flex flex-col justify-between h-full">
                <!-- Header -->
                <div>
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $demande->reference_dp }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Délai: {{ $demande->date_paiement?->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <!-- Statut -->
                    @php
                        $statusText = "Validé";
                        $badgeColor = "bg-green-50 text-green-700";
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
                <div class="border-t border-gray-100 pt-3 mt-3 flex-none">
                    <p class="text-xs text-gray-600 line-clamp-3">{{ $demande->description }}</p>
                </div>

                <!-- Footer -->
                <div class="mt-3 flex flex-wrap items-center justify-between gap-2 border-t border-gray-100 pt-3 text-xs text-gray-500">
                    <div class="flex flex-col">
                        <span>Créée le :</span>
                        <span class="text-gray-900">{{ $demande->created_at?->format('d/m/Y H:i') }}</span>
                    </div>

                    <div class="flex flex-col">
                        <span>Validation Contrôleur :</span>
                        <span class="text-gray-900">{{ $demande->date_validation_controleur?->format('d/m/Y H:i') ?? 'Non validée' }}</span>
                    </div>

                    <div class="flex flex-col">
                        <span>Validation DAF :</span>
                        <span class="text-gray-900">{{ $demande->date_validation_daf?->format('d/m/Y H:i') ?? 'Non validée' }}</span>
                    </div>

                    <div class="flex flex-col">
                        <span>Validation DG :</span>
                        <span class="text-gray-900">{{ $demande->date_validation_dg?->format('d/m/Y H:i') ?? 'Non validée' }}</span>
                    </div>

                    <div class="flex items-center">
                        <a href="{{ route('demandes.showValider', $demande->id) }}" 
                           class="view-details-btn text-indigo-600 hover:text-indigo-800 text-xs font-medium">
                            Voir détails
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-4 text-center py-12">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune demande validée</h3>
            <p class="text-gray-500">Il n’y a actuellement aucune demande validée par le Directeur.</p>
        </div>
    @endforelse
</div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $demandes->links() }}
    </div>
</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Boutons "Voir détails" redirigent simplement vers la page de détail
    const viewDetailBtns = document.querySelectorAll('.view-details-btn');
    viewDetailBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = this.getAttribute('href');
        });
    });

    // Boutons pour visualiser les fichiers joints
    const viewFileBtns = document.querySelectorAll('.view-file-btn');
    viewFileBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const file = this.dataset.file;
            window.open(file, '_blank');
        });
    });
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
