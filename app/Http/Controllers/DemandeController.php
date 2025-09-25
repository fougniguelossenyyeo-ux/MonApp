<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Entite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DemandeController extends Controller
{
    /**
     * Liste toutes les demandes
     */
    public function index()
    {
        $demandes = Demande::with(['entite', 'user'])->latest()->paginate(10);
        $totalMontant = Demande::sum('montant_paiement_fournisseur');

        return view('demandes.index', compact('demandes', 'totalMontant'));
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
            'user_id' => 'required|uuid|exists:users,id',
            'pieces_jointes' => 'nullablemimes:pdf|max:20480', // max MB
        ]);

        // Génération dynamique de la référence DP
        $date = now()->format('dmy');
        $randomNumber = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $validated['reference_dp'] = 'DP-CI' . $date . '-' . $randomNumber;

        // Gestion des fichiers
        if ($request->hasFile('pieces_jointes')) {
            $files = [];
            foreach ($request->file('pieces_jointes') as $file) {
                $path = $file->store('pieces_jointes', 'public');
                $files[] = $path;
            }
            $validated['pieces_jointes'] = json_encode($files);
        }

        // Création de la demande
        Demande::create($validated);

        return redirect()->route('demandes.index')->with('success', 'Demande créée avec succès');
    }

    /**
     * Affiche une demande
     */
    public function show(Demande $demande)
    {
        $demande->load(['entite', 'user']);
        $pieces = $demande->pieces_jointes ? json_decode($demande->pieces_jointes) : [];
        return view('demandes.show', compact('demande', 'pieces'));
    }

    /**
     * Formulaire d’édition
     */
    public function edit(Demande $demande)
    {
        $entites = Entite::all();
        $users = User::all();
        $pieces = $demande->pieces_jointes ? json_decode($demande->pieces_jointes) : [];
        return view('demandes.edit', compact('demande', 'entites', 'users', 'pieces'));
    }

    /**
     * Mise à jour
     */
    public function update(Request $request, Demande $demande)
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
            'priorite' => 'nullable|string|in:normal,urgent,très_urgent',
            'status' => 'required|integer|in:0,1,2,3,-1',
            'pieces_jointes.*' => 'nullable|file|max:10240',
        ]);

        // Gestion des fichiers supplémentaires
        $existingFiles = $demande->pieces_jointes ? json_decode($demande->pieces_jointes) : [];
        if ($request->hasFile('pieces_jointes')) {
            foreach ($request->file('pieces_jointes') as $file) {
                $path = $file->store('pieces_jointes', 'public');
                $existingFiles[] = $path;
            }
        }
        $validated['pieces_jointes'] = json_encode($existingFiles);

        $demande->update($validated);

        return redirect()->route('demandes.index')->with('success', 'Demande mise à jour avec succès');
    }

    /**
     * Suppression
     */
    public function destroy(Demande $demande)
    {
        // Supprimer les fichiers associés
        if ($demande->pieces_jointes) {
            foreach (json_decode($demande->pieces_jointes) as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        $demande->delete();

        return redirect()->route('demandes.index')->with('success', 'Demande supprimée avec succès');
    }
}
