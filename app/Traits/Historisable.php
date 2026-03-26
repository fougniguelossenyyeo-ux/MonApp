<?php

namespace App\Traits;

use App\Models\HistoriqueAction;

trait Historisable
{
    /**
     * Enregistre une action dans l'historique
     *
     * @param string $action  Nom de l'action (snake_case recommandé)
     * @param array $properties  Valeurs supplémentaires (anciennes/nouvelles valeurs, motif, etc.)
     * @return HistoriqueAction
     */
    public function logAction(string $action, array $properties = []): HistoriqueAction
    {
        return HistoriqueAction::create([
            'user_id' => auth()->id(),                   // Utilisateur connecté
            'action' => $action,                         // Action effectuée
            'subject_type' => get_class($this),         // Modèle sur lequel on agit
            'subject_id' => $this->id,                  // ID du modèle
            'properties' => $properties,                // Données supplémentaires
            'ip_address' => request()->ip(),            // IP de l'utilisateur
            'user_agent' => request()->userAgent(),     // Navigateur / App
            'entite_id' => $this->entite_id ?? null,    // Filiale ou direction si existante
            'created_at' => now(),                       // Date et heure
        ]);
    }
}