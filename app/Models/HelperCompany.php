<?php
// app/Models/HelperCompany.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelperCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'helper_id',
        'company_logo',
        'company_alias',
        'legal_name',
        'industry',
        'company_number',
        'gst_number',
        'hst_number',
        'email',
        'business_phone',
        'business_phone_verified_at',
        'suite',
        'street',
        'city',
        'state',
        'country',
        'zip_code',
        'is_approved',
    ];

    // Define relationships if any
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function helper()
    {
        return $this->belongsTo(Helper::class);
    }
}
