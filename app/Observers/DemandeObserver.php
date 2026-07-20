<?php

namespace App\Observers;

use App\Models\Demande;
use App\Models\HistoriqueAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class DemandeObserver
{
    /**
     * Trace la création d'une nouvelle demande
     */
    public function created(Demande $demande): void
    {
        $this->log($demande, 'cree_demande', [
            'reference' => $demande->reference_dp,
            'montant_ttc' => $demande->montant_paiement_fournisseur,
            'motif' => $demande->description ?? 'Non renseigné',
            'fournisseur' => $demande->denomination,
        ]);
    }

    /**
     * Trace toute modification importante (statut, montant, pièces, etc.)
     */
    public function updated(Demande $demande): void
    {
        $changes = $demande->getChanges();

        if (empty($changes)) {
            return;
        }

        // Changement de statut
        if (array_key_exists('status', $changes)) {
            $oldStatus = $demande->getOriginal('status');
            $newStatus = $demande->status;

            // Refus à n'importe quel niveau
            if ($newStatus < 0) { // tous les codes négatifs = refus
                $this->log($demande, 'demande_refuse', [
                    'ancien_statut' => $oldStatus,
                    'nouveau_statut' => $newStatus,
                    'motif_refus' => $demande->motif_annulation ?? 'Non renseigné',
                ]);

                return;
            }

            // Validation par niveau
            $action = match ($newStatus) {
                1 => 'valider_demande_niveau1',
                2 => 'valider_demande_niveau2',
                3 => 'valider_demande_niveau3',
                default => 'change_statut_demande',
            };

            $this->log($demande, $action, [
                'ancien_statut' => $oldStatus,
                'nouveau_statut' => $newStatus,
            ]);

            return;
        }

        // Modification classique (montant, pièces, description, etc.)
        $this->log($demande, 'modifie_demande', [
            'changements' => $changes,
        ]);
    }

    /**
     * Bloque + trace toute tentative de suppression physique
     */
    public function deleting(Demande $demande): bool
    {
        $this->log($demande, 'tentative_suppression_demande', [
            'statut_actuel' => $demande->status,
            'message' => 'Tentative de suppression physique interdite',
        ]);

        // Bloque la suppression
        return false;
    }

    /**
     * Méthode commune pour créer une entrée d'historique
     */
    private function log(Demande $demande, string $action, array $details = []): void
    {
        HistoriqueAction::create([
            'user_id' => Auth::id() ?? null,
            'action' => $action,
            'subject_type' => Demande::class,
            'subject_id' => $demande->id,
            'properties' => $details,
            'ip_address' => Request::ip() ?? 'unknown',
            'user_agent' => Request::userAgent() ?? 'unknown',
            'entite_id' => $demande->entite_id,
            'created_at' => now(),
        ]);
    }
}
