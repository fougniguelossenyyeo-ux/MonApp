  <!DOCTYPE html>
  <html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de Paiement</title>
    <style>
      * { box-sizing: border-box; }
      body { font-family: Arial, sans-serif; margin: 0; padding: 0; font-size: 10px; line-height: 1.2; background-color: white; }
      @page { size: A4 landscape; margin: 5mm; }
      @media print { 
        body { margin: 0; padding: 0; background-color: white; } 
        .form-container { width: 297mm; height: 210mm; margin: 0; padding: 6mm; page-break-after: always; } 
        button { display: none; } 
      }
      .form-container { width: 297mm; height: 210mm; margin: 0 auto; padding: 6mm; background-color: white; position: relative; }
      .header { display: grid; grid-template-columns: 1fr auto; align-items: center; padding-bottom: 2mm; margin-bottom: 3mm; gap: 6mm; }
      .logo-placeholder { width: 45mm; height: 12mm; border: 1px dashed #999; display: flex; align-items: center; justify-content: center; font-size: 9px; color: #666; }
      .form-number { text-align: right; font-weight: bold; font-size: 11px; }
      .section { margin-bottom: 3mm; border: 1px solid #000; padding: 2mm; position: relative; page-break-inside: avoid; }
      .section-title { font-weight: bold; text-align: center; background-color: 
#e0e0e0; padding: 1.5mm; margin: -2mm -2mm 2mm -2mm; border-bottom: 1px solid #000; font-size: 11px; }
      .row { display: flex; margin-bottom: 2mm; align-items: center; flex-wrap: wrap; gap: 2mm; }
      .row-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(38mm, 1fr)); gap: 3mm; margin-bottom: 2mm; align-items: center; }
      .field-group { display: flex; align-items: center; gap: 1.5mm; min-width: 0; }
      .field-label { font-weight: bold; white-space: nowrap; font-size: 9px; flex-shrink: 0; }
      .field-input { border: none; border-bottom: 1px solid #000; padding: 1mm; flex: 1; min-width: 0; font-size: 9px; background: transparent; }
      .short-input { width: 18mm; flex: none; }
      .medium-input { width: 30mm; flex: none; }
      .long-input { min-width: 45mm; }
      .table { width: 100%; border-collapse: collapse; margin: 2mm 0; table-layout: fixed; position: relative; }
      .table th, .table td { border: 1px solid #000; padding: 1.5mm; text-align: center; font-size: 8px; word-wrap: break-word; overflow: hidden; }
      .table th { background-color: white; font-weight: bold; }
      .comptabilisation-table { font-size: 7.5px; }
      .comptabilisation-table th, .comptabilisation-table td { padding: 1mm; }
      .signatures { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2mm; margin-top: 2mm; page-break-inside: avoid; }
      .signature-box { border: 1px solid #000; height: 16mm; padding: 1.5mm; text-align: center; display: flex; flex-direction: column; justify-content: space-between; }
      .signature-title { font-weight: bold; font-size: 8px; }
      .date-field { font-size: 7px; }
      .footer-section { display: grid; grid-template-columns: 1fr 1fr; gap: 3mm; margin-top: 3mm; page-break-inside: avoid; }
      .footer-box { border: 1px solid #000; padding: 2mm; min-height: 12mm; }
      .bottom-info { display: grid; grid-template-columns: repeat(4, 1fr); gap: 4mm; margin-top: 3mm; font-size: 8px; text-align: center; }
      .two-column { display: grid; grid-template-columns: 1fr 1fr; gap: 3mm; }
      .watermark-between { position: absolute; top: 135mm; left: 50%; transform: translateX(-50%); font-size: 70px; font-weight: bold; color: rgba(0,0,0,0.05); pointer-events: none; z-index: 0; white-space: nowrap; }
    </style>
  </head>
  <body>
    <div class="form-container">

      <!-- En-tête -->
  <div class="header">
      <div class="logo-placeholder">
          @if($demande->entite && $demande->entite->logo)
              <img src="{{ asset('storage/' . $demande->entite->logo) }}" 
                  alt="Logo {{ $demande->entite->libelle_entite }}" 
                  style="max-width:100%; max-height:100%; object-fit: contain;">
          @else
              LOGO DE L'ENTREPRISE
          @endif
      </div>

      <div class="form-number">
          {{ $demande->entite->libelle_entite ?? 'ENTITE' }}<br>
          N° - {{ $demande->reference_dp ?? '---' }}
      </div>
  </div>

      <!-- Titre principal -->
      <div class="section-title">DEMANDE DE PAIEMENT</div>

      <!-- Section informations de base -->
      <div class="section">
        <div class="row">
          <div class="field-group">
            <span class="field-label">à l'orde de:</span>
            <input type="text" class="field-input long-input" value="{{ $demande->nom_fournisseur }}" readonly>
          </div>
        </div>

        <div class="row-grid">
          <div class="field-group">
            <span class="field-label">Nom:</span>
            <input type="text" class="field-input" value="{{ $demande->entite->libelle_entite ?? '-' }}" readonly>
          </div>
          <div class="field-group">
            <span class="field-label">code du Projet:</span>
            <input type="text" class="field-input" value="{{ $demande->code_projet ?? '' }}" readonly>
          </div>
          <div class="field-group">
            <span class="field-label">centre analytique:</span>
            <input type="text" class="field-input" value="{{ $demande->code_analytique ?? '' }}" readonly>
          </div>
          <div class="field-group">
            <span class="field-label">Date d'émission:</span>
            <input type="text" class="field-input" value="{{ $demande->created_at?->format('d/m/Y') }}" readonly>
          </div>
        </div>

        <div class="row">
          <div class="field-group">
            <span class="field-label">Adresse:</span>
            <input type="text" class="field-input long-input" value="{{ $demande->adresse_fournisseur ?? '' }}" readonly>
          </div>
        </div>

        <div class="row-grid">
          <div class="field-group">
            <span class="field-label">code fournisseur:</span>
            <input type="text" class="field-input" value="{{ $demande->code_fournisseur ?? '' }}" readonly>
          </div>
          <div class="field-group">
            <span class="field-label">code Projet:</span>
            <input type="text" class="field-input" value="{{ $demande->code_projet ?? '' }}" readonly>
          </div>
          <div class="field-group">
            <span class="field-label">code analytique:</span>
            <input type="text" class="field-input" value="{{ $demande->code_analytique ?? '' }}" readonly>
          </div>
          <div class="field-group">
            <span class="field-label">Délai souhaité:</span>
            <input type="text" class="field-input" value="{{ $demande->date_paiement?->format('d/m/Y') }}" readonly>
          </div>
        </div>

        <div class="row-grid">
          <div class="field-group">
            <span class="field-label">TEL:</span>
            <input type="text" class="field-input" value="{{ $demande->contact_fournisseur ?? '' }}" readonly>
          </div>
          <div class="field-group">
            <span class="field-label">ch./vir. banq.:</span>
            <input type="text" class="field-input" value="" readonly>
          </div>
          <div class="field-group">
            <span class="field-label">code contrat:</span>
            <input type="text" class="field-input" value="{{ $demande->reference_contrat ?? '' }}" readonly>
          </div>
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
            <td></td><td></td><td>__</td><td>__</td>
            <td></td><td></td><td></td><td>__</td>
          </tr>
        </table>
      </div>

      <!-- Section PAIEMENT et CONTRAT -->
      <div class="two-column">
        <div class="section">
          <div class="section-title">PAIEMENT</div>
          <div class="row"><div class="field-group"><span class="field-label">val. com.:</span><input type="text" class="field-input" value="Validé" readonly></div></div>
          <div class="row"><div class="field-group"><span class="field-label">paiements cumulés:</span><input type="text" class="field-input short-input" value="" readonly><input type="text" class="field-input short-input" value="" readonly><input type="text" class="field-input short-input" value="" readonly></div></div>
        </div>
        <div class="section">
          <div class="section-title">CONTRAT</div>
          <div class="row"><div class="field-group"><span class="field-label">mont. échu:</span><input type="text" class="field-input short-input" value="" readonly><span class="field-label">solde:</span><input type="text" class="field-input short-input" value="" readonly><input type="text" class="field-input short-input" value="" readonly></div></div>
          <div class="row"><div class="field-group"><span class="field-label">montant total:</span><input type="text" class="field-input" value="" readonly></div></div>
        </div>
      </div>

      <!-- FILIGRANE -->
    <div class="watermark-between">
      {{ $demande->entite->libelle_entite ?? 'ENTITE' }}
  </div>

      <!-- Section détails facture -->
      <div class="section">
        <table class="table">
          <tr>
            <th style="width: 25%;">Facture du fournisseur</th>
            <th style="width: 35%;">Description</th>
            <th style="width: 15%;">Montant TTC</th>
            <th style="width: 10%;">TVA</th>
            <th style="width: 15%;">Montant H.T.</th>
          </tr>
          <tr style="height: 20mm;">
            <td style="vertical-align: top; padding: 3mm;">{{ $demande->reference_facture ?? '' }}</td>
            <td style="vertical-align: top; padding: 3mm;">{{ $demande->description ?? '' }}</td>
            <td style="vertical-align: top; padding: 3mm;">{{ number_format($demande->montant_paiement_fournisseur,0,',',' ') }}</td>
            <td style="vertical-align: top; padding: 3mm;">{{ $demande->tva ?? '' }}%</td>
            <td style="vertical-align: top; padding: 3mm;">{{ number_format($demande->montant_ht,0,',',' ') }}</td>
          </tr>
        </table>
      </div>

      <!-- Section approbations -->
      <div class="section">
        <div style="text-align: center; font-weight: bold; margin-bottom: 3mm; font-size: 10px;">
            APPROBATIONS (selon les normes en vigueur)
        </div>

        <div class="signatures">
            <div class="signature-box">
                <div class="signature-title">CONTROLEUR DE GESTION</div>
                <div class="date-field">date: {{ $demande->created_at?->format('d/m/Y') }}</div>
            </div>

            <div class="signature-box">
                <div class="signature-title">LE DIRECTEUR GENERAL</div>
                <div class="date-field">date: {{ $demande->created_at?->format('d/m/Y') }}</div>
            </div>

            <div class="signature-box">
                <div class="signature-title">Directeur Administratif & Financier</div>
                <div class="date-field">date: {{ $demande->created_at?->format('d/m/Y') }}</div>
            </div>
        </div>
      </div>

      <!-- Section finale -->
      <div class="footer-section">
        <div class="footer-box">
            <div class="signature-title">Bon(s) de réception ou P V de réception</div>
            <div style="text-align: right; margin-top: 8mm;"></div>
        </div>

        <div class="footer-box">
            <div class="signature-title">Date Saisie Comptable</div>
            <div class="signature-title" style="margin-top: 4mm;">Directeur Administratif & Financier</div>
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
 