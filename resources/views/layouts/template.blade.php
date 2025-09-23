<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DPaie - Tableau de bord</title>
       <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    
       <link rel="stylesheet" href="style1.css">
       @vite(['resources/css/app.css', 'resources/js/app.js'])
  

    
</head>
<body class="min-h-screen" style="background-color: #f0f4ff;">
    <!-- Bouton de bascule de la barre latérale pour mobile -->
    <button id="sidebarToggle" class="fixed top-4 left-4 z-30 md:hidden bg-white p-2 rounded-lg shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Superposition de la barre latérale -->
    <div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 bg-black bg-opacity-50 z-20 hidden md:hidden"></div>

    <!-- Barre latérale -->
     @include('layouts.sidebar')

    <!-- Contenu principal -->
    <div id="mainContent" class="md:ml-64 transition-all duration-300">
        <!-- En-tête -->
       @include('layouts.header')

        <!-- Contenu principal -->
        @yield('maincontent')
    </div>
 <script src="{{asset('assets/js/insccription.js')}}">
      
    </script>
@yield('scripts')
</body>
</html>