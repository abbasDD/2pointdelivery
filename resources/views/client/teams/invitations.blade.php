@extends('client.layouts.app')

@section('title', 'Invitations')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="mb-0">Your Invitations</h3>
        {{-- Switch to Self Team --}}
        @if (Auth::user()->id != session('original_user_id'))
            <a href="{{ route('client.team.switchToSelf') }}" class="btn btn-primary btn-sm">Switch to Self Team</a>
        @endif
    </div>

    @include('client.teams.invitations.list')


@endsection