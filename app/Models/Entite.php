<?php

namespace App\Models;

use App\Traits\Historisable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Entite extends Model
{
    use HasFactory, Historisable;

    protected $table = 'entites';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'libelle_entite',
        'logo',

    ];

    protected static function booted()
    {
        // Génération UUID
        static::creating(function ($entite) {
            if (empty($entite->id)) {
                $entite->id = (string) Str::orderedUuid();
            }
        });

        // Suppression sécurisée
        static::deleting(function ($entite) {

            $user = auth()->user();

            if (! $user || ! $user->role?->super_admin) {
                throw new \Illuminate\Auth\Access\AuthorizationException(
                    'Une entité ne peut pas être supprimée. Utilisez la désactivation.'
                );
            }

            // Nettoyage des fichiers
            if ($entite->logo) {
                Storage::disk('public')->delete('logos/'.$entite->logo);
            }

            // Suppression des permissions liées
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

    public function historiqueActions()
    {
        return $this->morphMany(HistoriqueAction::class, 'subject');
    }

    // ───── Accesseurs ─────

    public function getLogoUrlAttribute(): ?string
    {
        if (! $this->logo) {
            return null;
        }

        $logoPath = preg_replace('#^logos/#', '', $this->logo);

        return asset('storage/logos/'.$logoPath);
    }

    // ───── Méthodes métier ─────

}
