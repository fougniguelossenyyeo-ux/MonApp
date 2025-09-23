<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>
   <script src="https://cdn.tailwindcss.com"></script>

  <!-- Tailwind CSS via Vite -->
  @vite('resources/css/app.css')
 <!-- Charger le JS avec Vite -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
  
  @if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        icon: 'success',
        title: 'Succès',
        text: "{{ session('success') }}",
        timer: 3000,
        showConfirmButton: false
      });
    });
  @endif

  @if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: "{{ session('error') }}",
      });
    });
  @endif

  @if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        icon: 'error',
        title: 'Erreur de validation',
        html: `{!! implode('<br>', $errors->all()) !!}`,
      });
    });
  @endif
</script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-violet-50 to-indigo-100 flex items-center justify-center p-4">

  <div class="w-full max-w-md">

    <!-- En-tête avec logo -->
    <div class="text-center mb-8">
      <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-violet-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg">
        <i class="fas fa-credit-card text-white text-2xl"></i>
      </div>
      <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-violet-600 bg-clip-text text-transparent">
        DPaie
      </h1>
      <p class="text-gray-600 mt-2">Gestion des Demandes de Paiement</p>
    </div>

    <!-- Carte de connexion -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
      <div class="p-8">
        <div class="text-center mb-8">
          <h2 class="text-2xl font-bold text-gray-900">Connexion</h2>
          <p class="text-gray-600 mt-2">Connectez-vous à votre compte</p>
        </div>

        <!-- Formulaire de connexion -->
        <form id="loginForm" class="space-y-6" method="POST" action="{{ route('login') }}">
          @csrf

          <!-- Champ Email -->
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
                value=""
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                placeholder="votre.email@entreprise.com"
                required
              >
            </div>
            @error('email')
              <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Champ Mot de passe -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-lock text-gray-400"></i>
              </div>
              <input
                type="password"
                name="password"
                id="password"
                class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror"
                placeholder="••••••••"
                required
              >
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <button type="button" class="text-gray-400 hover:text-gray-600" id="togglePassword">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>
            @error('password')
              <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Lien mot de passe oublié -->
          <div class="flex items-center justify-end">
            <div class="text-sm">
              <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                Mot de passe oublié?
              </a>
            </div>
          </div>

          <!-- Bouton de soumission -->
          <div>
            <button
              type="submit"
              class="w-full bg-gradient-to-r from-blue-500 to-violet-600 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-md hover:shadow-lg"
            >
              Se connecter
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Pied de page -->
    <div class="mt-8 text-center">
      <p class="text-sm text-gray-600">
        &copy; 2025 Demande de Paiement KAMA SA. Tous droits réservés.
      </p>
    </div>
  </div>

  <!-- Script pour toggle mot de passe -->
  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', () => {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      togglePassword.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
  </script>

</body>
</html>


