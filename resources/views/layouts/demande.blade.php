<div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            
            <a href="{{ route('demandes.index') }}" class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                <div class="text-sm font-medium opacity-90">Demandes émises</div>
                <div class="text-2xl font-bold mt-1">{{ number_format($totalDemandes ?? 0, 0, ',', ' ') }} F CFA</div>
                <div class="text-xs opacity-75 mt-1">Total cette année</div>
            </a>

            <a href="{{ route('demandes.enAttenteControl') }}" class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                <div class="text-sm font-medium opacity-90">En attente de validation du Contrôleur</div>
                <div class="text-2xl font-bold mt-1">{{ number_format($totalEnAttenteControleur ?? 0, 0, ',', ' ') }} F CFA</div>
                <div class="text-xs opacity-75 mt-1">En cours de validation</div>
            </a>

            <a href="{{ route('demandes.enAttenteDaf') }}" class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                <div class="text-sm font-medium opacity-90">En attente du validation du DAF</div>
                <div class="text-2xl font-bold mt-1">{{ number_format($totalEnAttenteDaf ?? 0, 0, ',', ' ') }} F CFA</div>
                <div class="text-xs opacity-75 mt-1">Validation financière</div>
            </a>

            <a href="{{ route('demandes.enAttenteDirecteur') }}" class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                <div class="text-sm font-medium opacity-90">En attente de validation du DG</div>
                <div class="text-2xl font-bold mt-1">{{ number_format($totalEnAttenteDirecteur ?? 0, 0, ',', ' ') }} F CFA</div>
                <div class="text-xs opacity-75 mt-1">Validation stratégique</div>
            </a>

            <a href="{{ route('demandes.valider') }}" class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                <div class="text-sm font-medium opacity-90">Demandes validées</div>
                <div class="text-2xl font-bold mt-1">{{ number_format($totalValide ?? 0, 0, ',', ' ') }} F CFA</div>
                <div class="text-xs opacity-75 mt-1">Prêtes pour paiement</div>
            </a>

            <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                <div class="text-sm font-medium opacity-90">Statut global</div>
                <div class="text-2xl font-bold mt-1">{{ $tauxTraitement ?? 0 }}%</div>
                <div class="text-xs opacity-75 mt-1">Taux de traitement</div>
            </div>

        </div>
    </div>
</div>
