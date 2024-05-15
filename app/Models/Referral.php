<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'referred_user_id',
    ];

    // Define the relationship with the referrer user
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    // Define the relationship with the referred user
    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }
}
