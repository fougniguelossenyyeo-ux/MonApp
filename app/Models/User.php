<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nom', 'prenom', 'email', 'poste', 'signature', 'entite_id', 'role_id', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->id)) {
                $user->id = Str::uuid()->toString();
            }
        });
         static::deleting(function ($user) {
        // Bloque la suppression si c'est le super admin
        if ($user->role && $user->role->super_admin) {
            throw new \Illuminate\Auth\Access\AuthorizationException(
                "Le super administrateur système ne peut pas être supprimé pour des raisons de sécurité."
            );
        }
    });
    }
    

    // Relation avec un rôle
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    // Relation avec l'entité
    public function entite()
    {
        return $this->belongsTo(Entite::class, 'entite_id', 'id');
    }

    // Relation avec les demandes
    public function demandes()
    {
        return $this->hasMany(Demande::class, 'user_id', 'id');
    }

    // Vérifier si l'utilisateur a une permission via son rôle
public function hasPermission($permissionName): bool
{
    if ($this->role && $this->role->super_admin) {
        return true;
    }

    return $this->role()
        ->whereHas('permissions', function ($query) use ($permissionName) {
            $query->where('nom', $permissionName);
        })
        ->exists();
}

    
}