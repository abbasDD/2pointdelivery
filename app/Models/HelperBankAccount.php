<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HelperBankAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'helper_bank_accounts';

    protected $fillable = [
        'user_id',
        'helper_id',
        'payment_method',
        'account_number',
        'account_name',
        'is_active',
        'is_approved',
        'is_deleted',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_approved' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    /**
     * Get the user associated with the bank account.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the helper associated with the bank account.
     */
    public function helper()
    {
        return $this->belongsTo(Helper::class);
    }
}
