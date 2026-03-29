<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',           
        'description',
        'price_per_night',
        'capacity',
        'capacity_label',
        'image_path',
        'is_available',
    ];

    /**
     * Relación con Reservaciones
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Relación con Galería de Imágenes
     */
    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class);
    }
}