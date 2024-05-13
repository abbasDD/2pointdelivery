<?php
// app/Models/BookingPayment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'transaction_id',
        'base_price',
        'distance',
        'base_distance',
        'extra_distance_price',
        'weight',
        'base_weight',
        'extra_weight_price',
        'total_price',
        'payment_method',
        'payment_status',
        'payment_at',
    ];

    // Define relationships if any
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
