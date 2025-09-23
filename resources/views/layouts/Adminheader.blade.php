     <!-- Entête de gestion avec liens -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-wrap gap-6">
                    <a href="{{route('roles.index')}}" class="flex-1 min-w-[200px] p-4 rounded-lg hover:bg-indigo-50 transition-colors group">
                        <h3 class="text-lg font-semibold text-indigo-600 group-hover:text-indigo-800">Gestion des rôles</h3>
                        <p class="text-gray-600 mt-1">Créer et gérer les rôles utilisateurs</p>
                    </a>
                    <a href="{{route('users.list')}}" class="flex-1 min-w-[200px] p-4 rounded-lg hover:bg-indigo-50 transition-colors group">
                        <h3 class="text-lg font-semibold text-indigo-600 group-hover:text-indigo-800">Gestion des utilisateurs</h3>
                        <p class="text-gray-600 mt-1">Administrer les comptes utilisateurs</p>
                    </a>
                    <a href="#" class="flex-1 min-w-[200px] p-4 rounded-lg hover:bg-indigo-50 transition-colors group">
                        <h3 class="text-lg font-semibold text-indigo-600 group-hover:text-indigo-800">Gestion des permissions</h3>
                        <p class="text-gray-600 mt-1">Configurer les accès et autorisations</p>
                    </a>
                </div>
            </div>
        </div>