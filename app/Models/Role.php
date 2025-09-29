<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
        use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'libelle',
         'entite_id',
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
    public function entite()
    {
        return $this->belongsTo(Entite::class);
    }
    public function users()
{
    return $this->hasMany(User::class,'role_id');
}
}
