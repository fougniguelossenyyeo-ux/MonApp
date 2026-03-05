<?php

namespace App\Http\Controllers;

use App\Models\Entite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EntiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexe()
    {
        $entites = Entite::all();
        return view('entites.index', compact('entites'));
    }

    public function create()
    {
        return view('entites.create');
    }

  public function store(Request $request)
{
    $request->validate([
        'libelle_entite' => 'required|string|max:255',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $logoPath = null;

    if ($request->hasFile('logo')) {
        $file = $request->file('logo');
        // Crée un nom unique basé sur le libelle de l'entité + timestamp
        $fileName = \Str::slug($request->libelle_entite) . '_' . time() . '.' . $file->getClientOriginalExtension();
        // Stocke le fichier dans le dossier 'logos' du disque public
        $logoPath = $file->storeAs('logos', $fileName, 'public');
    }

    // Création de l'entité
    Entite::create([
        'libelle_entite' => $request->libelle_entite,
        'logo' => $logoPath,
    ]);

    return redirect()->route('entites.index')
                     ->with('success', 'Entité créée avec succès.');
}

    public function edit(Entite $entite)
    {
        return view('entites.edit', compact('entite'));
    }

    public function update(Request $request, Entite $entite)
    {
        $request->validate([
            'libelle_entite' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($entite->logo) {
                Storage::disk('public')->delete($entite->logo);
            }
            $entite->logo = $request->file('logo')->store('logos', 'public');
        }

        $entite->libelle_entite = $request->libelle_entite;
        $entite->save();

        return redirect()->route('entites.index')->with('success', 'Entité mise à jour.');
    }

    public function destroy(Entite $entite)
    {
        if ($entite->logo) {
            Storage::disk('public')->delete($entite->logo);
        }

        $entite->delete();
        return redirect()->route('entites.index')->with('success', 'Entité supprimée.');
    }
}
