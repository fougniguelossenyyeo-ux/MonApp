<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\Entite;
class RoleController extends Controller
{
    // Appliquer auth à tout le controller
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }
    public function indexe()
    {
        $entites = Entite::all();
        return view('entites.index', compact('entites'));
    }
   public function create()
{
    $entites = Entite::all();
    return view('roles.create', compact('entites'));
}
    public function store(Request $request)
    {
        $request->validate(['libelle' => 'required|string|max:255',
      'entite_id' => 'required|string|exists:entites,id',
         ]);

        $request->only(['libelle', 'entite_id']);
        return redirect()->route('roles.index')->with('success', 'Rôle créé avec succès.');
    }

    public function edit(Role $role)
    {
           $entites = Entite::all();
          
        return view('roles.edit', compact('role','entites'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate(['libelle' => 'required|string|max:255',
        'entite_id' => 'required|string|exists:entites,id',
    
    ]);
        $role->update($request->all());
        return redirect()->route('roles.index')->with('success', 'Rôle mis à jour.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Rôle supprimé.');
    }
}
