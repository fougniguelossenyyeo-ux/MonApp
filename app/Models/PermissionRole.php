<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Historisable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class PermissionRole extends Model
{
    use HasFactory, Historisable;

    protected $table = 'permission_role';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
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