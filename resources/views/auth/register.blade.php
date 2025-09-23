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

                <!-- Form -->
                <form method="POST" action="{{ route('auth.register.store') }}" id="registrationForm" class="space-y-6">
                    @csrf

                    <!-- Step 1: Personal Information -->
                    <div id="step1" class="step-content">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Informations personnelles</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                                <input type="text" name="prenom" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                                <input type="text" name="nom" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email professionnel</label>
                            <input type="email" name="email" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none">
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
                            <input type="text" name="poste" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none">
                        </div>
                    </div>

                    <!-- Step 2: Security -->
                    <div id="step2" class="step-content hidden">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Sécurité du compte</h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none pr-12">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center password-toggle" onclick="togglePassword('password')">
                                    <i class="fas fa-eye text-gray-400"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div id="passwordStrength" class="bg-gray-400 h-2 rounded-full" style="width: 0%"></div>
                                </div>
                                <p id="passwordMessage" class="text-xs text-gray-500 mt-1">Votre mot de passe doit contenir au moins 8 caractères</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="confirmPassword" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none pr-12">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center password-toggle" onclick="togglePassword('confirmPassword')">
                                    <i class="fas fa-eye text-gray-400"></i>
                                </div>
                            </div>
                            <p id="passwordMatch" class="text-xs text-gray-500 mt-1 hidden">Les mots de passe correspondent</p>
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
                            Créer mon compte<i class="fas fa-check ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

@vite(['resources/js/inscription.js'])

@endsection
