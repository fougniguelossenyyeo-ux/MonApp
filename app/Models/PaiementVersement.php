<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class PaiementVersement extends Model
{
    use HasFactory;
   

    protected $table = 'paiement_versements';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
    'paiement_id',
    'montant',
    'mode_paiement',              
    'date_versement',
    'commentaire',
    'statut_versement',     
];

    protected $casts = [
        'montant'     => 'decimal:2',
        'date_versement'    => 'date',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (PaiementVersement $versement) {
            if (empty($versement->id)) {
                $versement->id = (string) Str::orderedUuid();
            }
        });
    }

    // ────────────────────────────────────────────────
    // Relations uniquement
    // ────────────────────────────────────────────────

    /**
     * Le paiement auquel ce versement est rattaché
     */
    public function paiement()
    {
        return $this->belongsTo(Paiement::class, 'paiement_id');
    }

    /**
     * Historique des actions sur ce versement
     */
    public function historiqueActions()
    {
        return $this->morphMany(HistoriqueAction::class, 'subject');
    }
}