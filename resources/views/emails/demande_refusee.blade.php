<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de Paiement Refusée - {{ $demande->entite->libelle_entite ?? '-' }} - {{ $demande->reference_dp }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto p-6">
        <!-- Email Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-red-600">{{ $demande->denomination }}</h1>
                    <p class="text-gray-600 mt-1">Référence: {{ $demande->reference_dp }}</p>
                    <p class="text-gray-600 mt-1">Entité: {{ $demande->entite->libelle_entite ?? '-' }}</p>
                    <p class="status-badge bg-red-100 text-red-800 mt-2">Refusée par le Contrôleur</p>
                </div>
            </div>
        </div>

        <!-- Email Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="mb-8">
                <p class="text-gray-700 mb-4">
                    Bonjour {{ $demande->user->prenom ?? 'Utilisateur' }},
                </p>
                <p class="text-gray-700 mb-4">
                    Votre demande de paiement a été <strong>refusée par le Contrôleur</strong>.
                </p>
            </div>

            <!-- Request Details -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    Détails de la demande
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Infos de base -->
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

                    <!-- Infos financières -->
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Montant demandé</p>
                            <p class="font-medium text-gray-900 text-xl">{{ number_format($demande->montant_paiement_fournisseur,0,',',' ') }} F CFA</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date de paiement souhaitée</p>
                            <p class="font-medium text-gray-900">{{ $demande->date_paiement?->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Priorité</p>
                            <p class="font-medium text-gray-900">{{ ucfirst($demande->priorite) ?? 'Normale' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Objet de la dépense -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    Objet de la dépense
                </h2>
                <p class="text-gray-700">{{ $demande->description ?? '-' }}</p>
            </div>

            <!-- Closing -->
            <div>
                <p class="text-gray-700 mb-4">
                    Veuillez contacter le Contrôleur pour plus d’informations.
                </p>
                <p class="text-gray-700 mb-4">
                    Cordialement,
                </p>
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
