<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex gap-6 flex-nowrap">
            <a href="{{route('roles.index')}}" class="flex-1 min-w-0 p-4 rounded-lg hover:bg-indigo-50 transition-colors group">
                <h3 class="text-lg font-semibold text-indigo-600 group-hover:text-indigo-800">Gestion des rôles</h3>
                <p class="text-gray-600 mt-1">Créer et gérer les rôles utilisateurs</p>
            </a>
            <a href="{{route('users.list')}}" class="flex-1 min-w-0 p-4 rounded-lg hover:bg-indigo-50 transition-colors group">
                <h3 class="text-lg font-semibold text-indigo-600 group-hover:text-indigo-800">Gestion des utilisateurs</h3>
                <p class="text-gray-600 mt-1">Administrer les comptes utilisateurs</p>
            </a>
            <a href="{{route('permissions.index')}}" class="flex-1 min-w-0 p-4 rounded-lg hover:bg-indigo-50 transition-colors group">
                <h3 class="text-lg font-semibold text-indigo-600 group-hover:text-indigo-800">Gestion des permissions</h3>
                <p class="text-gray-600 mt-1">Configurer les accès et autorisations</p>
            </a>
            <a href="{{route('entites.index')}}" class="flex-1 min-w-0 p-4 rounded-lg hover:bg-indigo-50 transition-colors group">
                <h3 class="text-lg font-semibold text-indigo-600 group-hover:text-indigo-800">Gestion des entités</h3>
                <p class="text-gray-600 mt-1">Administrer les entités organisationnelles</p>
            </a>
            <a href="{{route('historiques.index')}}" class="flex-1 min-w-0 p-4 rounded-lg hover:bg-indigo-50 transition-colors group">
                <h3 class="text-lg font-semibold text-indigo-600 group-hover:text-indigo-800">Historique des actions</h3>
                <p class="text-gray-600 mt-1">Journal d'activités</p>
            </a>
        </div>
    </div>
</div>