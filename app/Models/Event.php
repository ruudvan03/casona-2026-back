<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'folio', 
        'customer_name', 
        'customer_email', 
        'customer_phone', 
        'event_type',
        'include_pool', 
        'include_kitchen',
        'event_date', 
        'start_time', 
        'end_time', 
        'total_price',
        'down_payment', 
        'notes', 
        'status'
    ];


    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($event) {
            $count = static::whereYear('created_at', now()->year)->count() + 1;
            $event->folio = 'EVE-' . now()->year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        });
    }
}