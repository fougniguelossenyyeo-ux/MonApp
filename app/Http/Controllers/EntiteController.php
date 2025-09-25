<?php

namespace App\Http\Controllers;
use App\Models\Entite;
use Illuminate\Http\Request;

class EntiteController extends Controller
{
 // Appliquer auth à tout le controller
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
            'libelle_entite' => 'required|string|max:255']);
       $request->only(['libelle_entite']);
        return redirect()->route('entites.index')->with('success', 'Entité créé avec succès.');
    }

    public function edit(Entite $entite)
    {
        return view('entites.edit', compact('entite'));
    }

    public function update(Request $request, Entite $entite)
    {
        $request->validate(['libelle_entite' => 'required|string|max:255']);
        $entite->update($request->all());
        return redirect()->route('entites.index')->with('success', 'Entité mis à jour.');
    }

    public function destroy(Entite $entite)
    {
        $entite->delete();
        return redirect()->route('entites.index')->with('success', 'Entité supprimé.');
    }
}
