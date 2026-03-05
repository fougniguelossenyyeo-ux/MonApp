@extends('layouts.template')
@section('maincontent')
@include('layouts.Adminheader')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Liste des rôles -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Liste des Rôles</h2>
                <p class="text-gray-600 mt-1">Sélectionnez un rôle pour voir ses permissions</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('roles.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter un rôle
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre d'utilisateurs</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entité</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($roles as $role)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $role->libelle }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $role->users->count() }} utilisateur{{ $role->users->count() > 1 ? 's' : '' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $role->entite->libelle_entite ?? '-' }}
                        </td>
                   <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
<div class="flex items-center justify-end space-x-2">

    {{-- Bouton Permissions --}}
    @if(!$role->super_admin)
        <a href="{{ route('permissions.edit', $role->id) }}"
           class="inline-flex items-center px-3 py-1 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors">
            <i class="fas fa-key mr-1"></i>
            Permissions
        </a>
    @else
        <button
            class="inline-flex items-center px-3 py-1 text-sm text-white bg-blue-400 rounded-md cursor-not-allowed opacity-50"
            title="Super Admin : permissions verrouillées"
            disabled>
            <i class="fas fa-key mr-1"></i>
            Permissions
        </button>
    @endif


    {{-- Bouton Modifier --}}
    <a href="{{ route('roles.edit', $role->id) }}"
       class="inline-flex items-center px-3 py-1 text-sm text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-md transition-colors">
        <i class="fas fa-edit mr-1"></i>
        Modifier
    </a>


    {{-- Bouton Supprimer --}}
    @if(!$role->super_admin)
        <form action="{{ route('roles.destroy', $role->id) }}"
              method="POST"
              class="delete-form inline-block"
              data-role="{{ $role->libelle }}">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="inline-flex items-center px-3 py-1 text-sm text-red-600 hover:text-red-900 hover:bg-red-50 rounded-md transition-colors">
                <i class="fas fa-trash mr-1"></i>
                Supprimer
            </button>
        </form>
    @else
        <button
            class="inline-flex items-center px-3 py-1 text-sm text-red-400 bg-red-100 rounded-md cursor-not-allowed opacity-50"
            title="Super Admin : suppression interdite"
            disabled>
            <i class="fas fa-trash mr-1"></i>
            Supprimer
        </button>
    @endif

</div>
</td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Conteneur pour afficher les permissions du rôle sélectionné -->
    <div id="permissions-container" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hidden">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Permissions du rôle : <span id="role-name"></span></h2>
        <div id="permissions-grid" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Les permissions seront injectées ici via JS -->
        </div>
    </div>

</main>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('permissions-container');
    const grid = document.getElementById('permissions-grid');
    const roleNameSpan = document.getElementById('role-name');

    // Boutons "Permissions"
    const buttons = document.querySelectorAll('.select-role-btn');
    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            const roleId = btn.getAttribute('data-role-id');

            // Récupérer les permissions via AJAX
            fetch(`/permissions/role/${roleId}/data`)
                .then(res => res.json())
                .then(data => {
                    container.classList.remove('hidden');
                    roleNameSpan.textContent = btn.closest('tr').querySelector('td div').textContent;

                    // Vider le contenu précédent
                    grid.innerHTML = '';

                    ['Demandes', 'Paiements', 'Général'].forEach(module => {
                        const perms = data.groupedPermissions[module] || [];
                        let html = `<div class="bg-gray-50 rounded-lg p-4">
                                        <h4 class="font-medium text-gray-700 mb-3 flex items-center">`;

                        if(module === 'Demandes') html += '<i class="fas fa-file-alt text-indigo-600 mr-2"></i>';
                        else if(module === 'Paiements') html += '<i class="fas fa-money-bill-wave text-indigo-600 mr-2"></i>';
                        else html += '<i class="fas fa-cog text-indigo-600 mr-2"></i>';

                        html += `${module}</h4><ul class="space-y-2">`;

                        perms.forEach(p => {
                            const checked = data.currentPermissions.includes(p.id) ? 'fa-check-circle text-green-500' : 'fa-times-circle text-gray-400';
                            html += `<li class="flex items-center">
                                        <i class="fas ${checked} mr-2"></i>
                                        <span class="text-sm text-gray-700">${p.nom}</span>
                                     </li>`;
                        });

                        html += '</ul></div>';
                        grid.insertAdjacentHTML('beforeend', html);
                    });
                })
                .catch(err => {
                    console.error(err);
                    alert('Impossible de récupérer les permissions pour ce rôle.');
                });
        });
    });
});
</script>
@endsection