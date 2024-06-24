<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamInvitation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_invitations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inviter_id',
        'invitee_id',
        'invitee_email',
        'status',
    ];

    /**
     * Get the inviter user that owns the invitation.
     */
    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    /**
     * Get the invitee user that owns the invitation.
     */
    public function invitee()
    {
        return $this->belongsTo(User::class, 'invitee_id');
    }
}
