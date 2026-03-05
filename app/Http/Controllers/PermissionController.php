<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affichage de la page :
     * - Liste déroulante des rôles
     * - Permissions groupées
     * - Permissions cochées selon rôle sélectionné
     */
    public function index(Request $request)
    {
        // 1️⃣ Tous les rôles
     $roles = Role::with('entite')
    ->where('super_admin', false)
    ->orderBy('libelle')
    ->get();

        // 2️⃣ Toutes les permissions
        $permissions = Permission::orderBy('nom')->get();

        // 3️⃣ Regroupement par préfixe (ex: demande.create → Demande)
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            $prefix = explode('.', $permission->nom)[0] ?? 'autres';
            return ucfirst($prefix);
        });

        // 4️⃣ Rôle sélectionné via dropdown
        $selectedRole = null;
        $currentPermissions = [];

        if ($request->filled('role_id')) {

            $selectedRole = Role::with('permissions')
                ->find($request->role_id);

            if ($selectedRole) {
                $currentPermissions = $selectedRole
                    ->permissions
                    ->pluck('id')
                    ->toArray();
            }
        }

        return view('permissions.index', compact(
            'roles',
            'groupedPermissions',
            'selectedRole',
            'currentPermissions'
        ));
    }

    /**
     * Mise à jour des permissions d’un rôle
     */
    public function update(Request $request)
    {
        // 1️⃣ Validation
        $validated = $request->validate([
            'role_id'       => 'required|exists:roles,id',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // 2️⃣ Récupération du rôle
        $role = Role::with('permissions')
            ->findOrFail($validated['role_id']);

        // 3️⃣ Protection Super Admin
        if ($role->super_admin) {
            return redirect()
                ->route('permissions.index', ['role_id' => $role->id])
                ->withErrors([
                    'role_id' => 'Les permissions du Super Administrateur ne peuvent pas être modifiées.'
                ]);
        }

        // 4️⃣ Sauvegarde des permissions AVANT modification
        $permissionsAvant = $role->permissions
            ->pluck('nom')
            ->toArray();

        // 5️⃣ Synchronisation
        $role->permissions()
            ->sync($validated['permissions'] ?? []);

        // 6️⃣ Récupération des permissions APRÈS modification
        $permissionsApres = Permission::whereIn(
                'id',
                $validated['permissions'] ?? []
            )
            ->pluck('nom')
            ->toArray();

        //Journalisation (si package activitylog installé)
        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($role)
                ->withProperties([
                    'permissions_avant' => $permissionsAvant,
                    'permissions_apres' => $permissionsApres,
                ])
                ->log("Modification des permissions du rôle {$role->libelle}");
        }

        // 8️⃣ Redirection avec rôle toujours sélectionné
        return redirect()
            ->route('permissions.index', ['role_id' => $role->id])
            ->with('success', "Les permissions du rôle « {$role->libelle} » ont été mises à jour avec succès.");
    }
  

// Dans PermissionController
public function saveRolePermissions(Request $request)
{
    $validated = $request->validate([
        'role_id' => 'required|exists:roles,id',
        'permissions' => 'nullable|array',
        'permissions.*' => 'exists:permissions,id',
    ]);

    $role = Role::findOrFail($validated['role_id']);

    // Synchroniser les permissions sélectionnées
    $role->permissions()->sync($validated['permissions'] ?? []);

    return redirect()->back()->with('success', 'Permissions mises à jour avec succès.');
}

public function edit($id)
{
    $role = Role::with('users')->findOrFail($id);

    $allPermissions = Permission::all();

    // Grouper les permissions selon le nom
    $groupedPermissions = [
        'Demandes' => $allPermissions->filter(fn($p) => str_contains($p->nom, 'demande')),
        'Paiements' => $allPermissions->filter(fn($p) => str_contains($p->nom, 'paiement')),
        'Général' => $allPermissions->filter(fn($p) => !str_contains($p->nom, 'demande') && !str_contains($p->nom, 'paiement')),
    ];

    // Permissions actuelles du rôle
    $currentPermissions = $role->permissions->pluck('id')->toArray();

    return view('permissions.edit', compact('role', 'groupedPermissions', 'currentPermissions'));
}

public function getRolePermissions($roleId)
{
    $role = Role::findOrFail($roleId);
    $permissions = $role->permissions()->pluck('id');
    return response()->json($permissions);
}
}