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
        'montant_restant',
        'statut',
        'mode_paiement',
        'reference_paiement',
        'date_paiement_effectif',
        'notes',
    ];

    protected $casts = [
        'montant_prevu'          => 'decimal:2',
        'montant_paye'           => 'decimal:2',
        'montant_restant'        => 'decimal:2',
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

    // ────────────────────────────────────────────────
    // Relations uniquement (aucune logique métier ici)
    // ────────────────────────────────────────────────

    public function demande()
    {
        return $this->belongsTo(Demande::class, 'demande_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function versements()
    {
        return $this->hasMany(PaiementVersement::class, 'paiement_id');
    }

    public function historiqueActions()
    {
        return $this->morphMany(HistoriqueAction::class, 'subject');
    }
}