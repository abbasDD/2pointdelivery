<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingMovingDetail extends Model
{
    use HasFactory;

    // Define the fillable attributes
    protected $fillable = [
        'booking_id',
        'booking_moving_id',
        'moving_detail_id',
        'name',
        'description',
        'weight',
        'volume',
    ];

    // Define the relationship with the Booking model
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Define the relationship with the BookingMoving model
    public function bookingMoving()
    {
        return $this->belongsTo(BookingMoving::class);
    }

    // Define the relationship with the MovingDetail model
    public function movingDetail()
    {
        return $this->belongsTo(MovingDetail::class);
    }
}
