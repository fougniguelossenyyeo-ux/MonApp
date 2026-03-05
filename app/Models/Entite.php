<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

/**
 * Entite
 *
 * Représente une direction, agence, filiale, département, etc. dans Kama
 */
class Entite extends Model
{
    use HasFactory;
    

    protected $table = 'entites';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'libelle_entite',
        'logo',
    ];

    /**
     * Génération automatique d'un UUID ordonné à la création
     * (meilleure performance d'indexation MySQL que UUIDv4 random)
     */
    protected static function booted()
    {
        static::creating(function ($entite) {
            if (empty($entite->id)) {
                $entite->id = (string) Str::orderedUuid();
            }
        });
    }

    // ────────────────────────────────────────────────
    // Relations
    // ────────────────────────────────────────────────

    /**
     * Utilisateurs rattachés à cette entité
     */
    public function users()
    {
        return $this->hasMany(User::class, 'entite_id', 'id');
    }

    /**
     * Demandes de paiement initiées dans le contexte de cette entité
     */
    public function demandes()
    {
        return $this->hasMany(Demande::class, 'entite_id', 'id');
    }

    // ────────────────────────────────────────────────
    // Accesseurs utiles (optionnels mais très pratiques)
    // ────────────────────────────────────────────────

    /**
     * URL publique du logo (si logo présent)
     *
     * @return string|null
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo) {
            return null;
        }

        // Suppose que les logos sont dans storage/public/logos/
        return asset('storage/logos/' . $this->logo);
    }
}