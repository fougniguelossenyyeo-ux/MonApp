<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Entite extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'libelle_entite',
        'logo', // nouveau champ pour le logo
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid()->toString();
            }
        });
    }

    // Relation avec les utilisateurs
    public function users()
    {
        return $this->hasMany(User::class, 'entite_id', 'id');
    }

    // Relation avec les demandes (utile pour l’impression avec logo)
    public function demandes()
    {
        return $this->hasMany(Demande::class, 'entite_id', 'id');
    }
}
