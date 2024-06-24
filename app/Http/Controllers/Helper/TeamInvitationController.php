<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use App\Models\HelperCompany;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamInvitationController extends Controller
{

    public function getInvitedUsers()
    {
        $user = Auth::user();
        $acceptedInvites = TeamInvitation::where('inviter_id', $user->id)->get();
        // dd($acceptedInvites);
        return view('helper.teams.index', compact('acceptedInvites'));
    }

    public function invite(Request $request)
    {
        // dd($request->invitee_email);

        $invitee = User::where('email', $request->invitee_email)->first();

        $invitationData = [];

        // Check if invitee exists
        if ($invitee) {
            // Check is user invited himself
            if ($invitee->id == Auth::id()) {
                return redirect()->back()->with('error', 'You can\'t invite yourself');
            }

            // Check is user already invited
            if (TeamInvitation::where('invitee_id', $invitee->id)->where('inviter_id', Auth::id())->exists()) {
                return redirect()->back()->with('error', 'You have already invited this user');
            }

            $invitationData['invitee_id'] = $invitee->id;
        }

        // Check if invitee email is not a user
        if (!$invitee) {
            $invitationData['inviter_id'] = Auth::id();
            $invitationData['invitee_email'] = $request->invitee_email;
        }

        TeamInvitation::create($invitationData);

        if ($invitee) {
            // Send Notification
            $userNotification = UserNotification::create([
                'sender_user_id' => auth()->user()->id,
                'receiver_user_id' => $invitee->id,
                'receiver_user_type' => 'helper',
                'reference_id' => $invitee->id,
                'type' => 'team_invitation',
                'title' => 'Team Invitation',
                'content' => 'You have been invited to join the team',
                'read' => 0
            ]);
        }


        // Send Email
        // Mail::to($request->invitee_email)->send(new TeamInvitationMail($invitee, Auth::user()));


        return redirect()->back()->with('success', 'Team Invitation sent successfully');
        // return response()->json(['message' => 'Team Invitation sent successfully']);
    }

    // removeTeamMemeber
    public function removeTeamMemeber($id)
    {
        $user = Auth::user();
        $invitation = TeamInvitation::where('id', $id)->first();

        // dd($invitation->invitee_id, $user->id);
        if ($invitation->inviter_id != $user->id) {
            return redirect()->back()->with('error', 'Unauthorized');
        }


        if ($invitation) {
            $invitation->delete();
            return redirect()->back()->with('success', 'Team Invitation removed successfully');
        }
        return redirect()->back()->with('error', 'Unauthorized');
    }

    public function acceptTeamInvitation($invitationId)
    {
        $invitation = TeamInvitation::findOrFail($invitationId);
        $this->authorize('accept', $invitation);

        $invitation->accepted = true;
        $invitation->save();

        return response()->json(['message' => 'TeamInvitation accepted successfully']);
    }

    public function switchUser($userId)
    {
        $user = Auth::user();
        $invitation = TeamInvitation::where('invitee_id', $user->id)
            ->where('inviter_id', $userId)
            ->where('status', 'accepted')
            ->first();

        if ($invitation) {
            Auth::loginUsingId($userId);
            return response()->json(['message' => 'Switched user successfully']);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Switch  to self user
    public function switchToSelf()
    {
        $user = Auth::user();
        Auth::loginUsingId($user->id);
        return response()->json(['message' => 'Switched user successfully']);
    }

    // Get list of invitations
    public function invitations()
    {
        $user = Auth::user();
        $invitations = TeamInvitation::where('invitee_id', $user->id)->get();
        // dd($invitations);
        return view('helper.teams.invitations', compact('invitations'));
    }

    public function acceptInvitation($invitationId)
    {
        $invitation = TeamInvitation::findOrFail($invitationId);
        $this->authorize('accept', $invitation);

        $invitation->status = 'accepted';
        $invitation->save();

        // return response()->json(['message' => 'TeamInvitation accepted successfully']);
        return redirect()->back()->with('success', 'Team Invitation accepted successfully');
    }

    public function declineInvitation($invitationId)
    {
        $invitation = TeamInvitation::findOrFail($invitationId);
        // $this->authorize('decline', $invitation);

        $invitation->status = 'declined';
        $invitation->save();

        // return response()->json(['message' => 'TeamInvitation declined successfully']);
        return redirect()->back()->with('success', 'Team Invitation declined successfully');
    }

    // Get list of accepted invites from other companies
    public function getAcceptedInvites()
    {
        $user = Auth::user();
        $invitations = TeamInvitation::where('invitee_id', $user->id)->where('status', 'accepted')->get();
        // dd($invitations);
        // return view('client.teams.invitations', compact('invitations'));

        $acceptedInvitation = [];

        // Add accepted invites from other companies
        foreach ($invitations as $invitation) {
            // Get helper company information
            $helperCompany = HelperCompany::where('user_id', $invitation->inviter_id)->first();
            if (!$helperCompany) {
                continue;
            }

            $acceptedInvitation['id'] = $invitation->id;
            $acceptedInvitation['inviter_id'] = $invitation->inviter_id;
            $acceptedInvitation['invitee_id'] = $invitation->invitee_id;
            $acceptedInvitation['company_logo'] = $helperCompany->company_logo ? asset('images/company/' . $helperCompany->company_logo) : asset('images/company/default.png');
            $acceptedInvitation['company_alias'] = $helperCompany->company_alias;
            $acceptedInvitation['legal_name'] = $helperCompany->legal_name;
        }

        return response()->json($invitations);
    }
}
