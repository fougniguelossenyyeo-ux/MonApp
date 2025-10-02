<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mot de passe oublié</title>
  <script src="https://cdn.tailwindcss.com"></script>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-violet-50 to-indigo-100 flex items-center justify-center p-4">

  <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-6">Réinitialiser le mot de passe</h2>

    @if (session('status'))
      <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
        {{ session('status') }}
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf
      <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
      <input type="email" name="email" id="email" required
             class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
             placeholder="votre.email@entreprise.com">

      <button type="submit"
              class="w-full bg-gradient-to-r from-blue-500 to-violet-600 text-white py-3 rounded-lg font-medium hover:from-blue-600 hover:to-violet-700">
        Envoyer le lien de réinitialisation
      </button>
    </form>

    <div class="text-center mt-4">
      <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 text-sm">Retour à la connexion</a>
    </div>
  </div>

</body>
</html>
