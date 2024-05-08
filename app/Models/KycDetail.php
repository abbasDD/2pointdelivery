<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'front_image',
        'back_image',
        'id_type',
        'id_number',
        'country',
        'state',
        'city',
        'issue_date',
        'expiry_date',
        'is_verified',
    ];

    /**
     * Get the user that owns the KYC detail.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
