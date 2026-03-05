@extends('layouts.template')

@section('maincontent')
@include('layouts.Adminheader')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">

        {{-- En-tête --}}
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Détails du rôle : {{ $role->libelle }}</h2>
                    <p class="text-gray-600 mt-1">Permissions associées à ce rôle</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            {{-- Informations du rôle --}}
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations du rôle</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Libellé du rôle</label>
                        <p class="text-gray-900">{{ $role->libelle }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date de création</label>
                        <p class="text-gray-900">{{ $role->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Formulaire des permissions --}}
            <form action="{{ route('permissions.save') }}" method="POST">
                @csrf
                <input type="hidden" name="role_id" value="{{ $role->id }}">

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Permissions attribuées</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Boucle sur les modules --}}
                        @foreach(['Demandes', 'Paiements', 'Général'] as $module)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-medium text-gray-700 mb-3 flex items-center">
                                    @if($module == 'Demandes')
                                        <i class="fas fa-file-alt text-indigo-600 mr-2"></i>
                                    @elseif($module == 'Paiements')
                                        <i class="fas fa-money-bill-wave text-indigo-600 mr-2"></i>
                                    @else
                                        <i class="fas fa-cog text-indigo-600 mr-2"></i>
                                    @endif
                                    {{ $module }}
                                </h4>
                                <ul class="space-y-2">
                                    @foreach($groupedPermissions[$module] ?? [] as $permission)
                                        <li class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                   class="mr-2"
                                                   {{ in_array($permission->id, $currentPermissions) ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ $permission->nom }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Boutons --}}
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <button type="button" 
                            onclick="window.history.back()"
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Retour
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all shadow-md hover:shadow-lg">
                        Enregistrer les permissions
                    </button>
                </div>
            </form>

            {{-- Utilisateurs liés --}}
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Utilisateurs affectés à ce rôle</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($role->users as $user)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $user->active ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                        <button class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-sm text-gray-500 text-center">Aucun utilisateur</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection