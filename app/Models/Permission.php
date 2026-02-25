<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nom',
        'description',
    ];

    protected static function booted()
    {
        static::creating(function (Permission $permission) {
            if (empty($permission->id)) {
                $permission->id = (string) Str::orderedUuid();
            }
        });
    }

    // ────────────────────────────────────────────────
    // Relations (très léger pour le moment)
    // ────────────────────────────────────────────────

    /**
     * Les rôles qui possèdent cette permission
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role', 'permission_id', 'role_id');
    }

    // ────────────────────────────────────────────────
    // Pas d'autres méthodes pour l'instant
    // Toute logique métier (assignation, vérification, etc.) reste dans les controllers
    // ────────────────────────────────────────────────
}