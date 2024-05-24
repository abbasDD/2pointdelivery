<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressBook extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'client_id',
        'pickup_address',
        'dropoff_address',
        'pickup_latitude',
        'pickup_longitude',
        'dropoff_latitude',
        'dropoff_longitude',
        'receiver_name',
        'receiver_phone',
        'receiver_email',
    ];

    /**
     * Get the user that owns the address book.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the client associated with the address book.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
