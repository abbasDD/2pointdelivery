<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>Sr No</th>
            <th>User Email</th>
            <th>Permissions</th>
            <th>Invite Date</th>
            <th>Status</th>
            {{-- Show only if original_user_id is null which means user is the team owner --}}
            @if (session('original_user_id') == null)
                <th>Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse ($invitations as $invitation)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $invitation->inviter_email }}</td>
                <td>Permissions</td>
                <td>
                    <p>{{ app('dateHelper')->formatTimestamp($invitation->created_at, 'Y-m-d') }} </p>
                </td>
                <td>
                    @if ($invitation->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($invitation->status == 'accepted')
                        <span class="badge bg-success">Accepted</span>
                    @else
                        <span class="badge bg-danger">Declined</span>
                    @endif
                </td>

                {{-- Show only if original_user_id is null which means user is the team owner --}}
                @if (session('original_user_id') == null)
                    <td>
                        @if ($invitation->status == 'pending')
                            <a class="btn btn-sm btn-success"
                                href="{{ route('client.invitation.accept', $invitation->id) }}">
                                Accept
                            </a>
                            <a class="btn btn-sm btn-danger"
                                href="{{ route('client.invitation.decline', $invitation->id) }}">
                                Decline
                            </a>
                        @else
                            <a class="btn btn-sm btn-primary"
                                href="{{ route('client.team.switchUser', $invitation->inviter_id) }}">
                                Switch User
                            </a>
                        @endif
                    </td>
                @endif

            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- {{ $invitations->links() }} --}}
