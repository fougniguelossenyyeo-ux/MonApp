<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermissionRole extends Model
{
    use HasFactory;

    protected $table = 'permission_role';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'role_id',
        'permission_id',
        'is_deleted',
    ];
protected $casts = [
    'is_deleted' => 'boolean',
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
}