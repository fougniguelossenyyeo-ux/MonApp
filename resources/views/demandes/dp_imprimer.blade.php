<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Demande de Paiement</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: Arial, sans-serif; font-size: 8px; line-height: 1.1; background-color: white; margin:0; padding:0; }

  @page {
      size: A4 landscape;
      margin: 0; /* supprime la marge automatique du navigateur */
  }
     .form-container {
      width: 297mm;
      min-height: 210mm; /* remplace height par min-height */
      margin: 0 auto;
      padding: 4mm;
      background-color: white;
      position: relative;
  }

  @media print {
      .print-btn {
          display: none; /* cache le bouton à l’impression */
      }
  }

    /* Bouton imprimer */
    .print-btn {
      position: absolute;
      top: 4mm;
      right: 4mm;
      padding: 5px 10px;
      font-size: 10px;
      background-color: #444;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .print-btn:hover { background-color: #222; }

    .header { display: grid; grid-template-columns: 1fr auto; align-items: center; padding-bottom: 1mm; margin-bottom: 2mm; gap: 4mm; }
    .logo-placeholder { width: 40mm; height: 10mm; border: 1px dashed #999; display: flex; align-items: center; justify-content: center; font-size: 7px; color: #666; }
    .form-number { text-align: right; font-weight: bold; font-size: 9px; line-height: 1.3; }
    .section { margin-bottom: 2mm; border: 1px solid #000; padding: 1.5mm; position: relative; }
    .section-title { 
      font-weight: bold; 
      text-align: center; 
      background-color: #e0e0e0; 
      padding: 1mm; 
      margin: -1.5mm -1.5mm 1.5mm -1.5mm; 
      border-bottom: 1px solid #000; 
      font-size: 9px; 
      -webkit-print-color-adjust: exact; 
      print-color-adjust: exact;
    }
    .row { display: flex; margin-bottom: 1.5mm; align-items: center; gap: 2mm; }
    .row-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 2mm; margin-bottom: 1.5mm; }
    .field-group { display: flex; align-items: center; gap: 1mm; min-width: 0; }
    .field-label { font-weight: bold; white-space: nowrap; font-size: 7.5px; flex-shrink: 0; }
    .field-input {
  border: none;           /* supprime toutes les bordures */
  padding: 0.5mm;
  flex: 1;
  min-width: 0;
  font-size: 7.5px;
  background: transparent;
}
.short-input {
  width: 15mm;
  flex: none;
}
.field-input {
    font-size: 9px; /* augmente la taille */
    font-weight: bold; /* met en gras */
}


    .table { width: 100%; border-collapse: collapse; margin: 1mm 0; }
    .table th, .table td { border: 1px solid #000; padding: 1mm; text-align: center; font-size: 7px; }
    .table th { background-color: white; font-weight: bold; }
    .comptabilisation-table { font-size: 6.5px; }
    .comptabilisation-table th, .comptabilisation-table td { padding: 0.8mm; }
    .signatures { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2mm; margin-top: 1.5mm; }
    .signature-box { border: 1px solid #000; height: 13mm; padding: 1mm; text-align: center; display: flex; flex-direction: column; justify-content: space-between; }
    .signature-title { font-weight: bold; font-size: 7px; }
    .date-field { font-size: 6.5px; margin-top: auto; }
    .footer-section { display: grid; grid-template-columns: 1fr 1fr; gap: 2mm; margin-top: 2mm; }
    .footer-box { border: 1px solid #000; padding: 1.5mm; min-height: 10mm; }
    .bottom-info { display: grid; grid-template-columns: repeat(4, 1fr); gap: 3mm; margin-top: 2mm; font-size: 7px; text-align: center; }
    .two-column { display: grid; grid-template-columns: 1fr 1fr; gap: 2mm; }
    .watermark-between { position: absolute; top: 105mm; left: 50%; transform: translateX(-50%); font-size: 60px; font-weight: bold; color: rgba(0,0,0,0.04); pointer-events: none; z-index: 0; white-space: nowrap; }
    .compact-row { display: flex; gap: 1mm; align-items: center; margin-bottom: 1mm; }
  </style>
</head>
<body>
  <div class="form-container">

    <!-- Bouton imprimer -->
    <button class="print-btn" onclick="window.print()">🖨️ Imprimer</button>

    <!-- En-tête -->
    <div class="header">
      <div class="logo-placeholder">
        @if($demande->entite->logo)
            <img src="{{ asset('storage/'.$demande->entite->logo) }}" alt="Logo" style="max-width:100%; max-height:100%;">
        @else
            LOGO DE L'ENTREPRISE
        @endif
      </div>
      <div class="form-number">
        <input type="text" class="field-input" value="{{ $demande->entite->libelle_entite }}" readonly><br>
        <input type="text" class="field-input" value="N° - {{ $demande->reference_dp }}" readonly>
      </div>
    </div>

    <!-- Titre principal -->
    <div class="section-title">DEMANDE DE PAIEMENT</div>

    <!-- Section informations de base -->
    <div class="section">
      <div class="row">
        <div class="field-group" style="flex: 1;">
          <span class="field-label">à l'ordre de:</span>
         <input type="text" class="field-input" value="{{ $demande->nom_fournisseur }}" readonly>
        </div>
      </div>

      <div class="row-grid">
        <div class="field-group"><span class="field-label">Email fournisseur:</span><input type="text" class="field-input" value="{{ $demande->email_fournisseur}}" readonly></div>
        <div class="field-group"><span class="field-label">code Projet:</span><input type="text" class="field-input" value="{{ $demande->code_projet }}" readonly></div>
        <div class="field-group"><span class="field-label">Reference BC:</span><input type="text" class="field-input" value="{{ $demande->Reference_bon_commande}}" readonly></div>
        <div class="field-group"><span class="field-label">Date d'émission:</span><input type="text" class="field-input" value="{{ $demande->date_paiement?->format('d/m/Y') }}" readonly></div>
      </div>

      <div class="row">
        <div class="field-group" style="flex: 1;">
          <span class="field-label">Adresse:</span>
          <input type="text" class="field-input" value="{{ $demande->adresse_fournisseur }}" readonly>
        </div>
      </div>

      <div class="row-grid">
        <div class="field-group"><span class="field-label">code fournisseur:</span><input type="text" class="field-input" value="{{ $demande->code_fournisseur }}" readonly></div>
        <div class="field-group"><span class="field-label">Reference EB:</span><input type="text" class="field-input" value="{{ $demande->reference_expression_besoin }}" readonly></div>
        <div class="field-group"><span class="field-label">code analytique:</span><input type="text" class="field-input" value="{{ $demande->code_analytique }}" readonly></div>
        <div class="field-group"><span class="field-label">Délai souhaité:</span><input type="text" class="field-input" value="{{ $demande->date_paiement?->addDays(15)->format('d/m/Y') }}" readonly></div>
      </div>

      <div class="row-grid" style="grid-template-columns: repeat(3, 1fr);">
        <div class="field-group"><span class="field-label">TEL:</span><input type="text" class="field-input" value="{{ $demande->contact_fournisseur }}" readonly></div>
        <div class="field-group"><span class="field-label">ch./vir. banq.:</span><input type="text" class="field-input" value="" readonly></div>
        <div class="field-group"><span class="field-label">code contrat:</span><input type="text" class="field-input" value="{{ $demande->reference_contrat }}" readonly></div>
      </div>
    </div>

    <!-- Section COMPTABILISATION -->
    <div class="section">
      <div class="section-title">COMPTABILISATION</div>
      <table class="table comptabilisation-table">
        <tr>
          <th colspan="2">numéro</th>
          <th colspan="2">date</th>
          <th colspan="2">Débit</th>
          <th colspan="2">Crédit</th>
        </tr>
        <tr>
          <th>Débit</th><th>Crédit</th><th>Débit</th><th>Crédit</th>
          <th>Débit</th><th>Crédit</th><th>Débit</th><th>Crédit</th>
        </tr>
        <tr>
          <td>___</td><td>___</td><td>________</td><td>________</td>
          <td>___</td><td>_</td><td>_</td><td>_________</td>
        </tr>
      </table>
    </div>

    <!-- Section PAIEMENT et CONTRAT -->
    <div class="two-column">
      <div class="section">
        <div class="section-title">PAIEMENT</div>
        <div class="compact-row">
          <span class="field-label">val. com.:</span>
          <input type="text" class="field-input" value="_" readonly style="width: 25mm;">
        </div>
        <div class="compact-row">
          <span class="field-label">paiements cumulés:</span>
          <input type="text" class="field-input short-input" value="" readonly>
          <input type="text" class="field-input short-input" value="" readonly>
          <input type="text" class="field-input short-input" value="" readonly>
        </div>
      </div>
      <div class="section">
        <div class="section-title">CONTRAT</div>
        <div class="compact-row">
          <span class="field-label">mont. échu:</span>
          <input type="text" class="field-input short-input" value="" readonly>
          <span class="field-label">solde:</span>
          <input type="text" class="field-input short-input" value="" readonly>
        </div>
        <div class="compact-row">
          <span class="field-label">montant total:</span>
          <input type="text" class="field-input" value="_" readonly style="width: 30mm;">
        </div>
      </div>
    </div>

    <!-- FILIGRANE -->
    <div class="watermark-between">{{ $demande->entite->libelle_entite }}</div>

   <!-- Section détails facture -->
<div class="section">
  <table class="table">
    <tr>
      <th style="width: 30%;">Facture du fournisseur</th>
      <th style="width: 40%;">Description</th>
      <th style="width: 15%;">Montant TTC</th>
      <th style="width: 15%;">TVA</th>
    </tr>
    <tr style="height: 20mm;"> <!-- un peu plus de hauteur -->
      <td style="vertical-align: top; padding: 2mm;">{{ $demande->reference_facture }}</td>
      <td style="vertical-align: top; padding: 2mm; text-align: left;">{{ $demande->description }}</td>
      <td style="vertical-align: top; padding: 2mm;">{{ number_format($demande->montant_paiement_fournisseur,0,',',' ') }}</td>
      <td style="vertical-align: top; padding: 2mm;">{{ $demande->tva }}%</td>
    </tr>
  </table>
</div>

<!-- Section approbations -->
<div class="section">
  <div style="text-align: center; font-weight: bold; margin-bottom: 2mm; font-size: 8px;">
    APPROBATIONS
  </div>
  
  <div class="signatures" style="grid-template-columns: repeat(3, 1fr); gap: 4mm;">
    <div class="signature-box" style="height: 25mm;"> <!-- agrandi -->
      <div class="signature-title">CONTROLEUR DE GESTION</div>
      <div class="date-field">{{ $demande->date_validation_controleur?->format('d/m/Y') }}</div>
    </div>
    <div class="signature-box" style="height: 25mm;">
      <div class="signature-title">LE DIRECTEUR GENERAL</div>
      <div class="date-field">{{ $demande->date_validation_dg?->format('d/m/Y') }}</div>
    </div>
    <div class="signature-box" style="height: 25mm;">
      <div class="signature-title">Directeur Administratif & Financier</div>
      <div class="date-field">{{ $demande->date_validation_daf?->format('d/m/Y') }}</div>
    </div>
  </div>
</div>

    <!-- Section finale -->
    <div class="footer-section">
      <div class="footer-box">
        <div class="signature-title">Bon(s) de réception ou P V de réception</div>
      </div>
      <div class="footer-box">
        <div class="signature-title">Date Saisie Comptable</div>
        <div class="signature-title" style="margin-top: 3mm;">Directeur Administratif & Financier</div>
      </div>
    </div>
    
    <div class="bottom-info">
      <span>remise du chèque</span>
      <span>caissier</span>
      <span>fournisseur</span>
      <span>N° d'identité</span>
    </div>

  </div>
</body>
</html>
