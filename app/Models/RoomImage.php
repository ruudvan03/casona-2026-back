<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomImage extends Model
{
    use HasFactory;

    // Esto permite que el controlador guarde estos datos masivamente
    protected $fillable = [
        'room_id',
        'path',
    ];

    // Relación inversa (opcional pero recomendada)
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}