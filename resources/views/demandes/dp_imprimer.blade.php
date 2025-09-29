<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de Paiement</title>
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 11px;
            line-height: 1.2;
            background-color: white;
        }
        
        @page {
            size: A4 landscape;
            margin: 5mm;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
                background-color: white;
            }
            
            .form-container {
                width: 297mm;
                height: 210mm;
                margin: 0;
                padding: 8mm;
                page-break-after: always;
            }
        }
        
        .form-container {
            width: 297mm;
            height: 210mm;
            margin: 0 auto;
            padding: 8mm;
            box-sizing: border-box;
            background-color: white;
            position: relative;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            font-weight: bold;
            color: rgba(0, 0, 0, 0.05);
            pointer-events: none;
            z-index: -1;
            white-space: nowrap;
        }
        
        .header {
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            padding-bottom: 3mm;
            margin-bottom: 4mm;
            gap: 10mm;
        }
        
        .logo-placeholder {
            width: 50mm;
            height: 15mm;
            border: 1px dashed #999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #666;
        }
        
        .form-number {
            text-align: right;
            font-weight: bold;
            font-size: 12px;
        }
        
        .section {
            margin-bottom: 4mm;
            border: 1px solid #000;
            padding: 3mm;
        }
        
        .section-title {
            font-weight: bold;
            text-align: center;
            background-color: #e0e0e0;
            padding: 2mm;
            margin: -3mm -3mm 3mm -3mm;
            border-bottom: 1px solid #000;
            font-size: 12px;
        }
        
        .row {
            display: flex;
            margin-bottom: 3mm;
            align-items: center;
            flex-wrap: wrap;
            gap: 3mm;
        }
        
        .row-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(40mm, 1fr));
            gap: 4mm;
            margin-bottom: 3mm;
            align-items: center;
        }
        
        .field-group {
            display: flex;
            align-items: center;
            gap: 2mm;
            min-width: 0;
        }
        
        .field-label {
            font-weight: bold;
            white-space: nowrap;
            font-size: 10px;
            flex-shrink: 0;
        }
        
        .field-input {
            border: none;
            border-bottom: 1px solid #000;
            padding: 1mm;
            flex: 1;
            min-width: 0;
            font-size: 10px;
            background: transparent;
        }
        
        .short-input {
            width: 20mm;
            flex: none;
        }
        
        .medium-input {
            width: 35mm;
            flex: none;
        }
        
        .long-input {
            min-width: 50mm;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 3mm 0;
            table-layout: fixed;
            position: relative;
        }
        
        .table th, .table td {
            border: 1px solid #000;
            padding: 2mm;
            text-align: center;
            font-size: 9px;
            word-wrap: break-word;
            overflow: hidden;
        }
        
        .table th {
            background-color: white;
            font-weight: bold;
        }
        
        .comptabilisation-table {
            font-size: 8px;
        }
        
        .comptabilisation-table th, .comptabilisation-table td {
            padding: 1.5mm;
        }
        
        .signatures {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 3mm;
            margin-top: 5mm;
        }
        
        .signature-box {
            border: 1px solid #000;
            height: 25mm;
            padding: 2mm;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .signature-title {
            font-weight: bold;
            font-size: 9px;
        }
        
        .date-field {
            font-size: 8px;
        }
        
        .footer-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5mm;
            margin-top: 5mm;
        }
        
        .footer-box {
            border: 1px solid #000;
            padding: 3mm;
            min-height: 20mm;
        }
        
        .bottom-info {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 5mm;
            margin-top: 4mm;
            font-size: 9px;
            text-align: center;
        }
        
        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4mm;
        }
        
        .table-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 30px;
            font-weight: bold;
            color: rgba(0, 0, 0, 0.05);
            pointer-events: none;
            z-index: -1;
            white-space: nowrap;
            text-align: center;
        }
        
        /* Pour l'affichage à l'écran */
        @media screen {
            body {
                background-color: white;
                padding: 0;
            }
            
            .form-container {
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="watermark">KAMA CI</div>
        
        <!-- En-tête -->
        <div class="header">
            <div class="logo-placeholder">
                LOGO DE L'ENTREPRISE
            </div>
            <div class="form-number">
                KAMA- CI<br>
                N° - 3784-25
            </div>
        </div>
        
        <!-- Titre principal -->
        <div class="section-title">DEMANDE DE PAIEMENT</div>
        
        <!-- Section informations de base -->
        <div class="section">
            <div class="row">
                <div class="field-group">
                    <span class="field-label">à l'ordre de:</span>
                    <input type="text" class="field-input long-input" value="Achat d'ordinateur lenovo">
                </div>
            </div>
            
            <div class="row-grid">
                <div class="field-group">
                    <span class="field-label">Nom:</span>
                    <input type="text" class="field-input" value="GAZ">
                </div>
                <div class="field-group">
                    <span class="field-label">DATES n° projet:</span>
                    <input type="text" class="field-input" value="PROJ-2025-001">
                </div>
                <div class="field-group">
                    <span class="field-label">centre analytique:</span>
                    <input type="text" class="field-input" value="Département Finance">
                </div>
                <div class="field-group">
                    <span class="field-label">n° émission:</span>
                    <input type="text" class="field-input" value="02/09/2025">
                </div>
            </div>
            
            <div class="row">
                <div class="field-group">
                    <span class="field-label">Adresse:</span>
                    <input type="text" class="field-input long-input" value="Abidjan, Cocody, Rue des Jardins">
                </div>
            </div>
            
            <div class="row-grid">
                <div class="field-group">
                    <span class="field-label">code fournisseur:</span>
                    <input type="text" class="field-input" value="FOU-2025-001">
                </div>
                <div class="field-group">
                    <span class="field-label">N° système:</span>
                    <input type="text" class="field-input" value="SYS-2025-001">
                </div>
                <div class="field-group">
                    <span class="field-label">centre analytique:</span>
                    <input type="text" class="field-input" value="CA-2025-001">
                </div>
                <div class="field-group">
                    <span class="field-label">validation échéance:</span>
                    <input type="text" class="field-input" value="02/09/2025">
                </div>
            </div>
            
            <div class="row-grid">
                <div class="field-group">
                    <span class="field-label">TEL:</span>
                    <input type="text" class="field-input" value="+225 05 96 15 89 72">
                </div>
                <div class="field-group">
                    <span class="field-label">ch./vir. banq.:</span>
                    <input type="text" class="field-input" value="Virement bancaire">
                </div>
                <div class="field-group">
                    <span class="field-label">cmpt. crédit:</span>
                    <input type="text" class="field-input" value="COMPTE-001">
                </div>
            </div>
        </div>
        
        <!-- Section COMPTABILISATION -->
        <div class="section">
            <div class="section-title">COMPTABILISATION</div>
            <table class="table comptabilisation-table">
                <tr>
                    <th colspan="2">numéro</th>
                    <th colspan="2">date (-445) (-5 2 _ _ _ )</th>
                    <th colspan="2">Débit</th>
                    <th colspan="2">Crédit</th>
                    <th>centre analytique</th>
                </tr>
                <tr>
                    <th>Débit</th>
                    <th>Crédit</th>
                    <th>Débit</th>
                    <th>Crédit</th>
                    <th>Débit</th>
                    <th>Crédit</th>
                    <th>Débit</th>
                    <th>Crédit</th>
                    <th></th>
                </tr>
                <tr>
                    <td>DEB-001</td>
                    <td>CRED-001</td>
                    <td>02/09/2025</td>
                    <td>02/09/2025</td>
                    <td>840 336</td>
                    <td>0</td>
                    <td>0</td>
                    <td>1 000 000</td>
                    <td>CA-2025-001</td>
                </tr>
            </table>
        </div>
        
        <!-- Section PAIEMENT et CONTRAT -->
        <div class="two-column">
            <div class="section">
                <div class="section-title">PAIEMENT</div>
                <div class="row">
                    <div class="field-group">
                        <span class="field-label">val. com.:</span>
                        <input type="text" class="field-input" value="Validé">
                    </div>
                </div>
                <div class="row">
                    <div class="field-group">
                        <span class="field-label">paiements cumulés:</span>
                        <input type="text" class="field-input short-input" value="0">
                        <input type="text" class="field-input short-input" value="0">
                        <input type="text" class="field-input short-input" value="0">
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">CONTRAT</div>
                <div class="row">
                    <div class="field-group">
                        <span class="field-label">mont. échu:</span>
                        <input type="text" class="field-input short-input" value="1 000 000">
                        <span class="field-label">solde:</span>
                        <input type="text" class="field-input short-input" value="0">
                        <input type="text" class="field-input short-input" value="0">
                    </div>
                </div>
                <div class="row">
                    <div class="field-group">
                        <span class="field-label">montant total:</span>
                        <input type="text" class="field-input" value="1 000 000">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Section détails facture -->
        <div class="section">
            <table class="table">
                <div class="table-watermark">KAMA CI</div>
                <tr>
                    <th style="width: 25%;">Facture du fournisseur</th>
                    <th style="width: 35%;">Description</th>
                    <th style="width: 15%;">Montant TTC</th>
                    <th style="width: 10%;">TVA</th>
                    <th style="width: 15%;">Montant H.T.</th>
                </tr>
                <tr style="height: 25mm;">
                    <td style="vertical-align: top; padding: 3mm;">FAC-2025-001</td>
                    <td style="vertical-align: top; padding: 3mm;">Achat d'ordinateur lenovo pour le service informatique</td>
                    <td style="vertical-align: top; padding: 3mm;">1 000 000</td>
                    <td style="vertical-align: top; padding: 3mm;">19%</td>
                    <td style="vertical-align: top; padding: 3mm;">840 336</td>
                </tr>
            </table>
        </div>
        
        <!-- Section approbations -->
        <div class="section">
            <div style="text-align: center; font-weight: bold; margin-bottom: 4mm; font-size: 11px;">
                APPROBATIONS (selon les normes en vigueur)
            </div>
            
            <div class="signatures">
                <div class="signature-box">
                    <div class="signature-title">CONTROLEUR DE GESTION</div>
                    <div class="date-field">date: 02/09/2025</div>
                </div>
                
                <div class="signature-box">
                    <div class="signature-title">LE DIRECTEUR GENERAL</div>
                    <div class="date-field">date: 02/09/2025</div>
                </div>
                
                <div class="signature-box">
                    <div class="signature-title">Directeur Administratif & Financier</div>
                    <div class="date-field">date: 02/09/2025</div>
                </div>
            </div>
        </div>
        
        <!-- Section finale -->
        <div class="footer-section">
            <div class="footer-box">
                <div class="signature-title">Bon(s) de réception ou P V de réception</div>
                <div style="text-align: right; margin-top: 10mm;">1+</div>
            </div>
            
            <div class="footer-box">
                <div class="signature-title">Date Saisie Comptable</div>
                <div class="signature-title" style="margin-top: 5mm;">Directeur Administratif & Financier</div>
            </div>
        </div>
        
        <!-- Informations en bas -->
        <div class="bottom-info">
            <span>remise du chèque</span>
            <span>caissier</span>
            <span>fournisseur</span>
            <span>N° d'identité</span>
        </div>
    </div>
</body>
</html>