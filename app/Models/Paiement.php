<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Paiement extends Model
{
    use HasFactory;
 

    protected $table = 'paiements';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'demande_id',
        'user_id',
        'montant_prevu',
        'montant_paye',
        'statut',
        'reference_paiement',
        'date_paiement_effectif',
        'commentaire',
    ];

    protected $casts = [
        'montant_prevu'          => 'decimal:2',
        'montant_paye'           => 'decimal:2',
        'montant_restant'        => 'decimal:2',
        'date_paiement_effectif' => 'date',
         'is_deleted'      => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function (Paiement $paiement) {
            if (empty($paiement->id)) {
                $paiement->id = (string) Str::orderedUuid();
            }
        });
    }

    // ────────────────────────────────────────────────
    // Relations uniquement (aucune logique métier ici)
    // ────────────────────────────────────────────────

    public function demande()
    {
        return $this->belongsTo(Demande::class, 'demande_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paiementVersements()
    {
        return $this->hasMany(PaiementVersement::class, 'paiement_id', 'id');
    }

    public function historiqueActions()
    {
        return $this->morphMany(HistoriqueAction::class, 'subject');
    }


/**
 * Montant déjà payé = somme des versements validés
 */
public function montantDejaPaye()
{
    return $this->paiementVersements
        ->where('statut_versement', 'valide')
        ->sum('montant');
}

/**
 * Montant restant à payer
 */
public function montantRestant()
{
    return $this->montant_prevu - $this->montantDejaPaye();
}
 /**
     * Marquer le paiement comme supprimé (soft delete)
     */
    public function markAsDeleted(): void
    {
        $this->is_deleted = true;
        $this->save();
    }
public function scopeActifs($query)
{
    return $query->where('is_deleted', false);
}
}