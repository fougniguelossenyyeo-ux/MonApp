<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Entite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;

class DemandeController extends Controller
{
    /**
     * Liste toutes les demandes
     */
    public function index()
    {
        // On récupère les demandes avec les relations entite et user
        $demandes = Demande::with(['entite', 'user'])
            ->orderByDesc('created_at')
            ->paginate(10); // Pagination 10 par page

        // On retourne la vue avec les demandes
        return view('demandes.index', compact('demandes'));
    }


    /**
     * Formulaire de création
     */
    public function create()
    {
        $entites = Entite::all();
        $users = User::all();
        return view('demandes.create', compact('entites', 'users'));
    }

    /**
     * Enregistrer une demande
     */
    public function store(Request $request)
    { 
        $validated = $request->validate([
            'denomination' => 'required|string|max:255',
            'entite_id' => 'required|uuid|exists:entites,id',
            'montant_paiement_fournisseur' => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
            'contact_fournisseur' => 'required|string|max:20',
            'adresse_fournisseur' => 'required|string',
            'email_fournisseur' => 'required|email',
            'reference_facture' => 'nullable|string|max:255',
            'reference_bon_commande' => 'nullable|string|max:255',
            'reference_contrat' => 'nullable|string|max:255',
            'reference_expression_besoin' => 'nullable|string|max:255',
            'code_fournisseur' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'code_analytique' => 'nullable|string|max:255',
            'centre_analytique' => 'nullable|string|max:255',
            'code_projet' => 'nullable|string|max:255',
            'priorite' => 'nullable|string|in:normal,urgent,tres_urgent',
            
            'pieces_jointes.*' => 'nullable|file|mimes:pdf|max:20480', // max MB
        ]);

     // Génération du code DP
    $validated['reference_dp'] = 'DP-CI' . date('dmY') . '-' . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
    $validated['user_id'] = auth()->id();
    // Statut initial et user_id
    $validated['status'] = 0;
    $validated['user_id'] = auth()->id(); // si l'utilisateur est connecté

    // Gestion des fichiers joints
    if ($request->hasFile('pieces_jointes')) {
    // Créer un nom basé sur la dénomination + date
    $denomination = preg_replace('/[^A-Za-z0-9\-]/', '_', $request->denomination); // sécurise le nom
    $date = date('d-m-Y'); // date actuelle
    $mergedFileName = 'pieces_jointes/' . $denomination . '_' . $date . '.pdf';
    $mergedFilePath = storage_path('app/public/' . $mergedFileName);

    $pdf = new Fpdi();

    foreach ($request->file('pieces_jointes') as $file) {
        $pageCount = $pdf->setSourceFile($file->getPathname());
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $tpl = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($tpl);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);
        }
    }

    $pdf->Output($mergedFilePath, 'F');

    $validated['pieces_jointes'] = $mergedFileName;
}

    // Création de la demande
    $demande = Demande::create($validated);

    // Debug pour vérifier l'insertion


    return redirect()->route('demandes.index')
                     ->with('success', "Demande {$demande->reference_dp} - {$demande->denomination} créée avec succès !");

}

    /**
v/**
 * Affiche une demande
 */
public function show(Demande $demande)
{
    // Charger les relations
    $demande->load(['entite', 'user']);

    // Comme on enregistre un seul PDF fusionné, inutile de décoder en JSON
    $piece = $demande->pieces_jointes ? asset('storage/' . $demande->pieces_jointes) : null;

    return view('demandes.show', compact('demande', 'piece'));
}
    /**
     * Formulaire d’édition
     */
   
    

   
}
