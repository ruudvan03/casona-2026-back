<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Booking extends Model
{
    protected $fillable = [
        'customer_name', 'customer_email', 'customer_phone',
        'bookable_id', 'bookable_type',
        'start_time', 'end_time',
        'total_price', 'status', 'contract_path'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Esto permite acceder a $booking->bookable (que puede ser Room o Venue)
    public function bookable(): MorphTo
    {
        return $this->morphTo();
    }
}