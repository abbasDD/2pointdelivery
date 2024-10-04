<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Impersonate;

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
        'provider_name',
        'provider_id',
        'referral_code',
        'fcm_token',
        'language_code',
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

    /**
     * Get the notifications sent by the user.
     */
    public function sentNotifications()
    {
        return $this->hasMany(UserNotification::class, 'sender_user_id');
    }

    /**
     * Get the notifications received by the user.
     */
    public function receivedNotifications()
    {
        return $this->hasMany(UserNotification::class, 'receiver_user_id');
    }

    public function sentTeamInvitations()
    {
        return $this->hasMany(TeamInvitation::class, 'inviter_id');
    }

    public function receivedTeamInvitations()
    {
        return $this->hasMany(TeamInvitation::class, 'invitee_id');
    }
}
