@extends('layouts.template')
@section('maincontent')
@include('layouts.Adminheader')

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
         

            <!-- Formulaire de création des entites -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Création d'une nouvelle Entité</h2>
                    <p class="text-gray-600 mt-1">Remplissez le formulaire ci-dessous pour créer une nouvelle Entité rôle dans le système.</p>
                </div>

                <form action="{{route('entites.store')}}" method="POST" id="roleForm" class="space-y-6">
                    @csrf
                    <div>
                        <label for="roleName" class="block text-sm font-medium text-gray-700 mb-2">
                            Libellé de l'entité <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="roleName" 
                            name="libelle_entite" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            placeholder="Ex: Administrateur, Caissier, Contrôleur..."
                        >
                        <p class="mt-1 text-sm text-gray-500">Entrez le nom de l'entité à créer</p>
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-4">
                        <a href="{{route('entites.index')}}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Annuler
                        </a>
                        <button 
                            type="submit" 
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors flex items-center"
                        >
                            <i class="fas fa-plus-circle mr-2"></i>
                            Créer une entité
                        </button>
                    </div>
                </form>
            </div>
        </main>


@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Succès',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    @endif
});
</script>
@endsection
    