@extends('layouts.template')
@section('maincontent')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumbs -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="Administrateur.html" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                    <i class="fas fa-cog mr-2"></i>
                    Administration
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                    <span class="ml-1 text-sm font-medium text-indigo-600 md:ml-2">Gestion des rôles</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Liste des rôles -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Liste des Rôles</h2>
                <p class="text-gray-600 mt-1">Gérez les rôles disponibles dans le système</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('roles.create')}}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter un rôle
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre d'utilisateurs</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($roles as $role)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{$role->libelle}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                1 utilisateur
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            Direction des Affaires Financières
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <!-- Bouton Modifier -->
                                <a href="{{route('roles.edit', $role->id)}}" class="inline-flex items-center px-3 py-1 text-sm text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-md transition-colors">
                                    <i class="fas fa-edit mr-1"></i>
                                    Modifier
                                </a>
                                
                                <!-- Bouton Supprimer -->
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 text-sm text-red-600 hover:text-red-900 hover:bg-red-50 rounded-md transition-colors" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?')">
                                        <i class="fas fa-trash mr-1"></i>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>

@endsection