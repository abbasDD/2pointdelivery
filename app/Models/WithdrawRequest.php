<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'helper_id',
        'amount',
        'status',
        'reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function helper()
    {
        return $this->belongsTo(Helper::class);
    }
}
