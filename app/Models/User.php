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
    public function hasPermission($permissionName)
{
    if ($this->role && $this->role->super_admin) {
        return true;
    }

    return $this->role
        ->permissions
        ->contains('nom', $permissionName);
}
    
}