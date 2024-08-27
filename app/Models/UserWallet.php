<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'user_type',
        'type',
        'booking_id',
        'amount',
        'note',
        'payment_method',
        'transaction_id',
        'status',
        'paid_at',
    ];

    /**
     * Get the user that owns the wallet entry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
