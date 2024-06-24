<?php

namespace App\Policies;

use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamInvitationPolicy
{
    use HandlesAuthorization;

    public function accept(User $user, TeamInvitation $invitation)
    {
        return $user->id === $invitation->invitee_id;
    }
}
