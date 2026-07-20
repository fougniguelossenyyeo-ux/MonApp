<?php

namespace App\Models;

use App\Traits\Historisable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Demande extends Model
{
    use HasFactory, Historisable;

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
        'refuse_par',
        'is_deleted',
    ];

    protected $casts = [
        'pieces_jointes' => 'array',
        'date_validation_controleur' => 'datetime',
        'date_validation_daf' => 'datetime',
        'date_validation_dg' => 'datetime',
        'is_deleted' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($demande) {
            if (empty($demande->id)) {
                $demande->id = (string) Str::orderedUuid();
            }
        });

        // Intercepter la suppression pour implémenter une suppression logique
        static::deleting(function ($demande) {

            $user = Auth::user();

            if (! $user) {
                throw new AuthorizationException('Utilisateur non authentifié.');
            }

            // Vérifier si le rôle de l'utilisateur est super admin
            if ($user->role && $user->role->super_admin) {
                return; // autorisé
            }

            throw new AuthorizationException(
                "Vous n'êtes pas autorisé à supprimer cette demande."
            );
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entite()
    {
        return $this->belongsTo(Entite::class);
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class, 'demande_id', 'id');
    }

    public function PaiementVersements()
    {
        return $this->hasManyThrough(PaiementVersement::class, Paiement::class, 'demande_id', 'paiement_id');
    }

    public function historiqueActions()
    {
        return $this->morphMany(HistoriqueAction::class, 'subject');
    }

    public function markAsDeleted()
    {
        $this->is_deleted = true;
        $this->save();
    }

    public function scopeActifs($query)
    {
        return $query->where('is_deleted', false);
    }
}
