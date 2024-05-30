<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingMoving extends Model
{
    use HasFactory;

    // Define the table name if it doesn't follow Laravel's naming convention
    protected $table = 'booking_movings';

    // Specify which attributes are mass assignable
    protected $fillable = [
        'booking_id',
        'transaction_id',
        'payment_method',
        'service_price',
        'distance_price',
        'floor_assess_price',
        'floor_plan_price',
        'job_details_price',
        'no_of_room_price',
        'priority_price',
        'weight_price',
        'sub_total',
        'tax_price',
        'total_price',
        'helper_fee',
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
        'incomplete_reason',
        'incomplete_booking_at',
    ];

    // If necessary, define any relationships, accessors, or mutators here
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
