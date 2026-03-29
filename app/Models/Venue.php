<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Venue extends Model
{
    protected $fillable = ['name', 'description', 'base_price', 'capacity'];

    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}