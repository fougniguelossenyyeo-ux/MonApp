<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DPaie - Accueil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <span class="ml-3 text-xl font-bold text-gray-900">DPaie</span>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="{{route('login')}}" class="ml-8 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Connexion
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">
                Gestion simplifiée des 
                <span class="text-indigo-600">demandes de paiement</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-10">
                DPaie est la solution digitale pour gérer efficacement vos demandes de paiement, 
                automatiser les processus et améliorer la transparence financière.
            </p>
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{route('login')}}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm">
                    Commencer maintenant
                </a>
               
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Pourquoi choisir DPaie ?</h2>
                <p class="mt-4 text-lg text-gray-600">
                    Une solution complète pour transformer votre gestion financière
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100">
                        <i class="fas fa-bolt text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Rapidité</h3>
                    <p class="mt-2 text-gray-600">
                        Traitez vos demandes de paiement 5 fois plus rapidement qu'avec les méthodes traditionnelles.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                        <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Sécurité</h3>
                    <p class="mt-2 text-gray-600">
                        Protocoles de sécurité avancés pour garantir la confidentialité de vos données financières.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100">
                        <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Suivi en temps réel</h3>
                    <p class="mt-2 text-gray-600">
                        Visualisez l'état de vos demandes à tout moment grâce à un tableau de bord intuitif.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
  

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <span class="ml-2 text-lg font-bold">DPaie</span>
                </div>
                <p class="text-gray-400 text-sm">© 2024 DPaie. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>

</html>