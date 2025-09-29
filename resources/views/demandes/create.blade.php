@extends('layouts.template')
@section('maincontent')
<div id="mainContent" class="flex items-center justify-center min-h-screen bg-gray-100">
    <main class="w-full max-w-4xl px-4 sm:px-6 lg:px-8 py-12">
        <!-- Step Progress -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex items-center justify-between">
                <!-- Step 1 -->
                <div class="flex items-center">
                    <div class="step-indicator flex-shrink-0 w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-indigo-600">Informations de base</p>
                    </div>
                </div>
                <div class="flex-1 mx-4">
                    <div class="h-1 bg-gray-200 rounded-full">
                        <div class="h-1 bg-indigo-600 rounded-full w-1/2"></div>
                    </div>
                </div>
                <!-- Step 2 -->
                <div class="flex items-center">
                    <div class="step-indicator flex-shrink-0 w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center">
                        <span>2</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Détails de la dépense</p>
                    </div>
                </div>
                <div class="flex-1 mx-4">
                    <div class="h-1 bg-gray-200 rounded-full">
                        <div class="h-1 bg-gray-200 rounded-full w-0"></div>
                    </div>
                </div>
                <!-- Step 3 -->
                <div class="flex items-center">
                    <div class="step-indicator flex-shrink-0 w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center">
                        <span>3</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Pièces jointes</p>
                    </div>
                </div>
                <div class="flex-1 mx-4">
                    <div class="h-1 bg-gray-200 rounded-full">
                        <div class="h-1 bg-gray-200 rounded-full w-0"></div>
                    </div>
                </div>
                <!-- Step 4 -->
                <div class="flex items-center">
                    <div class="step-indicator flex-shrink-0 w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center">
                        <span>4</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Révision</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <form  action="{{ route('demandes.store') }}" method="POST" id="dpForm" enctype="multipart/form-data">
                @csrf

                <!-- Step 1: Informations de base -->
                <div class="form-step">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Informations de base</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Dénomination -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dénomination</label>
                            <input type="text" name="denomination" id="requester" placeholder="Achat d'ordinateur lenovo"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <!-- Entité -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Entité <span class="text-red-500">*</span></label>
                            <select name="entite_id" id="entity" required
                                class="w-full max-w-md px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="">-- Sélectionnez une entité --</option>
                                @foreach($entites as $entite)
                                    <option value="{{ $entite->id }}" {{ old('entite_id') == $entite->id ? 'selected' : '' }}>
                                        {{ $entite->libelle_entite }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                     <!-- Montant HT -->
                    <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Montant HT (FCFA)</label>
                   <input type="number" name="montant_ht" id="amount_ht" value="0" min="0"
                         class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                           </div>

<!-- TVA -->
                   <div>
                       <label class="block text-sm font-medium text-gray-700 mb-2">TVA (%)</label>
                   <input type="number" name="tva" id="tva" value="0" min="0" step="0.01"
                             class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                       </div>
                        <!-- Date de paiement -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date de paiement souhaitée</label>
                            <input type="date" name="date_paiement" id="paymentDate"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <!-- Contact -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Téléphonique</label>
                            <input type="tel" name="contact_fournisseur" id="contact" placeholder="+225 05 96 15 89 72" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <!-- Adresse -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                            <input type="text" name="adresse_fournisseur" id="address" placeholder="Ex: Abidjan, Cocody, Rue des Jardins" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email_fournisseur" id="email" placeholder="yeo@kama-sa.com"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <!-- Références -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Référence Facture</label>
                            <input type="text" name="reference_facture" id="invoice_ref" placeholder="FAC-2025-001"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Référence Bon de Commande</label>
                            <input type="text" name="reference_bon_commande" id="order_ref" placeholder="BC-2025-001"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Référence Contrat</label>
                            <input type="text" name="reference_contrat" id="contract_ref" placeholder="CONTRAT-2025-001"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Référence Expression de Besoin</label>
                            <input type="text" name="reference_expression_besoin" id="requirement_ref" placeholder="EB-2025-001"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <!-- Code Fournisseur -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Code Fournisseur</label>
                            <input type="text" name="code_fournisseur" id="supplier_code" placeholder="FOU-2025-001"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                    </div>
                </div>

                <!-- Step 2: Détails de la dépense -->
                <div class="form-step hidden mt-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Détails de la dépense</h2>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Objet de la Dépense</label>
                            <textarea name="description" id="description" rows="4" placeholder="Décrivez l'objet de la dépense..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Code Analytique</label>
                            <input type="text" name="code_analytique" id="analytical_code" placeholder="CA-2025-001"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Centre Analytique</label>
                            <input type="text" name="centre_analytique" id="analytical_center" placeholder="Département Finance"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Code Projet</label>
                            <input type="text" name="code_projet" id="project_code" placeholder="PROJ-2025-001"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <!-- Priorité -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Priorité</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <label class="inline-flex items-center px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-100">
                                    <input type="radio" name="priorite" value="normal" class="text-indigo-600 focus:ring-indigo-500" checked>
                                    <span class="ml-2 text-gray-700">Normal</span>
                                </label>
                                <label class="inline-flex items-center px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-100">
                                    <input type="radio" name="priorite" value="urgent" class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-gray-700">Urgent</span>
                                </label>
                                <label class="inline-flex items-center px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-100">
                                    <input type="radio" name="priorite" value="tres_urgent" class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-gray-700">Très Urgent</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Pièces jointes -->
                <div class="form-step hidden mt-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Pièces jointes maximum 20MB</h2>
                    <div class="flex items-center">
                        <input type="file" name="pieces_jointes[]" id="fileInput" multiple class="hidden" accept="application/pdf">
                        <button type="button" id="fileButton"
                            class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-upload mr-2"></i> Choisir des fichiers
                        </button>
                        <span id="fileNames" class="ml-4 text-gray-700"></span>
                    </div>
                </div>

                <!-- Step 4: Révision -->
                <div class="form-step hidden mt-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 text-center">Révision de la demande</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-gray-50 rounded-lg shadow-sm" id="reviewSection"></table>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="mt-8 flex justify-between">
                    <button type="button" id="prevBtn"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors hidden">
                        <i class="fas fa-arrow-left mr-2"></i> Précédent
                    </button>
                    <button type="button" id="nextBtn"
                        class="ml-auto px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                        Suivant <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                    <button type="submit" id="submitBtn"
                        class="ml-auto px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors hidden">
                        <i class="fas fa-check mr-2"></i> Soumettre
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<!-- Champ caché pour stocker le TTC -->
<input type="hidden" name="montant_ttc" id="hiddenTTC" value="0">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dpForm = document.getElementById('dpForm');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.getElementById('submitBtn');
    const steps = document.querySelectorAll('.form-step');
    const reviewSection = document.getElementById('reviewSection');
    const fileInput = document.getElementById('fileInput');
    const fileButton = document.getElementById('fileButton');
    const fileNames = document.getElementById('fileNames');

    let currentStep = 0;
    steps[currentStep].classList.remove('hidden');

    nextBtn.addEventListener('click', () => {
        if(currentStep < steps.length - 1) {
            steps[currentStep].classList.add('hidden');
            currentStep++;
            steps[currentStep].classList.remove('hidden');
            updateButtons();
            if(currentStep === steps.length - 1) updateReview();
        }
    });

    prevBtn.addEventListener('click', () => {
        if(currentStep > 0) {
            steps[currentStep].classList.add('hidden');
            currentStep--;
            steps[currentStep].classList.remove('hidden');
            updateButtons();
        }
    });

    function updateButtons() {
        prevBtn.classList.toggle('hidden', currentStep === 0);
        nextBtn.classList.toggle('hidden', currentStep === steps.length - 1);
        submitBtn.classList.toggle('hidden', currentStep !== steps.length - 1);
    }

    fileButton.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', () => {
        const names = Array.from(fileInput.files).map(f => f.name).join(', ');
        fileNames.textContent = names || 'Aucun fichier choisi';
    });

    function updateReview() {
        const montantHT = parseFloat(document.getElementById('amount_ht').value) || 0;
        const tvaPourcentage = parseFloat(document.getElementById('tva').value) || 0;

        // Calcul TTC pour la base de données (champ caché)
        const ttc = montantHT + (montantHT * tvaPourcentage / 100);
        document.getElementById('hiddenTTC').value = ttc.toFixed(2);

        const data = {
            'Dénomination': document.getElementById('requester').value,
            'Entité': document.getElementById('entity').selectedOptions[0]?.text,
            'Montant HT': montantHT.toFixed(2) + ' F CFA',
            'TVA (%)': tvaPourcentage.toFixed(2) + ' %',
            'Date de paiement': document.getElementById('paymentDate').value,
            'Adresse': document.getElementById('address').value,
            'Email': document.getElementById('email').value,
            'Contact Téléphonique': document.getElementById('contact').value,
            'Référence Facture': document.getElementById('invoice_ref').value,
            'Référence Bon de Commande': document.getElementById('order_ref').value,
            'Référence Contrat': document.getElementById('contract_ref').value,
            'Référence Expression de Besoin': document.getElementById('requirement_ref').value,
            'Code Fournisseur': document.getElementById('supplier_code').value,
            'Objet de la dépense': document.getElementById('description').value,
            'Code Analytique': document.getElementById('analytical_code').value,
            'Centre Analytique': document.getElementById('analytical_center').value,
            'Code Projet': document.getElementById('project_code').value,
            'Fichiers joints': Array.from(fileInput.files).map(f => f.name).join(', ') || 'Aucun fichier'
        };

        reviewSection.innerHTML = '<tbody>';
        for (const key in data) {
            reviewSection.innerHTML += `
                <tr class="border-b border-gray-200">
                    <td class="px-6 py-4 font-medium text-gray-700 w-1/2">${key}</td>
                    <td class="px-6 py-4 text-gray-900 w-1/2">${data[key]}</td>
                </tr>
            `;
        }
        reviewSection.innerHTML += '</tbody>';
    }
});
</script>

@endsection
