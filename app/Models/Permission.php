<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\Historisable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Permission extends Model
{
    use HasFactory, Historisable;

    protected $table = 'permissions';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nom',
        'description',
        'entite_id',
         'is_deleted',
    ];
protected $casts = [
    'is_deleted' => 'boolean',
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
    // Relations uniquement
    // ────────────────────────────────────────────────

    /**
     * L'entité à laquelle cette permission appartient
     */
    public function entite()
    {
        return $this->belongsTo(Entite::class, 'entite_id', 'id');
    }

    /**
     * Les rôles qui possèdent cette permission
     */
 public function roles()
{
    return $this->belongsToMany(
        Role::class,
        'permission_role',
        'permission_id',
        'role_id'
    )->wherePivot('is_deleted', false);
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