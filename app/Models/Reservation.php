<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

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
        'payment_method', 
    ];

    protected static function booted()
    {
        static::creating(function ($reservation) {
            $nextId = (self::max('id') ?? 0) + 1;
            $reservation->folio = 'CAS-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Lógica de Disponibilidad Optimizada
     * Se asegura de que si alguien sale el 10, otro pueda entrar el 10.
     */
    public function scopeOverlapping(Builder $query, $roomId, $start, $end)
    {
        return $query->where('room_id', $roomId)
                     ->where('status', '!=', 'cancelled') // Ignorar reservaciones canceladas
                     ->where(function ($q) use ($start, $end) {
                         $q->where(function ($sub) use ($start, $end) {
                             $sub->where('check_in', '<', $end)
                                 ->where('check_out', '>', $start);
                         });
                     });
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}