<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'libelle', 
        'entite_id',
    ];

    protected static function booted()
    {
        static::creating(function ($role) {
            if (empty($role->id)) {
                $role->id = Str::uuid()->toString();
            }
        });
    }

    /**
     * Relation : un rôle peut avoir plusieurs utilisateurs
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }

    /**
     * Relation : un rôle peut avoir plusieurs permissions
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class, 
            'permission_role', 
            'role_id', 
            'permission_id'
        );
    }
    public function entite()
    {
        return $this->belongsTo(Entite::class, 'entite_id');
    }
}