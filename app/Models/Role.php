<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'libelle',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid()->toString();
            }
        });
    }

    /*public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'role_id');
    } */
}
