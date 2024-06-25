<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingReview extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'booking_reviews';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id',
        'helper_user_id',
        'helper_id',
        'rating',
        'review',
        'is_approved',
    ];

    /**
     * Get the booking that owns the review.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user who created the review.
     */
    public function helperUser()
    {
        return $this->belongsTo(User::class, 'helper_user_id');
    }

    /**
     * Get the helper that is reviewed.
     */
    public function helper()
    {
        return $this->belongsTo(Helper::class);
    }
}
