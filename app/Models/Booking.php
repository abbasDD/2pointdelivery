<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'client_user_id',
        'helper_user_id',
        'helper_user_id2',
        'service_type_id',
        'priority_setting_id',
        'service_category_id',
        'booking_type',
        'pickup_address',
        'dropoff_address',
        'pickup_latitude',
        'pickup_longitude',
        'dropoff_latitude',
        'dropoff_longitude',
        'booking_date',
        'booking_time',
        'secureship_order_id',
        'is_secureship_enabled',
        'receiver_name',
        'receiver_phone',
        'receiver_email',
        'delivery_note',
        'status',
        'total_price',
        'invoice_file',
        'label_file',
        'booking_at',
        'pickup_at',
        'dropoff_at',
        'completed_at',
        'cancelled_at',
        'is_deleted',
        'deleted_at',
    ];

    /**
     * Get the client user associated with the booking.
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_user_id');
    }

    /**
     * Get the helper user associated with the booking.
     */
    public function helper()
    {
        return $this->belongsTo(User::class, 'helper_user_id');
    }

    /**
     * Get the helper user associated with the booking.
     */
    public function helper2()
    {
        return $this->belongsTo(User::class, 'helper_user_id2');
    }

    /**
     * Get the service type associated with the booking.
     */
    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    /**
     * Get the priority setting associated with the booking.
     */
    public function prioritySetting()
    {
        return $this->belongsTo(PrioritySetting::class);
    }

    /**
     * Get the service category associated with the booking.
     */
    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    /**
     * Get the payment associated with the booking.
     */
    public function payment()
    {
        return $this->hasOne(BookingDelivery::class);
    }

    public function bookingDelivery()
    {
        return $this->hasOne(BookingDelivery::class, 'booking_id');
    }

    public function bookingMoving()
    {
        return $this->hasOne(BookingMoving::class, 'booking_id');
    }
}
