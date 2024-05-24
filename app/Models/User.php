<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_type',
        'client_enabled',
        'helper_enabled',
        'email',
        'password',
        'referral_code',
        'is_active',
        'is_updated',
        'is_deleted',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function chats()
    {
        // Chats where the user is user1
        $chats1 = $this->hasMany(Chat::class, 'user1_id');

        // Chats where the user is user2
        $chats2 = $this->hasMany(Chat::class, 'user2_id');

        // Merge the results
        return $chats1->union($chats2);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the client profile associated with the user.
     */
    public function client()
    {
        return $this->hasOne(Client::class);
    }

    /**
     * Get the client profile associated with the user.
     */
    public function helper()
    {
        return $this->hasOne(Helper::class);
    }

    /**
     * Get the address books for the user.
     */
    public function addressBooks()
    {
        return $this->hasMany(AddressBook::class);
    }
}
