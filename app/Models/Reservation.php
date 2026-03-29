<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    /**
     * Atributos que se pueden asignar de forma masiva.
     * Se agregó 'folio' para permitir su almacenamiento.
     */
    protected $fillable = [
        'folio',
        'room_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'check_in',
        'check_out',
        'total_price',
        'status',
    ];

    /**
     * Lógica para generar el Folio automáticamente al crear la reserva.
     */
    protected static function booted()
    {
        static::creating(function ($reservation) {
            // Calculamos el siguiente ID disponible
            $nextId = (self::max('id') ?? 0) + 1;
            
            // Formato: CAS - Año Actual - ID con 4 ceros (ej. CAS-2026-0001)
            $reservation->folio = 'CAS-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Scope para verificar traslape de fechas.
     * Evita que se reserven fechas ya ocupadas en La Casona.
     */
    public function scopeOverlapping(Builder $query, $roomId, $start, $end)
    {
        return $query->where('room_id', $roomId)
                     ->where(function ($q) use ($start, $end) {
                         $q->whereBetween('check_in', [$start, $end])
                           ->orWhereBetween('check_out', [$start, $end])
                           ->orWhere(function ($sub) use ($start, $end) {
                               $sub->where('check_in', '<=', $start)
                                   ->where('check_out', '>=', $end);
                           });
                     });
    }

    /**
     * Relación: Una reservación pertenece a una habitación.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}