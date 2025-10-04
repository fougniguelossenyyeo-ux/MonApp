<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PaiementVersement extends Model
{
    protected $table = 'paiement_versements';

    // Champs assignables
    protected $fillable = [
        'paiement_id',
        'montant',
        'commentaire',
        'date_versement'
    ];

    // Clé primaire non incrémentée
    public $incrementing = false;
    protected $keyType = 'string';

    // Casting automatique
    protected $casts = [
        'date_versement' => 'datetime',
    ];

    // Génération automatique d'un UUID à la création
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    // Relation : un versement appartient à un paiement
    public function paiement()
    {
        return $this->belongsTo(Paiement::class, 'paiement_id');
    }
}
