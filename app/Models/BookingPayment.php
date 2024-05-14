<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPayment extends Model
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
    ];

    /**
     * Get the booking associated with the payment.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
