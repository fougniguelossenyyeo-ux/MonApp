<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement à valider - {{ $paiement->demande->reference_dp }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .status-badge {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            display: inline-block;
        }
        a.btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: #4f46e5;
            color: #fff;
            border-radius: 0.375rem;
            text-decoration: none;
            font-weight: 500;
        }
        a.btn:hover {
            background-color: #4338ca;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto p-6">

        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ $paiement->demande->denomination }}</h1>
            <p class="text-gray-600 mt-1">Référence : {{ $paiement->demande->reference_dp }}</p>
            <p class="text-gray-600 mt-1">Entité : {{ $paiement->demande->entite->libelle_entite ?? '-' }}</p>
        </div>

        <!-- Contenu -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <p class="text-gray-700 mb-4">
                Bonjour DG,
            </p>
            <p class="text-gray-700 mb-4">
                Un paiement a été déclenché pour la demande ci-dessous. Merci de valider ou refuser le paiement.
            </p>

            <!-- Détails de la demande -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    Informations de la demande et paiement
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="space-y-2">
                        <p><strong>Demandeur :</strong> {{ $paiement->demande->user->prenom ?? '-' }} {{ $paiement->demande->user->nom ?? '-' }}</p>
                        <p><strong>Date de la demande :</strong> {{ $paiement->demande->date_validation_dg->format('d/m/Y') }}</p>
                        <p><strong>Contact Fournisseur :</strong> {{ $paiement->demande->contact_fournisseur }}</p>
                        <p><strong>Email Fournisseur :</strong> {{ $paiement->demande->email_fournisseur }}</p>
                    </div>

                    <div class="space-y-2">
                        <p><strong>Montant total :</strong> {{ number_format($paiement->montant_a_payer,0,',',' ') }} F CFA</p>
                        <p><strong>Montant déjà payé :</strong> {{ number_format($paiement->montant_deja_paye,0,',',' ') }} F CFA</p>
                        <p><strong>Montant restant :</strong> {{ number_format($paiement->montant_restant,0,',',' ') }} F CFA</p>
                   <p><strong>Montant de paiement souhaité :</strong> {{ number_format($versement->montant, 0, ',', ' ') }} F CFA</p>

                    </div>

                </div>
            </div>

            <!-- Lien de validation -->
            <div class="mb-6 text-center">
                <!-- Nouveau lien -->
                 <a href="{{ route('paiements.dg_valider', $paiement->id) }}" class="btn">
                   Valider ou Refuser le paiement
                     </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center text-sm text-gray-500">
            <p>Ce message a été généré automatiquement. Merci de ne pas y répondre.</p>
            <p class="mt-1">© 2025 DPaie - Système de Gestion des Demandes de Paiement</p>
        </div>

    </div>
</body>
</html>
