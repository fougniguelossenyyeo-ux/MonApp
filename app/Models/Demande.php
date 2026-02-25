<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Demande extends Model
{
    use HasFactory;
    use SoftDeletes;
    

    protected $table = 'demandes';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $primaryKey = 'id'; // explicite, bonne pratique

    protected $fillable = [
        'denomination',
        'reference_dp',
        'entite_id',
        'user_id',
        'montant_ht',
        'tva',
        'montant_paiement_fournisseur',
        'contact_fournisseur',
        'adresse_fournisseur',
        'email_fournisseur',
        'reference_facture',
        'reference_bon_commande',
        'reference_contrat',
        'reference_expression_besoin',
        'code_fournisseur',
        'code_analytique',
        'centre_analytique',
        'code_projet',
        'pieces_jointes',           // reste présent (JSON array de noms de fichiers)
        'status',
        'date_validation_controleur',
        'date_validation_daf',
        'date_validation_dg',
        'description',
    ];

    protected $casts = [
        'pieces_jointes'             => 'array',     // JSON → tableau PHP automatiquement
        'date_validation_controleur' => 'datetime',
        'date_validation_daf'        => 'datetime',
        'date_validation_dg'         => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($demande) {
            if (empty($demande->id)) {
                $demande->id = (string) Str::orderedUuid();
            }
        });
    }

    // ────────────────────────────────────────────────
    // Relations uniquement (pas de logique métier ici)
    // ────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function entite()
    {
        return $this->belongsTo(Entite::class, 'entite_id', 'id');
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class, 'demande_id', 'id');
    }

    public function versements()
    {
        return $this->hasManyThrough(
            PaiementVersement::class,
            Paiement::class,
            'demande_id',
            'paiement_id',
            'id',
            'id'
        );
    }

    public function historiqueActions()
    {
        return $this->morphMany(HistoriqueAction::class, 'subject');
    }
}