@extends('client.layouts.app')

@section('title', 'Teams')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="mb-0">Your Teams</h3>
        {{-- call inviteTeamModal function --}}
        <button onclick="showInviteModal()" class="btn btn-primary btn-sm">Invite User</button>
    </div>

    @include('client.teams.partials.list')

    {{-- Modal to Invite --}}
    @include('client.teams.partials.inviteModal')

    <script>
        // To show invite modal
        function showInviteModal() {
            $('#inviteTeamModal').modal('show');
        }
    </script>

@endsection
