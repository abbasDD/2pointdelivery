<?php

namespace App\Http\Controllers;

use App\Models\TeamInvitation;
use App\Http\Requests\StoreTeamInvitationRequest;
use App\Http\Requests\UpdateTeamInvitationRequest;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamInvitationController extends Controller
{
    public function inviteTeamMember(Request $request)
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

        $invitationData['inviter_id'] = 1;

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
                'receiver_user_type' => 'client',
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

    // Get list of invited users a company
    public function getInvitedUsers()
    {
        $user = Auth::user();
        $acceptedInvites = TeamInvitation::where('inviter_id', $user->id)->get();
        // dd($acceptedInvites);
        return view('client.teams.index', compact('acceptedInvites'));
    }

    // Get list of invitations from other companies
    public function invitations()
    {
        $user = Auth::user();
        $invitations = TeamInvitation::where('invitee_id', $user->id)->get();
        // dd($invitations);
        return view('client.teams.invitations', compact('invitations'));
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

    public function switchUser($userId)
    {
        $user = Auth::user();
        $invitation = TeamInvitation::where('invitee_id', $user->id)
            ->where('inviter_id', $userId)
            ->where('status', 'accepted')
            ->first();

        if ($invitation) {

            // Store original user ID in the session
            session(['original_user_id' => $user->id]);

            Auth::loginUsingId($userId);
            // return response()->json(['message' => 'Switched user successfully']);
            return redirect()->back()->with('success', 'Switched user successfully');
        }

        // return response()->json(['message' => 'Unauthorized'], 403);
        return redirect()->back()->with('error', 'Unauthorized');
    }

    // Switch  to self user
    public function switchToSelf()
    {
        // Get from session
        $originalUserId = session('original_user_id');
        Auth::loginUsingId($originalUserId);
        // Remove from session
        session()->forget('original_user_id');
        // return response()->json(['message' => 'Switched user successfully']);
        return redirect()->back()->with('success', 'Switched user successfully');
    }

    // Get list of accepted invites from other companies
    public function getAcceptedInvites()
    {
        $user = Auth::user();
        $invitations = TeamInvitation::where('invitee_id', $user->id)->where('status', 'accepted')->get();
        // dd($invitations);
        // return view('client.teams.invitations', compact('invitations'));
        return response()->json($invitations);
    }
}
