<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Demande extends Model
{
    use HasFactory, HasUuids;

    // Nom de la table
    protected $table = 'demandes';

    // Type de clé primaire
    protected $keyType = 'string';
    public $incrementing = false;

    // Champs qui peuvent être remplis en masse (mass assignable)
    protected $fillable = [
        'denomination',
        'entite_id',
        'reference_dp',
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
        'status',
        'user_id',
    ];
      // Casts pour transformer les dates en objets Carbon
    protected $casts = [
        'date_paiement' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function entite()
    {
        return $this->belongsTo(Entite::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
