<div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex space-x-6">
            
            <!-- Carte 1 : Paiements émis -->
            <a href="{{ route('paiements.emis') }}" class="flex-1 bg-white bg-opacity-20 rounded-lg p-6 backdrop-blur-sm shadow-md hover:shadow-lg transition">
                <div class="text-sm font-medium opacity-90">Paiements émis</div>
                <div class="text-2xl font-bold mt-1">{{ number_format($totalEmis ?? 0, 0, ',', ' ') }} F CFA</div>
                <div class="text-xs opacity-75 mt-1">Total (hors refusés)</div>
            </a>

            <!-- Carte 2 : Paiements en cours -->
            <a href="{{ route('paiements.encours') }}" class="flex-1 bg-white bg-opacity-20 rounded-lg p-6 backdrop-blur-sm shadow-md hover:shadow-lg transition">
                <div class="text-sm font-medium opacity-90">Paiements en cours</div>
                <div class="text-2xl font-bold mt-1">{{ number_format($totalEncours ?? 0, 0, ',', ' ') }} F CFA</div>
                <div class="text-xs opacity-75 mt-1">Somme des versements en attente</div>
            </a>

            <!-- Carte 3 : Paiements partiels -->
            <a href="{{ route('paiements.partiellement') }}" class="flex-1 bg-white bg-opacity-20 rounded-lg p-6 backdrop-blur-sm shadow-md hover:shadow-lg transition">
                <div class="text-sm font-medium opacity-90">Paiements partiels</div>
                <div class="text-2xl font-bold mt-1">{{ number_format($totalPartiels ?? 0, 0, ',', ' ') }} F CFA</div>
                <div class="text-xs opacity-75 mt-1">En attente de paiement complet</div>
            </a>

            <!-- Carte 4 : Paiements effectués -->
            <a href="{{ route('paiements.valides') }}" class="flex-1 bg-white bg-opacity-20 rounded-lg p-6 backdrop-blur-sm shadow-md hover:shadow-lg transition">
                <div class="text-sm font-medium opacity-90">Paiements effectués</div>
                <div class="text-2xl font-bold mt-1">{{ number_format($totalValide ?? 0, 0, ',', ' ') }} F CFA</div>
                <div class="text-xs opacity-75 mt-1">Somme des versements validés</div>
            </a>

        </div>
    </div>
</div>
