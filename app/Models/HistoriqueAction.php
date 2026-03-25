<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class HistoriqueAction extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'historique_actions';

    public $incrementing = false;
    protected $keyType = 'string';

    // Pas de updated_at, seulement created_at
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'user_agent',
        'entite_id',
        'created_at',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    // ───────────── Relations ─────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    public function entite()
    {
        return $this->belongsTo(Entite::class);
    }
}