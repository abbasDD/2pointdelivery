<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovingDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'moving_detail_category_id',
        'uuid',
        'name',
        'description',
        'weight',
        'volume',
        'is_active',
        'is_deleted',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the category that owns the moving detail.
     */
    public function category()
    {
        return $this->belongsTo(MovingDetailCategory::class, 'moving_detail_category_id');
    }
}
