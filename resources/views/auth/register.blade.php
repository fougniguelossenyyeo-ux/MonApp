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
                </div>
            </div>

            <!-- Right Panel - Form -->
            <div class="md:w-3/5 p-8">
                <!-- Step Indicator -->
                <div class="flex justify-center mb-8">
                    <div class="flex space-x-4">
                        <div id="stepIndicator1" class="step-indicator flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center mb-2 bg-indigo-500 text-white">
                                <span>1</span>
                            </div>
                            <span class="text-xs">Informations</span>
                        </div>
                        <div id="stepIndicator2" class="step-indicator flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center mb-2 bg-gray-200 text-gray-500">
                                <span>2</span>
                            </div>
                            <span class="text-xs">Sécurité</span>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('auth.register') }}" id="registrationForm">
                    @csrf

                    <!-- Step 1: Personal Information -->
                    <div id="step1" class="step-content">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label>Prénom</label>
                                <input type="text" name="prenom" value="{{ old('prenom') }}" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label>Nom</label>
                                <input type="text" name="nom" value="{{ old('nom') }}" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg">
                        </div>

                        <div class="mt-4">
                            <label>Fonction</label>
                            <select name="fonction" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg">
                                <option value="">Sélectionnez votre fonction</option>
                                <option value="administrateur" {{ old('fonction')=='administrateur' ? 'selected' : '' }}>Administrateur</option>
                                <option value="daf" {{ old('fonction')=='daf' ? 'selected' : '' }}>DAF</option>
                                <option value="controleur" {{ old('fonction')=='controleur' ? 'selected' : '' }}>Contrôleur</option>
                                <option value="dg" {{ old('fonction')=='dg' ? 'selected' : '' }}>DG</option>
                                <option value="caissier" {{ old('fonction')=='caissier' ? 'selected' : '' }}>Caissier(e)</option>
                                <option value="tresorerie" {{ old('fonction')=='tresorerie' ? 'selected' : '' }}>Trésorerie</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label>Service</label>
                            <input type="text" name="poste" value="{{ old('poste') }}" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg">
                        </div>
                    </div>

                    <!-- Step 2: Security -->
                    <div id="step2" class="step-content hidden mt-4">
                        <div>
                            <label>Rôle</label>
                            <select name="role_id" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg">
                                <option value="">Sélectionnez un rôle</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id')==$role->id ? 'selected' : '' }}>
                                        {{ $role->libelle }} @if($role->entite) ({{ $role->entite->libelle_entite }}) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4 relative">
                            <label>Mot de passe</label>
                            <input type="password" name="password" id="password" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg pr-10" placeholder="••••••••">
                            <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                        <div class="mt-4 relative">
                            <label>Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg pr-10" placeholder="••••••••">
                            <button type="button" id="toggleConfirmPassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between pt-6">
                        <button type="button" id="prevBtn" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hidden">
                            Précédent
                        </button>
                        <button type="button" id="nextBtn" class="px-6 py-3 bg-indigo-600 text-white rounded-lg ml-auto">
                            Suivant
                        </button>
                        <button type="submit" id="submitBtn" class="px-6 py-3 bg-green-600 text-white rounded-lg ml-auto hidden">
                            Créer le compte
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
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.getElementById('submitBtn');
    const stepIndicator1 = document.getElementById('stepIndicator1');
    const stepIndicator2 = document.getElementById('stepIndicator2');

    // Passer à l'étape 2
    nextBtn.addEventListener('click', () => {
        step1.classList.add('hidden');
        step2.classList.remove('hidden');
        nextBtn.classList.add('hidden');
        prevBtn.classList.remove('hidden');
        submitBtn.classList.remove('hidden');

        stepIndicator1.querySelector('div').classList.replace('bg-indigo-500', 'bg-gray-300');
        stepIndicator1.querySelector('div').classList.replace('text-white', 'text-gray-500');
        stepIndicator2.querySelector('div').classList.replace('bg-gray-200', 'bg-indigo-500');
        stepIndicator2.querySelector('div').classList.replace('text-gray-500', 'text-white');
    });

    // Revenir à l'étape 1
    prevBtn.addEventListener('click', () => {
        step2.classList.add('hidden');
        step1.classList.remove('hidden');
        nextBtn.classList.remove('hidden');
        prevBtn.classList.add('hidden');
        submitBtn.classList.add('hidden');

        stepIndicator1.querySelector('div').classList.replace('bg-gray-300', 'bg-indigo-500');
        stepIndicator1.querySelector('div').classList.replace('text-gray-500', 'text-white');
        stepIndicator2.querySelector('div').classList.replace('bg-indigo-500', 'bg-gray-200');
        stepIndicator2.querySelector('div').classList.replace('text-white', 'text-gray-500');
    });

    // Toggle mot de passe
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    togglePassword.addEventListener('click', () => {
        passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
        togglePassword.innerHTML = passwordInput.type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });

    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmInput = document.getElementById('password_confirmation');
    toggleConfirmPassword.addEventListener('click', () => {
        confirmInput.type = confirmInput.type === 'password' ? 'text' : 'password';
        toggleConfirmPassword.innerHTML = confirmInput.type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
});
</script>
@endsection
