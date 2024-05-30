<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'service_type_id',
        'vehicle_type_id',
        'name',
        'description',
        'image',
        'is_secureship_enabled',
        'base_distance',
        'base_price',
        'extra_distance_price',
        'base_weight',
        'extra_weight_price',
        'helper_fee',
        'volume_enabled',
        'moving_price_type',
        'no_of_room_enabled',
        'floor_plan_enabled',
        'floor_assess_enabled',
        'job_details_enabled',
        'moving_details_enabled',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the service type that owns the service category.
     */
    public function serviceType()
    {
        return $this->belongsTo('App\Models\ServiceType', 'service_type_id');
    }

    /**
     * Get the vehicle type that owns the service category.
     */
    public function vehicleType()
    {
        return $this->belongsTo('App\Models\VehicleType', 'vehicle_type_id');
    }
}
