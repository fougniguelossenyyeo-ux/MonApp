<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Paiement extends Model
{
    use HasFactory;

    // Clé primaire UUID
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    // Champs assignables
    protected $fillable = [
        'demande_id',
        'montant_deja_paye',
        'montant_a_payer',
        'montant_restant',
        'status_paiement',
    ];

    // Génération automatique d'UUID
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Relation avec la demande
     */
    public function demande()
    {
        return $this->belongsTo(Demande::class, 'demande_id');
    }

    /**
     * Relation vers les versements associés
     * Cette relation permet de récupérer tous les versements d'un paiement
     */
    public function paiementsVersements()
    {
        return $this->hasMany(PaiementVersement::class, 'paiement_id', 'id');
    }
}
