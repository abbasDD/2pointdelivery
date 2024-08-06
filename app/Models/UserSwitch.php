<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSwitch extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'original_user_id',
        'switched_user_id',
        'platform',
    ];

    /**
     * Get the user that initiated the switch.
     */
    public function originalUser()
    {
        return $this->belongsTo(User::class, 'original_user_id');
    }

    /**
     * Get the user that was switched to.
     */
    public function switchedUser()
    {
        return $this->belongsTo(User::class, 'switched_user_id');
    }
}
