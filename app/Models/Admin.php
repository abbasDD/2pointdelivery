<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'admin_type',
        'first_name',
        'last_name',
        'profile_image',
        'thumbnail',
    ];

    /**
     * Get the user associated with the admin.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
