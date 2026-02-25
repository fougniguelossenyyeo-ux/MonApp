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
    ];

    protected static function booted()
    {
        static::creating(function ($pivot) {
            if (empty($pivot->id)) {
                $pivot->id = Str::uuid()->toString();
            }
        });
    }

    /**
     * Relation avec le rôle
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Relation avec la permission
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }
}