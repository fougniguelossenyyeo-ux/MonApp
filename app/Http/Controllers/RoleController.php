<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

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

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate(['libelle' => 'required|string|max:255']);
        Role::create($request->all());
        return redirect()->route('roles.index')->with('success', 'Rôle créé avec succès.');
    }

    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate(['libelle' => 'required|string|max:255']);
        $role->update($request->all());
        return redirect()->route('roles.index')->with('success', 'Rôle mis à jour.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Rôle supprimé.');
    }
}
