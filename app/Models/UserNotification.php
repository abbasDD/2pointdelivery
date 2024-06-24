<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sender_user_id',
        'receiver_user_id',
        'reference_id',
        'type',
        'title',
        'content',
        'read',
        'deleted',
    ];

    /**
     * Get the user that sent the notification.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    /**
     * Get the user that received the notification.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }
}
