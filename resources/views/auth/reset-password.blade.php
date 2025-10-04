{{-- resources/views/auth/reset-password.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-violet-50 to-indigo-100 flex items-center justify-center p-4">

<div class="w-full max-w-md">

    <!-- En-tête avec logo -->
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-violet-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg">
            <i class="fas fa-key text-white text-2xl"></i>
        </div>
        <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-violet-600 bg-clip-text text-transparent">
            DPaie
        </h1>
        <p class="text-gray-600 mt-2">Gestion des Demandes de Paiement</p>
    </div>

    <!-- Carte -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="p-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Réinitialiser le mot de passe</h2>
                <p class="text-gray-600 mt-2">Entrez votre nouveau mot de passe</p>
            </div>

            <!-- Formulaire Laravel -->
            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                <!-- jeton reçu par mail -->
                <input type="hidden" name="token" value="{{ $token }}">
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email', request('email')) }}"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                            placeholder="votre.email@entreprise.com"
                            required
                        >
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nouveau mot de passe -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror"
                            placeholder="••••••••"
                            required
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" class="text-gray-400 hover:text-gray-600" id="togglePassword">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Le mot de passe doit contenir au moins 8 caractères.</p>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="••••••••"
                            required
                        >
                    </div>
                </div>

                <!-- Bouton -->
                <div>
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-blue-500 to-violet-600 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-md hover:shadow-lg"
                    >
                        Réinitialiser le mot de passe
                    </button>
                </div>
            </form>
        </div>

        <!-- Pied -->
        <div class="bg-gray-50 px-8 py-6 border-t border-gray-100 text-center">
            <p class="text-sm text-gray-600">
                Vous vous souvenez de votre mot de passe ? 
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    Retour à la connexion
                </a>
            </p>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center">
        <p class="text-sm text-gray-600">
            &copy; 2025 Demande de Paiement KAMA SA. Tous droits réservés.
        </p>
    </div>
</div>

<script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function(e) {
        e.preventDefault();
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle eye icon
        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });
</script>

</body>
</html>
