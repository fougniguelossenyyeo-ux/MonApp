@extends('layouts.template') 
@section('maincontent')
@include('layouts.Adminheader')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="md:flex">
            <!-- Left Panel -->
            <div class="hidden md:block md:w-2/5 bg-gradient-to-br from-indigo-500 to-purple-600 p-8">
                <div class="h-full flex flex-col justify-center text-white">
                    <h2 class="text-2xl font-bold mb-4">Bienvenue sur DPaie</h2>
                    <p class="mb-6 opacity-90">Veuillez enregistrer le nouvel utilisateur et configurer ses accès.</p>
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
                <!-- Step Indicator -->
                <div class="flex justify-center mb-8">
                    <div class="flex space-x-4">
                        <div class="step-indicator step-active flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center mb-2 bg-indigo-500 text-white">
                                <span>1</span>
                            </div>
                            <span class="text-xs">Informations</span>
                        </div>
                        <div class="step-indicator flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center mb-2 bg-gray-200">
                                <span>2</span>
                            </div>
                            <span class="text-xs">Sécurité</span>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('auth.register.store') }}" id="registrationForm" class="space-y-6">
                    @csrf

                    <!-- Step 1: Personal Information -->
                    <div id="step1" class="step-content">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Informations personnelles</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                                <input type="text" name="prenom" value="{{ old('prenom') }}" required 
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                                <input type="text" name="nom" value="{{ old('nom') }}" required 
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email professionnel</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fonction</label>
                            <select name="fonction" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none">
                                <option value="">Sélectionnez votre fonction</option>
                                <option value="administrateur">Administrateur</option>
                                <option value="daf">DAF</option>
                                <option value="controleur">Contrôleur</option>
                                <option value="dg">DG</option>
                                <option value="caissier">Caissier(e)</option>
                                <option value="tresorerie">Trésorerie</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                            <input type="text" name="poste" value="{{ old('poste') }}" 
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none">
                        </div>
                    </div>

                    <!-- Step 2: Security -->
                    <div id="step2" class="step-content hidden">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Sécurité du compte et rôle</h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                            <select name="role_id" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->libelle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="relative mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                            <input type="password" name="password" id="password" required
                                   pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
                                   title="Le mot de passe doit contenir au moins 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial"
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none pr-10"
                                   placeholder="••••••••">
                            <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                        <div class="relative mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none pr-10"
                                   placeholder="••••••••">
                            <button type="button" id="toggleConfirmPassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between pt-6">
                        <button type="button" id="prevBtn" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors hidden">
                            <i class="fas fa-arrow-left mr-2"></i>Précédent
                        </button>
                        <button type="button" id="nextBtn" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors ml-auto">
                            Suivant<i class="fas fa-arrow-right ml-2"></i>
                        </button>
                        <button type="submit" id="submitBtn" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors ml-auto hidden">
                            Créer le compte<i class="fas fa-check ml-2"></i>
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
    const form = document.getElementById('registrationForm');
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.getElementById('submitBtn');

    // Gestion du bouton "Suivant"
    nextBtn.addEventListener('click', function() {
        step1.classList.add('hidden');
        step2.classList.remove('hidden');
        nextBtn.classList.add('hidden');
        prevBtn.classList.remove('hidden');
        submitBtn.classList.remove('hidden');
    });

    // Gestion du bouton "Précédent"
    prevBtn.addEventListener('click', function() {
        step2.classList.add('hidden');
        step1.classList.remove('hidden');
        nextBtn.classList.remove('hidden');
        prevBtn.classList.add('hidden');
        submitBtn.classList.add('hidden');
    });

    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });

    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmInput = document.getElementById('password_confirmation');
    toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmInput.type === 'password' ? 'text' : 'password';
        confirmInput.type = type;
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });

    // Form submit confirmation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Voulez-vous vraiment créer ce compte ?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4F46E5',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, créer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Success alert
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
