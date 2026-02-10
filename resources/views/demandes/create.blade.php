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
                        <p class="text-sm font-medium text-indigo-600">Informations Bénéficiaire</p>
                    </div>
                </div>
                <div class="flex-1 mx-4">
                    <div class="h-1 bg-gray-200 rounded-full">
                        <div class="h-1 bg-indigo-600 rounded-full step-progress" data-step="1"></div>
                    </div>
                </div>
                <!-- Step 2 -->
                <div class="flex items-center">
                    <div class="step-indicator flex-shrink-0 w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center">
                        <span>2</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Information de Facturation</p>
                    </div>
                </div>
                <div class="flex-1 mx-4">
                    <div class="h-1 bg-gray-200 rounded-full">
                        <div class="h-1 bg-gray-200 rounded-full step-progress" data-step="2"></div>
                    </div>
                </div>
                <!-- Step 3 -->
                <div class="flex items-center">
                    <div class="step-indicator flex-shrink-0 w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center">
                        <span>3</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Centre Analytique</p>
                    </div>
                </div>
                <div class="flex-1 mx-4">
                    <div class="h-1 bg-gray-200 rounded-full">
                        <div class="h-1 bg-gray-200 rounded-full step-progress" data-step="3"></div>
                    </div>
                </div>
                <!-- Step 4 -->
                <div class="flex items-center">
                    <div class="step-indicator flex-shrink-0 w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center">
                        <span>4</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Documentation</p>
                    </div>
                </div>
                <div class="flex-1 mx-4">
                    <div class="h-1 bg-gray-200 rounded-full">
                        <div class="h-1 bg-gray-200 rounded-full step-progress" data-step="4"></div>
                    </div>
                </div>
                <!-- Step 5 -->
                <div class="flex items-center">
                    <div class="step-indicator flex-shrink-0 w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center">
                        <span>5</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Révision</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <form action="{{ route('demandes.store') }}" method="POST" id="dpForm" enctype="multipart/form-data">
                @csrf

                <!-- Step 1: Informations Générales -->
                <div class="form-step">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Informations Bénéficiaire</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Dénomination -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dénomination</label>
                            <input type="text" name="denomination" id="requester" placeholder="elifat technologie"
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
                    </div>
                </div>

                <!-- Step 2: Facturation -->
                <div class="form-step hidden mt-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Informations de Facturation</h2>
                    <div class="space-y-6">
                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" id="description" rows="4" placeholder="Décrivez l'objet de la dépense..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none"></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Montant HT -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Montant HT (FCFA)</label>
                                <input type="number" name="montant_ht" id="amount_ht" value="0" min="0"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            </div>
                            <!-- TVA -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">TVA</label>
                                <select name="tva" id="tva">
                                    <option value="TVA 0%">TVA 0%</option>
                                    <option value="TVA sur hydrocarbure 9%">TVA sur hydrocarbure 9%</option>
                                  <option value="TVA 18%" selected>TVA 18%</option>
                              </select>

                            </div>
                            <!-- Référence Facture -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Référence Facture</label>
                                <input type="text" name="reference_facture" id="invoice_ref" placeholder="FAC-2025-001"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            </div>
                            <!-- Référence Bon de Commande -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Référence Bon de Commande</label>
                                <input type="text" name="reference_bon_commande" id="order_ref" placeholder="BC-2025-001"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            </div>
                            <!-- Référence Contrat -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Référence Contrat</label>
                                <input type="text" name="reference_contrat" id="contract_ref" placeholder="CONTRAT-2025-001"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            </div>
                            <!-- Référence Expression de Besoin -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Référence Expression de Besoin</label>
                                <input type="text" name="reference_expression_besoin" id="requirement_ref" placeholder="EB-2025-001"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Centre Analytique -->
                <div class="form-step hidden mt-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Centre Analytique </h2>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                            <!-- Code Fournisseur -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Code Fournisseur</label>
                                <input type="text" name="code_fournisseur" id="supplier_code" placeholder="FOU-2025-001"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Pièces jointes -->
                <div class="form-step hidden mt-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Documentation</h2>
                    <p class="text-sm text-gray-600 mb-4">Veuillez joindre les pièces justificatives (Maximum 100MB par fichier)</p>
                    <div class="flex items-center">
                        <input type="file" name="pieces_jointes[]" id="fileInput" multiple class="hidden" accept="application/pdf">
                        <button type="button" id="fileButton"
                            class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-upload mr-2"></i> Choisir des fichiers
                        </button>
                        <span id="fileNames" class="ml-4 text-gray-700"></span>
                    </div>
                    <div id="fileList" class="mt-4 space-y-2"></div>
                </div>

                <!-- Step 5: Révision -->
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
                        <i class="fas fa-check mr-2"></i> Ajouter
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
    const stepIndicators = document.querySelectorAll('.step-indicator');
    const stepProgress = document.querySelectorAll('.step-progress');
    const reviewSection = document.getElementById('reviewSection');
    const fileInput = document.getElementById('fileInput');
    const fileButton = document.getElementById('fileButton');
    const fileNames = document.getElementById('fileNames');
    const fileList = document.getElementById('fileList');

    let currentStep = 0;
    steps[currentStep].classList.remove('hidden');

    nextBtn.addEventListener('click', () => {
        if(currentStep < steps.length - 1) {
            steps[currentStep].classList.add('hidden');
            currentStep++;
            steps[currentStep].classList.remove('hidden');
            updateStepIndicators();
            updateButtons();
            if(currentStep === steps.length - 1) updateReview();
        }
    });

    prevBtn.addEventListener('click', () => {
        if(currentStep > 0) {
            steps[currentStep].classList.add('hidden');
            currentStep--;
            steps[currentStep].classList.remove('hidden');
            updateStepIndicators();
            updateButtons();
        }
    });

    function updateButtons() {
        prevBtn.classList.toggle('hidden', currentStep === 0);
        nextBtn.classList.toggle('hidden', currentStep === steps.length - 1);
        submitBtn.classList.toggle('hidden', currentStep !== steps.length - 1);
    }

    function updateStepIndicators() {
        stepIndicators.forEach((indicator, index) => {
            const textElement = indicator.nextElementSibling.querySelector('p');
            if (index < currentStep) {
                indicator.classList.remove('bg-gray-200', 'text-gray-500');
                indicator.classList.add('bg-indigo-600', 'text-white');
                indicator.innerHTML = '<i class="fas fa-check"></i>';
                textElement.classList.remove('text-gray-500');
                textElement.classList.add('text-indigo-600');
            } else if (index === currentStep) {
                indicator.classList.remove('bg-gray-200', 'text-gray-500');
                indicator.classList.add('bg-indigo-600', 'text-white');
                indicator.innerHTML = `<span>${index + 1}</span>`;
                textElement.classList.remove('text-gray-500');
                textElement.classList.add('text-gray-900');
            } else {
                indicator.classList.remove('bg-indigo-600', 'text-white');
                indicator.classList.add('bg-gray-200', 'text-gray-500');
                indicator.innerHTML = `<span>${index + 1}</span>`;
                textElement.classList.remove('text-indigo-600', 'text-gray-900');
                textElement.classList.add('text-gray-500');
            }
        });

        stepProgress.forEach((progress, index) => {
            if (index < currentStep) {
                progress.classList.remove('bg-gray-200', 'w-0');
                progress.classList.add('bg-indigo-600', 'w-full');
            } else {
                progress.classList.remove('bg-indigo-600', 'w-full');
                progress.classList.add('bg-gray-200', 'w-0');
            }
        });
    }

    fileButton.addEventListener('click', () => fileInput.click());
    
    fileInput.addEventListener('change', () => {
        const files = Array.from(fileInput.files);
        const names = files.map(f => f.name).join(', ');
        fileNames.textContent = names || 'Aucun fichier choisi';
        
        // Afficher la liste des fichiers
        fileList.innerHTML = '';
        files.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200';
            fileItem.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                    <span class="text-sm text-gray-700">${file.name}</span>
                    <span class="text-xs text-gray-500 ml-2">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                </div>
            `;
            fileList.appendChild(fileItem);
        });
    });

    function updateReview() {
        const montantHT = parseFloat(document.getElementById('amount_ht').value) || 0;
        const tvaPourcentage = parseFloat(document.getElementById('tva').value) || 0;
        const ttc = montantHT + (montantHT * tvaPourcentage / 100);
        document.getElementById('hiddenTTC').value = ttc.toFixed(2);

        // Récupérer tous les champs
        const data = {
            'Dénomination': document.getElementById('requester').value || '-',
            'Entité': document.getElementById('entity').selectedOptions[0]?.text || '-',
            'Contact Téléphonique': document.getElementById('contact').value || '-',
            'Adresse': document.getElementById('address').value || '-',
            'Email': document.getElementById('email').value || '-',
            'Description': (document.getElementById('description').value || '-').replace(/\n/g, '<br>'),
            'Montant HT': montantHT ? montantHT.toFixed(2) + ' F CFA' : '0.00 F CFA',
            'TVA (%)': tvaPourcentage ? tvaPourcentage.toFixed(2) + ' %' : '0.00 %',
            'Montant TTC': ttc ? ttc.toFixed(2) + ' F CFA' : '0.00 F CFA',
            'Référence Facture': document.getElementById('invoice_ref').value || '-',
            'Référence Bon de Commande': document.getElementById('order_ref').value || '-',
            'Référence Contrat': document.getElementById('contract_ref').value || '-',
            'Référence Expression de Besoin': document.getElementById('requirement_ref').value || '-',
            'Code Analytique': document.getElementById('analytical_code').value || '-',
            'Centre Analytique': document.getElementById('analytical_center').value || '-',
            'Code Projet': document.getElementById('project_code').value || '-',
            'Code Fournisseur': document.getElementById('supplier_code').value || '-',
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Succès',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: "{{ session('error') }}",
            timer: 3000,
            showConfirmButton: false
        });
    @endif
});
</script>
@endsection
@endsection