@extends('layouts.template')

@section('maincontent')

@include('layouts.Adminheader')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-12">
    <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">

        <!-- En-tête -->
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-blue-50">
            <h2 class="text-xl font-bold text-gray-900">Attribution des permissions aux rôles</h2>
            <p class="mt-1 text-sm text-gray-600">
                Configurez les droits d'accès pour chaque rôle (permissions liées ou non à une entité)
            </p>
        </div>

        <!-- Formulaire principal -->
        <form method="POST" action="{{ route('permissions.update') }}" class="p-6 lg:p-8" id="permForm">
            @csrf
            @method('PUT')

            <!-- Champ caché pour l'ID du rôle -->
            <input type="hidden" name="role_id" id="hidden_role_id" value="{{ old('role_id') }}">

            <!-- Sélection du rôle -->
            <div class="mb-8">
                <label for="roleSelect" class="block text-sm font-medium text-gray-700 mb-2.5">
                    Rôle concerné <span class="text-red-600">*</span>
                </label>
                <select 
                    id="roleSelect"
                    required
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 transition-colors text-gray-900"
                >
                    <option value="">— Sélectionnez un rôle —</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}"
                                data-super="{{ $role->super_admin ? '1' : '0' }}"
                                {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->libelle }}
                            @if($role->entite)
                                ({{ $role->entite->libelle_entite }})
                            @endif
                            @if($role->super_admin)
                                ★ Super Admin
                            @endif
                        </option>
                    @endforeach
                </select>

                @error('role_id')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Zone permissions -->
            <div class="mb-10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <label class="block text-sm font-medium text-gray-700">
                        Permissions disponibles
                    </label>
                    <div class="text-sm flex gap-5">
                        <button type="button" id="selectAll" class="text-indigo-600 hover:text-indigo-800 font-medium">
                            Tout sélectionner
                        </button>
                        <button type="button" id="deselectAll" class="text-gray-600 hover:text-gray-800 font-medium">
                            Tout désélectionner
                        </button>
                    </div>
                </div>

                <!-- Recherche -->
                <input 
                    type="text" 
                    id="searchPerm" 
                    placeholder="Rechercher une permission..." 
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg mb-4 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-colors"
                >

                <!-- Liste des permissions groupées -->
                <div id="permissionsList" class="max-h-[55vh] overflow-y-auto border border-gray-200 rounded-lg bg-gray-50/70 divide-y divide-gray-100">
                    @forelse($groupedPermissions as $groupName => $perms)
                        <div class="group-section">
                            <div class="bg-indigo-50/70 px-5 py-2.5 font-medium text-indigo-800 sticky top-0 z-10 flex justify-between items-center">
                                <span>{{ $groupName }}</span>
                                <button type="button" class="select-group text-xs text-indigo-600 hover:underline" data-group="{{ $groupName }}">
                                    Sélectionner groupe
                                </button>
                            </div>

                            @foreach($perms as $permission)
                                <label class="flex items-start px-5 py-3 hover:bg-white transition-colors cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="permissions[]" 
                                        value="{{ $permission->id }}"
                                        class="mt-1 h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                        {{ in_array($permission->id, old('permissions', $currentPermissions ?? [])) ? 'checked' : '' }}
                                    >
                                    <div class="ml-3 flex-1">
                                        <div class="text-sm font-medium text-gray-900">{{ $permission->nom }}</div>
                                        @if($permission->description)
                                            <div class="text-xs text-gray-500 mt-0.5">{{ $permission->description }}</div>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @empty
                        <div class="p-12 text-center text-gray-500 italic">
                            Aucune permission trouvée
                        </div>
                    @endforelse
                </div>

                @error('permissions')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Boutons d'action -->
            <div class="flex flex-col sm:flex-row sm:justify-end gap-4 pt-6 border-t border-gray-200">
                <button 
                    type="button" 
                    onclick="history.back()"
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                >
                    Annuler
                </button>

                <button 
                    type="submit" 
                    id="submitBtn"
                    disabled
                    class="px-7 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition-all shadow-sm hover:shadow-md font-medium flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Enregistrer les permissions
                </button>
            </div>
        </form>
    </div>
</main>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const roleSelect  = document.getElementById('roleSelect');
    const hiddenRole  = document.getElementById('hidden_role_id');
    const submitBtn   = document.getElementById('submitBtn');
    const checkboxes  = document.querySelectorAll('input[name="permissions[]"]');

    // Boutons "Tout sélectionner / Tout désélectionner"
    document.getElementById('selectAll').addEventListener('click', () => {
        checkboxes.forEach(cb => cb.checked = true);
    });
    document.getElementById('deselectAll').addEventListener('click', () => {
        checkboxes.forEach(cb => cb.checked = false);
    });

    // Recherche rapide
    document.getElementById('searchPerm').addEventListener('input', function() {
        const term = this.value.toLowerCase();
        document.querySelectorAll('#permissionsList label').forEach(label => {
            const text = label.textContent.toLowerCase();
            label.style.display = text.includes(term) ? 'flex' : 'none';
        });
    });

    // Changement de rôle
    roleSelect.addEventListener('change', function() {
        hiddenRole.value = this.value;
        submitBtn.disabled = !this.value;

        const isSuperAdmin = this.selectedOptions[0].dataset.super === '1';

        // Verrouiller tout si Super Admin
        checkboxes.forEach(cb => cb.disabled = isSuperAdmin);
        submitBtn.disabled = isSuperAdmin;

        if (!this.value) {
            checkboxes.forEach(cb => cb.checked = false);
            return;
        }

        // AJAX pour récupérer les permissions du rôle
        fetch(`/permissions/role/${this.value}/data`)
            .then(response => response.json())
            .then(data => {
                checkboxes.forEach(cb => cb.checked = false);

                data.forEach(permissionId => {
                    const checkbox = document.querySelector(
                        'input[name="permissions[]"][value="' + permissionId + '"]'
                    );
                    if (checkbox) checkbox.checked = true;
                });
            })
            .catch(error => {
                console.error('Erreur chargement permissions:', error);
            });
    });

});
</script>
@endsection