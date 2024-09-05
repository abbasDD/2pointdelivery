<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
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
        'tax_id',
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
    ];

    /**
     * Get the user associated with the client.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the address books for the client.
     */
    public function addressBooks()
    {
        return $this->hasMany(AddressBook::class);
    }
}
