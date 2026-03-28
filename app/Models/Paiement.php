<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Historisable;
use Illuminate\Support\Str;

class Paiement extends Model
{
    use HasFactory, Historisable;

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
        'date_paiement_effectif' => 'date',
       
    ];

    protected static function booted()
    {
        static::creating(function (Paiement $paiement) {
            if (empty($paiement->id)) {
                $paiement->id = (string) Str::orderedUuid();
            }
        });
    }

    // ───── Relations ─────

    public function demande()
    {
        return $this->belongsTo(Demande::class, 'demande_id', 'id');
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

    // ───── Méthodes métier ─────

    /**
     * Montant déjà payé (versements validés)
     */
    public function montantDejaPaye(): float
    {
        return (float) $this->paiementVersements()
            ->where('statut_versement', 'valide')
            ->sum('montant');
    }

    /**
     * Montant restant à payer
     */
    public function montantRestant(): float
    {
        return (float) $this->montant_prevu - $this->montantDejaPaye();
    }

    /**
     * Accessor (optionnel mais pratique)
     */
    public function getMontantRestantAttribute(): float
    {
        return $this->montantRestant();
    }

  
    

   
}