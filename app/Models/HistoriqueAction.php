<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueAction extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'historique_actions';

    public $incrementing = false;

    protected $keyType = 'string';

    // Pas de updated_at / deleted_at
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'user_agent',
        'entite_id',
        'created_at',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    // ───────────── Relations ─────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    public function entite()
    {
        return $this->belongsTo(Entite::class);
    }

    // ───────────── Accessor ─────────────

    public function getDetailAttribute()
    {
        $model = strtolower(class_basename($this->subject_type));

        // Entité
        $entite = $this->entite?->libelle_entite ?? 'Non définie';

        // Référence (gestion sécurisée)
        $reference = $this->resolveReference();

        // Libellé action
        $texte = $this->resolveActionLabel($model);

        // Ajouter référence si existe
        if ($reference) {
            $texte .= " ($reference)";
        }

        // Ajouter entité
        $texte .= " pour l'entité $entite";

        return $texte;
    }

    // ───────────── Helpers ─────────────

    /**
     * Déterminer la référence selon le type de sujet
     */
    private function resolveReference(): ?string
    {
        if (! $this->subject) {
            return null;
        }

        return $this->subject->reference_dp
            ?? $this->subject->demande?->reference_dp
            ?? $this->subject->paiement?->demande?->reference_dp
            ?? $this->subject->paiement?->reference_dp
            ?? $this->subject->id;
    }

    /**
     * Générer le libellé de l'action
     */
    private function resolveActionLabel(string $model): string
    {
        return match ($this->action) {

            // ───────── DEMANDE ─────────
            'soumettre_demande' => "Soumission d'une demande",
            'valider_niveau1' => 'Validation niveau 1 de la demande',
            'valider_niveau2' => 'Validation niveau 2 de la demande',
            'valider_niveau3' => 'Validation niveau 3 de la demande',
            'refuser_niveau1' => 'Refus niveau 1 de la demande',
            'refuser_niveau2' => 'Refus niveau 2 de la demande',
            'refuser_niveau3' => 'Refus niveau 3 de la demande',
            'imprimer' => 'Impression de la demande',

            // ───────── VERSEMENT ─────────
            'soumettre_versement' => 'Soumission du versement',
            'valider_versement' => 'Validation du versement',
            'refuser_versement' => 'Refus du versement',

            // ───────── DEFAULT ─────────
            default => ucfirst($this->action)." de $model",
        };
    }
}
