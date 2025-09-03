<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Derivation extends Model
{
    /** @use HasFactory<\Database\Factories\DerivationFactory> */
    use HasFactory;

    protected $fillable = [
        'tramite_id',
        'from_area_id',
        'to_area_id',
        'user_id',
        'observations',
        'status',
        'received_at',
    ];

    protected $casts = [
        'received_at' => 'datetime',
    ];

    public function tramite()
    {
        return $this->belongsTo(
            Tramite::class,
            'tramite_id',
        );
    }

    public function fromArea()
    {
        return $this->belongsTo(
            Area::class,
            'from_area_id'
        );
    }

    public function toArea()
    {
        return $this->belongsTo(
            Area::class,
            'to_area_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
        );
    }

    // 📌 Scope para bandeja de entrada (trámites que llegan a mi área)
    public function scopeInbox($query, $areaId)
    {
        return $query->where('to_area_id', $areaId)
            ->where('status', 'sent');
    }

    // 📌 Scope para bandeja de salida (trámites que mi área envió)
    public function scopeOutbox($query, $areaId)
    {
        return $query->where('from_area_id', $areaId);
    }
}
