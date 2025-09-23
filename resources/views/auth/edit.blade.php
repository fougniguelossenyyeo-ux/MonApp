@extends('layouts.template') 
@section('maincontent')
@include('layouts.Adminheader')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="md:flex">
            <!-- Left Panel -->
            <div class="hidden md:block md:w-2/5 bg-gradient-to-br from-indigo-500 to-purple-600 p-8">
                <div class="h-full flex flex-col justify-center text-white">
                    <h2 class="text-2xl font-bold mb-4">Modification de l'utilisateur</h2>
                    <p class="mb-6 opacity-90">Mettez à jour les informations et les accès de l'utilisateur.</p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="mr-3 text-green-300">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <span>Gestion simplifiée des dépenses</span>
                        </div>
                        <div class="flex items-center">
                            <div class="mr-3 text-green-300">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <span>Suivi en temps réel</span>
                        </div>
                        <div class="flex items-center">
                            <div class="mr-3 text-green-300">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <span>Sécurité des données</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Form -->
            <div class="md:w-3/5 p-8">
                <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information -->
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Informations personnelles</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                            <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required 
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                            <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" required 
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email professionnel</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fonction</label>
                        <select name="fonction" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none">
                            <option value="">Sélectionnez la fonction</option>
                            <option value="administrateur" {{ $user->fonction == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                            <option value="daf" {{ $user->fonction == 'daf' ? 'selected' : '' }}>DAF</option>
                            <option value="controleur" {{ $user->fonction == 'controleur' ? 'selected' : '' }}>Contrôleur</option>
                            <option value="dg" {{ $user->fonction == 'dg' ? 'selected' : '' }}>DG</option>
                            <option value="caissier" {{ $user->fonction == 'caissier' ? 'selected' : '' }}>Caissier(e)</option>
                            <option value="tresorerie" {{ $user->fonction == 'tresorerie' ? 'selected' : '' }}>Trésorerie</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                        <input type="text" name="poste" value="{{ old('poste', $user->poste) }}"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none">
                    </div>

                    <!-- Security Information -->
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Sécurité du compte</h3>
                       <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                            <select name="role_id" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none">
        @foreach($roles as $role)
            <option value="{{ $role->id }}" {{ isset($user) && $role->id == $user->role_id ? 'selected' : '' }}>
                {{ $role->libelle }}
            </option>
        @endforeach
    </select>
                        </div>


                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe (laisser vide pour ne pas changer)</label>
                        <input type="password" name="password" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none">
                    </div>

                    <div class="flex justify-end pt-6">
                            <!-- Bouton Annuler -->
                <!-- Bouton Annuler -->
    <a href="{{ route('users.list') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors mr-4">
        Annuler
    </a>
                        <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Modifier ce compte <i class="fas fa-check ml-2"></i>
                        </button>
                    </div>

                </form>
            </div>
        </div>
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
    