<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ServiceType extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'type',
        'name',
        'image',
        'description',
        'is_active'
    ];

    public function vehicle_types()
    {
        return $this->belongsToMany(VehicleType::class, 'service_vehicle');
    }

    /**
     * The service categories that belong to the service type.
     */
    public function serviceCategories()
    {
        return $this->hasMany(ServiceCategory::class);
    }

    public function helpers()
    {
        return $this->belongsToMany(Helper::class, 'service_helper');
    }
}
