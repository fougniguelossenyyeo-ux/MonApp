<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class HistoriqueAction extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'historique_actions';

    public $incrementing = false;
    protected $keyType = 'string';

    // Pas de updated_at, seulement created_at
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
public function getDetailAttribute()
{
    $model = strtolower(class_basename($this->subject_type));

    //  Entité toujours affichée
    $entite = $this->entite?->libelle_entite ?? 'Non définie';

    $reference = null;

    if ($this->subject) {

        // Cas Demande
        if (isset($this->subject->reference_dp)) {
            $reference = $this->subject->reference_dp;
        }

        //  Cas Paiement
        elseif (isset($this->subject->demande)) {
            $reference = $this->subject->demande->reference_dp ?? null;
        }

        //  Cas PaiementVersement (TON CAS )
        elseif (isset($this->subject->paiement)) {
            $reference = $this->subject->paiement->demande->reference_dp ?? null;
        }

        // fallback
        else {
            $reference = $this->subject->id;
        }
    }

    //  Actions métier
    $texte = match (true) {

        // ───────── DEMANDE ─────────
        $this->action === 'soumettre_demande'
            => "Soumission d'une demande",

        $this->action === 'valider_niveau1'
            => "Validation niveau 1 de la demande",

        $this->action === 'valider_niveau2'
            => "Validation niveau 2 de la demande",

        $this->action === 'valider_niveau3'
            => "Validation niveau 3 de la demande",

        $this->action === 'refuser_niveau1'
            => "Refus niveau 1 de la demande",

        $this->action === 'refuser_niveau2'
            => "Refus niveau 2 de la demande",

        $this->action === 'refuser_niveau3'
            => "Refus niveau 3 de la demande",

        $this->action === 'imprimer'
            => "Impression de la demande",

        // ───────── VERSEMENT ─────────
        $this->action === 'soumettre_versement'
            => "Soumission du versement du paiement",

        $this->action === 'valider_versement'
            => "Validation du versement du paiement",

        $this->action === 'refuser_versement'
            => "Refus du versement du paiement",

        // ───────── PAR DEFAUT ─────────
        default => ucfirst($this->action) . " de $model",
    };

    //  Ajouter référence
    if ($reference) {
        $texte .= " ($reference)";
    }

    //  Ajouter entité
    $texte .= " pour l'entité $entite";

    return $texte;
}
}