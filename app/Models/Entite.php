<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    protected static function booted()
    {
        // Génération d'UUID ordonné
        static::creating(function ($entite) {
            if (empty($entite->id)) {
                $entite->id = (string) Str::orderedUuid();
            }
        });

        // Supprimer logo et permissions avant suppression
        static::deleting(function ($entite) {
            // Supprimer logo
            if ($entite->logo) {
                Storage::disk('public')->delete('logos/' . $entite->logo);
            }

            // Supprimer toutes les permissions liées
            $entite->permissions()->delete();
        });
    }

    // ───── Relations ─────

    public function users()
    {
        return $this->hasMany(User::class, 'entite_id', 'id');
    }

    public function demandes()
    {
        return $this->hasMany(Demande::class, 'entite_id', 'id');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'entite_id', 'id');
    }

    // ───── Accesseurs ─────

  public function getLogoUrlAttribute(): ?string
{
    if (!$this->logo) {
        return null;
    }

    // S'assurer qu'il n'y a pas de double "logos/"
    $logoPath = preg_replace('#^logos/#', '', $this->logo);

    return asset('storage/logos/' . $logoPath);
}
}