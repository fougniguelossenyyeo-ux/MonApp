<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Versement refusé - {{ $paiement->demande->reference_dp }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.6rem;
            border-radius: 0.375rem;
            display: inline-block;
        }

        a.btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #dc2626;
            color: #fff;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
        }

        a.btn:hover {
            background-color: #b91c1c;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
<div class="max-w-4xl mx-auto p-6">

    <!-- HEADER -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">
            Versement refusé par le DG
        </h1>

        <p class="text-gray-600 mt-1">
            Référence : {{ $paiement->demande->reference_dp }}
        </p>

        <p class="text-gray-600 mt-1">
            Entité : {{ $paiement->demande->entite->libelle_entite ?? '-' }}
        </p>
    </div>

    <!-- CONTENU -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">

        <p class="text-gray-700 mb-4">
            Bonjour,
        </p>

        <p class="text-gray-700 mb-6">
            Le Directeur Général a <strong class="text-red-600">refusé</strong> un versement lié à cette demande de paiement.
        </p>

        <!-- DETAILS -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                Détails du versement refusé
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Demande -->
                <div class="space-y-2">
                    <p><strong>Demandeur :</strong>
                        {{ $paiement->demande->user->prenom ?? '-' }}
                        {{ $paiement->demande->user->nom ?? '-' }}
                    </p>

                    <p><strong>Fournisseur :</strong>
                        {{ $paiement->demande->nom_fournisseur ?? '-' }}
                    </p>

                    <p><strong>Contact :</strong>
                        {{ $paiement->demande->contact_fournisseur ?? '-' }}
                    </p>

                    <p><strong>Email :</strong>
                        {{ $paiement->demande->email_fournisseur ?? '-' }}
                    </p>
                </div>

                <!-- Paiement -->
                <div class="space-y-2">

                    <p><strong>Montant total :</strong>
                        {{ number_format($paiement->montant_a_payer, 0, ',', ' ') }} F CFA
                    </p>

                    <p><strong>Déjà payé :</strong>
                        {{ number_format($paiement->montant_deja_paye, 0, ',', ' ') }} F CFA
                    </p>

                    <p><strong>Restant :</strong>
                        {{ number_format($paiement->montant_restant, 0, ',', ' ') }} F CFA
                    </p>

                    <p><strong>Montant refusé :</strong>
                        {{ number_format($versement->montant, 0, ',', ' ') }} F CFA
                    </p>

                    <p><strong>Date :</strong>
                        {{ now()->format('d/m/Y H:i') }}
                    </p>

                    <p><strong>Motif du refus :</strong>
                        {{ $versement->commentaire ?? '-' }}
                    </p>

                </div>
            </div>
        </div>

        <!-- STATUT -->
        <div class="mb-6">
            <span class="status-badge bg-red-100 text-red-800">
                REFUSÉ PAR LE DG
            </span>
        </div>

        <!-- ACTION BUTTON -->
        <div class="text-center">
            <a href="{{ route('paiements.show', $paiement->id) }}" class="btn">
                Voir le paiement
            </a>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="text-center text-sm text-gray-500 mt-6">
        <p>Notification automatique du système de gestion des paiements.</p>
        <p class="mt-1">© 2026 - DPaie</p>
    </div>

</div>
</body>
</html>