@extends('helper.layouts.app')

@section('title', 'Teams')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="mb-0">Your Teams</h3>
        {{-- Show only if original_user_id is null which means user is the team owner --}}
        @if (session('original_user_id') == null)
            {{-- call inviteTeamModal function --}}
            <button onclick="showInviteModal()" class="btn btn-primary btn-sm">Invite User</button>
        @endif
    </div>

    @include('helper.teams.partials.list')

    {{-- Show only if original_user_id is null which means user is the team owner --}}
    @if (session('original_user_id') == null)
        {{-- Modal to Invite --}}
        @include('helper.teams.partials.inviteModal')
    @endif

    <script>
        // To show invite modal
        function showInviteModal() {
            $('#inviteTeamModal').modal('show');
        }
    </script>

@endsection
