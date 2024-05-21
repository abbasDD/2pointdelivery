<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingDelivery extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id',
        'transaction_id',
        'payment_method',
        'distance_price',
        'weight_price',
        'priority_price',
        'service_price',
        'vehicle_price',
        'tax_price',
        'helper_fee',
        'total_price',
        'payment_status',
        'payment_at',
        'accepted_at',
        'start_booking_image',
        'signatureStart',
        'start_booking_at',
        'start_intransit_at',
        'complete_booking_image',
        'signatureCompleted',
        'complete_booking_at',
    ];

    /**
     * Get the booking associated with the payment.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
