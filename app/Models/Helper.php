<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Helper extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'date_of_birth',
        'company_enabled',
        'service_badge_id',
        'phone_no',
        'phone_verified_at',
        'profile_image',
        'thumbnail',
        'suite',
        'street',
        'city',
        'state',
        'country',
        'zip_code',
        'is_approved',
    ];

    /**
     * Get the user associated with the helper.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service_types()
    {
        return $this->belongsToMany(ServiceType::class, 'service_helper');
    }
}
