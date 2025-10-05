<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Validé</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333333;
            line-height: 1.6;
            padding: 40px 20px;
            min-height: 100vh;
        }
        .container {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 40px;
            max-width: 650px;
            margin: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #22c55e;
        }
        .icon-success {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(34, 197, 94, 0.4);
        }
        .icon-success svg {
            width: 50px;
            height: 50px;
            stroke: white;
            stroke-width: 3;
            fill: none;
        }
        h2 {
            color: #22c55e;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .greeting {
            font-size: 18px;
            color: #4b5563;
            margin-bottom: 20px;
        }
        .highlight {
            font-weight: 700;
            color: #4f46e5;
        }
        .info-section {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 2px solid #d1d5db;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
        }
        .amount-paid {
            color: #22c55e;
        }
        .amount-remaining {
            color: #ef4444;
        }
        .reference {
            color: #4f46e5;
        }
        .message {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            font-size: 16px;
            color: #6b7280;
            font-style: italic;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            font-size: 12px;
            color: #9ca3af;
        }
        @media (max-width: 600px) {
            .container {
                padding: 25px;
            }
            h2 {
                font-size: 24px;
            }
            .info-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon-success">
                <svg viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2>Paiement Validé</h2>
        </div>

        <p class="greeting">
            Bonjour <span class="highlight">{{ $paiement->demande->user->name }}</span>,
        </p>

        <p style="margin-bottom: 20px; font-size: 16px; color: #4b5563;">
            Nous avons le plaisir de vous informer que votre demande de paiement a été validée avec succès par la Direction Générale.
        </p>

        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Référence</span>
                <span class="info-value reference">{{ $paiement->demande->reference_dp }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Montant payé</span>
                <span class="info-value amount-paid">{{ number_format($paiement->montant_deja_paye, 0, ',', ' ') }} F CFA</span>
            </div>
            <div class="info-row">
                <span class="info-label">Montant restant</span>
                <span class="info-value amount-remaining">{{ number_format($paiement->montant_restant, 0, ',', ' ') }} F CFA</span>
            </div>
        </div>

        <p class="message">
            Merci pour votre collaboration et votre confiance. 🙏
        </p>

        <div class="footer">
            Ce message est généré automatiquement, merci de ne pas y répondre.
        </div>
    </div>
</body>
</html>