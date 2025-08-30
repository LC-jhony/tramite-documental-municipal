<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tramite extends Model
{
    /** @use HasFactory<\Database\Factories\TramiteFactory> */
    use HasFactory;
    protected $fillable = [
        'representation',
        'full_name',
        'first_name',
        'last_name',
        'dni',
        'ruc',
        'empresa',
        'phone',
        'email',
        'address',
        // datos del Documento
        'number',
        'subject',
        'origen',
        'document_type_id',
        'area_oreigen_id',
        'gestion_id',
        'user_id',
        'folio',
        'reception_date',
        'file_path',
        'condition',
        'status',
    ];
    protected $casts = [
        'representation' => 'boolean',
        'reception_date' => 'date',
        'status' => 'string',
    ];
    public function documentTypes()
    {
        return $this->belongsTo(
            TypeDocument::class,
            'document_type_id',
        );
    }
    public function areaOrigen()
    {
        return $this->belongsTo(
            Area::class,
            'area_oreigen_id',
        );
    }
    public function derivations()
    {
        return $this->hasMany(Derivation::class);
    }
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
        );
    }
    public function derive($toAreaId, $userId, $observation = null)
    {
        $derivation = $this->derivations()->create([
            'from_area_id' => $this->area_oreigen_id,
            'to_area_id' => $toAreaId,
            'user_id' => $userId,
            'observation' => $observation,
            'status' => 'sent',
        ]);
        $this->update([
            'status' => 'derived',
            'area_oreigen_id' => $toAreaId,
        ]);
        return $derivation;
    }
    // 📥 Bandeja de entrada
    public function scopeInbox($query, $areaId)
    {
        return $query->whereHas('derivations', function ($q) use ($areaId) {
            $q->where('to_area_id', $areaId)
                ->where('status', 'sent');
        });
    }

    // 📤 Bandeja de salida
    public function scopeOutbox($query, $areaId)
    {
        return $query->whereHas('derivations', function ($q) use ($areaId) {
            $q->where('from_area_id', $areaId);
        });
    }

    // 🛠 Helper centralizado para cambiar estado según derivación
    public function actualizarEstadoDesdeDerivacion(string $derivationStatus, int $toAreaId): void
    {
        $newStatus = match ($derivationStatus) {
            'sent' => 'derived',
            'received' => 'in_process',
            'returned' => 'returned',
            default => $this->status,
        };

        $this->update([
            'status' => $newStatus,
            'area_oreigen_id' => $toAreaId,
        ]);
    }
}
