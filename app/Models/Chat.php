<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user1_id',
        'user2_id',
    ];

    public function otherUser()
    {
        // User as user1
        $user1 = $this->belongsTo(User::class, 'user1_id');

        // User as user2
        $user2 = $this->belongsTo(User::class, 'user2_id');

        // Merge the results
        return $user2->union($user1);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
