<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Demande extends Model
{
    use HasFactory;

    protected $table = 'demandes';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

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
        'pieces_jointes',
        'status',
        'date_validation_controleur',
        'date_validation_daf',
        'date_validation_dg',
        'description',
        'motif_refus',
    ];

    protected $casts = [
        'pieces_jointes'             => 'array',
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

        // Protection ultime : bloquer toute tentative de suppression
        static::deleting(function ($demande) {
            throw new \Illuminate\Auth\Access\AuthorizationException(
                "Une demande ne peut jamais être supprimée dans Kama. Utilisez l’annulation via le statut."
            );
        });
    }

    public function user()     { return $this->belongsTo(User::class); }
    public function entite()   { return $this->belongsTo(Entite::class); }
    public function paiement() { return $this->hasOne(Paiement::class); }
    public function versements() {
        return $this->hasManyThrough(PaiementVersement::class, Paiement::class, 'demande_id', 'paiement_id');
    }
    public function historiqueActions() {
        return $this->morphMany(HistoriqueAction::class, 'subject');
    }
}