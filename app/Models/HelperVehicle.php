<?php

// File: app/Models/HelperVehicle.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelperVehicle extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'helper_vehicles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'helper_id',
        'vehicle_type_id',
        'vehicle_number',
        'vehicle_make',
        'vehicle_model',
        'vehicle_color',
        'vehicle_year',
        'vehicle_image',
        'thumbnail',
        'is_active',
        'is_approved',
        'is_deleted',
        'deleted_at',
    ];

    /**
     * Get the user that owns the helper vehicle.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the helper that owns the vehicle.
     */
    public function helper()
    {
        return $this->belongsTo(Helper::class);
    }

    /**
     * Get the vehicle type of the vehicle.
     */
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }
}
