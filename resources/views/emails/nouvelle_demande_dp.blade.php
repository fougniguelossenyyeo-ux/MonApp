<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de Paiement-{{ $demande->entite->libelle_entite ?? '-' }} - {{ $demande->reference_dp }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .status-badge {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            display: inline-block;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto p-6">
        <!-- Email Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $demande->denomination }}</h1>
                    <p class="text-gray-600 mt-1">Référence: {{ $demande->reference_dp }}</p>
                     <p class="text-gray-600 mt-1">Entité: {{ $demande->entite->libelle_entite ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Email Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <!-- Introduction -->
            <div class="mb-8">
   

@php
$controleur = $validateur;
@endphp


                <p class="text-gray-700 mb-4">
                  Bonjour {{ $validateur->prenom }} {{ $validateur->nom }},
                </p>
                <p class="text-gray-700 mb-4">
                    Veuillez trouver ci-dessous les détails de la demande de paiement soumise pour validation.
                </p>
            </div>

            <!-- Request Details -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    Informations de la demande
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Info -->
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Demandeur</p>
                            <p class="font-medium text-gray-900">{{ $demande->user->prenom }} {{ $demande->user->nom }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date de la demande</p>
                            <p class="font-medium text-gray-900">{{ $demande->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Contact Téléphonique</p>
                            <p class="font-medium text-gray-900">{{ $demande->contact_fournisseur }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium text-gray-900">{{ $demande->email_fournisseur }}</p>
                        </div>
                    </div>
                    
                    <!-- Financial Info -->
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Montant demandé</p>
                            <p class="font-medium text-gray-900 text-xl">{{ number_format($demande->montant_paiement_fournisseur,0,',',' ') }} F CFA</p>
                        </div>
                    
                       
                    </div>
                </div>
            </div>

            <!-- Objet de la dépense -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    Descritption
                </h2>
                <p class="text-gray-700">
                    {{ $demande->description ?? '-' }}
                </p>
            </div>

           

            <!-- Closing -->
          <!-- Closing -->
<div class="mb-6 text-center">
    <!-- Lien vers la consultation de la demande entière -->
<a href="{{ route('demandes.showEnAttenteControl', $demande->id, true) }}"  
      style="display:inline-block;padding:12px 24px;background:#2563eb;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:600;">
       Consulter et valider/refuser la demande
    </a>
</div>
        </div>

        <!-- Email Footer -->
        <div class="mt-6 text-center text-sm text-gray-500">
            <p>Ce message a été généré automatiquement. Merci de ne pas y répondre.</p>
            <p class="mt-1">© 2025 DPaie - Système de Gestion des Demandes de Paiement</p>
        </div>
    </div>
</body>
</html>
