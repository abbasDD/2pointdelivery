<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycType extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'is_active',
    ];

    /**
     * Get the kyc details for the category.
     */
    public function kycDetails()
    {
        return $this->hasMany(KycDetail::class);
    }
}
