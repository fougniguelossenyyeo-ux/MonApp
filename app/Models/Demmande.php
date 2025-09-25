<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Demmande extends Model
{
    use HasFactory;
    
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'denomination',
        'entite_id',
        'montant_paiement_fournisseur',
        'date_paiement',
        'contact_fournisseur',
        'adresse_fournisseur',
        'email_fournisseur',
        'reference_facture',
        'reference_bon_commande',
        'reference_contrat',
        'reference_expression_besoin',
        'code_fournisseur',
        'description',
        'code_analytique',
        'centre_analytique',
        'code_projet',
        'priorite',
        'pieces_jointes',
    ];
protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid()->toString();
            }
        });
    }

    public function entite()
    {
        return $this->belongsTo(Entite::class, 'entite_id');
    }


}
