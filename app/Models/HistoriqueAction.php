<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class HistoriqueAction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'historique_actions';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'details',
    ];

    protected $casts = [
        'details'     => 'array',     // ou 'json' si tu préfères garder string JSON
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'deleted_at'  => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($historique) {
            if (empty($historique->id)) {
                $historique->id = (string) Str::orderedUuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function subject()
    {
        return $this->morphTo('subject', 'entity_type', 'entity_id');
    }
}