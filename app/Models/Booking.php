<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'uuid',
        'client_user_id',
        'helper_user_id',
        'service_type_id',
        'priority_setting_id',
        'service_category_id',
        'pickup_address',
        'dropoff_address',
        'pickup_latitude',
        'pickup_longitude',
        'dropoff_latitude',
        'dropoff_longitude',
        'booking_date',
        'booking_time',
        'booking_type',
        'secureship_order_id',
        'is_secureship_enabled',
        'receiver_name',
        'receiver_phone',
        'receiver_email',
        'delivery_note',
        'status',
        'total_price',
        'payment_method',
        'payment_status',
        'booking_at',
        'pickup_at',
        'dropoff_at',
        'completed_at',
        'cancelled_at',
        'is_deleted',
        'deleted_at',
    ];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function helper()
    {
        return $this->belongsTo(Helper::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function prioritySetting()
    {
        return $this->belongsTo(PrioritySetting::class);
    }

    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class);
    }
    public function bookingPayment()
    {
        return $this->belongsTo(BookingPayment::class);
    }
}
