@php
    $permissions = auth()->user()->role->permissions->pluck('nom');

    $cards = ['emises']; // toujours visible

    if ($permissions->contains(function ($p) {
        return str_starts_with($p, 'voir_demande_valider1_');
    })) {
        $cards[] = 'controleur';
    }

    if ($permissions->contains(function ($p) {
        return str_starts_with($p, 'voir_demande_valider2_');
    })) {
        $cards[] = 'daf';
    }

    if ($permissions->contains(function ($p) {
        return str_starts_with($p, 'voir_demande_valider3_');
    })) {
        $cards[] = 'dg';
    }

    if ($permissions->contains(function ($p) {
        return str_starts_with($p, 'voir_demande_valider123_');
    })) {
        $cards[] = 'valide';
    }

    $count = count($cards);

    if ($count === 5) {
        $gridClass = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5';
    } elseif ($count === 4) {
        $gridClass = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-4';
    } elseif ($count === 3) {
        $gridClass = 'grid-cols-1 sm:grid-cols-3';
    } elseif ($count === 2) {
        $gridClass = 'grid-cols-1 sm:grid-cols-2';
    } else {
        $gridClass = 'grid-cols-1';
    }
@endphp

<div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <div class="grid {{ $gridClass }} gap-4 items-stretch">

            {{-- DEMANDES EMISES --}}
            @if(in_array('emises', $cards))
                <a href="{{ route('demandes.index') }}" class="bg-white/20 rounded-lg p-4 backdrop-blur-sm hover:bg-white/30 transition">
                    <div class="text-sm font-medium opacity-90">Demandes émises</div>
                    <div class="text-2xl font-bold mt-1">
                        {{ number_format($totalDemandes ?? 0, 0, ',', ' ') }} F CFA
                    </div>
                    <div class="text-xs opacity-75 mt-1">Total cette année</div>
                </a>
            @endif

            {{-- CONTROLEUR --}}
            @if(in_array('controleur', $cards))
                <a href="{{ route('demandes.enAttenteControl') }}" class="bg-white/20 rounded-lg p-4 backdrop-blur-sm hover:bg-white/30 transition">
                    <div class="text-sm font-medium opacity-90">Validation contrôleur</div>
                    <div class="text-2xl font-bold mt-1">
                        {{ number_format($totalEnAttenteControleur ?? 0, 0, ',', ' ') }} F CFA
                    </div>
                    <div class="text-xs opacity-75 mt-1">En cours de validation</div>
                </a>
            @endif

            {{-- DAF --}}
            @if(in_array('daf', $cards))
                <a href="{{ route('demandes.enAttenteDaf') }}" class="bg-white/20 rounded-lg p-4 backdrop-blur-sm hover:bg-white/30 transition">
                    <div class="text-sm font-medium opacity-90">Validation DAF</div>
                    <div class="text-2xl font-bold mt-1">
                        {{ number_format($totalEnAttenteDaf ?? 0, 0, ',', ' ') }} F CFA
                    </div>
                    <div class="text-xs opacity-75 mt-1">Validation financière</div>
                </a>
            @endif

            {{-- DG --}}
            @if(in_array('dg', $cards))
                <a href="{{ route('demandes.enAttenteDirecteur') }}" class="bg-white/20 rounded-lg p-4 backdrop-blur-sm hover:bg-white/30 transition">
                    <div class="text-sm font-medium opacity-90">Validation DG</div>
                    <div class="text-2xl font-bold mt-1">
                        {{ number_format($totalEnAttenteDirecteur ?? 0, 0, ',', ' ') }} F CFA
                    </div>
                    <div class="text-xs opacity-75 mt-1">Validation stratégique</div>
                </a>
            @endif

            {{-- VALIDÉ --}}
            @if(in_array('valide', $cards))
                <a href="{{ route('demandes.valider') }}" class="bg-white/20 rounded-lg p-4 backdrop-blur-sm hover:bg-white/30 transition">
                    <div class="text-sm font-medium opacity-90">Demandes validées</div>
                    <div class="text-2xl font-bold mt-1">
                        {{ number_format($totalValide ?? 0, 0, ',', ' ') }} F CFA
                    </div>
                    <div class="text-xs opacity-75 mt-1">Prêtes pour paiement</div>
                </a>
            @endif

        </div>
    </div>
</div>