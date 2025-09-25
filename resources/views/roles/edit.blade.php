@extends('layouts.template')
@section('maincontent')
@include('layouts.Adminheader')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Formulaire de modification de rôle -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Modifier un rôle</h2>
            <p class="text-gray-600 mt-1">Mettez à jour les informations du rôle ci-dessous.</p>
        </div>

        <form action="{{ route('roles.update', $role->id) }}" method="POST" id="roleForm" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Libellé du rôle -->
            <div>
                <label for="roleName" class="block text-sm font-medium text-gray-700 mb-2">
                    Libellé du rôle <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="roleName" 
                    name="libelle" 
                    value="{{ old('libelle', $role->libelle) }}"
                    required
                    class="w-full max-w-md px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                    placeholder="Ex: Administrateur, Caissier, Contrôleur..."
                >
            </div>

            <!-- Sélection de l'entité -->
            <div>

   <label class="block text-sm font-medium text-gray-700 mb-2">
        Entité <span class="text-red-500">*</span>
    </label>
    <select 
        name="entite_id" 
        required 
        class="w-full max-w-md px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
    >
        @foreach($entites as $entite)
            <option value="{{ $entite->id }}" {{ $entite->id == $role->entite_id ? 'selected' : '' }}>
                {{ $entite->libelle_entite }}
            </option>
        @endforeach
    </select>

            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="{{ route('roles.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors flex items-center"
                >
                    <i class="fas fa-edit mr-2"></i>
                    Modifier le rôle
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
