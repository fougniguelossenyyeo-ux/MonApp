<?php

namespace App\Models;

use App\Traits\Historisable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory, Historisable;

    protected $table = 'roles';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'libelle',
        'entite_id',
        'is_deleted',
        'super_admin',
    ];

    protected $casts = [
        'super_admin' => 'boolean',
        'is_deleted' => 'boolean',
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

    public function markAsDeleted(): void
    {
        $this->is_deleted = true;
        $this->save();
    }

    public function scopeActifs($query)
    {
        return $query->where('is_deleted', false);
    }

    public function historiqueActions()
    {
        return $this->morphMany(HistoriqueAction::class, 'subject');
    }
}
