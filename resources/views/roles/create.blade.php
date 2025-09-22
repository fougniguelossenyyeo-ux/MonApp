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
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                            <a href="#" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Gestion des rôles</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                            <span class="ml-1 text-sm font-medium text-indigo-600 md:ml-2">Création de rôle</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Formulaire de création de rôle -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Création d'un nouveau rôle</h2>
                    <p class="text-gray-600 mt-1">Remplissez le formulaire ci-dessous pour créer un nouveau rôle dans le système.</p>
                </div>

                <form action="{{route('roles.store')}}" method="POST" id="roleForm" class="space-y-6">
                    @csrf
                    <div>
                        <label for="roleName" class="block text-sm font-medium text-gray-700 mb-2">
                            Libellé du rôle <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="roleName" 
                            name="libelle" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            placeholder="Ex: Administrateur, Caissier, Contrôleur..."
                        >
                        <p class="mt-1 text-sm text-gray-500">Entrez le nom du rôle à créer</p>
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-4">
                        <a href="{{route('roles.index')}}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Annuler
                        </a>
                        <button 
                            type="submit" 
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors flex items-center"
                        >
                            <i class="fas fa-plus-circle mr-2"></i>
                            Créer le rôle
                        </button>
                    </div>
                </form>
            </div>
        </main>


@endsection