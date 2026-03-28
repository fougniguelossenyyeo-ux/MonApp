@php
    $permissions = auth()->user()->role->permissions->pluck('nom');
    $nom = strtolower(optional(auth()->user()->role->entite)->libelle_entite ?? '');

    $cards = [];

    // Toujours visible
    $cards[] = 'emises';

    if ($permissions->contains('voir_demande_valider1_' . $nom)) {
        $cards[] = 'controleur';
    }

    if ($permissions->contains('voir_demande_valider2_' . $nom)) {
        $cards[] = 'daf';
    }

    if ($permissions->contains('voir_demande_valider3_' . $nom)) {
        $cards[] = 'dg';
    }

    if ($permissions->contains('voir_demande_valider123_' . $nom)) {
        $cards[] = 'valide';
    }

    $count = count($cards);

    // Définir la grille dynamique
    if ($count == 1) {
        $gridClass = 'grid-cols-1';
    } elseif ($count == 2) {
        $gridClass = 'grid-cols-1 md:grid-cols-2';
    } elseif ($count == 3) {
        $gridClass = 'grid-cols-1 md:grid-cols-3';
    } else {
        $gridClass = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4';
    }
@endphp

<div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <div class="grid {{ $gridClass }} gap-4">

            <!-- Demandes émises (toujours visible) -->
             @if(in_array('emises', $cards))
            <a href="{{ route('demandes.index') }}" class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                <div class="text-sm font-medium opacity-90">Demandes émises</div>
                <div class="text-2xl font-bold mt-1">
                    {{ number_format($totalDemandes ?? 0, 0, ',', ' ') }} F CFA
                </div>
                <div class="text-xs opacity-75 mt-1">Total cette année</div>
            </a>
            @endif
            <!-- Contrôleur -->
             @if(in_array('controleur', $cards))
            @if($permissions->contains('voir_demande_valider1_' . $nom))
            <a href="{{ route('demandes.enAttenteControl') }}" class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                <div class="text-sm font-medium opacity-90">En attente de validation au niveau 1</div>
                <div class="text-2xl font-bold mt-1">
                    {{ number_format($totalEnAttenteControleur ?? 0, 0, ',', ' ') }} F CFA
                </div>
                <div class="text-xs opacity-75 mt-1">En cours de validation</div>
            </a>
            @endif
                @endif
            <!-- DAF -->
         @if(in_array('daf', $cards))
            @if($permissions->contains('voir_demande_valider2_' . $nom))
            <a href="{{ route('demandes.enAttenteDaf') }}" class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                <div class="text-sm font-medium opacity-90">En attente de validation au niveau 2</div>
                <div class="text-2xl font-bold mt-1">
                    {{ number_format($totalEnAttenteDaf ?? 0, 0, ',', ' ') }} F CFA
                </div>
                <div class="text-xs opacity-75 mt-1">Validation financière</div>
            </a>
            @endif
         @endif  
         @if(in_array('dg', $cards))
            <!-- DG -->
            @if($permissions->contains('voir_demande_valider3_' . $nom))
            <a href="{{ route('demandes.enAttenteDirecteur') }}" class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                <div class="text-sm font-medium opacity-90">En attente de validation au niveau 3</div>
                <div class="text-2xl font-bold mt-1">
                    {{ number_format($totalEnAttenteDirecteur ?? 0, 0, ',', ' ') }} F CFA
                </div>
                <div class="text-xs opacity-75 mt-1">Validation stratégique</div>
            </a>
            @endif
            @endif
             @if(in_array('valide', $cards))
            <!-- VALIDÉ (SEULEMENT 123) -->
            @if($permissions->contains('voir_demande_valider123_' . $nom))
            <a href="{{ route('demandes.valider') }}" class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                <div class="text-sm font-medium opacity-90">Demandes validées</div>
                <div class="text-2xl font-bold mt-1">
                    {{ number_format($totalValide ?? 0, 0, ',', ' ') }} F CFA
                </div>
                <div class="text-xs opacity-75 mt-1">Prêtes pour paiement</div>
            </a>
            @endif
                @endif
        </div>
    </div>
</div>